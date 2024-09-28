<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersDevices;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends controller
{
    public function verifyTwoFactor(Request $request)
    {

        $request->validate([
            '2fa' => 'required',
        ]);
        $user = User::where('email', '=', $request->input('username'))->first() ??  Auth::user();

        if ($request->input('2fa') == $user->token_2fa) {

            $user->token_2fa_expiry = \Carbon\Carbon::now()->addDays(365);

            $user->save();

            $dev = UsersDevices::where('user_id', '=', $request->id)->where('token', '=', $request->tk)->count();

            if ($dev >= 1) {
                $mydev = UsersDevices::where('user_id', '=', $request->id)->where('token', '=', $request->tk)->first();
                $mydev->aut = 1;
                $mydev->save();

                $str = preg_replace('/[^A-Za-z0-9. -]/', '',  env('APP_URL'));
                $str = str_replace('.', '', $str);
                $cookie_name = 'tk' . $user->id . $str;
                return redirect(RouteServiceProvider::HOME)->withCookie(cookie()->forever($cookie_name, $request->tk));
            } else {
                $mydev = new UsersDevices();
                $mydev->user_id = $request->id;
                $mydev->token = $request->tk;
                $mydev->aut = 1;
                $mydev->save();

                $str = preg_replace('/[^A-Za-z0-9. -]/', '',  env('APP_URL'));
                $str = str_replace('.', '', $str);
                $cookie_name = 'tk' . $user->id . $str;
                return redirect(RouteServiceProvider::HOME)->withCookie(cookie()->forever($cookie_name, $request->tk));
            }
        } 
        return redirect()->route('2fa.form', [
            'username' => $user->email,
            'tk' => $user->token_2fa,
            'id' => $user->id,
            'msg' => ''
        ]);
    }

    public function showTwoFactorForm(Request $request)
    {
        if (isset($request['msg'])) {
            $msg = $request['msg'];
        } else {
            $msg = "";
        }
        return view('twofactorauth::auth.twofactor', ['username' => $request->username, 'msg' => $msg, 'tk' => $request['tk'], 'id' => $request['id']]);
    }
}
