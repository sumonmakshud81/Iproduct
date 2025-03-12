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
  # This is a PA_DSS_Auth_Trait trait.
  ##############################################################################
 */

namespace App\Http\Controllers\Traits;


use Illuminate\Support\Facades\Log;
use Auth;
use Hash;
use Carbon\Carbon;
use App\User;

trait PA_DSS_Auth_Trait
{
    protected $passwordRegexPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\d)(?=.*[$%&_+-.@#]).+$/";
    protected $passwordMustBeExpiredAfterEveryXDays = 90;
    protected $passwordMustBeDifferentFromXPreviousPassword = 5;
    protected $passwordUpdateUponFirstLoginMessage = "Since your current password is system generated this needs to be changed. Please set your own password for future login.";
    protected $eligibleForAgeGreaterThanOrEqualTo = 14;
    protected $whitelistedCharacters = "$ % & _ + - . @ #";
    protected $exampleStrongPassword = 'mY$trongP7Word';

    /**
     * Check if password expired
     *
     * @param User $user
     * @return bool
     */
    protected function isPasswordExpired(User $user)
    {
        $passwordHistory = PasswordHistory::whereUserId($user->id)->latest()->firstOrFail();

        $expiresAt = Carbon::parse($passwordHistory->created_at)->addDays($this->passwordMustBeExpiredAfterEveryXDays);

        if(Carbon::now() > $expiresAt) {
            return true;
        }

        return false;
    }

    /**
     * Check if it's first time login or not
     *
     * @param User $user
     * @return bool
     */
    protected function isFirstLogin(User $user)
    {
        Log::info("Is First Login :".$user->is_first_login);

        if( $user->is_first_login ) {
            return true;
        }

        return false;
    }

    /**
     * Check if password matches with any previous password
     *
     * @param User $user
     * @param $newPassword
     * @return bool
     */
    protected function isPasswordMatchesWithXPreviousPassword(User $user, $newPassword)
    {
        $matches = false;

        $passwordHistories = PasswordHistory::where('user_id',$user->id)
                            ->latest()
                            ->limit( $this->passwordMustBeDifferentFromXPreviousPassword )
                            ->get();

        foreach($passwordHistories as $passwordHistory) {
            if (\Hash::check($newPassword, $passwordHistory->password)) {
                $matches = true;
                break;
            }
        }

        return $matches;
    }

    /**
     * Save new password in password histories table
     *
     * @param $userId
     * @param $updatedPassword
     */
    protected function saveAsPasswordHistory($userId, $updatedPassword)
    {
        PasswordHistory::create([
            'user_id'   =>  $userId,
            'password'  =>  $updatedPassword
        ]);
    }

    /**
     * Password expiration message to display
     *
     * @return string
     */
    protected function passwordExpirationMessage()
    {
        return "It's been " . $this->passwordMustBeExpiredAfterEveryXDays . " days since you changed your password. Please update your current password with a new one.";
    }

    /**
     * Password pattern message to display
     *
     * @return string
     */
    protected function passwordRegexPatternMessage()
    {
        return "Password must contain at least one uppercase letter, one lowercase letter, one number and one special character among {$this->whitelistedCharacters}. For example: {$this->exampleStrongPassword}";
    }

    /**
     * Message to display if password matches with any of the previous X passwords
     *
     * @return string
     */
    protected function passwordMatchesWithXPreviousPasswordMessage()
    {
        return "Sorry! the password you are tyring to set matches with one of the " . $this->passwordMustBeDifferentFromXPreviousPassword . " previous passwords. Please try a different one.";
    }

    /**
     * Prompt password change upon first login message
     *
     * @return string
     */
    protected function passwordUpdateUponFirstLoginMessage()
    {
        return $this->passwordUpdateUponFirstLoginMessage;
    }
}