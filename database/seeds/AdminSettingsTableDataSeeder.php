<?php

use Illuminate\Database\Seeder;

class AdminSettingsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $about_us_short = "Stay at Home, Stay Safe and get your doctor consultancy from your home. Please signup to get consultancy from an expert doctor. Are you an experienced doctor with having service excellence in health profession? Please signup and contribute in good of social and heathcare.";
        $about_us_long = "Angel Doctor is an Online Doctor Consultancy Platform. You are no more needed to go to doctor&#39;s chamber for your doctor consultancyRequests. Stay Home, Saty Safe and get your consultancyRequests service from your home. We have lot of high profile doctors who were working with excellence. AND for your information our administration panel does not approve any low profile doctor to ensure best health service.";
        $address = "House: 18, Road: 6, Nikunja 2, Khilkhet, Dhaka.";
        \App\AdminSettings::create(['name_company_name' => 'Angel Doctor', 'slogan' => 'The Doctor is No Far', 'phone_number' => '+880-181-2391633', 'email' => 'info@doorsoft.co', 'address' => $address, 'logo' => 'angel_doctor.png', 'favicon' => 'angel_doctor.png', 'first_section_image' => 'angel_doctor.png', 'language' => 'en', 'about_us_short' => $about_us_short, 'about_us_long' => $about_us_long, 'interesting_fact' => 'pick from db', 'date_format' => 'D/M/Y', 'currency' => 'BDT', 'time_zone' => 'Asia/Dhaka', 'created_by' => 1, 'updated_by' => 1]);
    }
}
