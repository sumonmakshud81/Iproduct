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
# This is ProfileController
##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\PA_DSS_Auth_Trait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use PA_DSS_Auth_Trait;

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => 'required|confirmed|between:6,32',
                'password_confirmation' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $user = User::findOrFail(auth()->user()->id);

            if (Hash::check($request->password, $user->password)) {
                return redirect()->back()->withErrors("Current password and Old Password should not be same");
            }

            if (Hash::check($request->current_password, $user->password)) {
                DB::beginTransaction();
                $user->password = Hash::make($request->password);
                $user->is_first_login = 0;
                $user->question = $request->question;
                $user->answer = $request->answer;
                $user->save();
                DB::commit();

                return redirect('/')->with(saveMessage(__('index.password_update_successfully')));
            } else {
                return redirect()->back()->withInput()->with(dangerMessage(__('index.current_password_mitchmatch')));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(dangerMessage(__('index.something_went_wrong')));
        }
    }

    /**
     * Change User Profile
     */
    public function changeProfile()
    {
        $title = __('index.change_profile');

        $user = User::findOrFail(auth()->user()->id);

        return view('pages.profile.change-profile', compact('title', 'user'));
    }

    /**
     * Update User Profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:filter',
            'phone_number' => 'required',
            'photo' => 'mimes:jpeg,jpg,png,gif|max:10000|dimensions:width=45,height=45',
        ],
            [
                'name.required' => __('index.name_required'),
                'email.required' => __('index.email_required'),
                'email.email' => __('index.email_validation'),
                'phone_number.required' => __('index.phone_required'),
                'photo.mimes' => __('index.photo_mimes'),
                'photo.dimensions' => __('index.profile_photo_dimensions'),
            ]);

        $photoNameToStore = '';
        if ($request->hasFile('photo')) {
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = $photo->getClientOriginalName();
                $photoNameToStore = time() . "_" . $filename;

                $photo->move(base_path() . '/uploads/user_photos/', $photoNameToStore);
            }
        }

        $user = User::findOrFail(auth()->user()->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->photo = ($photoNameToStore == '' ? $request->photo_old : $photoNameToStore);
        $user->save();

        return redirect()->back()->with(saveMessage());
    }

    public function securityQuestion()
    {
        $title = __('index.set_security_question');

        $file_path = "assets/sampleQustions.json";
        $json = file_get_contents($file_path);
        $questions = json_decode($json);

        $user = User::findOrFail(auth()->user()->id);

        return view('pages.profile.security-question', compact('title', 'user', 'questions'));
    }

    public function updateSecurityQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ],
            [
                'question.required' => __('index.question_required'),
                'answer.required' => __('index.answer_required'),
            ]);

        $user = User::findOrFail(auth()->user()->id);

        $user->question = $request->question;
        $user->answer = $request->answer;
        if ($user->is_first_login == 1) {
            $user->is_first_login = 0;
        }
        $user->save();

        return redirect()->route('set-security-question')->with(saveMessage());

    }
}
