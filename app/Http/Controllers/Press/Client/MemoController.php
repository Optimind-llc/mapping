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
use App\Repositories\LineRepository;
use App\Repositories\CombinationRepository;
use App\Repositories\PartTypeRepository;
use App\Repositories\MemoRepository;
use App\Repositories\VehicleRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
 * Class MemoController
 * @package App\Http\Controllers\Press\Client
 */
class MemoController extends Controller
{
    protected $worker;
    protected $failureType;
    protected $figure;
    protected $line;
    protected $combination;
    protected $partType;
    protected $memo;
    protected $vehicle;

    public function __construct (
        WorkerRepository $worker,
        FailureTypeRepository $failureType,
        FigureRepository $figure,
        LineRepository $line,
        CombinationRepository $combination,
        PartTypeRepository $partType,
        MemoRepository $memo,
        VehicleRepository $vehicle
    )
    {
        $this->worker = $worker;
        $this->failureType = $failureType;
        $this->figure = $figure;
        $this->line = $line;
        $this->combination = $combination;
        $this->partType = $partType;
        $this->memo = $memo;
        $this->vehicle = $vehicle;
    }

    public function list(Request $request)
    {
        $combinations = $this->combination->onlyActive()->toArray();

        $result = [];
        foreach ($combinations as $c) {
            $keepingMemo = $this->memo->isKeeping($c['l'], $c['v'], $c['p']);

            if ($keepingMemo !== null) {
                $keepingMemoArray = $keepingMemo->toArray();

                $figure = $keepingMemo['figure'];
                $keepingMemoArray['figure'] = config('app.url').$figure['path'];

                $keepingMemoArray['part'] = $keepingMemo['pt_pn'];
                unset($keepingMemoArray['pt_pn']);

                $keepingMemoArray['capacity'] = $keepingMemo['partType']['capacity'];

                $keepingMemoArray['hasPair'] = false;
                if ($keepingMemoArray['part_type']['left_pair'] !== null || $keepingMemoArray['part_type']['right_pair'] !== null) {
                    $keepingMemoArray['hasPair'] = true;
                }
                unset($keepingMemoArray['part_type']);
            }
            else {
                $keepingMemoArray = null;
            }

            $pt = $this->partType->findByPn($c['p']);

            $hasPair = false;
            if ($pt->leftPair !== null || $pt->rightPair !== null ) {
                $hasPair = true;
            }

            $result[] = [
                'line' => $c['l'],
                'vehicle' => $c['v'],
                'part' => $c['p'],
                'keepingMemo' => $keepingMemoArray,
                'capacity' => $pt->capacity,
                'figure' => config('app.url').$pt->figures->first()['path'],
                'hasPair' => $hasPair
            ];
        }

        return [
            'vehicle' => $this->vehicle->onlyActive(),
            'result' => $result
        ];
    }

    public function initial(Request $request)
    {
        return [
            'workers' => $this->worker->formated(),
            'failures' => $this->failureType->onlyActive()
        ];
    }

    public function save(Request $request)
    {
        $pt_pn = $request->part;
        $discarded = $request->discarded;
        $figure = $this->figure->onlyActive($pt_pn);

        if (is_null($figure)) {
            return \Response::json([
                'status' => 1,
                'message' => 'Figure not found',
            ], 400);
        }

        $figure_id = $figure->id;

        $param = [
            'line_code' => $request->line,
            'vehicle_code' => $request->vehicle,
            'pt_pn' => $pt_pn,
            'discarded' => $discarded,
            'figure_id' => $figure_id,
            'keep' => $request->keep,
            'comment' => $request->comment,
            'ft_ids' => $this->failureType->activeIds(),
            'created_choku' => $request->choku,
            'created_by' => $request->worker,
            'created_iPad_id' => $request->iPadId
        ];

        $fs = [];
        if ($request->failures) {
            $fs = $request->failures;
        }

        DB::connection('press')->beginTransaction();
        if ($this->memo->create($param, $fs)) {
            DB::connection('press')->commit();
            return [
                'message' => 'Save memo succeeded'
            ];
        } else {
            DB::connection('press')->rollBack();
            return \Response::json([
                'message' => 'Save memo failed',
            ], 400);
        }
    }

    public function history(Request $request)
    {
        $orderBy = 'created_at';
        $skip = $request->skip;
        $take = $request->take;

        $memos = $this->memo->getHistory($orderBy, $skip, $take)->map(function($memo) {
            $memoArray = $memo->toArray();
            $memoArray['figure'] = config('app.url').$memo->figure->path;
    
            $memoArray['part'] = $memo['pt_pn'];
            unset($memoArray['pt_pn']);

            $memoArray['hasPair'] = false;
            if ($memoArray['part_type']['left_pair'] !== null || $memoArray['part_type']['right_pair'] !== null) {
                $memoArray['hasPair'] = true;
            }
            unset($memoArray['part_type']);

            return $memoArray;
        });

        return $memos;
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $param = [
            'discarded' => $request->discarded,
            'keep' => $request->keep,
            'comment' => $request->comment,
            'updated_choku' => $request->choku,
            'updated_by' => $request->worker,
            'updated_iPad_id' => $request->iPadId,
            'ft_ids' => $this->failureType->activeIds()
        ];

        $fs = [];
        if ($request->failures) {
            $fs = $request->failures;
        }

        $ufs = [];
        if ($request->updatedFailures) {
            $ufs = $request->updatedFailures;
        }

        $dfs = [];
        if ($request->deletedFailures) {
            $dfs = $request->deletedFailures;
        }

        DB::connection('press')->beginTransaction();
        if ($this->memo->update($request->memoId, $param, $fs, $ufs, $dfs)) {
            DB::connection('press')->commit();
            return [
                'status' => 1,
                'message' => 'Update memo succeeded'
            ];
        } else {
            DB::connection('press')->rollBack();
            return \Response::json([
                'status' => 0,
                'message' => 'Update mamo failed',
                'error' => [
                    'status' => 100,
                    'message' => ''
                ]
            ], 400);
        }
    }
}
