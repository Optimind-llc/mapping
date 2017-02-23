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

    public function isKeeping($line_code, $vehicle_code, $pt_pn)
    {
        $memo = Memo::where('line_code', '=', $line_code)
            ->where('vehicle_code', '=', $vehicle_code)
            ->where('pt_pn', '=', $pt_pn)
            ->where('keep', '=', 1)
            ->first();

        return $memo;
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
            ];

            $this->failure->create($param);
        }
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

    public function getHistory($orderBy, $skip, $take)
    {
        $memo = Memo::withFigure()
            ->withFailures()
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn as part',
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

}