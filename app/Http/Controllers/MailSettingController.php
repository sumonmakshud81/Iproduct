<?php
/*
  ##############################################################################
  # iProduction - Production and Manufacture Management Software
  ##############################################################################
  # AUTHOR:		Door Soft
  ##############################################################################
  # EMAIL:		info@doorsoft.co
  ##############################################################################
  # COPYRIGHT:		RESERVED BY Door Soft
  ##############################################################################
  # WEBSITE:		https://www.doorsoft.co
  ##############################################################################
  # This is MailSettingController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\MailSetting;
use Illuminate\Http\Request;

class MailSettingController extends Controller
{
    public function index()
    {
        $jsonString = file_get_contents('assets/json/smtp.json');
        $mail_setting_info = json_decode($jsonString, true);
        return view('pages.mail_setting', compact('mail_setting_info'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'mail_driver' => 'required|string|max:100',
            'mail_host' => 'required|string|max:100',
            'mail_port' => 'required|max:100',
            'mail_encryption' => 'required|string|max:15',
            'mail_username' => 'required|email|string|max:100',
            'mail_password' => 'required|string|max:100',
            'mail_from' => 'required|email|string|max:100',
            'mail_fromname' => 'required|string|max:100',
            'api_key' => 'required|string|max:200',
        ],
            [
                'mail_driver.required' => __('index.mail_driver_required'),
                'mail_host.required' => __('index.host_address_required'),
                'mail_port.required' => __('index.port_address_required'),
                'mail_encryption.required' => __('index.encryption_required'),
                'mail_username.required' => __('index.username_required'),
                'mail_password.required' => __('index.password_required'),
                'mail_from.required' => __('index.from_required'),
                'mail_fromname.required' => __('index.mail_fromname_required'),
                'api_key.required' => __('index.api_key_required'),
            ]
    );

        $mail_setting_info = MailSetting::first();
        $mail_setting_info->mail_driver = $request->mail_driver;
        $mail_setting_info->mail_host = $request->mail_host;
        $mail_setting_info->mail_port = $request->mail_port;
        $mail_setting_info->mail_encryption = $request->mail_encryption;
        $mail_setting_info->mail_username = $request->mail_username;
        $mail_setting_info->mail_password = $request->mail_password;
        $mail_setting_info->mail_from = $request->mail_from;
        $mail_setting_info->from_name = $request->mail_fromname;

        if ($mail_setting_info->save()) {
            $smtp_data['mail_driver'] = $request->mail_driver;
            $smtp_data['mail_host'] = $request->mail_host;
            $smtp_data['mail_port'] = $request->mail_port;
            $smtp_data['mail_encryption'] = $request->mail_encryption;
            $smtp_data['mail_username'] = $request->mail_username;
            $smtp_data['mail_password'] = $request->mail_password;
            $smtp_data['mail_from'] = $request->mail_from;
            $smtp_data['from_name'] = $request->mail_fromname;
            $smtp_data['api_key'] = $request->api_key;
            $newJsonString = json_encode($smtp_data, JSON_PRETTY_PRINT);

            file_put_contents(base_path('assets/json/smtp.json'), stripslashes($newJsonString));
            return redirect()->back()->with(saveMessage());
        } else {
            return redirect()->back()->with(waringMessage());
        }
    }
}
