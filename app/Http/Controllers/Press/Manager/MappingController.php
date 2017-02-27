<?php

namespace App\Http\Controllers\Press\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Repositories
use App\Repositories\InspectionResultRepository;
use App\Repositories\PartTypeRepository;
// Services
use App\Services\Choku;
// Models
use App\Models\Press\FailureType;
use App\Models\Press\Figure;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MappingController
 * @package App\Http\Controllers\V2\Manager
 */
class MappingController extends Controller
{
    protected $inspectionResult;
    protected $partType;

    public function __construct (
        InspectionResultRepository $inspectionResult,
        PartTypeRepository $partType
    )
    {
        $this->inspectionResult = $inspectionResult;
        $this->partType = $partType;
    }

    public function getData(Request $request)
    {
        $line = $request->line;
        $vehicle = $request->vehicle;
        $pn = $request->part;
        $chokus = $request->chokus;

        if ($request->narrowedBy !== 'realtime') {
            $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->s.' 00:00:00')->addHours(3);
            $end_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->e.' 00:00:00')->addHours(27);
        }
        else {
            $choku = new Choku(Carbon::today());
            $start_at = $choku->getLastChokuStart();
            $end_at = Carbon::now();
            $chokus = ['W', 'Y', 'B'];
        }

        $pair = $this->partType->hasPair($pn);

        $parts = [];
        if ($pair) {
            if($pair['self'] === 'left') {
                $left_F = $this->inspectionResult->listForMapping($line, $vehicle, $pn, $start_at, $end_at, $chokus);

                $parts[] = [
                    'figure' => Figure::where('pt_pn', '=', $pn)->where('status', '=', 1)->first()->path,
                    'failures' => $left_F['failures'],
                    'pn' => $pn
                ];

                $right_F = $this->inspectionResult->listForMapping($line, $vehicle, $pair['pairPn'], $start_at, $end_at, $chokus);

                $parts[] = [
                    'figure' => Figure::where('pt_pn', '=', $pair['pairPn'])->where('status', '=', 1)->first()->path,
                    'failures' => $right_F['failures'],
                    'pn' => $pair['pairPn']
                ];
            }
            elseif($pair['self'] === 'right') {
                $left_F = $this->inspectionResult->listForMapping($line, $vehicle, $pair['pairPn'], $start_at, $end_at, $chokus);

                $parts[] = [
                    'figure' => Figure::where('pt_pn', '=', $pair['pairPn'])->where('status', '=', 1)->first()->path,
                    'failures' => $left_F['failures'],
                    'pn' => $pair['pairPn']
                ];

                $right_F = $this->inspectionResult->listForMapping($line, $vehicle, $pn, $start_at, $end_at, $chokus);

                $parts[] = [
                    'figure' => Figure::where('pt_pn', '=', $pn)->where('status', '=', 1)->first()->path,
                    'failures' => $right_F['failures'],
                    'pn' => $pn
                ];
            }

            $ft_ids = $left_F['ft_ids']->merge($right_F['ft_ids']->toArray())->unique();
        }
        else {
            $single_FF = $this->inspectionResult->listForMapping($line, $vehicle, $pn, $start_at, $end_at, $chokus);

            $parts[] = [
                'figure' => Figure::where('pt_pn', '=', $pn)->where('status', '=', 1)->first()->path,
                'failures' => $single_FF['failures'],
                'pn' => $pn
            ];

            $ft_ids = $single_FF['ft_ids'];
        }

        $failureTypes = FailureType::whereIn('id', $ft_ids)
            ->orderBy('label')
            ->select(['id', 'name'])
            ->get();

        return [
            'data' => [
                'parts' => $parts,
                'failureTypes' => $failureTypes
            ]
        ];
    }
}








