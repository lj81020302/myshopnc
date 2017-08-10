<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=level&op=level" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo 代理商等级管理;?> - 编辑</h3>
        <h5><?php echo 代理商商等级收费及设置;?></h5>
      </div>
    </div>
  </div>
  <form id="grade_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value="<?php echo $output['level_array']['id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="join_fee"><em>*</em>等级名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['level_array']['level_name'];?>" id="level_name" name="level_name" class="input-txt">
          <span class="err"></span>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sg_price"><em>*</em>加盟费用</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['level_array']['join_fee'];?>" id="join_fee" name="join_fee" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo "收费标准，单：元/年，必须为数字；数值越大表明级别越高" ?></p>
        </dd>
      </dl>
      
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#grade_form").valid()){
     $("#grade_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#grade_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

rules : {
            
        	level_name : {
                required  : true,
            },
			join_fee : {
                required  : true,
                number : true,
                min : 0
            },
            
        },
        messages : {
        	level_name : {
                required  : '<i class="fa fa-exclamation-circle"></i>等级名称不能为空',
            },
        	join_fee : {
                required  : '<i class="fa fa-exclamation-circle"></i><?php echo "加盟费用仅能为大于0数字";?>',
                number : '<i class="fa fa-exclamation-circle"></i><?php echo "加盟费用仅能为大于0数字";?>',
                min : '<i class="fa fa-exclamation-circle"></i><?php echo "加盟费用仅能为大于0数字";?>',
            },
            
        }
    });
});
</script> 
