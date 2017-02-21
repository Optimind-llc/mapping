<?php

namespace App\Http\Controllers\Press\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Services\Choku;
// Repositories
use App\Repositories\InspectionResultRepository;
use App\Repositories\LineRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReportController
 * @package App\Http\Controllers\V2\Manager
 */
class ReportController extends Controller
{
    protected $inspectionResult;
    protected $line;

    public function __construct (
        InspectionResultRepository $inspectionResult,
        LineRepository $line
    )
    {
        $this->inspectionResult = $inspectionResult;
        $this->line = $line;
    }

    public function check(Request $request)
    {
        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $request->date.' 00:00:00');

        $choku = new Choku($date_obj);
        $start = $choku->getDayStart();
        $end = $choku->getDayEnd();

        $inspectionResults = $this->inspectionResult
            ->forReport($start, $end, $request->choku)
            ->groupBy('line_code')
            ->map(function($l) {
                return $l->count();
            })
            ->toArray();

        return [
            'message' => 'success',
            'data' => $inspectionResults
        ];
    }

    public function export($process, $inspection, $line, $pn, $date, $choku)
    {
        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');
        $choku_obj = new Choku($date_obj);
        $start = $choku_obj->getDayStart();
        $end = $choku_obj->getDayEnd();

        return $this->inspectionResult->listForReport($process, $inspection, $line, $pn, $start, $end, $choku)->toArray();
    }
}
