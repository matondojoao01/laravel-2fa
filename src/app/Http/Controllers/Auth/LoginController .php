<?php

namespace Matondo\app\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

            $tk = md5($_SERVER['HTTP_USER_AGENT'] . $user->id);

            Mail::send('twofactorauth::emails.twofactor', ['token' => $token_2fa], function($message) use ($user) {
                $message->to($user->email)->subject('Seu código de autenticação 2FA');
            });

            return redirect()->route('2fa.form', [
                'username' => $user->email,
                'tk' => $tk,
                'id' => $user->id,
                'msg' => 'Por favor, insira o código enviado para seu e-mail.'
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

