<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
	protected $_validate=array(
			array('user_email','email','请输入正确的Email地址'),
			array('user_email','','Email已经存在',0,'unique',1),
			array("password","require","密码最少4位",1,"4,20"),
			array('repassword','password','确认密码不正确',0,'confirm'),
			//array('user_nickname','','该呢称已被使用',0,'unique',0),
			array('verify','require','验证码必须！'),
			array('user_nickname','user_nickname','请输入一个称呼'),
	);
	
	/**
	 * 判读当前用户是否已经登录
	 */
	public function UserLogin(){
		$sUserId=session("user_id");
		$User=M("User");
		//用户id+email等于session中存放的user_id
		$uRows=$User->where(array("user_email"=>cookie("user_email")))->field("id,user_email")->find();
		if (!$sUserId==md5($uRows["id"].$uRows["user_email"])){
			session("user_id",null);
			cookie("user_email",null);
			return 0;
		}
		$sUserPassword=session("password");
		$where=array(
				"id"=>$uRows["id"],
				"password"=>$sUserPassword,
				);
		$rows=$User->field("status")->where($where)->find();
		return intval($rows["status"]);
	}
	/**
	 * 根据sessid获取当前登录用户数据
	 * @param unknown_type $user_sessid
	 */
	public function UserData($user_sessid){
		$sUserId=session("user_id");
		$User=M("User");
		if ($this->UserLogin()){
			$uRows=$User->where(array("user_email"=>cookie("user_email")))->find();
			if ($sUserId==md5($uRows["id"].$uRows["user_email"])){
				$uRows["integral"]=$this->sumUserIntegral($uRows["id"]);
				return $uRows;
			}else{
				
				Log::write("获取当前登录用户数据时产生了错误：".$User->getLastSql());
				return "获取数据出错，请联系管理员";
			}
		}else{
			return "请登录后再操作";
		}
		
	}
	/**
	 * 获取用户积分列表
	 * @param unknown_type $id
	 * @param unknown_type $field
	 */
	public function getUserIntegral($id,$field=""){
		$user_integral=M("UserIntegral");
		if (empty($field)){
			$rows=$user_integral->where(array("user"=>$id))->select();
			return $rows;
		}else{
			$rows=$user_integral->field($field)->where(array("user"=>$id))->selct();
			return $rows[$field];
		}
		
	}
	/**
	 * 统计用户发表的主题数量
	 * @param int $id
	 * @return int
	 */
	public function  getUserTopicNumber($id){
		$topic=M("Topic");
		$topicNum=$topic->where(array("add_user"=>$id,"stats"=>1))->count();
		return $topicNum;
	}
	/**
	 * 统计用户回复帖子数量
	 * @param unknown_type $id
	 * @return unknown
	 */
	public function  getUserReplyNumber($id){
		$reply=M("Reply");
		$replyNum=$reply->where(array("add_user"=>$id,"stats"=>1))->count();
		return $replyNum;
	}
	/**
	 * 统计用户积分
	 * @param unknown_type $id
	 */
	public function  sumUserIntegral($id){
		$user_integral=M("UserIntegral");
		$sum=$user_integral->where(array("user"=>$id))->sum("integral");
		return $sum;
	}
	/**
	 * 增加用户登录次数
	 * 
	 */
	public function addUserLoginNumber($user){
		$User=M("User");
		load("extend");
		$where=array("id"=>$user);
		$User->where($where)->save(array("last_login_ip"=>get_client_ip()));
		$num=$User->where($where)->setInc("login_number",1);
		return $num;
	}

}
	
	
?>