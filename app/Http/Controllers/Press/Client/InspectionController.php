<?php

namespace App\Http\Controllers\Press\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\Press\Process;
// Repositories
use App\Repositories\WorkerRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\FigureRepository;
use App\Repositories\InspectionResultRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
 * Class InspectionController
 * @package App\Http\Controllers\Press\Client
 */
class InspectionController extends Controller
{
    protected $worker;
    protected $failureType;
    protected $figure;
    protected $inspectionResult;

    public function __construct (
        WorkerRepository $worker,
        FailureTypeRepository $failureType,
        FigureRepository $figure,
        InspectionResultRepository $inspectionResult
    )
    {
        $this->worker = $worker;
        $this->failureType = $failureType;
        $this->figure = $figure;
        $this->inspectionResult = $inspectionResult;
    }

    public function initial(Request $request)
    {
        return [
            'workers' => $this->worker->formated(),
            'failures' => $this->failureType->onlyActive()
        ];
    }

    public function getFigure($pn, Request $request)
    {
        //ここでイメージファイルを返す
        return $this->figure->onlyActive($pn);
    }

    public function getControlNum(Request $request)
    {
        //ここで管理番号の状態を返す
        return 'Nothing';
    }

    public function saveFailure(Request $request)
    {
        $figure_id = $this->figure->onlyActive($request->partType)->id;

        DB::connection('press')->beginTransaction();
        $param = [
            'vehicle_code' => $request->vehicle,
            'line_code' => $request->line,
            'pt_pn' => $request->partType,
            'figure_id' => $figure_id,
            'status' => $request->status,
            'discarded' => $request->discarded,
            'created_choku' => $request->choku,
            'created_by' => $request->worker,
            'palet_num' => $request->paletNum,
            'palet_max' => $request->paletMax,
            'f_comment' => $request->comment,
            'ft_ids' => $this->failureType->activeIds(),
            'processed_at' => $request->processedAt,
            'control_num' => $request->controlNum
        ];

        $fs = [];
        if ($request->failures) {
            $fs = $request->failures;
        }

        if ($this->inspectionResult->create($param, $fs)) {
            DB::connection('press')->commit();
            return [
                'message' => 'Save inspection succeeded'
            ];
        } else {
            DB::connection('press')->rollBack();
            return \Response::json([
                'message' => 'Some parts already be inspected',
            ], 400);
        }
    }

    public function saveModification(Request $request)
    {
        DB::connection('press')->beginTransaction();
        $param = [
            'status' => $request->status,
            'discarded' => $request->discarded,
            'updated_choku' => $request->choku,
            'updated_by' => $request->worker,
            'm_comment' => $request->comment,
            'ft_ids' => $this->failureType->activeIds()
        ];

        $fs = [];
        if ($request->failures) {
            $fs = $request->failures;
        }

        $mfs = [];
        if ($request->modifications) {
            $mfs = $request->modifications;
        }

        if ($this->inspectionResult->update($request->inspectionId, $param, $fs, $mfs)) {
            DB::connection('press')->commit();
            return [
                'message' => 'Save inspection succeeded'
            ];
        } else {
            DB::connection('press')->rollBack();
            return \Response::json([
                'message' => 'Some parts already be inspected',
            ], 400);
        }
    }


    public function result($controlNum, Request $request)
    {
        $result = $this->inspectionResult->findByCN($controlNum);

        return [
            'id' => $result->id,
            'vehicle' => $result->vehicle_code,
            'line' => $result->line_code,
            'partType' => $result->pt_pn,
            'status' => $result->status,
            'createdChoku' => $result->created_choku,
            'createdBy' => $result->created_by,
            'paletNum' => $result->palet_num,
            'paletMax' => $result->palet_max,
            'comment' => $result->f_comment,
            'processedAt' => $result->processed_at->format('Y-m-d h:i:s'),
            'inspectedAt' => $result->inspected_at->format('Y-m-d h:i:s'),
            'controlNum' => $result->control_num
        ];
    }

    public function update(Request $request)
    {
        $process = $request->process;
        $inspection = $request->inspection;
        $choku = $request->choku;
        $worker = $request->worker;
        $parts = $request->parts;

        $notFound = [];
        DB::connection('950A')->beginTransaction();
        foreach ($parts as $part) {
            $targetPart = Part::with(['partType'])->find($part['partId']);
            $d2 = $targetPart->partType->division2;

            // Check inspection result exist
            if ($this->inspectionResult->exist($process, $inspection, $part['partId'])) {
                $param = [
                    'part_id' => $targetPart->id,
                    'process' => $process,
                    'inspection' => $inspection,
                    'ft_ids' => $this->failureType->narrowedIds($process, $inspection, $d2),
                    'mt_ids' => $this->modificationType->narrowedIds($process, $inspection, $d2),
                    'hmt_ids' => $this->holeModificationType->narrowedIds($process, $inspection, $d2),
                    'updated_choku' => $choku,
                    'updated_by' => $worker,
                    'status' => $part['status'],
                    'comment' => $part['comment']
                ];

                $fs = [];
                if (array_key_exists('failures', $part)) {
                    $fs = $part['failures'];
                }

                $ms = [];
                if (array_key_exists('modifications', $part)) {
                    $ms = $part['modifications'];
                }

                $hs = [];
                if (array_key_exists('holes', $part)) {
                    $hs = $part['holes'];
                }

                $dfs = [];
                if (array_key_exists('deletedF', $part)) {
                    $dfs = $part['deletedF'];
                }

                $dms = [];
                if (array_key_exists('deletedM', $part)) {
                    $dms = $part['deletedM'];
                }

                $this->inspectionResult->update($param, $fs, $ms, $hs, $dfs, $dms);

            }
            else {
                $notFound[] = [
                    'panelId' => $part['panelId'],
                    'name' => $targetPart->partType->name,
                    'pn' => $targetPart->partType->pn
                ];
            }
        }

        if (count($notFound) === 0) {
            DB::connection('950A')->commit();
            return [
                'message' => 'Update inspection succeeded'
            ];
        } else {
            DB::connection('950A')->rollBack();
            return \Response::json([
                'message' => 'Inspection result not found',
                'notFound' => $notFound
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        $process = $request->process;
        $inspection = $request->inspection;
        $partIds = $request->partIds;

        foreach ($partIds as $partId) {
            $this->inspectionResult->delete($process, $inspection, $partId);
        }

        return [
            'message' => 'Delete inspection succeeded'
        ];
    }
}
