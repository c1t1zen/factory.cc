<?php

namespace App\Http\Controllers\Frontend\Web;

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
//use Illuminate\Http\Request;
use Request;
use App\Http\Requests;
use Config;
use DB;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class SearchController extends FrontendController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $perPage=5;
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

        $page_title = trans('frontend_search.page_title');
        $meta_desc = trans('frontend_search.meta_desc');
        $meta_key = trans('frontend_search.meta_key');
 
        $search_string = Request::get('search_keyword') !== null && !empty(Request::get('search_keyword')) ? Request::get('search_keyword') : '';   //get keywords input for search

        if ($search_string != '') {

            //Get current page form url e.g. &page=6
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            //Create a new Laravel collection from the array data
            //get search result form many models
            $searchResults = $this->search_news($search_string);
            $searchResults =  array_merge($searchResults,  $this->search_products($search_string));
 
            $countSearchResults=count($searchResults);
            
    //       $searchResults = $this->search_news($search_string);
            $collection = new Collection($searchResults);

            //Define how many items we want to be visible in each page
            $perPage = $this->perPage;

            //Slice the collection to get the items to display in current page
            $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

            //Create our paginator and pass it to the view
            $searchResult = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
 
        } else{
            $searchResult = [];
            $countSearchResults=0;
        }
        
        $this->data = compact('page_title', 'meta_desc', 'meta_key', 'searchResult', 'search_string','countSearchResults');
        return view(Config::get('front_theme') . '.search.index', $this->data);
    }

    function search_news($search_string = '') {
        $search_string = $search_string;
        $result = array();

        $search_result = \App\Models\News::where("name_".\App::getLocale(), "LIKE", "%$search_string%")
                        ->orWhere("description_".\App::getLocale(), "LIKE", "%$search_string%")
                        ->orWhere("content_".\App::getLocale(), "LIKE", "%$search_string%")
                        ->Where("is_published", "=", 1)->get(); //->get()->paginate(9);//->paginate(9);   

        foreach ($search_result as $row) {
            $s['model'] = 'اخبار'; 
            $s['date'] = $row->created_at;               
            $s['title'] = $row->name;
            $s['desc'] = $row->description;
            $s['url'] = route('news.item',$row->id);
            array_push($result, $s);
        }

        return $result;
    }
    
    
        function search_products($search_string = '') {
        $search_string = $search_string;
        $result = array();

        $search_result = \App\Models\Product::where("name", "LIKE", "%$search_string%")
                        ->orWhere("description", "LIKE", "%$search_string%")
                        ->orWhere("content", "LIKE", "%$search_string%")
                        ->get(); //->get()->paginate(9);//->paginate(9);   

        foreach ($search_result as $row) {
            $s['model'] = 'منتجات'; 
            $s['date'] = $row->created_at;               
            $s['title'] = $row->name;
            $s['desc'] = $row->description;
            $s['url'] = route('product.item',$row->id);
            array_push($result, $s);
        }

        return $result;
    }
    
    

    function search_section($search_string = '') {
        $search_string = urldecode($search_string);
        $result = array();
        $table = 'section';
        $CI = & get_instance();
        $search_array = explode(' ', $search_string);
//	$CI->db->where($table.'_c.lang', $CI->kitlang->get_lang());
        foreach ($search_array as $k => $v) {
            $CI->db->or_like($table . '_c.c_title', $v);
            $CI->db->or_like($table . '_c.c_content', $v);
            $CI->db->or_like($table . '_c.c_desc', $v);
        }
        $CI->db->join($table . '_c', $table . '.' . $table . '_id = ' . $table . '_c.' . $table . '_id and ' . $table . "_c.lang = '" . $CI->kitlang->get_lang() . "'");
        $search_result = $CI->db->get($table)->result();
        foreach ($search_result as $row) {
            $s['title'] = $row->c_title;
            $s['desc'] = $row->c_desc;
            $s['url'] = site_url('section_subject/show/' . $row->c_slug, FALSE);
            array_push($result, $s);
        }
        return $result;
    }

}
