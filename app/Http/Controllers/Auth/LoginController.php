<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('prevent-back-history');
    }

    public function showLoginForm()
    {
        if (session()->has('user_id')) {
            if (session()->get('role_id') == 1) {
                return redirect()->route('admin.home');
            } else {
                return redirect()->route('welcome');
            }
        }
        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        if (session()->has('user_id')) {
            if (session()->get('role_id') == 1) {
                return redirect()->route('admin.home');
            } else {
                return redirect()->route('welcome');
            }
        }
        return view('admin.login');
    }

    protected function authenticated(Request $request, $user)
    {
        $user_agent = $this->get_user_agent();
        $user_ip = $this->get_ip();
        $soure_login = $request->source_login;
        $page_name = request()->path();
        
        $request->session()->put('user_id', auth()->user()->user_id);
        $request->session()->put('role_id', auth()->user()->role_id);
        $request->session()->put('device_info', request()->userAgent());
        $request->session()->put('access_ip', $user_ip);

        $affected = DB::insert(
            'insert into `list_session` (
            `session_token`,
            `user_id`,
            `user_agent`,
            `name`,
            `version`,
            `platform`,
            `pattern`,
            `access_ip`,
            `session_start`,
            `last_active`,
            `page_count`,
            `exit_page`,
            `logged_out`
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->session()->get('_token'),
                $request->session()->get('user_id'),
                $user_agent['user_agent'],
                $user_agent['name'],
                $user_agent['version'],
                $user_agent['platform'],
                $user_agent['pattern'],
                $request->session()->get('access_ip'),
                Carbon::now(),
                Carbon::now(),
                1,
                $page_name,
                0
            ]
        );

        if ($affected) {
            if ($soure_login == "frontend") {
                if (session('role_id') != 4) {
                    $this->redirectTo = '/profile/my-content';
                } else {
                    $this->redirectTo = '/profile/edit';
                }
            } else {
                if (session('role_id') != 4) {
                    $this->redirectTo = '/admin/home';
                } else {
                    $this->redirectTo = '/profile/edit';
                }
            }
        }
    }

    public function get_user_agent()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";
        // First get the platform
        if (preg_match('/android/i', $u_agent)) {
            $platform = 'android';
        } elseif (preg_match('/ubuntu/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/iphone/i', $u_agent)) {
            $platform = 'iphone';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Edg/i', $u_agent)) {
            $bname = 'Edge';
            $ub = "Chrome";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }
        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }
        return array(
            'user_agent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }

    public function get_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $affected = DB::update(
            'update `list_session` set '
                . '`logged_out` = ? '
                . 'where `session_token` = ? AND `user_id` = ?;',
            [
                1,
                $request->session()->get('_token'),
                $request->session()->get('user_id')
            ]
        );

        if ($affected > 0) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
