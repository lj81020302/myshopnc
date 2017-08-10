<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<style type="text/css">
.d-head{border: 1px solid #dff0d8;padding: 15px;background: #dff0d8;}
.d-main{border: 1px solid #dff0d8;padding: 15px;height:60px;line-height: 60px;}
</style>                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">账号余额</div>
            <div class="pull-right" style="padding-bottom: 10px;">
    			<a href="#addAlert" data-toggle="modal" class="btn btn-success" onclick="applyInfo()">申请提现</a>
            </div>
        </div>
        <div class="block-content collapse in">
            <div class="span3" align="center" style="margin-left: 12%;">
            
            	<div class="d-head">
                	帐户余额
            	</div>
                <div class="d-main">
            		￥<?php echo $output['agent_account']['balance']?>
            	</div>
            
            </div>
            <div class="span3" align="center" style="margin-left: 12%;">
            	<div class="d-head">
            		<label>
                	可提现金额
            		</label>
            	</div>
                <div class="d-main">
            		￥<?php echo $output['agent_account']['balance']?>
            		<br>
                </div>
            </div>
            <div class="span3" align="center" style="margin-left: 12%;">
            	<div class="d-head">
            		<label>
                	佣金总额
            		</label>
            	</div>
                <div class="d-main">
            		￥<?php echo $output['commission_total'] ?>
                </div>
            </div>
        </div>
    </div>
            
            <!-- morris graph chart -->
            <div class="row-fluid section">
                 <!-- block -->
                <div class="block">
                    <div class="navbar navbar-inner block-header">
                        <div class="muted pull-left">佣金统计</div>
                        <div class="pull-right" style="padding-bottom: 10px;">
                        	<button class="btn btn-danger" onclick="getCommissionCount(1)" id="today">今日</button>
                            <button class="btn" onclick="getCommissionCount(2)" id="yesterday">昨日</button>
                            <button class="btn" onclick="getCommissionCount(3)" id="week">本周</button>
                            <button class="btn" onclick="getCommissionCount(4)" id="month">本月</button>
                        </div>
                    </div>
                    <div class="block-content collapse in">
                        <div class="span12">
                            <div id="hero-graph" style="height: 230px;">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /block -->
            </div>

            <!-- morris bar & donut charts -->
            <!-- dialog start -->
				<div id="addAlert" class="modal hide">
					<div class="modal-header">
						<button data-dismiss="modal" class="close" type="button">&times;</button>
						<h3>申请提现</h3>
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
  								<label class="control-label">银行账户<span class="required">*</span></label>
  								<div class="controls" style="color: red;">
  									<select id="bankaccount_id">
                                      <option value="0">请选择帐户</option>
                                      <?php foreach ($output['bankaccountList'] as $bankaccount){?>
                                      <option value="<?php echo $bankaccount['id']?>" <?php if($bankaccount['is_default']==1){echo 'selected';}?>><?php echo $bankaccount['bank_name'];?></option>
                                      <?php }?>
                                    </select>
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">最多可提现<span class="required">*</span></label>
  								<div class="controls" style="color: red;">
  									<input type="hidden" id="available_money" value="0">
  									<span id="available_money_text">0</span>
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">提现金额<span class="required">*</span></label>
  								<div class="controls">
  									<input type="text" name="name" data-required="1" class="span6 m-wrap" id="withdraw_money" onkeyup="num(this)">
  								</div>
  							</div>
  							
						</fieldset>
					</form>
					</div>
					<div class="modal-footer">
						<a class="btn btn-primary" href="#" onclick="applyWithdraw()">确认</a>
						<a data-dismiss="modal" class="btn" href="#">取消</a>
					</div>
				</div>
				<!-- dialog end -->
    <!-- /block -->
</div>

<link rel="stylesheet" href="<?php echo AGENT_RESOURCE_URL?>/vendors/morris/morris.css">
<script src="<?php echo AGENT_RESOURCE_URL?>/vendors/raphael-min.js"></script>
<script src="<?php echo AGENT_RESOURCE_URL?>/vendors/morris/morris.min.js"></script>
<script type="text/javascript" src="<?php echo AGENT_TEMPLATES_URL?>/js/myalert.js"></script>
<script>
$(function() {
	getCommissionCount(1);
});
function getCommissionCount(type_id){
	if(type_id==1){
		$(".btn").removeClass("btn-danger");
		$("#today").addClass("btn btn-danger");
	}else if(type_id==2){
		$(".btn").removeClass("btn-danger");
		$("#yesterday").addClass("btn btn-danger");
	}else if(type_id==3){
		$(".btn").removeClass("btn-danger");
		$("#week").addClass("btn btn-danger");
	}else if(type_id==4){
		$(".btn").removeClass("btn-danger");
		$("#month").addClass("btn btn-danger");
	}
		
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('balance', 'getCommissionCount');?>',  
        async:false,  
        data:{
            'type_id': type_id
            },
//         dataType: 'html',
        success: function(data){
        	var tax_data = JSON.parse(data);
			var type_name = '';
        	if(type_id == 1){
        		type_name = 'hour';
        	}else if(type_id == 2){
        		type_name = 'hour';
        	}else if(type_id == 3){
        		type_name = 'day';
        	}else if(type_id == 4){
        		type_name = 'day';
        	}
	    	showChart(type_name, tax_data);
        }
    });
// 	showChart('hour', type_id);
}
function showChart(type_name, tax_data){
	$("#hero-graph").html("");
    Morris.Line({
        element: 'hero-graph',
        data: tax_data,
        xkey: 'xdate',
        xLabels: type_name,
        ykeys: ['ydata'],
        labels: ['佣金']
    });
}

function applyWithdraw(){
	if(validateData()){
    	$.ajax({
            type:"POST",
            url:'<?php echo urlAgent('balance', 'applyWithdraw');?>',  
            async:false,  
            data:{
                'money': $.trim($("#withdraw_money").val()),
                'bankaccount_id': $("#bankaccount_id").val()
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
function applyInfo(){
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('agent', 'getAgentInfo');?>',  
        async:false,  
        data:{},
        success: function(data){
            var agent = JSON.parse(data);
            $("#available_money").val(agent['balance']);
            $("#available_money_text").html('￥'+agent['balance']);
        }  
    });  
}
/*验证数据*/
function validateData(){
	if($("#bankaccount_id").val() == 0){
		$("#bankaccount_id").focus();
		showAlerts("warning", "请选择银行帐户");
		return false;
	}
	if($.trim($("#withdraw_money").val()) == ''){
		$("#withdraw_money").focus();
		showAlerts("warning", "请输入提现金额");
		return false;
	}
	if($.trim($("#withdraw_money").val()) > $("#available_money").val()){
		$("#withdraw_money").focus();
		showAlerts("warning", "可提现金额不足");
		return false;
	}
    
    return true;
}
function num(obj){
	obj.value = obj.value.replace(/[^\d.]/g,""); //清除"数字"和"."以外的字符
	obj.value = obj.value.replace(/^\./g,""); //验证第一个字符是数字
	obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个, 清除多余的
	obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
	obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3'); //只能输入两个小数
}
</script>