<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use App\Mail\TwoFactorMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('twofactorauth::auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|min:10|max:15|unique:users',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = str_replace(' ', '', $request->phone);
        $user->password = Hash::make($request->password);
        $user->save();

        $token_2fa = Str::random(6);
        $user->token_2fa = $token_2fa;
        $user->token_2fa_expiry = now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new TwoFactorMail($token_2fa));
        
        if(!empty($user->phone))
        {
            SendSmsJob::dispatch($user->phone, $token_2fa);
        }

        Auth::login($user);

        $tk = md5($_SERVER['HTTP_USER_AGENT'] . $user->id);

        return redirect()->route('2fa.form', [
            'username' => $user->email,
            'tk' => $tk,
            'id' => $user->id,
            'msg' => ''
        ]);
    }
}
