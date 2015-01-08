<?php
namespace Home\controller;
use Home\controller\PublicController;
//版块控制器
class CategoryAction extends PublicController{
	public function index(){
		load("extend");
		$Category=D("Category");
		$obHead=$Category->field("parent_id,id")->where(array("id"=>$_GET["id"],"category_stats"=>1))->find();
		if(!$obHead["parent_id"]){
			//非终极版块
			$this->assign("list",CategorySubList($_GET["id"]));
			$this->display();
		}else{
			//置顶
			$top_list=$Category->limit(5)->where(array("top"=>1))->getCategoryData($_GET["id"]);
			$this->assign("top_list",$top_list["newdata"]);
			//普通主题
			import("ORG.Util.Page");
			$count_array      = $Category->where(array("top"=>0))->getCategoryData($_GET["id"]);
			$count=$count_array["topicNum"];
			$Page       = new Page($count,10);
			//分页
			$Page->setConfig('header','个主题');
			//分页样式定制
			$Page->setConfig("theme", "本版共有%totalRow% %header%  %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%");
			$show       = $Page->show();
			$cache_str=md5($count.$Page->firstRow.$Page->listRows."_category");
			if(S($cache_str)){
				$list=S($cache_str);
			}else{
				$list=$Category->limit($Page->firstRow.','.$Page->listRows)->where(array("recommend"=>0))->getCategoryData($_GET["id"],array("reply"=>false));
				S($cache_str,$list);
			}
			$categoryObj=M("Category");
			$categoryName=$categoryObj->field("category_title")->where(array("id"=>$_GET["id"]))->find();
			$this->assign("pageTitle",$categoryName["category_title"]);
			$this->assign("list",$list["newdata"]);
			if(C("URL_MODEL")==4){
				$arr1 = array (
						"/forum-(\d)-(\d)\.html\?\&p=(\d)/i",
						"/\?\&p=\d/i" 
				);
				$arr2 = array (
						"forum-\\1-\\3.html",
						"" 
				);
				$show = preg_replace ( $arr1, $arr2, $show );
			}
			$this->assign("navpage",$show);
			$this->display("SubIndex");
		}
	}
}
?>