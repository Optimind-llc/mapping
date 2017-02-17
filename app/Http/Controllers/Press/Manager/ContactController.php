<?php

namespace App\Http\Controllers\Press\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Vehicle950A\Choku;
// Models
use App\Models\Vehicle950A\Worker;
// Repositories
use App\Repositories\InspectionResultRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReportController
 * @package App\Http\Controllers\Press\Manager
 */
class ContactController extends Controller
{
    protected $inspectionResult;

    public function __construct (InspectionResultRepository $inspectionResult)
    {
        $this->inspectionResult = $inspectionResult;
    }

    public function check()
    {
        return 'Nothing';
    }

    public function export()
    {
        return 'Nothing';
    }
}
