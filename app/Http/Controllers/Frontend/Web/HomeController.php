<?php

namespace App\Http\Controllers\Frontend\Web;

use App\Http\Controllers\Frontend\FrontendController;
use Config;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends FrontendController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
//        echo $this->basePublic;
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index() {
       
        
 
        return view(Config::get('front_web') . '.home', $this->data);
    }

}
