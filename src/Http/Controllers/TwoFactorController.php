<?php

namespace Matondo\TwoFactorAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Matondo\TwoFactorAuth\Models\UsersDevices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class TwoFactorController extends Controller
{
    public function showTwoFactorForm(Request $request)
    {
        return view('twofactorauth::auth.twofactor', [
            'username' => $request->username,
            'tk' => $request->tk,
            'id' => $request->id,
            'msg' => $request->msg ?? ''
        ]);
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['2fa' => 'required']);

        $user = User::where('email', $request->username)->first();

        if (!$user) {
            return redirect()->route('2fa.form', [
                'msg' => 'user_not_found',
                'username' => $request->username
            ]);
        }

        if ($request->input('2fa') == $user->token_2fa && Carbon::now()->lt($user->token_2fa_expiry)) {
            $user->token_2fa_expiry = Carbon::now()->addDays(365); 
            $user->save();

            $device = UsersDevices::where('user_id', $user->id)
                                  ->where('token', $request->tk)
                                  ->first();

            if ($device) {
                $device->authorized = true;
                $device->save();

                $cookie_name = 'tk' . $user->id;
                return redirect(RouteServiceProvider::HOME)->withCookie(cookie()->forever($cookie_name, $request->tk));
            }

            return redirect(RouteServiceProvider::HOME);
        } else {
            return redirect()->route('2fa.form', [
                'msg' => 'invalid_code',
                'username' => $request->username,
                'tk' => $request->tk,
                'id' => $request->id
            ]);
        }
    }
}
