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
            $param = [
                'type_id' => $f['typeId'],
                'ir_id' => $ir_id,
                'figure_id' => $figure_id,
                'x' => $f['x'],
                'y' => $f['y'],
                'sub_x' => 0,
                'sub_y' => 0,
                'f_qty' => $f['fQty'],
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
        $this->failure->deleteByIds($dfs);
    }

    public function create($param, $fs)
    {
        $new = new InspectionResult;
        $new->vehicle_code = $param['vehicle_code'];
        $new->line_code = $param['line_code'];
        $new->pt_pn = $param['pt_pn'];
        $new->figure_id = $param['figure_id'];
        $new->status = $param['status'];
        $new->discarded = $param['discarded'];
        $new->created_choku = $param['created_choku'];
        $new->created_by = $param['created_by'];
        $new->palet_num = $param['palet_num'];
        $new->palet_max = $param['palet_max'];
        $new->f_comment = $param['f_comment'];
        $new->ft_ids = serialize($param['ft_ids']->toArray());
        $new->processed_at = $param['processed_at'];
        $new->inspected_at = $this->now;
        $new->control_num = $param['control_num'];
        $new->created_at = $this->now;
        $new->updated_at = $this->now;
        $new->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($new->id, $param['figure_id'], $fs);
        }

        return $new;
    }

    public function update($id, $param, $fs, $mfs)
    {
        $ir = InspectionResult::find($id);
        $ft_ids = array_unique(array_merge($param['ft_ids']->toArray(), unserialize($ir->ft_ids)));

        $ir->status = $param['status'];
        $ir->discarded = $param['discarded'];
        $ir->ft_ids = serialize($ft_ids);
        $ir->updated_choku = $param['updated_choku'];
        $ir->updated_by = $param['updated_by'];
        $ir->m_comment = $param['m_comment'];
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

    public function findByCN($controlNum)
    {
        return InspectionResult::where('control_num', '=', $controlNum)->first();
    }

    public function delete($p, $i, $partId)
    {
        $delete = InspectionResult::identify($p, $i, $partId)->delete();
        return $delete;
    }

    public function listForReport($p, $i, $line, $pn, $start, $end, $choku)
    {
        $chokus = [$choku, 'NA'];
        $ir = InspectionResult::with([
                'failures' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
                'modifications' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
                'holes' => function($q) {
                    return $q->select('id', 'ir_id', 'type_id', 'status');
                },
                'holes.holeModification' => function($q) {
                    return $q->select('hole_id', 'type_id');
                },
            ])
            ->where('latest', '=', 1)
            ->where('process', '=', $p)
            ->where('inspection', '=', $i)
            ->where('line', '=', $line)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->whereIn('created_choku', $chokus)
            ->whereHas('parts', function($q) use($pn) {
                $q->where('pn', '=', $pn);
            })
            ->select(['id', 'status', 'comment', 'ft_ids', 'mt_ids', 'hmt_ids', 'created_choku', 'updated_choku', 'created_by', 'updated_by', 'inspected_at', 'created_at', 'updated_at'])
            ->orderBy('inspection_results.created_at', 'asc')
            ->get()
            ->map(function($ir) {
                return [
                    'status' => $ir->status,
                    'comment' => $ir->comment,
                    'ft_ids' => unserialize($ir->ft_ids),
                    'mt_ids' => unserialize($ir->mt_ids),
                    'hmt_ids' => unserialize($ir->hmt_ids),
                    'ht_ids' => $ir->holes->map(function($h) {
                        return $h->type_id;
                    }),
                    'created_choku' => $ir->created_choku,
                    'updated_choku' => $ir->updated_choku,
                    'created_by' => $ir->created_by,
                    'updated_by' => $ir->updated_by,
                    'inspected_at' => $ir->inspected_at,
                    'created_at' => $ir->created_at,
                    'updated_at' => $ir->updated_at,
                    'fs' => $ir->failures->map(function($f) {
                        return $f->type_id;
                    }),
                    'ms' => $ir->modifications->map(function($m) {
                        return $m->type_id;
                    }),
                    'hs' => $ir->holes->keyBy('type_id')->map(function($h) {
                        $hm = -1;
                        if ($h->holeModification) {
                            $hm = $h->holeModification->type_id;
                        }

                        return [
                            'status' => $h->status,
                            'hm' =>  $hm
                        ];
                    })
                ];
            });

        return $ir;
    }   
}
