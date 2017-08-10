<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="banner">
  <div class="user-box">
    <div class="user-login">
      <h3>代理商登录</h3>
      <form id="login_form" action="<?php echo urlAgent('login')?>" method="post">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
        <dl>
          <dt>代理商编号：</dt>
          <dd>
            <input type="text" class="text" autocomplete="off"  name="user_name" id="user_name">
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt>密码：</dt>
          <dd>
            <input type="password" class="text" name="password" autocomplete="off"  id="password">
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt></dt>
          <dd>
            <input type="hidden" value="<?php echo AGENT_SITE_URL?>/index.php?act=agent_register&op=step0" name="ref_url">
            <input name="提交" type="submit" class="button" value="登&nbsp;&nbsp;录">
            <a href="<?php echo MEMBER_SITE_URL;?>/index.php?act=login&op=forget_password&ref_url=<?php echo SHOP_SITE_URL;?>/index.php?act=show_joinin" target="_blank"><?php echo $lang['login_index_forget_password'];?></a></dd>
        </dl>
      </form>
      <div class="register">还没有成为我们的合作伙伴？ <a href="<?php echo AGENT_SITE_URL;?>/index.php?act=agent_register&op=step0" target="_blank">快速申请</a></div>
    </div>
  </div>
  <ul id="fullScreenSlides" class="full-screen-slides">
    <?php $pic_n = 0;?>
    <?php if(!empty($output['pic_list']) && is_array($output['pic_list'])){ ?>
    <?php foreach($output['pic_list'] as $key => $val){ ?>
    <?php if(!empty($val)){ $pic_n++; ?>
    <li style="background-color: #F1A595; background-image: url(<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/'.$val;?>)" ></li>
    <?php } ?>
    <?php } ?>
    <?php } ?>
  </ul>
</div>

<div class="indextip">
  <div class="container"> <span class="title"><i></i>
    <h3>贴心提示</h3>
    </span> <span class="content"><?php echo $output['show_txt'];?></span></div>
</div>


<!--整体流程-->
<div class="flow-box">
      <?php if(!empty($output['help_list']) && is_array($output['help_list'])){ $i = 0;?>
      <?php foreach($output['help_list'] as $key => $val){ $i++;?>
<?php if($i==1){?>
  <div class="settled-flow sb_floor"><div class="settled"> <div class="left"><i class="i_1"></i><p><?php echo $val['help_title'];?></p> <p class="eng">PROCESS</p><span class="span_1"></span></div>
       <?php }else if($i==2){?>
<div class="settled-stander sb_floor"><div class="stand"><div class="left"><i class="i_2"></i> <p><?php echo $val['help_title'];?></p><p class="eng">STANDARD</p><span class="span_2"></span> </div>
       <?php }else if($i==3){?> 
<div class="settled-rules sb_floor"><div class="rule"><div class="left"> <i class="i_3"></i> <p><?php echo $val['help_title'];?></p><p class="eng">PROCESS</p> <span class="span_3"></span></div>
       <?php }else if($i==4){?>
<div class="settled-brands sb_floor"><div class="brand"><div class="left"><i class="i_4"></i><p>合作品牌</p><p class="eng">COOPERATIVE RULES</p><span class="span_4"></span> </div>
       <?php } ?>
        <div class="right"><?php echo $val['help_info'];?></div></div></div>
       <?php } ?>
      <?php } ?></div>  
</div>
<a class="back-top">
</a>
<script>
$(document).ready(function(){
	$("#login_form ").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
		rules: {
			user_name: "required",
			password: "required"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    }
                }
            }
			<?php } ?>
		},
		messages: {
			user_name: "用户名不能为空",
			password: "密码不能为空"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : '验证码不能为空',
                minlength: '验证码不能为空',
				remote	 : '验证码错误'
            }
			<?php } ?>
		}
	});
});
</script>
<?php if( $pic_n > 1) { ?>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<?php }else { ?>
<script>
$(document).ready(function(){
    $(".tabs-nav > li > h3").bind('mouseover', (function(e) {
    	if (e.target == this) {
    		var tabs = $(this).parent().parent().children("li");
    		var panels = $(this).parent().parent().parent().children(".tabs-panel");
    		var index = $.inArray(this, $(this).parent().parent().find("h3"));
    		if (panels.eq(index)[0]) {
    			tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
    			panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
    		}
    	}
    }));
});
</script>
<?php } ?>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/resource/js/index_introduce.js" charset="utf-8"></script>


