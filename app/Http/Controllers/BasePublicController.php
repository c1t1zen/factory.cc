<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Auth;

class BasePublicController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $basePublic = "Var Base BasePublicController";
    protected $data = [];
    protected $currentRoute;
    protected $routeName = "";
    public $userId = '';

    public function __construct() {

        $this->getPreviousRoute();
        if (Auth::check())// user is logged
            $this->userId = Auth::user()->id;
    }

    public function getPreviousRoute() {
        $this->routeName = \Request::route() ? \Request::route()->getName() : '';
        if (!Session::has('PreviousRoute')) {
            Session::put('PreviousRoute', $this->routeName);
        }
        return Session::put('PreviousRoute', $this->routeName);
        ;
    }

}
