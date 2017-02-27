<?php

namespace App\Http\Controllers\Press\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Repositories
use App\Repositories\InspectionResultRepository;
use App\Repositories\PartTypeRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\MemoRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReferenceController
 * @package App\Http\Controllers\V2\Manager
 */
class ReferenceController extends Controller
{
    protected $inspectionResult;
    protected $partType;
    protected $failureType;
    protected $memo;

    public function __construct (
        InspectionResultRepository $inspectionResult,
        PartTypeRepository $partType,
        FailureTypeRepository $failureType,
        MemoRepository $memo
    )
    {
        $this->inspectionResult = $inspectionResult;
        $this->partType = $partType;
        $this->failureType = $failureType;
        $this->memo = $memo;
    }

    public function allfailureTypes(Request $request)
    {
        $fts = $this->failureType->withDeactive();

        return [
            'data' => $fts,
            'message' => 'success'
        ];
    }

    public function serchInspection(Request $request)
    {
        $line = $request->l;
        $vehicle = $request->v;
        $pn = $request->p;
        $chokus = $request->chokus;
        $judge = $request->judge;
        $failureTypes = $request->failures;

        $start = Carbon::createFromFormat('Y-m-d H:i:s', $request->s.' 00:00:00')->addHours(6);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $request->e.' 00:00:00')->addHours(27);

        $irs = $this->inspectionResult->listForReference($line, $vehicle, $pn, $start, $end, $chokus, $judge, $failureTypes);

        $failureTypes = $this->failureType->getByIds($irs['ft_ids']);

        return [
            'data' => [
                'result_count' => $irs['result_count'],
                'failureTypes' => $failureTypes,
                'result' => $irs['irs']
            ],
            'message' => 'success'
        ];
    }


    public function serchMemo(Request $request)
    {
        $line = $request->l;
        $vehicle = $request->v;
        $pn = $request->p;
        // $chokus = $request->chokus;
        // $judge = $request->judge;
        // $failureTypes = $request->failures;

        $start = Carbon::createFromFormat('Y-m-d H:i:s', $request->s.' 00:00:00')->addHours(6);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $request->e.' 00:00:00')->addHours(27);

        $memos = $this->memo->listForReference($line, $vehicle, $pn, $start, $end);

        $failureTypes = $this->failureType->getByIds($memos['ft_ids']);

        return [
            'data' => [
                'result_count' => $memos['result_count'],
                'failureTypes' => $failureTypes,
                'result' => $memos['memos']
            ],
            'message' => 'success'
        ];
    }
}
