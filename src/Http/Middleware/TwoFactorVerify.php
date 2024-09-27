<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\User;
use Matondo\TwoFactorAuth\Models\UsersDevices;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactor;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Auth\Middleware\Authenticate as Middleware;


class TwoFactorVerify extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {           
        $user = User::find(Auth::user()->id);
        
        $str = preg_replace('/[^A-Za-z0-9. -]/', '',  env('APP_URL'));
        $str = str_replace('.', '', $str);
        $cookie_name = 'tk' . $user->id . $str;

        $new_auth = true;
        if (isset($_COOKIE[$cookie_name])) {
            $mycookie = cookie::get($cookie_name);
            $new_auth = false;
        }

        if ($new_auth == false) {
            $device_aut = UsersDevices::where('aut', '=', 1)->where('token', '=', $mycookie)->where('user', $user->id)->count();
            if ($device_aut <= 0) {
                $new_auth = true;
            }
        } else {
            $device_aut = 0;
        }


        if ($user->token_2fa_expiry < \Carbon\Carbon::now() | $new_auth == true) {
   
            $user->token_2fa = mt_rand(10000, 99999);
            $user->save();

            $tk = md5($_SERVER['HTTP_USER_AGENT'] . $user->id);

            $mydevice = preg_replace('/[0-9]/', '', $_SERVER['HTTP_USER_AGENT']);
            $mydevice = preg_replace('/[.;_,{}()]/', '', $mydevice);
            $mydevice = preg_replace('/[\/]/', '', $mydevice);

            if ($device_aut <= 0) {

                $del = UsersDevices::where('aut', '=', null)->where('token', '=', $tk)->where('user', $user->id)->get();
                foreach ($del as $d) {
                    $d->delete();
                }

                $dev = UsersDevices::where('aut', '=', 1)->where('token', '=', $tk)->where('user', $user->id)->count();
                if ($dev <= 0) {
                    $new = new UsersDevices;
                    $new->user = $user->id;
                    $new->device = $mydevice;
                    $new->ip = $_SERVER['REMOTE_ADDR'];
                    $new->token = $tk;
                    $new->save();
                }      

                Mail::to($user->email)->send(new TwoFactor('Para acessar o sistema, utilize este código de acesso: <font size=+3><b>' . $user->token_2fa.'</b></font>'));

                return redirect('/2fa?usr=' . Auth::user()->email . '&tk=' . $tk . '&id=' . $user->id);
            }
        }
        return $next($request);
    }

}