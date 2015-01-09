<?php
namespace Home\Model;
use Think\Model;
class CategoryModel extends Model{
	protected $stats=1;
	/**
	 * 返回版块数据
	 * Enter description here ...
	 * @param unknown_type $id
	 * $data=array("reply"=>false,"reply_data"=>array())
	 * 说明：
	 * 1 )reply为true时，将显示对应主题的回复数据(默认10条，可通过reply_data设置数量)
	 * 2）reply_data参数值与当前模型getTopicReply方法$data参数相同
	 */
	public function getCategoryData($id=0,$data=array()){
		$id=intval($id);
		$topic=M("Topic");
		$category_where["stats"]=$this->stats;
		if ($id>0){
			//根据id统计
			$category_where["category_id"]=array("eq",$id);
			$where_id="topic.category_id=$id";
		}else{
			//统计所有版块数据
			$category_where["category_id"]=array("gt",$id);
			$where_id="topic.category_id>$id";
		}
		if ($this->options["where"]){
			$category_where=array_merge($this->options["where"],$category_where);
		}
		$topicNum=$topic->where($category_where)->count();
		$reply=M("Reply");
		//回帖数量
		$replyNum=$reply->where($category_where)->count();
		//总贴子数量
		$topicAndreplyNum=$topicNum+$replyNum;
		//当前版块最新帖子
		if (!$this->options["limit"]){
			$limit=1;
		}else{
			$limit=$this->options["limit"];
		}				
		//默认根据回复时间排序
		if (!array_key_exists("order", $this->options)){
			//根据最新回复时间,默认
			$DB_PREFIX=C("DB_PREFIX");
			$where="and topic.stats={$this->stats} and top=".intval($category_where["top"]);
			$sql="SELECT topic.`id`,topic.`title`,topic.`add_time`,topic.`add_user`,topic.`category_id`,
				topic.`category_chil_id`,topic.top,reply.add_time as reply_time
				FROM `{$DB_PREFIX}topic`  as topic, {$DB_PREFIX}reply as reply where ($where_id
				 and reply.topic_id=reply.id {$where})
				 %ORDER%
				 %LIMIT%
			";
			$rows=$topic->limit($limit)->order("reply_time desc,add_time desc,id desc")->query($sql,true);
			
		}else{
			//根据最新发表时间		
			$rows=$topic->field("content,stats",true)->order($this->options["order"])->where($category_where)->limit($limit)->select();		
			
		}
		//是否显示回复数据
		if ($data["reply"] && $rows){			
			foreach ($rows as $key=>$value){
				$rows[$key]["reply"]=$this->getTopicReply($value["id"],$data["reply_data"]);
			}
		}
		$datas=array(
			"topicNum"=>$topicNum,
			"replyNum"=>$replyNum,
			"topicAndreplyNum"=>$topicAndreplyNum,
			"newdata"=>$rows,			
		);
		return $datas;
	}
	/**
	 * 根据主题id获取应该的主题回复
	 * @param unknown_type $id
	 * $data=array("order"=>"add_time","limit"=>10)
	 */
	public function getTopicReply($id,$data=array()){
		$reply=M("Reply");
		$where["id"]=$id;
		$where["stats"]=$this->stats;
		if ($data["order"]){
			$order=$data["order"];
		}else{
			$order="add_time desc";
		}
		if ($data["limit"]){
			$limit=$data["limit"];
		}else{
			$limit=10;
		}
		$rows=$reply->order("add_time desc")->limit($limit)->order($orde)->where($where)->select();
		return $rows;
	}
	/**
	 * 根据ID获取对应版块下的子版块
	 * @param unknown_type $id
	 * @param unknown_type $data
	 * @return unknown
	 */
	public function getCategorySubList($id,$data=array()){
		$Category=M("Category");
		$where=array(
			"parent_id"=>$id,
			"category_stats"=>1,
			"category_level"=>array('neq',0),	
					
		);
		$rows=$Category->where($where)->order("category_order desc")->select();
		return $rows;
	}

}
?>