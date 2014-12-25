<?php if (!defined('THINK_PATH')) exit(); if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="post">
		<p class="meta"><span class="date">【<!--<?php echo ($vo["category_title"]); ?>-->】</span> </p>
				<div class="category">
				<div class="category-list">
                
                    <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?><div class="category-block">
                            <span class="category-image">
                                <a href="<!--<?php echo (categoryurl($vos["id"])); ?>-->" title="" target="_self" >
                                <?php if(($vos["category_image"]) != ""): ?><img src="<!--<?php echo ($vos["category_image"]); ?>-->" border="0"/>		
                                <?php else: ?>
                                <img src="__BBSPUBLIC__/Resources/Image/IE.png" width="90" height="68" border="0"/><?php endif; ?>
                               </a>
                            </span>
                            <span class="category-item">
                                <p>『 <a href="<!--<?php echo (categoryurl($vos["id"])); ?>-->"><!--<?php echo ($vos["category_title"]); ?>--></a> 』</p> 
                                <p><em>主题:<!--<?php echo (categorydata($vos["id"],"topicNum")); ?>--></em><em>帖数:<!--<?php echo (categorydata($vos["id"],"topicAndreplyNum")); ?>--></em></p>
                                <p>
                                <a href="<!--<?php echo (pageurl($vos["id"])); ?>-->" target="_blank" title="<!--<?php echo (categorydata($vos["id"],"title")); ?>-->">
                                <!--<?php echo (msubstr(categorydata($vos["id"],"title"),0,16,"utf-8",false)); ?>--></a></p>
                            </span>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    
                    
                    
                </div>	
		</div>
</div><?php endforeach; endif; else: echo "" ;endif; ?>