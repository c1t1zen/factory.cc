<?php

namespace App\Http\Controllers\Frontend\Web;

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Config;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class AboutUsController extends FrontendController {

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
       
        $page_title='ِعن الوسيط';
        
        $data = \App\Models\AboutUs::where('id',1)->first();
        $meta_desc= $data->meta_desc;        
        $meta_key=$data->meta_key;                
        
        $this->data = compact('page_title','meta_desc','meta_key', 'data');        
        return view(Config::get('front_theme').'.about_us.index',$this->data);
    }
    
//    public function item($id,Request $request){
//        $title='برامج الشركة';
//        $products = \App\Models\Product::find($id);
//        $features_main = \App\Models\ProductArticle::where('category_id',$id)->where('feature_type',1);
//        $this->data = compact('title', 'products');        
//        return view(Config::get('front_theme').'.product.item',$this->data);        
//        
//    }



}
