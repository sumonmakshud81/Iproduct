<?php
/*
##############################################################################
# iProduction - Production and Manufacture Management Software
##############################################################################
# AUTHOR:        Door Soft
##############################################################################
# EMAIL:        info@doorsoft.co
##############################################################################
# COPYRIGHT:        RESERVED BY Door Soft
##############################################################################
# WEBSITE:        https://www.doorsoft.co
##############################################################################
# This is AdminController Controller
##############################################################################
 */

namespace App\Http\Controllers;

use App\AdminSettings;
use App\Company;
use App\Http\Controllers\Controller;
use App\Timezone;
use App\WhiteLabelSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class AdminController extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function settings()
    {
        $settingsInfo = AdminSettings::all();
        $time_zone_list = Timezone::get();
        if (!$settingsInfo->first()) {
            $settingsInfo = null;
        } else {
            $settingsInfo = $settingsInfo->first();
        }
        return view('pages.settings', compact('settingsInfo', 'time_zone_list'));
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function setting_update(Request $request)
    {

        request()->validate([
            'name_company_name' => 'required|string|max:150',
            'contact_person' => 'required|string|max:200',
            'phone' => 'required|max:50',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'address' => 'required|max:250',
            'currency' => 'required|max:3',
            'currency_position' => 'required',
            'precision' => 'required',
            'decimals_separator' => 'required',
            'thousands_separator' => 'required',
            'time_zone' => 'required',
            'date_format' => 'required',
            'logo' => 'mimes:jpeg,jpg,png|max:10000|dimensions:width=230,height=50',
        ],
            [
                'name_company_name.required' => __('index.company_name_required'),
                'contact_person.required' => __('index.contact_person_required'),
                'phone.required' => __('index.phone_required'),
                'email.required' => __('index.email_required'),
                'email.regex' => __('index.email_validation'),
                'address.required' => __('index.address_required'),
                'currency.required' => __('index.currency_required'),
                'currency_position.required' => __('index.currency_position_required'),
                'precision.required' => __('index.precision_required'),
                'decimals_separator.required' => __('index.decimals_separator_required'),
                'thousands_separator.required' => __('index.thousands_separator_required'),
                'time_zone.required' => __('index.time_zone_required'),
                'date_format.required' => __('index.date_format_required'),
                'logo.mimes' => __('index.logo_mimes'),
                'logo.dimensions' => __('index.logo_dimensions'),
            ]
        );
        $logoNameToStore = '';
        if ($request->hasFile('logo')) {

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = $image->getClientOriginalName();
                $logoNameToStore = time() . "_" . $filename;
                $image_resize = Image::make($image->getRealPath());
                $image_resize->save(base_path() . '/uploads/settings/' . $logoNameToStore);
            }
        }
        DB::beginTransaction();
        try {
            $settings = AdminSettings::first();
            $settings->name_company_name = $request->name_company_name;
            $settings->contact_person = $request->contact_person;
            $settings->phone = $request->phone;
            $settings->email = $request->email;
            $settings->address = $request->address;
            $settings->currency = $request->currency;
            $settings->currency_position = $request->currency_position;
            $settings->precision = $request->precision;
            $settings->decimals_separator = $request->decimals_separator;
            $settings->thousands_separator = $request->thousands_separator;
            $settings->time_zone = $request->time_zone;
            $settings->web_site = $request->web_site;
            $settings->date_format = $request->date_format;
            $settings->logo = ($logoNameToStore == '' ? $request->logo_old : $logoNameToStore);
            $settings->save();

            $company = Company::first();
            $company->company_name = $request->name_company_name;
            $company->contact_person = $request->contact_person;
            $company->phone = $request->phone;
            $company->email = $request->email;
            $company->address = $request->address;
            $company->website = $request->web_site;
            $company->currency = $request->currency;
            $company->timezone = $request->time_zone;
            $company->date_format = $request->date_format;
            $company->logo = ($logoNameToStore == '' ? $request->logo_old : $logoNameToStore);
            $company->save();
            DB::commit();
            return redirect()->back()->with(updateMessage());

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(dangerMessage($e->getMessage()));
        }

    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function whiteLabel()
    {
        $whiteLabel = WhiteLabelSettings::where('del_status', 'Live');
        if ($whiteLabel->count()) {
            $whiteLabelInfo = null;
        } else {
            $whiteLabelInfo = $whiteLabel->first();
        }
        return view('pages.white-label', compact('whiteLabelInfo'));
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function whiteLabelUpdate(Request $request)
    {

        request()->validate([
            'site_title' => 'required|string|max:150',
            'footer' => 'required|string|max:150',
            'company_name' => 'required|string|max:150',
            'logo' => 'mimes:jpeg,jpg,png|max:10000|dimensions:width=230,height=50',
            'mini_logo' => 'mimes:jpeg,jpg,png|max:10000|dimensions:width=60,height=60',
            'favicon' => 'mimes:ico|max:50',
        ],
            [
                'site_title.required' => __('index.site_title_required'),
                'footer.required' => __('index.footer_required'),
                'company_name.required' => __('index.company_name_required'),
                'logo.mimes' => __('index.logo_mimes'),
                'logo.dimensions' => __('index.logo_dimensions'),
                'mini_logo.mimes' => __('index.mini_logo_mimes'),
                'mini_logo.dimensions' => __('index.mini_logo_dimensions'),
            ]
        );

        $faviconNameToStore = '';
        if ($request->hasFile('favicon')) {
            if ($request->hasFile('favicon')) {
                $favicon = $request->file('favicon');
                $filename = $favicon->getClientOriginalName();
                $faviconNameToStore = time() . "_" . $filename;

                $favicon->move(base_path() . '/uploads/white_label/', $faviconNameToStore);
            }
        }

        $logoNameToStore = '';
        if ($request->hasFile('logo')) {
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = $image->getClientOriginalName();
                $logoNameToStore = time() . "_" . $filename;
                $image_resize = Image::make($image->getRealPath());
                $image_resize->save(base_path() . '/uploads/white_label/' . $logoNameToStore);
            }
        }

        $miniLogoNameToStore = '';
        if ($request->hasFile('mini_logo')) {
            if ($request->hasFile('mini_logo')) {
                $image = $request->file('mini_logo');
                $filename = $image->getClientOriginalName();
                $miniLogoNameToStore = time() . "_" . $filename;
                $image_resize = Image::make($image->getRealPath());
                $image_resize->save(base_path() . '/uploads/white_label/' . $miniLogoNameToStore);
            }
        }

        $whiteLabelInfo = WhiteLabelSettings::firstOrNew();
        $whiteLabelInfo->site_title = $request->site_title;
        $whiteLabelInfo->footer = $request->footer;
        $whiteLabelInfo->company_name = $request->company_name;
        $whiteLabelInfo->company_website = $request->company_website;
        $whiteLabelInfo->favicon = ($faviconNameToStore == '' ? $request->favicon_old : $faviconNameToStore);
        $whiteLabelInfo->logo = ($logoNameToStore == '' ? $request->logo_old : $logoNameToStore);
        $whiteLabelInfo->mini_logo = ($miniLogoNameToStore == '' ? $request->mini_logo_old : $miniLogoNameToStore);
        $whiteLabelInfo->save();

        return redirect()->back()->with(updateMessage());
    }
}
