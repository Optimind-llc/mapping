<?php

namespace App\Http\Controllers\Press\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\Worker;
// Repositories
use App\Repositories\VehicleRepository;
use App\Repositories\LineRepository;
use App\Repositories\PartTypeRepository;
use App\Repositories\CombinationRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class InitialController
 * @package App\Http\Controllers\Press\Manager
 */
class InitialController extends Controller
{
    protected $vehicle;
    protected $line;
    protected $partType;
    protected $combination;

    public function __construct (
        VehicleRepository $vehicle,
        LineRepository $line,
        PartTypeRepository $partType,
        CombinationRepository $combination
    )
    {
        $this->vehicle = $vehicle;
        $this->line = $line;
        $this->partType = $partType;
        $this->combination = $combination;
    }

    public function all()
    {
        return [ 'data' => [
            'chokus' => [],
            'vehicles' => $this->vehicle->onlyActive(),
            'lines' => $this->line->onlyActive(),
            'parts' => $this->partType->onlyActive(),
            'combinations' => $this->combination->onlyActive()
        ]];
    }
}
























