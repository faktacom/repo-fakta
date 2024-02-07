<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Cookie;


class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $guest_token = request()->cookie('guest_token');
        if (!session()->has('user_id')) {
            if ($guest_token == null) {
                $guest_token = Str::random(40);
                $this->add_session($guest_token);
            } else {
                $this->update_session($guest_token);
            }
        }
        $response = $next($request);
        if (method_exists($response, 'withCookie')) {
            return $response->withCookie('guest_token', $guest_token, 15);
        }
        return $response;
    }

    public function get_user_agent()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $ub = 'Unknown';
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
        } else {
            $bname = 'Unknown';
            $ub = 'Unknown';
        }
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 0) {
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
    
    public function add_session($guest_token){
        $user_agent = $this->get_user_agent();
        $user_ip = $this->get_ip();
        if ($user_agent['name'] != "Unknown" &&  $user_agent['version'] != "?" &&  $user_agent['platform'] != "Unknown") {
            $sql = "INSERT INTO `list_session` ("
                . "`session_token`, "
                . "`user_id`, "
                . "`user_agent`, "
                . "`name`, "
                . "`version`, "
                . "`platform`, "
                . "`pattern`, "
                . "`access_ip`, "
                . "`session_start`, "
                . "`last_active`, "
                . "`logged_out` "
                . ") VALUES (?, NULL, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 0);";
            $data = [
                $guest_token,
                $user_agent['user_agent'],
                $user_agent['name'],
                $user_agent['version'],
                $user_agent['platform'],
                $user_agent['pattern'],
                $user_ip,
            ];
                $query = DB::insert($sql, $data);
        }
    }
    public function update_session($guest_token){
        $sql = "UPDATE `list_session` SET "
                . "`last_active` = NOW() "
                . "WHERE `session_token` = ?;";
        $data = [
            $guest_token
        ];
        $query = DB::update($sql, $data);
    }
}
