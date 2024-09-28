<?php

namespace App\Http\Middleware;

use App\Jobs\SendSmsJob;
use App\Mail\TwoFactorMail;
use Closure;
use App\Models\User;
use App\Models\UsersDevices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Mail;

class TwoFactorVerify extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $user = Auth::user();

        $str = preg_replace('/[^A-Za-z0-9. -]/', '', env('APP_URL'));
        $str = str_replace('.', '', $str);
        $cookie_name = 'tk' . $user->id . $str;

        $mycookie = $request->cookie($cookie_name);
        $new_auth = is_null($mycookie);

        if (!$new_auth) {
            $device_auth_count = UsersDevices::where('aut', 1)
                ->where('token', $mycookie)
                ->where('user_id', $user->id)
                ->count();

            if ($device_auth_count <= 0) {
                $new_auth = true;
            }
        }

        if ($user->token_2fa_expiry < now() || $new_auth) {

            $user->token_2fa = mt_rand(10000, 99999);
            $user->save();

            $tk = md5($_SERVER['HTTP_USER_AGENT'] . $user->id);
            $mydevice = preg_replace('/[0-9]/', '', $request->header('User-Agent'));
            $mydevice = preg_replace('/[.;_,{}()]/', '', $mydevice);
            $mydevice = preg_replace('/[\/]/', '', $mydevice);

            UsersDevices::where('aut', null)
                ->where('token', $tk)
                ->where('user_id', $user->id)
                ->delete();

            $dev = UsersDevices::where('aut', 1)
                ->where('token', $tk)
                ->where('user_id', $user->id)
                ->count();

            if ($dev <= 0) {
                $newDevice = new UsersDevices;
                $newDevice->user_id = $user->id;
                $newDevice->device = $mydevice;
                $newDevice->ip = $request->ip();
                $newDevice->token = $tk;
                $newDevice->save();
            }

            $token_2fa = $user->token_2fa;

            Mail::to($user->email)->send(new TwoFactorMail($token_2fa));

            if (!empty($user->phone)) {
                SendSmsJob::dispatch($user->phone, $token_2fa);
            }

            return redirect()->route('2fa.form', [
                'username' => $user->email,
                'tk' => $tk,
                'id' => $user->id,
                'msg' => ''
            ]);
        }

        return $next($request);
    }
}
