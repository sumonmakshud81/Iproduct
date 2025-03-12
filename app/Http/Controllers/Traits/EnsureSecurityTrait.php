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
  # This is a EnsureSecurityTrait trait.
  ##############################################################################
 */

namespace App\Http\Controllers\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Security\BlockList;
use App\Models\Security\IncomingRequest;

trait EnsureSecurityTrait
{
    protected function blockNow($username, $minutesToBlock, $action, $remarks)
    {
        $blockList = new BlockList();
        $blockList->username = $username;
        $blockList->action = $action;
        $blockList->remarks = $remarks;
        $blockList->unblock_at = Carbon::now()->addMinutes($minutesToBlock)->format('Y-m-d H:i:s');
        $blockList->save();
    }

    protected function logRequest($username, $payload = null, $feature, $ipAddress, $status = 1)
    {
        IncomingRequest::create([
            'username'      =>  $username,
            'payload'       =>  $payload,
            'feature'       =>  $feature,
            'ip_address'    =>  $ipAddress,
            'status'        =>  $status
        ]);
    }

    protected function isBlocked($username, $action)
    {
        $blockList = BlockList::where('username', $username)->where('unblock_at', '>', date('Y-m-d H:i:s'))->whereAction($action)->first();
        if($blockList) {
            return $blockList;
        }
        return false;
    }

    protected function blockedApiResponse(Request $request, $remarks)
    {
        return response()->json([
            'messages'  =>  [ trans('messages.feature_block', ['remarks' => $remarks]) ],
            'api_token' =>  $request->user()->api_token,
            'data'      =>  [],
            'code'      =>  422
        ], 200);
    }

    protected function checkIfAttemptLimitExceeds($username, $feature, $attemptLimit, $minutesOfInterval)
    {
        $maxNoOfAttempt = IncomingRequest::where('username',$username)
            ->whereFeature($feature)->whereStatus(false)
            ->whereRaw("TIMESTAMPDIFF(MINUTE, created_at, NOW()) < $minutesOfInterval")
            ->count();

        if($maxNoOfAttempt >= $attemptLimit) {
            return true;
        }

        return false;
    }

    protected function swapUserSession()
    {
        $login_cookie_id_mb = str_random(128);

        while(User::where('login_cookie_id_mb', $login_cookie_id_mb)->first()) {
            $login_cookie_id_mb = str_random(128);
        }

        auth()->user()->update(['login_cookie_id_mb' => $login_cookie_id_mb]);

        session()->put('_MB_FASTPAY_CK', $login_cookie_id_mb);
    }

}