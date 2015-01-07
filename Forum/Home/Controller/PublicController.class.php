<?php
namespace Home\controller;
use Think\Controller;
class PublicController extends Controller{
	protected $userlogin;
	public function index(){
		//echo "fsd";
	}
	public function _initialize(){
		$User=D("User");
		$this->userlogin=$User->UserLogin();
		$this->assign("login",$this->userlogin);
		$this->assign("sidebar",$sidebar);
		if ($_GET["verify"]){
			import("ORG.Util.Image");
			//Image::buildImageVerify();
			$this->bulidImageVerify();
			exit(0);
		}
	}
	public function _empty(){
		echo "dfs".$_GET['cid'];
		$this->error("页面不存在");
	}
	
}
?>