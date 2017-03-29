<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller {
    public function index(){
        $this->display("index");
    }

    // 判断登录
    public function login(){
    	$office = M("office");
    	$map['o_id'] = $_POST['o_id'];

		$wrong = 0;
		$data = null;
		$ip = $_SERVER['REMOTE_ADDR'];

		if(VerifyController::check()){

			$data = $office->where($map)->field('o_name,o_psd,o_ispower')->find();

			if($data && $data['o_psd'] == md5($_POST['o_psd'])) {
				// 把id存入session中
				$_SESSION['o_id'] = $map['o_id'];
				// 把用户名存入session中
				$_SESSION['o_name'] = $data['o_name'];
				$_SESSION['is_power'] = $data['o_ispower'];
				VerifyController::reset($ip);
				$wrong = 1;
			}else{
				if(isset($_SESSION['fail_count']) && $_SESSION['fail_count'] != 3){
					$_SESSION['fail_count']++;

				}elseif($_SESSION['fail_count'] == 3){
					VerifyController::setRefuseTimeAndIp(time(),$ip);
				}else{
					$_SESSION['fail_count'] = 1;

				}
				$wrong = 3;
			}
		}else{
			$wrong = 2;
			$data = VerifyController::getReturnContent();
		}

		$re = array('wrong'=>$wrong,'data'=>$data);
		$this->ajaxReturn($re,'JSON');
    }

    // 退出
    public function logout(){
    	session(null);
		$this->display('index');
    }

}