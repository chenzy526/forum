<?php
namespace Home\Model;
use Think\Model;
class TopicModel extends Model{
	public function getReplyList($topid,$count=false){
		$reply=M("Reply");
		$order=$this->options["order"];
		if (!$order){
			$order="add_time asc,id asc";
		}
		$limit=$this->options["limit"];
		if (!$limit){
			$limit=1;
		}
		if ($count){
			$rows=$reply->where(array("topic_id"=>$topid,"stats"=>1))->order($order)->limit($limit)->count();
		}else{
			$rows=$reply->where(array("topic_id"=>$topid,"stats"=>1))->order($order)->limit($limit)->select();
			//dump($rows);
		}
		return $rows;
		
	}
	/**
	 * 令牌检验，防止跨域提交
	 * @param string $options
	 * @param int  $cid
	 * @param int $user
	 * @param string $token
	 * @return bool|string
	 */
	public function ChedkToken($options="get",$cid="",$user="",$token=""){
		$User=D("User");
		$cid=empty($cid)?$_GET["cid"]:$cid;
		$user_data=$User->UserData(session("user_id"));
		$user=empty($user)?$user_data["id"]:$user;
		if ($options=="get"){
			$token=empty($token)?$_POST["token"]:$token;
			if(!$cid || !$user || !$token){
				return false;
			}
			$Tokens=md5($cid.$user.$token);
			if (session("__token__")==$Tokens){
				return true;
			}else{
				return false;
			}
		}elseif ($options=="set"){
			$tokens=md5(rand(0, 1000).time());
			session("__token__",md5($cid.$user.$tokens));
			return $tokens;
		}elseif ($options=="clear"){
			return session("__token__",null);	
		}
	}
	
}
?>