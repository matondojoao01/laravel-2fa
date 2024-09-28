<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use App\Mail\TwoFactorMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Twilio\Rest\Client;  

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('twofactorauth::auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token_2fa = Str::random(6);
            $user->token_2fa = $token_2fa;
            $user->token_2fa_expiry = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new TwoFactorMail($token_2fa));

            if(!empty($user->phone))
            {
                SendSmsJob::dispatch($user->phone, $token_2fa);
            }

            $tk = md5($_SERVER['HTTP_USER_AGENT'] . $user->id);

            return redirect()->route('2fa.form', [
                'username' => $user->email,
                'tk' => $tk,
                'id' => $user->id,
                'msg' => ''
            ]);
        } else {
            return redirect()->back()->with('error', 'Credenciais incorretas.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
