<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<!-- 公司信息 -->

<div id="apply_company_info" class="apply-company-info">
  <div class="alert">
    <h4>注意事项：</h4>
    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。</div>
  <form id="form_company_info" action="index.php?act=agent_register&op=apply_agent_save" method="post">
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="2">公司及联系人信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>代理商名称：</th>
          <td><input name="agent_name" type="text" class="w200"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>设置密码：</th>
          <td><input type="password" id="agent_password" name="agent_password" value=""  class="w100"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>确认密码：</th>
          <td><input type="password" id="password_confirm" name="password_confirm" value=""  class="w100"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>等级：</th>
          <td>
          <select id="level_id" name="level_id" class="w100">
                        <option value="0">请选择等级</option>
                        <?php foreach ($output['level_list'] as $k=>$v){ ?>
                              <option value="<?php echo $v['id'] ?>"><?php echo $v['level_name']?></option>  
                        <?php } ?>
                    </select>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人姓名：</th>
          <td><input name="consigner" type="text" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>联系人手机：</th>
          <td><input name="mobile" type="text" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>店铺地址：</th>
          <td><input name="shop_address" type="text" class="w200">
            <span></span></td>
        </tr>
         <tr>
          <th>店铺照片：</th>
          <td><input name="shop_img" type="file" class="w60" />
            <span class="block">请确保图片清晰。</span>
            <input name="shop_img1" type="hidden"/><span></span>
            </td>
        </tr>
        <tr>
          <th>推荐人编号：</th>
          <td><input name="parent_no" type="text" class="w100" />
            <span>如果有推荐人，请输入推荐人编号，如果没有，则无需输入。</span></td>
        </tr>
      </tbody>
       <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">立即提交</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('input[name="shop_img"]').fileupload({
        dataType: 'json',
        url: '<?php echo urlAgent('agent_register', 'ajax_upload_image');?>',
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
            	$('input[name="shop_img"]').after('<img height="60" src="'+data.result.pic_url+'">');
            	$('input[name="shop_img1"]').val(data.result.pic_name);
            } else {
            	alert(data.result.message);
            }
        },
        fail: function(){
        	alert('上传失败，请尝试上传小图或更换图片格式');
        }
    });
    $('#company_address').nc_region();
    $('#business_licence_address').nc_region();
    
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
            $('#apply_agreement').hide();
            $('#apply_company_info').show();
        } else {
            alert('请阅读并同意协议');
        }
    });

    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
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
            level_id:{
            	required    : true,
//                 number      : true,
                min         : 1,
            },
            mobile: {
                required : true,
                mobile : true
            },
            consigner: {
                required : true,
            },
            shop_address: {
                required : true,
            },
            
        },
        messages : {
            agent_name : {
                required : '代理商名称不能为空',
            },
            agent_password  : {
                required : '密码不能为空',
                minlength: '密码最小长度为6',
				maxlength: '密码最大长度为20'
            },
            password_confirm : {
                required : '确认密码不能为空',
                equalTo  : '两次输入密码不一致'
            },
            level_id:{
            	required    : "请选择会员等级",
                min         : "请选择会员等级",
            },
            mobile: {
                required : '输入正确的手机号',
                mobile : '输入正确的手机号'
            },
            shop_address: {
                required : '店铺地址不能为空',
            },
        }
    });

    $('#btn_apply_company_next').on('click', function() {
        if($('#form_company_info').valid()) {
        	$('#province_id').val($("#company_address").fetch('area_id_1'));
            $('#form_company_info').submit();
        }
    });
});
</script> 
