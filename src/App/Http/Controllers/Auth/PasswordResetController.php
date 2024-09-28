<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use App\Mail\TwoFactorMail;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('twofactorauth::auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return back()->with('status', __('auth.password_reset_link_sent'));
    }

    public function showResetForm($token)
    {
        return view('twofactorauth::auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:password_resets,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $passwordReset = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();

        if (!$passwordReset) {
            return back()->withErrors(['token' => __('auth.invalid_token')]);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        $token_2fa = Str::random(6);
        $user->token_2fa = $token_2fa;
        $user->token_2fa_expiry = now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new TwoFactorMail($token_2fa));

        if (!empty($user->phone)) {
            SendSmsJob::dispatch($user->phone, $token_2fa);
        }

        $tk = md5($_SERVER['HTTP_USER_AGENT'] . $user->id);

        return redirect()->route('2fa.form', [
            'username' => $user->email,
            'tk' => $tk,
            'id' => $user->id,
            'msg' => ''
        ]);
    }
}