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

    public function __construct (
        WorkerRepository $worker,
        FailureTypeRepository $failureType,
        FigureRepository $figure,
        LineRepository $line,
        CombinationRepository $combination,
        PartTypeRepository $partType,
        MemoRepository $memo
    )
    {
        $this->worker = $worker;
        $this->failureType = $failureType;
        $this->figure = $figure;
        $this->line = $line;
        $this->combination = $combination;
        $this->partType = $partType;
        $this->memo = $memo;
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
                if ($keepingMemo['partType']['left_pair'] !== null || $keepingMemo['partType']['right_pair'] !== null) {
                    $keepingMemoArray['hasPair'] = true;
                }
                unset($keepingMemoArray['part_type']);
            }
            else {
                $keepingMemoArray = null;
            }

            $pt = $this->partType->findByPn($c['p']);

            $result[] = [
                'line' => $c['l'],
                'vehicle' => $c['v'],
                'part' => $c['p'],
                'keepingMemo' => $keepingMemoArray,
                'capacity' => $pt->capacity,
                'figure' => config('app.url').$pt->figures->first()['path']
            ];
        }

        return $result;
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
            'figure_id' => $figure_id,
            'keep' => $request->keep,
            'comment' => $request->comment,
            'ft_ids' => $this->failureType->activeIds(),
            'created_choku' => $request->choku,
            'created_by' => $request->worker,
            'created_iPad_id' => $request->iPadId,
            'modificated_at' => $request->modificatedAt
            // 'y/m/d'
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
            if ($memo['partType']['left_pair'] !== null || $memo['partType']['right_pair'] !== null) {
                $memoArray['hasPair'] = true;
            }
            unset($memoArray['part_type']);

            return $memoArray;
        });

        return $memos;
    }
}
