<?php

namespace App\Repositories;

use Carbon\Carbon;
// Models
use App\Models\Press\Memo;
// Repositories
use App\Repositories\MemoFailureRepository;

/**
 * Class MemoRepository.
 */
class MemoRepository
{
    protected $now;
    protected $memoFailure;

    public function __construct (MemoFailureRepository $memoFailure)
    {
        $this->now = Carbon::now();
        $this->failure = $memoFailure;
    }

    protected function createFailures($memo_id, $figure_id, $fs)
    {
        foreach ($fs as $f) {
            $m_qty = array_key_exists('mQty', $f) ? $f['mQty'] : null;

            $param = [
                'type_id' => $f['typeId'],
                'memo_id' => $memo_id,
                'figure_id' => $figure_id,
                'x1' => $f['x1'],
                'y1' => $f['y1'],
                'x2' => $f['x2'],
                'y2' => $f['y2'],
                'palet_first' => $f['paletFirst'],
                'palet_last' =>  $f['paletLast'],
                'modificated_at' => Carbon::createFromFormat('Y/m/d', $f['modificatedAt'])
            ];

            $this->failure->create($param);
        }
    }

    protected function updateFailures($ufs)
    {
        foreach ($ufs as $uf) {
            $param = [
                'palet_first' => $uf['paletFirst'],
                'palet_last' => $uf['paletLast'],
                'modificated_at' => $uf['modificatedAt']
            ];

            $update = $this->failure->update($uf['failureId'], $param);
        }
    }

    protected function deleteFailures($dfs)
    {
        return $this->failure->deleteByIds($dfs);
    }

    public function isKeeping($line_code, $vehicle_code, $pt_pn)
    {
        $memo = Memo::withPair()
            ->withFailures()
            ->withFigure()
            ->where('line_code', '=', $line_code)
            ->where('vehicle_code', '=', $vehicle_code)
            ->where('pt_pn', '=', $pt_pn)
            ->where('keep', '=', 1)
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn',
                'keep',
                'comment',
                'created_choku as choku',
                'created_by as worker',
                'created_at as inspectedAt'
            ])
            ->first();

        return $memo;
    }

    public function create($param, $fs)
    {
        $new = new Memo;
        $new->line_code = $param['line_code'];
        $new->vehicle_code = $param['vehicle_code'];
        $new->pt_pn = $param['pt_pn'];
        $new->figure_id = $param['figure_id'];
        $new->keep = $param['keep'];
        $new->comment = $param['comment'];
        $new->ft_ids = serialize($param['ft_ids']->toArray());
        $new->created_choku = $param['created_choku'];
        $new->created_by = $param['created_by'];
        $new->created_iPad_id = $param['created_iPad_id'];
        $new->created_at = $this->now;
        $new->updated_at = $this->now;
        $new->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($new->id, $param['figure_id'], $fs);
        }

        return $new;
    }

    public function update($id, $param, $fs, $mfs, $dfs)
    {
        $memo = Memo::find($id);
        if ($memo === null) {
            return false;
        }

        $ft_ids = array_unique(array_merge($param['ft_ids']->toArray(), unserialize($memo->ft_ids)));

        $memo->keep = $param['keep'];
        $memo->comment = $param['comment'];
        $memo->updated_choku = $param['updated_choku'];
        $memo->updated_by = $param['updated_by'];
        $memo->updated_iPad_id = $param['updated_iPad_id'];
        $memo->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($id, $memo->figure_id, $fs);
        }

        // Update failures
        if (count($mfs) !== 0) {
            $this->updateFailures($mfs);
        }

        // Delete failures
        if (count($dfs) !== 0) {
            $this->deleteFailures($dfs);
        }

        return $memo;

    }

    public function getHistory($orderBy, $skip, $take)
    {
        $memo = Memo::withPair()
            ->withFigure()
            ->withFailures()
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn',
                'keep',
                'comment',
                'created_choku as choku',
                'created_by as worker',
                'created_at as inspectedAt'
            ])
            ->orderBy($orderBy, 'desc')
            ->skip($skip)
            ->take($take)
            ->get();

        return $memo;
    }

    public function listForReference($line, $vehicle, $part, $start, $end)
    {
        $memos = Memo::withFailures()
            ->with([
                'partType' => function($q) {
                    $q->select(['pn', 'capacity']);
                }
            ])
            ->where('keep', '=', 0)
            ->where('pt_pn', '=', $part)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end);

        if ($line !== null) {
            $memos = $memos->where('line_code', '=', $line);
        }

        if ($vehicle !== null) {
            $memos = $memos->where('vehicle_code', '=', $vehicle);
        }

        $result_count = $memos->count();

        $memos = $memos->select([
            'id',
            'line_code',
            'vehicle_code',
            'pt_pn',
            'created_choku',
            'created_by',
            'created_at',
            'comment',
            'ft_ids'
        ])
        ->take(100)
        ->get();

        $ft_ids = $memos->map(function($memo){
            return unserialize($memo->ft_ids);
        })
        ->flatten()
        ->unique();

        $memos = $memos->map(function($memo){
            return [
                'l' => $memo->line_code,
                'v' => $memo->vehicle_code,
                'p' => $memo->pt_pn,
                'cBy' => $memo->created_by,
                'com' => $memo->comment,
                'cAt' => $memo->created_at->toDateTimeString(),
                'cap' => $memo->partType->capacity,
                'f' => $memo->failures->map(function($f) {
                    return [
                        't' => $f->typeId,
                        'first' => $f->paletFirst,
                        'last' => $f->paletFirst
                    ];
                })
            ];
        });

        return [
            'result_count' => $result_count,
            'ft_ids' => $ft_ids,
            'memos' => $memos
        ];
    }
}