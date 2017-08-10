<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<div class="nc-register-bg">
  <div class="nc-register-box">
    <div class="nc-register-layout">
      <div class="left">
        <div class="nc-register-mode">
          <ul class="tabs-nav">
            <li><a href="#default">代理商申请<i></i></a></li>
          </ul>
          <div id="tabs_container" class="tabs-container">
            <div id="default" class="tabs-content">
              <form id="register_form" method="post" class="nc-login-form" action="<?php echo urlAgent('agent_register', 'apply_agent_save');?>">
                <?php Security::getToken();?>
                <dl>
                  <dt>名称：</dt>
                  <dd>
                    <input type="text" id="agent_name" name="agent_name" value="" class="text"/>
                  </dd>
                </dl>
                <dl>
                  <dt>设置密码：</dt>
                  <dd>
                    <input type="password" id="agent_password" name="agent_password" value=""  class="text"/>
                  </dd>
                </dl>
                <dl>
                  <dt>确认密码：</dt>
                  <dd>
                    <input type="password" id="password_confirm" name="password_confirm"  class="text" tipMsg="请再次输入密码"/>
                  </dd>
                </dl>
                <dl>
                  <dt>等级：</dt>
                  <dd>
                    <select id="level_id" name="level_id">
<!--                         <option value="0">请选择等级</option> -->
                        <?php foreach ($output['level_list'] as $k=>$v){ ?>
                              <option value="<?php echo $v['id'] ?>"><?php echo $v['join_fee']?></option>  
                        <?php } ?>
                    </select>
                  </dd>
                </dl>
                <dl>
                  <dt>手机号：</dt>
                  <dd>
                    <input type="text" class="text" tipMsg="请输入手机号码" value="" name="mobile" id="mobile"  >
                  </dd>
                </dl>
                <dl>
                  <dt>联系人：</dt>
                  <dd>
                    <input type="text" id="consigner" name="consigner" class="text" tipMsg="请输入联系人姓名"/>
                  </dd>
                </dl>
                <dl>
                  <dt>店铺地址：</dt>
                  <dd>
                    <input type="text" id="shop_address" name="shop_address" class="text" tipMsg="请输入您的店铺地址"/>
                  </dd>
                </dl>
                <dl>
                  <dt>店铺图片：</dt>
                  <dd>
<!--                     <img alt="" src="http://localhost/shopnc/data/upload/shop/common/logo.png"> -->
                    <input type="file" id="shop_img" name="shop_img" class="text" style="width:72px;">
                    <input type="hidden"  name="shop_img1" class="text"><span></span>
<!--                     <input type="text" id="user_name" name="user_name" class="text" tipMsg=""/> -->
                  </dd>
                </dl>
                <?php if(C('captcha_status_register') == '1') { ?>
                <div class="code-div mt15">
                  <dl>
                    <dt>验证码：</dt>
                    <dd>
                      <input type="text" id="captcha" name="captcha" class="text w80" size="10" tipMsg="<?php echo $lang['login_register_input_code'];?>" />
                    </dd>
                  </dl>
                  <span><img src="index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>" name="codeimage" id="codeimage"/> <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_password_change_code']; ?></a></span></div>
                <?php } ?>
                <dl class="clause-div">
                  <dd>
                    <input name="agree" type="checkbox" class="checkbox" id="clause" value="1" checked="checked" />
                    阅读并同意<a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="agreement" title="阅读并同意">《服务协议》</a></dd>
                </dl>
                <div class="submit-div">
                  <input type="submit" id="Submit" value="立即申请" class="submit"/>
                </div>
                <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
                <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
                <input type="hidden" name="form_submit" value="ok" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$('input[name="shop_img"]').fileupload({
    dataType: 'json',
    url: '<?php echo urlShop('store_joinin', 'ajax_upload_image');?>',	
    formData: '',
    add: function (e,data) {
        data.submit();
    },
    done: function (e,data) {
        if (!data.result){
        	alert('上传失败，请尝试上传小图或更换图片格式');return;
        }
        if(data.result.state) {
        	$('input[name="shop_img"]').nextAll().remove('img');
        	$('input[name="shop_img"]').after('<img height="25" src="'+data.result.pic_url+'">');
        	$('input[name="shop_img1"]').val(data.result.pic_name);
        } else {
        	alert(data.result.message);
        }
    },
    fail: function(){
    	alert('上传失败，请尝试上传小图或更换图片格式');
    }
});

$(function(){
	jQuery.validator.addMethod("letters_name", function(value, element) {
		return this.optional(element) || (/^[A-Za-z0-9\u4e00-\u9fa5_-]+$/i.test(value) && !/^\d+$/.test(value));
	}, "Letters only please");
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password,password_confirm'});
	//注册方式切换
	$('.nc-register-mode').tabulous({
		 //动画缩放渐变效果effect: 'scale'
		 effect: 'slideLeft'//动画左侧滑入效果
		//动画下方滑入效果 effect: 'scaleUp'
		//动画反转效果 effect: 'flip'
	});
	var div_form = '#default';
	$(".nc-register-mode .tabs-nav li a").click(function(){
        if($(this).attr("href") !== div_form){
            div_form = $(this).attr('href');
            $(""+div_form).find(".makecode").trigger("click");
    	}
	});
	
//注册表单验证
    $("#register_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('register_form', '', '', 'onerror');
    	},
        onkeyup: false,
        rules : {
            agent_name : {
                required : true,
            },
            agent_password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },
            password_confirm : {
                required : true,
                equalTo  : '#agent_password'
            },
            mobile: {
                required : true,
                mobile : true
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : true,
                remote   : {
                    url : 'index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&type=50,120&nchash=<?php echo getNchash();?>&t=' + Math.random();
                        }
                    }
                }
            },
			<?php } ?>
            agree : {
                required : true
            }
        },
        messages : {
            agent_name : {
                required : '<i class="icon-exclamation-sign"></i>代理商名称不能为空',
            },
            agent_password  : {
                required : '<i class="icon-exclamation-sign"></i>密码不能为空',
                minlength: '<i class="icon-exclamation-sign"></i>密码最小长度为6',
				maxlength: '<i class="icon-exclamation-sign"></i>密码最大长度为20'
            },
            password_confirm : {
                required : '<i class="icon-exclamation-sign"></i>确认密码不能为空',
                equalTo  : '<i class="icon-exclamation-sign"></i>两次输入密码不一致'
            },
            mobile: {
                required : '<i class="icon-exclamation-sign"></i>输入正确的手机号',
                mobile : '<i class="icon-exclamation-sign"></i>输入正确的手机号'
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : '<i class="icon-remove-circle" title="验证码错误"></i>',
				remote	 : '<i class="icon-remove-circle" title="验证码错误"></i>'
            },
			<?php } ?>
            agree : {
                required : '<i class="icon-exclamation-sign"></i>请先同意《服务协议》'
            }
        }
    });
});
</script>
