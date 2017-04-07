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
class ClientController extends FrontendController {

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
       

        
        $data = \App\Models\Client::orderBy('id', 'desc')->paginate(9);
        $page_title=    trans('frontend_clients.page_title') ;
        $meta_desc= trans('frontend_clients.meta_desc');        
        $meta_key=trans('frontend_clients.meta_key');
        
        $this->data = compact('page_title','meta_desc','meta_key', 'data');        
        return view(Config::get('front_theme').'.client.index',$this->data);
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
