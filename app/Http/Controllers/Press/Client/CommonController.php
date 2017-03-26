<?php

namespace App\Http\Controllers\Press\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Repositories
use App\Repositories\WorkerRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CommonController
 * @package App\Http\Controllers\Press\Client
 */
class CommonController extends Controller
{
    protected $worker;

    public function __construct (WorkerRepository $worker)
    {
        $this->worker = $worker;
    }

    public function worker(Request $request)
    {
        $line = $request->line;
        $chokus = $request->chokus;

        return [
            'workers' => $this->worker->filteredByLine($line),
        ];
    }
}
