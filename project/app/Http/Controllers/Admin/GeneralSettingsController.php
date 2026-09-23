<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Services\PaymentInstructionService;
use Validator;

class GeneralSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected $rules =
    [
        'logo'              => 'mimes:jpeg,jpg,png,svg',
        'favicon'           => 'mimes:jpeg,jpg,png,svg',
        'error_photo'       => 'mimes:jpeg,jpg,png,svg',
        'footer_logo'       => 'mimes:jpeg,jpg,png,svg',
		'lazy_baner'       => 'mimes:jpeg,jpg,png,svg',
		'og_baner'       => 'mimes:jpeg,jpg,png,svg',
    ];

    private function setEnv($key, $value,$prev)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . $prev,
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }


    public function update(Request $request){
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = GeneralSettings::findOrFail(1);
        $input  = $request->all();

        $imageFields = [
            'logo' => 'assets/images/logo',
            'footer_logo' => 'assets/images/logo',
            'lazy_baner' => 'assets/images/logo',
            'og_baner' => 'assets/images/logo',
            'favicon' => ['dir' => 'assets/images', 'options' => ['max_bytes' => 204800, 'min_bytes' => 0, 'max_width' => 512, 'max_height' => 512, 'convert' => true]],
            'error_photo' => 'assets/images',
        ];

        foreach ($imageFields as $field => $config) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $dir = is_array($config) ? $config['dir'] : $config;
            $options = is_array($config) ? ($config['options'] ?? []) : [];

            admin_delete_image($dir, $data?->{$field});
            $input[$field] = admin_upload_image($request->file($field), $dir, $options);
        }

        if($request->captcha_secret_key){

            $this->setEnv('NOCAPTCHA_SECRET',$request->captcha_secret_key,env('NOCAPTCHA_SECRET'));
        }
        if($request->captcha_site_key){
            $this->setEnv('NOCAPTCHA_SITEKEY',$request->captcha_site_key,env('NOCAPTCHA_SITEKEY'));
        }
        $mode = $request->input('payment_mode', $data->payment_mode ?? 'sms');
        if (!in_array($mode, ['sms', 'manual'], true)) {
            $mode = 'sms';
        }
        $input['payment_mode'] = $mode;
        $input['payment_auto_approve'] = ($mode === 'manual' && $request->boolean('payment_auto_approve')) ? 1 : 0;
        foreach (['bkash', 'nagad', 'rocket'] as $provider) {
            $key = "payment_{$provider}_account_type";
            $input[$key] = PaymentInstructionService::normalizeType($request->input($key));
        }
        $data->update($input);
        $msg = 'Data Updated Successfully';
        return response()->json($msg);

    }

    public function logo(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.logo',compact('data'));
    }
    public function favicon(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.favicon',compact('data'));
    }
    public function websiteContent(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.websiteContent',compact('data'));
    }
    public function popularTags(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.popularTags',compact('data'));
    }
    public function footer(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.footer',compact('data'));
    }
    public function errorPage(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.errorPage',compact('data'));
    }

    public function tawkto($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_talkto =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_talkto =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function disqus($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_disqus =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_disqus =0;
            $data->update();
            return response()->json($id);
        }

    }
    public function capcha($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_capcha =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_capcha =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function emailVerfication($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_verification_email =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_verification_email =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function smtp($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_smtp =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_smtp =0;
            $data->update();
            return response()->json($id);
        }

    }


}
