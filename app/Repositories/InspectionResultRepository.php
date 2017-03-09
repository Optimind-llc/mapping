<?php

namespace App\Repositories;

use Carbon\Carbon;
// Models
use App\Models\Press\InspectionResult;
// Repositories
use App\Repositories\FailureRepository;

/**
 * Class InspectionResultRepository.
 */
class InspectionResultRepository
{
    protected $now;
    protected $failure;

    public function __construct (FailureRepository $failure)
    {
        $this->now = Carbon::now();
        $this->failure = $failure;
    }

    protected function createFailures($ir_id, $figure_id, $fs)
    {
        foreach ($fs as $f) {
            $m_qty = array_key_exists('mQty', $f) ? $f['mQty'] : null;
            if ($m_qty == -1) {
                $m_qty = null;
            }

            $param = [
                'type_id' => $f['typeId'],
                'ir_id' => $ir_id,
                'figure_id' => $figure_id,
                'x1' => $f['x1'],
                'y1' => $f['y1'],
                'x2' => $f['x2'],
                'y2' => $f['y2'],
                'f_qty' => $f['fQty'],
                'm_qty' =>  $m_qty,
                'responsible_for' => $f['responsibleFor']
            ];

            $this->failure->create($param);
        }
    }

    protected function updateFailures($mfs)
    {
        foreach ($mfs as $mf) {
            $m_qty = array_key_exists('mQty', $mf) ? $mf['mQty'] : null;
            if ($m_qty == -1) {
                $m_qty = null;
            }

            $param = [
                'f_qty' => $mf['fQty'],
                'm_qty' => $m_qty,
                'responsible_for' => $mf['responsibleFor']
            ];

            $this->failure->update($mf['failureId'], $param);
        }
    }

    protected function deleteFailures($dfs)
    {
        return $this->failure->deleteByIds($dfs);
    }

    public function hasControlNum()
    {
        $ir = InspectionResult::whereNotNull('control_num')
            ->select([
                'id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn as part',
                'inspected_choku as choku',
                'inspected_at as inspectedAt',
                'modificated_at as modificatedAt',
                'control_num as controlNum'
            ])
            ->orderBy('control_num')
            ->get();

        return $ir;
    }

    public function getResultByQRcode($QRcode)
    {
        $ir = InspectionResult::withFigure()
            ->withFailures()
            ->withPair()
            ->where('QRcode', '=', $QRcode)
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn',
                'f_keep as keep',
                'discarded',
                'inspected_choku as choku',
                'inspected_by as worker',
                'palet_num as paletNum',
                'palet_max as paletMax',
                'f_comment as commentInF',
                'm_comment as commentInM',
                'processed_at as processedAt',
                'inspected_at as inspectedAt',
                'modificated_at as modificatedAt',
                'control_num as controlNum'
            ])
            ->first();

        return $ir;
    }

    public function create($param, $fs)
    {
        $new = new InspectionResult;
        $new->QRcode = $param['QRcode'];
        $new->line_code = $param['line_code'];
        $new->vehicle_code = $param['vehicle_code'];
        $new->pt_pn = $param['pt_pn'];
        $new->mold_type_num = $param['mold_type_num'];
        $new->figure_id = $param['figure_id'];
        $new->status = $param['status'];
        $new->f_keep = $param['f_keep'];
        $new->discarded = $param['discarded'];
        $new->inspected_choku = $param['inspected_choku'];
        $new->inspected_by = $param['inspected_by'];
        $new->palet_num = $param['palet_num'];
        $new->palet_max = $param['palet_max'];
        $new->f_comment = $param['f_comment'];
        $new->ft_ids = serialize($param['ft_ids']->toArray());
        $new->processed_at = $param['processed_at'];
        $new->inspected_at = $param['inspected_at'];
        $new->control_num = $param['control_num'];
        $new->inspected_iPad_id = $param['inspected_iPad_id'];
        $new->created_at = $param['inspected_at'];
        $new->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($new->id, $param['figure_id'], $fs);
        }

        return $new;
    }

    public function toBeModificated()
    {
        $ir = InspectionResult::withFigure()
            ->withFailures()
            ->withPair()
            ->where('m_keep', '=', 1)
            ->orWhere(function ($query) {
                $query->where('discarded', '=', 0)
                    ->where('f_keep', '=', 0)
                    ->whereHas('failures', function($q) {
                        $q->whereNull('m_qty');
                    });
            })
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn',
                'f_keep',
                'm_keep',
                'discarded',
                'inspected_choku as fChoku',
                'inspected_by as fWorker',
                'modificated_choku as mChoku',
                'modificated_by as mWorker',
                'updated_by as uWorker',
                'palet_num as paletNum',
                'palet_max as paletMax',
                'f_comment as fComment',
                'm_comment as mComment',
                'processed_at as processedAt',
                'inspected_at as inspectedAt',
                'modificated_at as modificatedAt',
                'control_num as controlNum'
            ])
            ->orderBy('control_num')
            ->get();

        return $ir;
    }

    public function updateByModification($id, $param, $fs, $mfs)
    {
        $ir = InspectionResult::find($id);
        if ($ir === null) {
            return false;
        }

        $ft_ids = array_unique(array_merge($param['ft_ids']->toArray(), unserialize($ir->ft_ids)));

        $ir->status = $param['status'];
        $ir->discarded = $param['discarded'];
        $ir->m_keep = $param['m_keep'];
        $ir->ft_ids = serialize($ft_ids);
        $ir->modificated_choku = $param['modificated_choku'];
        $ir->modificated_by = $param['modificated_by'];
        $ir->m_comment = $param['m_comment'];
        $ir->modificated_iPad_id = $param['modificated_iPad_id'];
        $ir->modificated_at = $this->now;
        $ir->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($id, $ir->figure_id, $fs);
        }

        // Modificate failures
        if (count($mfs) !== 0) {
            $this->updateFailures($mfs);
        }

        return $ir;
    }

    public function update($id, $param, $fs, $mfs, $dfs)
    {
        $now = Carbon::now();
        $ir = InspectionResult::find($id);
        if ($ir === null) {
            return false;
        }

        $ft_ids = array_unique(array_merge($param['ft_ids']->toArray(), unserialize($ir->ft_ids)));

        $ir->status = $param['status'];
        $ir->discarded = $param['discarded'];
        $ir->f_keep = $param['f_keep'];
        $ir->m_keep = $param['m_keep'];
        $ir->ft_ids = serialize($ft_ids);
        $ir->updated_choku = $param['updated_choku'];
        $ir->updated_by = $param['updated_by'];
        $ir->f_comment = $param['f_comment'];
        $ir->m_comment = $param['m_comment'];
        $ir->control_num = $param['control_num'];
        $ir->updated_iPad_id = $param['updated_iPad_id'];
        $ir->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($id, $ir->figure_id, $fs);
        }

        // Modificate failures
        if (count($mfs) !== 0) {
            $this->updateFailures($mfs);
        }

        // Delete failures
        if (count($dfs) !== 0) {
            $this->deleteFailures($dfs);
        }

        return $ir;
    }

    public function getHistory($orderBy, $skip, $take)
    {
        $ir = InspectionResult::withFigure()
            ->withFailures()
            ->withPair()
            // ->whereNotNull('control_num')
            ->whereNotNull('modificated_at')
            // ->where('discarded', '=', 0)
            ->where('f_keep', '=', 0)
            ->where('m_keep', '=', 0)
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn',
                'f_keep',
                'm_keep',
                'discarded',
                'inspected_choku as fChoku',
                'inspected_by as fWorker',
                'modificated_choku as mChoku',
                'modificated_by as mWorker',
                'updated_by as uWorker',
                'palet_num as paletNum',
                'palet_max as paletMax',
                'f_comment as fComment',
                'm_comment as mComment',
                'processed_at as processedAt',
                'inspected_at as inspectedAt',
                'modificated_at as modificatedAt',
                'control_num as controlNum'
            ])
            ->orderBy($orderBy, 'desc')
            ->skip($skip)
            ->take($take)
            ->get();

        return $ir;
    }

    public function clearControlNum($id)
    {
        $ir = InspectionResult::find($id);
        $ir->control_num = null;
        $ir->save();

        return true;
    }

    public function findByCN($controlNum)
    {
        return InspectionResult::where('control_num', '=', $controlNum)->first();
    }

    public function delete($p, $i, $partId)
    {
        $delete = InspectionResult::identify($p, $i, $partId)->delete();
        return $delete;
    }

    public function forReport($start, $end, $chokus)
    {
        // $chokus = [$choku, 'NA'];
        $ir = InspectionResult::with([
                'failures' => function($q) {
                    return $q->select('ir_id', 'type_id');
                }
            ])
            ->where('discarded', '=', 0)
            ->narrow($start, $end, 'inspected', $chokus)
            ->select(['id', 'line_code'])
            ->get();

        return $ir;
    }   

    public function listForReport($line, $start, $end, $choku)
    {
        // $chokus = [$choku, 'NA'];
        $ir = InspectionResult::withFailures()
            ->withPart()
            ->where('f_keep', '=', 0)
            // ->where('m_keep', '=', 0)
            ->where('discarded', '=', 0)
            ->where('line_code', '=', $line)
            ->where('inspected_at', '>=', $start)
            ->where('inspected_at', '<', $end)
            ->where('inspected_choku', '=', $choku)
            ->select([
                'id',
                'mold_type_num',
                'line_code',
                'vehicle_code',
                'pt_pn',
                'inspected_at',
                'inspected_by',
                'palet_num',
                // 'palet_max',
                'f_comment',
                'processed_at',
                'modificated_by',
                'm_comment',
                'modificated_at',
                'ft_ids',
            ])
            ->orderBy('inspected_at')
            ->get();

        return $ir;
    }

    public function listForMapping($line, $vehicle, $part, $start, $end, $chokus)
    {
        $ir = InspectionResult::withFailures()
            ->where('f_keep', '=', 0)
            ->where('m_keep', '=', 0)
            ->where('discarded', '=', 0)
            ->where('pt_pn', '=', $part)
            ->where('inspected_at', '>=', $start)
            ->where('inspected_at', '<', $end)
            ->whereIn('inspected_choku', $chokus);

        if ($line !== null) {
            $ir = $ir->where('line_code', '=', $line);
        }

        if ($vehicle !== null) {
            $ir = $ir->where('vehicle_code', '=', $vehicle);
        }

        $ir = $ir->select([
            'id',
            'inspected_choku',
            'ft_ids'
        ])
        ->get();

        $ft_ids = $ir->map(function($ir){
            return unserialize($ir->ft_ids);
        })
        ->flatten()
        ->unique();

        $failures = $ir->map(function($ir){
            return $ir->failures;
        })
        ->flatten()
        ->map(function($f){
            return [
                'typeId' => $f->typeId,
                'x' => $f->x1,
                'y' => $f->y1,
                'fQty' => $f->fQty,
                'mQty' => $f->mQty,
                'responsibleFor' => $f->responsibleFor
            ];
        });

        return [
            'ft_ids' => $ft_ids,
            'failures' => $failures,
        ];
    }

    public function listForReference($line, $vehicle, $part, $start, $end, $chokus, $judge, $failureTypes)
    {
        $irs = InspectionResult::withFailures()
            ->where('f_keep', '=', 0)
            ->where('m_keep', '=', 0)
            ->where('discarded', '=', 0)
            ->where('pt_pn', '=', $part)
            ->where('inspected_at', '>=', $start)
            ->where('inspected_at', '<', $end)
            ->whereIn('inspected_choku', $chokus);

        if (array_sum($judge) === 0) {
            $irs = $irs->whereHas('failures', function($q) use($failureTypes) {
                $q->whereIn('type_id', $failureTypes);
            });
        }

        if ($line !== null) {
            $irs = $irs->where('line_code', '=', $line);
        }

        if ($vehicle !== null) {
            $irs = $irs->where('vehicle_code', '=', $vehicle);
        }

        $result_count = $irs->count();

        $irs = $irs->select([
            'id',
            'line_code',
            'vehicle_code',
            'pt_pn',
            'inspected_choku',
            'modificated_choku',
            'inspected_by',
            'modificated_by',
            'palet_num',
            'f_comment',
            'm_comment',
            'inspected_at',
            'modificated_at',
            'ft_ids'
        ])
        ->take(100)
        ->get();

        $ft_ids = $irs->map(function($ir){
            return unserialize($ir->ft_ids);
        })
        ->flatten()
        ->unique();

        $irs = $irs->map(function($ir){
            $mAt = '';
            if ($ir->modificated_at !== null) {
                $mAt = $ir->modificated_at->toDateTimeString();
            }
            return [
                'l' => $ir->line_code,
                'v' => $ir->vehicle_code,
                'p' => $ir->pt_pn,
                'iChoku' => $ir->inspected_choku,
                'mChoku' => $ir->modificated_choku,
                'iBy' => $ir->inspected_by,
                'mBy' => $ir->modificated_by,
                'pNum' => $ir->palet_num,
                'fCom' => $ir->f_comment,
                'mCom' => $ir->m_comment,
                'iAt' => $ir->inspected_at->toDateTimeString(),
                'mAt' => $mAt,
                'pNum' => $ir->palet_num,
                'f' => $ir->failures->map(function($f) {
                    return [
                        't' => $f->typeId,
                        'fQ' => $f->fQty,
                        'mQ' => $f->mQty,
                        'r' => $f->responsibleFor
                    ];
                })
            ];
        });

        return [
            'result_count' => $result_count,
            'ft_ids' => $ft_ids,
            'irs' => $irs
        ];
    }
}
