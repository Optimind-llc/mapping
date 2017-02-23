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
            $param = [
                'f_qty' => $mf['fQty'],
                'm_qty' => $mf['mQty'],
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
            ->where('QRcode', '=', $QRcode)
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn as part',
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
        $new->inspected_at = $this->now;
        $new->control_num = $param['control_num'];
        $new->inspected_iPad_id = $param['inspected_iPad_id'];
        $new->created_at = $this->now;
        $new->updated_at = $this->now;
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
            ->whereNotNull('control_num')
            ->where('discarded', '=', 0)
            ->where('f_keep', '=', 0)
            ->hasFailures()
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn as part',
                // 'f_keep as keep',
                // 'discarded',
                'inspected_choku as choku',
                'inspected_by as worker',
                'palet_num as paletNum',
                'palet_max as paletMax',
                'f_comment as comment',
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
        $ft_ids = array_unique(array_merge($param['ft_ids']->toArray(), unserialize($ir->ft_ids)));

        $ir->status = $param['status'];
        $ir->discarded = $param['discarded'];
        $ir->ft_ids = serialize($ft_ids);
        $ir->modificated_choku = $param['modificated_choku'];
        $ir->modificated_by = $param['modificated_by'];
        $ir->m_comment = $param['m_comment'];
        $ir->modificated_iPad_id = $param['modificated_iPad_id'];
        $ir->modificated_at = $this->now;
        $ir->updated_at = $this->now;
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
        $ir = InspectionResult::find($id);
        $ft_ids = array_unique(array_merge($param['ft_ids']->toArray(), unserialize($ir->ft_ids)));

        $ir->status = $param['status'];
        $ir->discarded = $param['discarded'];
        $ir->ft_ids = serialize($ft_ids);
        $ir->updated_choku = $param['updated_choku'];
        $ir->updated_by = $param['updated_by'];
        $ir->f_comment = $param['f_comment'];
        $ir->m_comment = $param['m_comment'];
        $ir->updated_iPad_id = $param['updated_iPad_id'];
        $ir->updated_at = $this->now;
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
            // ->whereNotNull('control_num')
            ->whereNotNull('modificated_at')
            // ->where('discarded', '=', 0)
            // ->where('f_keep', '=', 0)
            ->select([
                'id',
                'figure_id',
                'line_code as line',
                'vehicle_code as vehicle',
                'pt_pn as part',
                // 'f_keep as keep',
                // 'discarded',
                'inspected_choku as choku',
                'inspected_by as worker',
                'palet_num as paletNum',
                'palet_max as paletMax',
                'm_comment as comment',
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
            ->narrow($start, $end, 'inspected', $chokus)
            ->select(['id', 'line_code'])
            ->get();

        return $ir;
    }   
}
