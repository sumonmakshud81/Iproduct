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
# This is UserLoginController
##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\PA_DSS_Auth_Trait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserLoginController extends Controller
{
    use PA_DSS_Auth_Trait;

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function index()
    {
        return view('pages.login');
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email:filter',
            'password' => ['required'],
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {


            if (auth()->user()->status == 'Inactive') {
                auth()->logout();
                return redirect()->back()->withInput()->with(dangerMessage(__('index.user_not_active')));
            }

            if ($this->isFirstLogin(auth()->user())) {
                return redirect('profile/edit-credentials');
            }

            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->withInput()->with(dangerMessage(__('index.invalid_credentials')));
        }

    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function logout()
    {
        auth()->user()->logout();
        return redirect()->route('index')->with(saveMessage(__('index.logout_successfully')));
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function forceLogout()
    {
        auth()->logout();
        return redirect('/login');
    }

    public function forgotPasswordStepOne()
    {
        return view('pages.authentication.forgotPasswordStepOne');
    }

    public function forgotPasswordStepTwo()
    {
        $file_path = "assets/sampleQustions.json";
            $json = file_get_contents($file_path);
            $questions = json_decode($json);
        return view('pages.authentication.forgotPasswordStepTwo', compact('questions'));
    }

    public function forgotPasswordStepFinal()
    {
        return view('pages.authentication.forgotPasswordStepFinal');
    }

    public function postStepOne(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email:filter|exists:tbl_users,email',
        ],
            [
                'email.exists' => __('index.email_not_found'),
                'email.email' => __('index.email_validation'),
                'email.required' => __('index.email_required'),
            ]);


        $userData = User::where('email', $request->email);

        if ($userData->count() > 0) {
            $userInfo = $userData->first();
            $request->session()->put('user_id', $userInfo->id);

            return redirect()->route('forgot-password-step-two');
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function postStepTwo(Request $request)
    {
        $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',
        ],
            [
                'question.required' => __('index.question_required'),
                'answer.required' => __('index.answer_required'),
            ]);

        $question = $request->question;
        $answer = $request->answer;
        $user = User::findOrFail(session('user_id'));
        $matchQuestion = $user->question;
        $matchAnswer = $user->answer;

        if ($matchQuestion == $question) {
            if ($matchAnswer == $answer) {

                return redirect()->route('forgot-password-step-final');
            } else {
                return redirect()->back()->with(dangerMessage(__('index.incorrect_answer')));
            }
        } else {
            return redirect()->back()->with(dangerMessage(__('index.incorrect_question')));
        }
    }

    public function postStepFinal(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|between:6,32',
            'password_confirmation' => 'required',
        ],
            [
                'password.required' => __('index.password_required'),
                'password.confirmed' => __('index.current_password_not_match'),
                'password.between' => __('index.password_length'),
                'password_confirmation.required' => __('index.password_confirmation_required'),
            ]);

        $user = User::findOrFail(session('user_id'));
        $user->password = Hash::make($request->password);
        $user->save();
        session()->forget('user_id');
        return redirect()->route('login')->with(saveMessage(__('index.password_change_successfully')));
    }
}
