<?php

namespace App\Http\Controllers\Frontend\Web;

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\ContactUs;
use Config;
use Carbon\Carbon;
use Mail;
use App\Mail\SendMail;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class ContactUsController extends FrontendController {

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
 
        $page_title              = 'اتصل بنا';     
        $meta_desc            = 'اتصل بنا';        
        $meta_key               = 'اتصل بنا';        
        
        $this->data = compact('page_title','meta_desc','meta_key');
        return view(Config::get('front_theme') . '.contact_us.index', $this->data);
    }


    public function contactUsTypeMail(Request $request) {

        $this->validate($request, [

            'name' => 'required|max:100',
          //  'company' => 'required|max:100',
            'email' => 'required|email|max:255',
        //    'title' => 'required|max:100',
            'message' => 'required|max:500',
            'captcha_contact_page' => 'required|captcha',
        ]);

        $input = $request->all();

        // insert order in db
        $model = new ContactUs($input);
        $insert = $model->save();

        if ($insert) {
            $sentToEmail = Config::get('settings.contact_request_price'); //'mahmoud.shedeed@gmail.com';
            $myEmail = New SendMail();

            $myEmail->addressFrom = $input['email'];
            $myEmail->addressCC = Config::get('settings.contact_cc');
            $myEmail->addressBC = Config::get('settings.contact_bcc');
            $myEmail->addressReplyTo = Config::get('settings.contact_reply_to');

            $myEmail->subject = ' ';

            if ($input['request_type'] == 'request_contact_us') {
                $myEmail->subject = '   اتصل بنا ';
            }

             $myEmail->msgTitle = $input['title'];        
            $myEmail->msgBody = $input['message'];
            $myEmail->first_name = $input['name'];
            //        $myEmail->last_name =  $input['last_name'];
            $emailSent = Mail::to($sentToEmail)->send($myEmail);

            if ($emailSent)
            // tell user it's done successfully
                return \Response::json(array('success' => true), 200);
        }else {
            return \Response::json(array('error' => true), 600);
        }
    }

}
