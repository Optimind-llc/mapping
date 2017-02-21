<?php

namespace App\Http\Controllers\Press\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
// use App\Models\Press\Process;
// Repositories
use App\Repositories\WorkerRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\FigureRepository;
use App\Repositories\InspectionResultRepository;
use App\Repositories\LineRepository;
use App\Repositories\CombinationRepository;
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
    protected $line;
    protected $combination;

    public function __construct (
        WorkerRepository $worker,
        FailureTypeRepository $failureType,
        FigureRepository $figure,
        InspectionResultRepository $inspectionResult,
        LineRepository $line,
        CombinationRepository $combination
    )
    {
        $this->worker = $worker;
        $this->failureType = $failureType;
        $this->figure = $figure;
        $this->inspectionResult = $inspectionResult;
        $this->line = $line;
        $this->combination = $combination;
    }

    public function initial(Request $request)
    {
        return [
            'workers' => $this->worker->formated(),
            'failures' => $this->failureType->onlyActive()
        ];
    }

    public function getControlNum(Request $request)
    {
        $irs = $this->inspectionResult
            ->hasControlNum()
            ->map(function($ir) {
                $controlNum = $ir->controlNum;

                $controlStatus = 0;
                if ($controlNum !== null && $ir->modificatedAt === null) {
                    $controlStatus = 1;
                }
                else if ($controlNum !== null && $ir->modificatedAt !== null) {
                    $controlStatus = 2;
                }

                return [
                    'controlNum' => $controlNum,
                    'status' => $controlStatus,
                    'choku' => $ir->choku,
                    'line' => $ir->line,
                    'vehicle' => $ir->vehicle,
                    'part' => $ir->part,
                    'modificatedAt' => $ir->modificatedAt
                ];
            })
            ->groupBy('choku');

        return $irs;
    }

    public function check(Request $request)
    {
        $QRcode = $request->QRcode;
        $ir = $this->inspectionResult->getResultByQRcode($QRcode);

        if ($ir != null) {
            $irArray = $ir->toArray();

            $controlNum = $irArray['controlNum'];
            $figure = $irArray['figure'];

            $controlStatus = 0;
            if ($controlNum !== null && $irArray['modificatedAt'] === null) {
                $controlStatus = 1;
            }
            else if ($controlNum !== null && $irArray['modificatedAt'] !== null) {
                $controlStatus = 2;
            }

            $status = 1;
            $irArray['controlNum'] = [
                'num' => $controlNum,
                'status' => $controlStatus
            ];
            $irArray['figure'] = config('app.url').$figure['path'];

        }
        else {
            $line_code = $this->line->getCodeByCodeInQR(substr($QRcode, 19, 3));
            $pt_pn = substr($QRcode, 26, 10);
            $vehicle_code = $this->combination->identifyVehicle($line_code, $pt_pn);

            $figure = $this->figure->onlyActive($pt_pn);

            $status = 0;
            $irArray = [
                'line' => $line_code,
                'vehicle' => $vehicle_code,
                'part' => $pt_pn,
                'figure' => config('app.url').$figure->path,
            ];
        }

        return [
            'status' => $status,
            'result' => $irArray
        ];
    }

    public function saveForFailure(Request $request)
    {
        $QRcode = $request->QRcode;

        $line_code = $this->line->getCodeByCodeInQR(substr($QRcode, 19, 3));
        $pt_pn = substr($QRcode, 26, 10);
        $vehicle_code = $this->combination->identifyVehicle($line_code, $pt_pn);
        $figure_id = $this->figure->onlyActive($pt_pn)->id;

        $processed_at = Carbon::createFromFormat('YmdHis', substr($QRcode, 82, 14))->toDateTimeString(); 

        DB::connection('press')->beginTransaction();
        $param = [
            'QRcode' => $request->QRcode,
            'line_code' => $line_code,
            'vehicle_code' => $vehicle_code,
            'pt_pn' => $pt_pn,
            'figure_id' => $figure_id,
            'status' => 1,
            'f_keep' => $request->keep,
            'discarded' => $request->discarded,
            'inspected_choku' => $request->choku,
            'inspected_by' => $request->worker,
            'palet_num' => $request->paletNum,
            'palet_max' => $request->paletMax,
            'f_comment' => $request->comment,
            'ft_ids' => $this->failureType->activeIds(),
            'processed_at' => $processed_at,
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

    public function toBeModificated(Request $request)
    {
        $irs = $this->inspectionResult->toBeModificated()->map(function($ir) {
            $irArray = $ir->toArray();
            $irArray['figure'] = config('app.url').$ir->figure->path;
            return $irArray;
        });

        return $irs;
    }

    public function saveForModification(Request $request)
    {
        $param = [
            'status' => $request->status,
            'discarded' => $request->discarded,
            'modificated_choku' => $request->choku,
            'modificated_by' => $request->worker,
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

        DB::connection('press')->beginTransaction();
        if ($this->inspectionResult->updateByModification($request->inspectionId, $param, $fs, $mfs)) {
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

    public function update(Request $request)
    {
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

        $dfs = [];
        if ($request->deletedFailures) {
            $dfs = $request->deletedFailures;
        }

        DB::connection('press')->beginTransaction();
        if ($this->inspectionResult->update($request->inspectionId, $param, $fs, $mfs, $dfs)) {
            DB::connection('press')->commit();
            return [
                'message' => 'Update inspection succeeded'
            ];
        } else {
            DB::connection('press')->rollBack();
            return \Response::json([
                'message' => 'Update inspection failed',
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





    public function getFigure($pn, Request $request)
    {
        //ここでイメージファイルを返す
        return $this->figure->onlyActive($pn);
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
}
