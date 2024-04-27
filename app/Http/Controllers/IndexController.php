<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @class IndexController
 */
class IndexController extends Controller
{
    /** Функция для вывода view
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        date_default_timezone_set('Europe/Moscow');
        $request->session()->put('_timeToLog', date('d-m-Y H:i:s'));
        return view('index');
    }
}
