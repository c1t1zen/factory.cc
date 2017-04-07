<?php

namespace App\Http\Controllers\Frontend\Web;

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Order;
use App\Models\PriceRequest;
use Config;
use Carbon\Carbon;
use Mail;
use App\Mail\SendMail;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class SendEmailController extends FrontendController {

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

    /**
     * Send My Test Mail Example
     *
     * @return void
     */
    public function requestTypeMail(Request $request)  {

         $this->validate($request, [

            'first_name' => 'required|max:25',
            'last_name' => 'required|max:25',
            'email' => 'required|email|max:255',
            'title' => 'required|max:100',
            'message' => 'required|max:500',
        ]);

        $input = $request->all();
 
 
        $sentToEmail = Config::get('settings.contact_email'); //'mahmoud.shedeed@gmail.com';

        $myEmail = New SendMail();

        $myEmail->addressFrom = $input['email'];
        $myEmail->addressCC = Config::get('settings.contact_cc');
        $myEmail->addressBC = Config::get('settings.contact_bcc');
        $myEmail->addressReplyTo = Config::get('settings.contact_reply_to');
        
        $myEmail->subject = ' ';
        if ($input['request_type'] == 'request_price') { 
            $myEmail->subject = 'طلب  عرض  اسعار  ';
        }
        if ($input['request_type'] == 'request_download') {
            $myEmail->subject = 'طلب نسخة تجريبية ';
        }
        if ($input['request_type'] == 'request_contact') {
             $myEmail->subject = '   اتصل بنا ';
        }
        $myEmail->msgTitle = $input['title'];        
        $myEmail->msgBody = $input['message'];
        $myEmail->first_name = $input['first_name'];
        $myEmail->last_name =  $input['last_name'];
        $emailSent = Mail::to($sentToEmail)->send($myEmail);

        if ($emailSent)
        // tell user it's done successfully
            return \Response::json(array('success' => true), 200);
//    	dd("Mail Send Successfully");
        
        
    }
    
    
    public function requestPriceTypeMail(Request $request)  {

         $this->validate($request, [

            'name' => 'required|max:100',
            'company' => 'required|max:100',
            'email' => 'required|email|max:255',
            'city' => 'required|max:100',
         //   'message' => 'required|max:500',
            'category_id' => 'required',             
            'users_count' => 'required|integer|max:99999',         
            'captcha' => 'required|captcha',                 
        ]);

        $input = $request->all();
//        $input['order_status_id']=1;
//        $input['order_date']= Carbon::today();
        // insert order in db
        $model = new PriceRequest($input);
        
        $insert=$model->save();
        
        if($insert){
            $sentToEmail = Config::get('settings.contact_request_price'); //'mahmoud.shedeed@gmail.com';
            $myEmail = New SendMail();

            $myEmail->addressFrom = $input['email'];
            $myEmail->addressCC = Config::get('settings.contact_cc');
            $myEmail->addressBC = Config::get('settings.contact_bcc');
            $myEmail->addressReplyTo = Config::get('settings.contact_reply_to');

            $myEmail->subject = ' ';

                if ($input['request_type'] == 'request_price') { 
                    $myEmail->subject = 'طلب  عرض  اسعار  ';
                    $myEmail->emailTemplate ='product_price_request_mail_tpl';                 
                    $myEmail->product =  $input['category_id'];   
                    $myEmail->users_count =  $input['users_count'];              
                }
        //    $myEmail->msgTitle = $input['title'];      
            $myEmail->company = $input['company'];          
            $myEmail->first_name = $input['name'];
            $myEmail->message = $input['message'];            
//            $myEmail->phone = $input['phone'];                        
//            $myEmail->mobile = $input['mobile'];            
            $myEmail->city = $input['city'];
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
