<?php
/**
 * 导航栏
 * Enter description here ...
 */
/*
 * +--------------------------------------
 * |msubstr  引入string.class.php 类里的文件
 * 
 */
 function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		$slice = iconv_substr($str,$start,$length,$charset);
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice.'...' : $slice;
}

function NaviGation($parent_id=0){
	$category=M("Category");
	$rows=$category->order()->where(array("category_stats"=>1,"parent_id"=>1,"parent_id"=>$parent_id))->select();
	return $rows;
}
/**
 * 树型导航栏
 */
function ThreeNav($id){
	$category=M("Category");
	$where=array(
			"id"=>$id,
			"category_stats"=>1,
	);
	$rows=$category->where($where)->find();
	if ($rows["parent_id"]){
		//得出上级栏目
		$where=array(
				"parent_id"=>$rows["parent_id"],
				"category_stats"=>1,
				"parent_id"=>array("neq",0),
				
		);
		// dump($rows);
		$level=$category->where(array("id"=>$rows["parent_id"],"parent_id"=>0,"category_stats"=>1))->field("category_title,id")->find();
		$ar1["level_1"]=array("category_title"=>$level["category_title"] ,"id"=>$level["id"] );
		$rowss["subCategoryList"]=$category->where($where)->order("category_order")->select();
		$prows=array_merge($ar1,$rowss);
		return $prows;
	}else{
		return array("level_1"=>$rows);
	}
	
}
/**
 * 路径导航
 * @param unknown_type $id
 */
function NavPath($id){
	$rows=ThreeNav($id);
	$lev_1=$rows["level_1"];
	$a1=CategoryURL($lev_1["id"]);
	$index="<a href='".__APP__."'>首页</a>>>";
	$lev_1="<span><a href='$a1'>".$lev_1["category_title"]."</a>>></span>";
	if ($rows["subCategoryList"]){
		$arr_rows=$rows["subCategoryList"];
		$arr_rows=array_sort($arr_rows,"parent_id","desc");
		foreach ($arr_rows as $key=>$value){
			if($value["parent_id"]==$id || $value["id"]==$id){
				$a=CategoryURL($value["id"]);
				$str.= "<span><a href='{$a}'>".$value["category_title"]."</a></sapn>";
			}
		}
		return  $index.$lev_1.$str;
	}else{
		return $index.str_replace(">>", "", $lev_1);
	}
}
/**
 * 多维数组排序
 * @param unknown_type $arr
 * @param unknown_type $keys
 * @param unknown_type $type
 * @return multitype:unknown
 */
function array_sort($arr,$keys,$type='asc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}
/**
 * 根据用户id，返回用户资料
 * Enter description here ...
 * @param int $id
 * @param string $field
 * @param bool $displayPassword
 */
function uidToData($id,$field="",$displayPassword=false){
	$User=D("User");
	if ($displayPassword){
		$rows=$User->where(array("id"=>$id))->find();
	}else{
		$rows=$User->field("password",true)->where(array("id"=>$id))->find();
	}
	if(!$rows["head_image"]){
		$rows["head_image"]=TMPL_PATH."Public/Resources/Image/noavatar_small.gif";
	}
	$rows["user_information"]=json_decode($rows["user_information"]);
	$rows["integral"]=$User->sumUserIntegral($id);
	$rows["topicNumber"]=$User->getUserTopicNumber($id);
	$rows["replayNumber"]=$User->getUserReplyNumber($id);
	$rows["topicAndreplayNumber"]=$rows["topicNumber"]+$rows["replayNumber"];
	if (!$field){
		return $rows;
	}else{
		return $rows[$field];
	}
	
}
/**
 * 内空页面导航地址
 * Enter description here ...
 * @param unknown_type $id
 */
function PageURL($id,$page=""){
	$config=C("TMPL_PARSE_STRING");
	if (C("URL_MODEL")==4){
		//html伪静态
		if ($page){
			$url=$config["__WEBSITE_URL__"]."/thread-".$id."-".intval($page).".html";
		}else{
			$url=$config["__WEBSITE_URL__"]."/thread-".$id."-1.html";
		}
	}else{
		if (!$page){
			$url=__APP__."?m=content&cid=".$id;
		}else{
			$url=__APP__."?m=content&cid=".$id."&p=".$page;
		}
	}
	return $url;
}
/**
 * 栏目页面导航地址
 * Enter description here ...
 * @param unknown_type $id
 */
function CategoryURL($id,$page=""){
	//+--------------
	//if ($id == 1){return  "111111111";}
	//+--------------
	$config=C("TMPL_PARSE_STRING");
	if (C("URL_MODEL")==4){
		//html伪静态
		if (!$page){
			$page=intval($page);
			$url=$config["__WEBSITE_URL__"]."/forum-".$id."-{$page}.html";
		}else{
			$url=$config["__WEBSITE_URL__"]."/forum-".$id."-1.html";
		}
	}else{
		if (!$page){
			//$url=__APP__."?a=index&m=Category&id=".$id;
			$url=__APP__."?m=Home&c=Category&id=".$id;
		}else {
			//$url=__APP__."?a=index&m=Category&id=".$id."&p=".$page;
			$url=__APP__."?m=Home&c=Category&id=".$id."&p=".$page;
		}
	}
	return $url;
}
/**
 * 根据ID获取版块数据
 * @param unknown_type $id
 */
function CategoryData($id,$field="",$page=""){
	$Category=D("Category");
	$rows=$Category->getCategoryData($id);
	if(!empty($field)){
		if (array_key_exists($field, $rows["newdata"][0])){
			return $rows["newdata"][0][$field];
		}else{
			return $rows[$field];
		}
	}else{
		return $rows;
	}
}
/**
 * 根据id获取该版块下的子版块数据
 * @param unknown_type $id
 */
function CategorySubList($id){
	$category=D("Category");
	return $category->getCategorySubList($id);
}
/**
 * 获取论坛最新主题
 * @param unknown_type $number
 */
function NewBbsTopic($number=10,$reply=false){
	$Category=D("Category");
	$rows=$Category->limit($number)->getCategoryData(0,array("reply"=>$reply));
	return $rows["newdata"];
}
/**
 * 主题状态图片
 * @param 主题 $id
 */
function TopicHeadImage($id,$href="#"){
	$topic=M("Topic");
	$where=array(
			"id"=>$id,
			"stats"=>1,
			);
	$rows=$topic->field("title,content,stats",true)->where($where)->find();
	$rTime=time()-$rows["add_time"];
	$rTime=round(($rTime)/(60*60));
	if ($rTime<2){
		$image=TMPL_PATH."Public/Resources/Image/folder_new.gif";
		$img='<a target="_blank" href="'.$href.'"><img title="有最新回复，点击图标在新窗口打开" src="'.$image.'" width="25" height="25" /></a>';
	}else{
		if ($rows["top"]){
			$image=TMPL_PATH."Public/Resources/Image/pin_1.gif";
			$img='<a target="_blank" href="'.$href.'"><img title="置顶帖，点击图标在新窗口打开" src="'.$image.'" width="25" height="25" /></a>';
		}elseif ($rows["recommend"]){
			$image=TMPL_PATH."Public/Resources/Image/pin_3.gif";
			$img='<a target="_blank" href="'.$href.'"><img title="推荐帖，点击图标在新窗口打开" src="'.$image.'" width="25" height="25" /></a>';
		}else{
			$image=TMPL_PATH."Public/Resources/Image/folder_common.gif";
			$img='<a target="_blank" href="'.$href.'"><img title="普通帖，点击图标在新窗口打开" src="'.$image.'" width="25" height="25" /></a>';
		}
	}
	return $img;
}
/**
 * 可供用户登录的微博列表
 * 
 * @param unknown_type $id
 * @param unknown_type $field
 */
function weibolist($id=null,$field=""){
	$weibo=M("WeiboList");
	if (!$id){
		$rows=$weibo->order("id desc")->select();
		if (empty($field)){
			return $rows;
		}else{
			return $rows[$field];
		}
	}else {
		$rows=$weibo->where(array("id"=>$id))->find();
		if ($field){
			return $rows[$field];
		}else{
			return $rows;
		}
	}
}
/**
 * 增加用户积分
 * @param int $user
 * @param float $ntegral
 * @param string $note
 */
function AddUserIntegral($user,$integral,$note=""){
	if (!$integral) return true;
	$user_integral=M("UserIntegral");
	$config=C("SCENARIO_CONFIG");
	$integralData=array(
			"user"=>$user,
			"add_time"=>time(),
			"integral"=>$integral,
			"note"=>$note,
			);
	if ($add=$user_integral->add($integralData)){
		return $add;
	}else{
		Log::write("增加积分时，产生了一个错误:".$user_integral->getLastSql());
		return false;
	}
	
}
/**
 * 获取最后一后一个回复用户
 * @param string $topic_id
 */
function getLastReplayUser($topic_id,$field=""){
	$reply=M("Reply");
	$rows=$reply->where(array("topic_id"=>$topic_id,"stats"=>1))->order("add_time desc,id desc")->find();
	if ($rows){
		return uidToData($rows["add_user"],$field);
	}else{
		return false;
	}
}
/**
 * 用户权限
 * @param int $user
 * @param string $permission
 * @return bool
 */
function UserPermission($user,$permission,$field=""){
	$User=uidToData($user);
	$user_group=$User["user_group"];
	$Group=M("UserGroup");
	if (!$User["id"]){
		//游客组
		$group_rows=$Group->where(array("id"=>1))->find();
		$pre=$group_rows["permission"];
		$pre=json_decode($pre,true);
		if(!$field){
			return $pre["$permission"];
		}else{
			return $group_rows[$field];
		}
	}
	if (!$user_group){
		$user_group=2;
	}
	$group_rows=$Group->where(array("id"=>$user_group))->find();
	$pre=$group_rows["permission"];
	$pre=json_decode($pre,true);
	if(!$field){
		return $pre["$permission"];
	}else{
		return $group_rows[$field];
	}
}
/**
 * 得到管理组权限
 * @param int $user
 * @param string $permission
 * @param string $field
 * @return array
 */
function AdminPermission($user,$permission,$field=""){
	$User=uidToData($user);
	$admin=M("Admin");
	$rows=$admin->where(array("user_id"=>$User["id"],"status"=>1))->find();
	if ($rows){
		$admin_group=M("AdminGroup");
		$admin_rows=$admin_group->where(array("id"=>$rows["admin_group_id"]))->find();
		if (!$field){
			$pre=$admin_rows["permission"];
			$pre=json_decode($pre,true);
			return $pre["$permission"];
		}else{
			return $admin_rows[$field];
		}
	}else {
		return false;
	}
}



/**
 * CURL提交数据
 * @param string $url
 * @param array $post_data
 * @return mixed
 */
function curl_post($url,$post_data=array()){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
/**
 * 301跳转
 * @param string $url
 * @param string $thisProject
 */
function jump($url,$thisProject=true){
	if (empty($url)){
		exit("请提供URL");
	}
	header("HTTP:/1.1 301 Moved Permanently");
	if ($thisProject){
		$config=C("TMPL_PARSE_STRING");
		$url=$config["__PROJECT_URL__"].$url;
	}
	header("Location:{$url}");
	setcookie("tourl",NULL);
	exit(0);
}
function highlight_codes($html){
	load("extend");
	return highlight_code($html[0]);
}
/**
 * 计算中英文混合字符串的长度
 * @param string $str
 * @return int
 */
function ccStrLen($str) 
{
	$ccLen=0;
	$ascLen=strlen($str);
	$ind=0;
	$hasCC=ereg("[xA1-xFE]",$str); #判断是否有汉字
	$hasAsc=ereg("[x01-xA0]",$str); #判断是否有ASCII字符
	if($hasCC && !$hasAsc) #只有汉字的情况
		return strlen($str)/2;
	if(!$hasCC && $hasAsc) #只有Ascii字符的情况
		return strlen($str);
	for($ind=0;$ind<$ascLen;$ind++)
	{
	if(ord(substr($str,$ind,1))>0xa0)
	{
	$ccLen++;
	$ind++;
	}
		else
			{
			$ccLen++;
	}
	}
	return $ccLen;
}


?>
