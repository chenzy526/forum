<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>
	<?php if($pageTitle != '' ): echo ($pageTitle); ?>- __PROJECT_NAME__
	<?php else: ?> 首页- __PROJECT_NAME__<?php endif; ?>
</title>
<meta name="keywords" content="<?php echo ($keywords); ?> -<?php echo ($pageTitle); ?>-__PROJECT_NAME__" />
<meta name="description" content="<?php echo ($description); ?>- <?php echo ($pageTitle); ?>-__PROJECT_NAME__" />
<script src="/forum/Public/Script/jquery.js"></script>
<script src="/forum/Public/Script/main.js"></script>
	<script type="text/javascript">
		function search_txt(){
				      $("#search-text").attr("value","");				      		   
		}
	</script>
<link rel="stylesheet" href="/forum/Public/Css/style.css" />
<link rel="stylesheet" href="/forum/Public/Css/default.css" />

	<style>
	   #sinaLink{background:url("/forum/Public/Image/site_v5.png") no-repeat scroll 0 -26px transparent;}
	   #qqLink{background:url("/forum/Public/Image/site_v5.png") no-repeat scroll 0 -102px transparent;}
	</style>
</head>
<body>
<div id="header-wrapper">
	<div id="header">
		<div id="menu">
			<!-- 导航栏 -->
            <ul>
            	<li <?php if(($_GET["id"]) == ""): ?>class="current_page_item"<?php endif; ?>><a href="__PROJECT_URL__">论坛首页</a></li>
                <?php $_result=NaviGation();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if(($_GET["id"]) == $vo["id"]): ?>class="current_page_item"<?php endif; ?>><a href="<?php echo (categoryurl($vo["id"])); ?>" class="last"><?php echo ($vo["category_title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
            <!-- end导航栏 -->
		</div>
		<!-- end #menu -->
		<div id="search">
			<form method="get" action="__PROJECT_URL__">
            	<input type="hidden" name="m" value="search" />
				<fieldset>
					<input name="words" type="text" id="search-text" onclick="search_txt();" value="<?php if($_GET["words"] == ""): ?>请输入关键字<?php else: echo ($_GET["words"]); endif; ?>" size="15" />
					<label>
						<select name="a" id="search_select" class="search_select">
						  <option value="title">搜索标题</option>
						  <option value="fulltext">搜索全文</option>
					    </select>
					</label>
				<input type="submit" id="search-submit" class="search-submit" value="GO" />
				</fieldset>
			</form>
            <script>
			   function search_txt(){$("#search-text").attr("value","");}
			</script>
		</div>
		
	</div>
</div>
<!-- end #header -->
<!-- end #header-wrapper -->
<div id="logo">
	<h1><a href="#">在线论坛 </a></h1>
	<!--<p><em>PHPMVC在线支持论坛 <a href="http://beauty-soft.net/book/php_mvc/">beauty-soft.net</a></em></p>-->
</div>
<hr />
<!-- end #logo -->
<div id="page">

	<div id="page-bgtop">
    	<div id="content">
        	
<?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="post">
		<p class="meta"><span class="date">【<?php echo ($vo["category_title"]); ?>】</span> </p>
				<div class="category">
				<div class="category-list">
                    <?php $_result=CategorySubList($vo['id']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?><div class="category-block">
                            <span class="category-image">
                                <a href="<?php echo (categoryurl($vos["id"])); ?>" title="" target="_self" >
                                <?php if(($vos["category_image"]) != ""): ?><img src="<?php echo ($vos["category_image"]); ?>" border="0"/>		
                                <?php else: ?>
                                <img src="/forum/Public/Image/IE.png" width="90" height="68" border="0"/><?php endif; ?>
                               </a>
                            </span>
                            <span class="category-item">
                                <p>『 <a href="<?php echo (categoryurl($vos["id"])); ?>"><?php echo ($vos["category_title"]); ?></a> 』</p> 
                                <p><em>主题:<?php echo (categorydata($vos["id"],"topicNum")); ?></em><em>帖数:<?php echo (categorydata($vos["id"],"topicAndreplyNum")); ?></em></p>
                                <p>
                                <a href="<?php echo (pageurl($vos["id"])); ?>" target="_blank" title="<?php echo (categorydata($vos["id"],"title")); ?>">
                                <?php echo (msubstr(categorydata($vos["id"],"title"),0,16,"utf-8",false)); ?></a></p>
                            </span>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    
                    
                    
                </div>	
		</div>
</div><?php endforeach; endif; else: echo "" ;endif; ?>


		

		</div>
		<?php if(($m) != "content"): ?><div id="sidebar">
		       			<ul>
				<li>
					<h2>会员中心</h2>
					<!--登录-->
					<div id="User-Login">
					<div id="minicard"></div>
					<?php if($login == 1 ): ?><script type="text/javascript">
							$("#minicard").load("__PROJECT_URL__"+"?m=user&a=userminicard");
						</script>
					<?php else: ?> 
					<form  method="post" id="userloginform" name="userloginform" onsubmit="return userlogin(this)" action="__PROJECT_URL__?m=user&a=userlogin">
                       <p>邮箱：<input name="user_email" onclick="clname();" type="text" id="user_email" value="用户名/邮箱"  />
                      </p>
                       <p>密码：<input type="password" name="password" id="password"  /></p>
                       <p><span>验证：</span><span><input type="text" size="6" class="Verification" id="Verification"  /></span><span><img src="http://tp.localhost/index.php/index/verify"  onclick="show(this);"/></span></p>
                       <p><input type="submit"  id="UserLoginBut" value="登录" /><span>[<a  href="__PROJECT_URL__?m=user&a=register" target="_blank">注册新用户</a>]</span><span>[<a  href="__PROJECT_URL__?m=user&a=getpassword" target="_blank">忘记密码</a>]</span>
                       <p class="wei_ico"><span>使用其它帐号登录：</span>
                       <span><a id="qqLink"  title="使用QQ登录"   href="__PROJECT_URL__?m=user&a=weibologin&wid=1">使用QQ帐号登录</a></span>
                       <span><a id="sinaLink"  title="使用新浪微博帐户登录"   href="__PROJECT_URL__?m=user&a=weibologin&wid=2">使用新浪微博帐户登录</a></span></p>
                       
                   </form>
                       <script type="text/javascript">
   						function show(obj){
      						obj.src="__PROJECT_URL__?verify=1&"+Math.random();
      
   						}
   						//微博登录
   						function sina_weibo_login(){
	   						window.location.href="";
   						}
   						function userlogin(obj){
   							var url = obj.action;
	   						$.post(url, { user_email:$("#user_email").val(), password:$("#password").val(), verify:$("#Verification").val()}, function(json){
	   							if(json.status){
		   							//登录成功
		   							$("#userloginform").remove();
		   							$("#minicard").load("__PROJECT_URL__"+"?m=user&a=userminicard");
	   							}else{
	   								//登录失败
		   							alert( json.info);
	   							}
		   							
		   					},"json");
	   						return false;
   						}
						
					</script><?php endif; ?>
                     
                    </div>
                    <!--end登录-->
                    
                    
				</li>
				<li>
					<h2>最新回复</h2>
					<ul>
                    <?php $_result=NewBbsTopic(10,true);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (pageurl($vo["id"])); ?>" title="<?php echo ($vo["title"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</li>
				<li>
					<h2>用户排行榜</h2>
					<ul>
                    
                    
                       					</ul>
				</li>
			</ul>
		</div><?php endif; ?>
		<!-- end #sidebar -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer">
<input type="hidden" id="sys_url" name="sys_url" value="<!--tmp-$Think.config.SYS_URL-->" />
	<!--<p>Copyright (c) 2012 BeautySoft  by <a href="http://beauty-soft.com/book/php_mvc">beauty-soft.com</a>.</p>-->
</div>
<!-- end #footer -->
</body>
</html>