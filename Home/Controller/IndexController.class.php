<?php
namespace Home\controller;
use Think\Controller;
//use Think\Dispatcher;
// bbs项目index控制器
class IndexController extends Controller {
    public function index(){
    	$this->display();
//     	load("extend");
//     	$category=D("Category");
    	//推荐主题
//     	$where=array(
//     		"parent_id"=>0,
//     		"category_stats"=>1,
//     	);   	
//     	$category_list=$category->where($where)->order("category_order desc")->select();
//     	$this->assign("category_list",$category_list);
//      	$this->display();  	
    }
 }