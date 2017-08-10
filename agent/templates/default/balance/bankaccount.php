<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">提现账号列表</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
            	
            	<div class="table-toolbar" style="display: inline-block;">
                  <div class="btn-group">
                  	<a href="#addAlert" data-toggle="modal" class="btn btn-success" onclick="showAddAlert(0)">新增</a>
                  </div>
               </div>
               <!-- dialog start -->
				<div id="addAlert" class="modal hide">
					<div class="modal-header">
						<button data-dismiss="modal" class="close" type="button">&times;</button>
						<h3><span id="options_name">添加</span>账号</h3>
					</div>
					<div class="modal-body">
						<form action="#" id="form_sample_1" class="form-horizontal" novalidate="novalidate">
						<fieldset>
							<input type="hidden" id="account_id" value="0">
    						<!-- alerts -->
    						<div style="height: 50px;" class="my-alert">
    							<div class="alert hide" id="alerts">
    								<button class="close" data-dismiss="alert">×</button>
    								<span id="tips_info"></span>
    							</div>
    						</div>
    						<!-- alerts end -->
  							<div class="control-group">
  								<label class="control-label">银行名称<span class="required">*</span></label>
  								<div class="controls">
  									<input type="text" name="name" data-required="1" class="span6 m-wrap" id="bankname">
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">支行名称<span class="required">*</span></label>
  								<div class="controls">
  									<input type="text" name="name" data-required="1" class="span6 m-wrap" id="bankchild">
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">银行卡号<span class="required">*</span></label>
  								<div class="controls">
  									<input name="email" type="text" class="span6 m-wrap" id="accountnumber">
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">真实姓名<span class="required">*</span></label>
  								<div class="controls">
  									<input name="email" type="text" class="span6 m-wrap" id="realname">
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">手机号<span class="required">*</span></label>
  								<div class="controls">
  									<input name="email" type="text" class="span6 m-wrap" id="mobile">
  								</div>
  							</div>
  							
						</fieldset>
					</form>
					</div>
					<div class="modal-footer">
						<a class="btn btn-primary" href="#" onclick="addUpdateBankaccount()">确认</a>
						<a data-dismiss="modal" class="btn" href="#">取消</a>
					</div>
				</div>
				<div id="delAlert" class="modal hide">
					<div class="modal-header">
						<button data-dismiss="modal" class="close" type="button">&times;</button>
						<h3>提示</h3>
					</div>
					<div class="modal-body">
						<p>删除后不可恢复，您确定要删除吗？</p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-primary" href="#" data-id="" id="del-btn" onclick="doDel()">确定</a>
						<a data-dismiss="modal" class="btn" href="#">取消</a>
					</div>
				</div>
				<!-- dialog end -->
                <div class="span6" style="float: right;">
                <div class="dataTables_filter" id="example_filter">
                <label>
                <input type="text" aria-controls="example" placeholder="请输入手机号" id="search_name" value="<?php echo $output['search_name']?>">
                <button type="reset" class="btn" style="margin-bottom: 10px;" id="search_btn">搜索</button>
                </label>
                </div>
                </div>
                
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
					<thead>
						<tr>
							<th>银行名称</th>
							<th>支行名称</th>
							<th>银行账号</th>
							<th>真实姓名</th>
							<th>手机号</th>
							<th>是否默认</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td>
							<?php echo $data['bank_name'];?>&nbsp;
							</td>
							<td>
							<?php echo $data['bank_child'];?>&nbsp;
							</td>
							<td class="center">
							<?php echo $data['account_num'];?>
							</td>
							<td>
							<?php echo $data['realname'];?>&nbsp;
							</td>
							<td>
							<?php echo $data['mobile'];?>
							</td>
							<td>
							<?php echo $data['is_default']==1?'<span class="badge badge-success">默认</span>':'';?>
							
							</td>
							<td>
							<a href="#delAlert" data-toggle="modal" class="btn btn-danger btn-mini" onclick="showDel(<?php echo $data['id']?>)">删除</a>
							<a href="#addAlert" data-toggle="modal" class="btn btn-mini" onclick="showAddAlert(<?php echo $data['id']?>)">修改</a>
							<?php echo $data['is_default']==1?'':'<a href="javascript:;" class="btn btn-warning btn-mini" onclick="setDefault('.$data['id'].')">设为默认</a>';?>
							
							</td>
						</tr>
					<?php }
					   }?>
					</tbody>
				</table>
				<div class="pagination">
				<?php echo $output['page']->show();?>
				</div>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>
<script type="text/javascript" src="<?php echo AGENT_TEMPLATES_URL?>/js/myalert.js"></script>
<script>
$(function() {
    $("#search_btn").click(function(){
        var search_name = $("#search_name").val();
        if(search_name == ''){
            return false;
        }
        window.location.href = "<?php echo AGENT_SITE_URL?>/index.php?act=balance&op=bankaccount&top_code=10400&menu_code=10440&search_name="+search_name;
//         goDataList();
    })
});
/*显示弹窗*/
function showAddAlert(account_id){
	if(account_id==0){
		$("#options_name").html("新增");
		$("#account_id").val(0);		
	}else{
		$("#options_name").html("修改");
		$("#account_id").val(account_id);
		$.ajax({
	        type:"POST",
	        url:'<?php echo urlAgent('balance', 'getBankaccount');?>',  
	        async:false,  
	        data:{'account_id': account_id},
	        success: function(data){
		        var bankaccount = JSON.parse(data);
	            if(data){
	                $("#bankname").val(bankaccount['bank_name']);
	                $("#bankchild").val(bankaccount['bank_child']);
	                $("#accountnumber").val(bankaccount['account_num']);
	                $("#realname").val(bankaccount['realname']);
	                $("#mobile").val(bankaccount['mobile']);
	    		}
	        }  
	    });  
	}
}
//*删除*/
function showDel(account_id){
	$("#del-btn").data("del-id", account_id);
}
function doDel(){
	var account_id = $("#del-btn").data("del-id");
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('balance', 'delBankaccount');?>',  
        async:false,  
        data:{'account_id': account_id},
        success: function(data){
            if(!data){
        		
    		}else{
            	document.location.reload();
    		}
        }  
    });  
    
}
/*设置默认*/
function setDefault(account_id){
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('balance', 'defaultBankaccount');?>',  
        async:false,  
        data:{'account_id': account_id},
        success: function(data){
            if(!data){
        		
    		}else{
            	document.location.reload();
    		}
        }  
    });  
}
/*添加修改*/
function addUpdateBankaccount(){
	if(validateData()){
    	$.ajax({
            type:"POST",
            url:'<?php echo urlAgent('balance', 'addUpdateBankaccount');?>',  
            async:false,  
            data:{
                'account_id': $.trim($("#account_id").val()),
                'bankname':$.trim($("#bankname").val()),
                'bankchild':$.trim($("#bankchild").val()),
                'accountnumber':$.trim($("#accountnumber").val()),
                'realname':$.trim($("#realname").val()),
                'mobile':$.trim($("#mobile").val())
                },
    //         dataType: 'html',
            success: function(data){
                if(!data){
            		
        		}else{
                	document.location.reload();
        		}
            }  
        });  
	}
}
/*验证数据*/
function validateData(){
	if($.trim($("#bankname").val()) == ''){
		$("#bankname").focus();
		showAlerts("warning", "请输入银行名称");
		return false;
	}
	if($.trim($("#bankchild").val()) == ''){
		$("#bankchild").focus();
		showAlerts("warning", "请输入支行名称");
		return false;
	}
	if($.trim($("#accountnumber").val()) == ''){
		$("#accountnumber").focus();
		showAlerts("warning", "请输入银行卡号");
		return false;
	}
	if($.trim($("#realname").val()) == ''){
		$("#realname").focus();
		showAlerts("warning", "请输入真实姓名");
		return false;
	}
	if($.trim($("#mobile").val()) == ''){
		$("#mobile").focus();
		showAlerts("warning", "请输入手机号");
		return false;
	}
    
    return true;
}
</script>