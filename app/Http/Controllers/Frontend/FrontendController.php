<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BasePublicController;
use Config;

class FrontendController extends BasePublicController {

    //
    public function __construct() {
        parent::__construct();
        //    $this->dashboardPath = Config::get('front_theme') . '.dashboard' ;
        $this->share_data();
    }

 
    public function share_data() {

       
       view()->share('menu_items', $menu_items =  \App\Models\MenuItem::getTree());        
       
     //   view()->share('product', $products = \App\Models\Product::all()->pluck('name', 'id')->all());      //->pluck('name', 'id')->all()     
        view()->share('users_count', $users_count=array(1 =>1,2 =>2,3 =>3,4 =>4,5 =>5));      //->pluck('name', 'id')->all()     
        
        // contact data
        view()->share('contact_phone', $contact_phone = \App\Models\Setting::where('key', 'contact_phone')->first());
        view()->share('contact_whats_up_no', $contact_whats_up_no = \App\Models\Setting::where('key', 'contact_whats_up_no')->first());        
        view()->share('contact_address', $contact_address = \App\Models\Setting::where('key', 'contact_address')->first());      //->pluck('name', 'id')->all()  
        view()->share('contact_email', $contact_email = \App\Models\Setting::where('key', 'contact_email')->first());      //->pluck('name', 'id')->all()     
        view()->share('socials',$socials = \App\Models\Setting::where('key', 'LIKE', '%social_%')->get());      //->pluck('name', 'id')->all()             
        
    }

}
