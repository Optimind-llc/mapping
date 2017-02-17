<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ShowController
 * @package App\Http\Controllers
 */
class PagesController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $hotReload = env('HOT_RELOAD');
        $domain = env('APP_URL');

        return view('manager.index', compact('hotReload', 'domain'));
    }
}