<?php

namespace Admin\Controller;


class VerifyController
{
    public static function check()
    {
        if($_SESSION['refuse_time'] != null && $_SESSION['ip'] != null){
            $refuseTime = $_SESSION['refuse_time'] ;
            $ip = $_SERVER['REMOTE_ADDR'];

            $refuseCount = $_SESSION[md5($ip)];
            if($_SESSION['ip'] === md5($ip) && time() - $refuseTime < 30 * $refuseCount){
                return false;
            }else{
                unset($_SESSION['refuse_time']);
                unset($_SESSION['ip']);
                $_SESSION['fail_count'] = 0;

                return true;
            }
        }else{
            return true;
        }
    }
    public static  function  getRefuseCount($ip){
        $refuseCount = $_SESSION[md5($ip)];
        return $refuseCount;
    }
    public static function setRefuseTimeAndIp($time,$ip)
    {
        $key = md5($ip);

        if($_SESSION[$key]){
            $_SESSION[$key]++;
        }else{
            $_SESSION[$key] = 1;
        }
        $_SESSION['refuse_time'] = $time;
        $_SESSION['ip'] = md5($ip);
    }

    public static function getReturnContent()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $refuseCount = $_SESSION[md5($ip)];
        $allTime = $refuseCount * 30;
        $waitTime = $allTime - (time() - $_SESSION['refuse_time']);
        if($waitTime>60){
            $waitTime = ((intval($waitTime/60))).'分钟'.($waitTime%60);
        }
        return "您已错误登陆超过3次!请在".($waitTime)."秒以后重新登陆";
    }

    public static function reset($ip)
    {
        unset($_SESSION[$ip]);
    }
}