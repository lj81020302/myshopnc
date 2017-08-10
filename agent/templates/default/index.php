<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<!-- morris graph chart -->
<div class="row-fluid section">
     <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">订单统计</div>
            <div class="pull-right order_div" style="padding-bottom: 10px;">
            	<button class="btn btn-danger" onclick="getOrderCount(1)" id="today">今日</button>
                <button class="btn" onclick="getOrderCount(2)" id="yesterday">昨日</button>
                <button class="btn" onclick="getOrderCount(3)" id="week">本周</button>
                <button class="btn" onclick="getOrderCount(4)" id="month">本月</button>
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
<!-- morris graph chart -->
<div class="row-fluid section">
     <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">佣金统计</div>
            <div class="pull-right commission_div" style="padding-bottom: 10px;">
            	<button class="btn btn-danger" onclick="getCommissionCount(1)" id="today_commission">今日</button>
                <button class="btn" onclick="getCommissionCount(2)" id="yesterday_commission">昨日</button>
                <button class="btn" onclick="getCommissionCount(3)" id="week_commission">本周</button>
                <button class="btn" onclick="getCommissionCount(4)" id="month_commission">本月</button>
            </div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <div id="commission-graph" style="height: 230px;">
                
                </div>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>

<!-- morris bar & donut charts -->
<!-- morris graph chart -->
<div class="row-fluid section">
     <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">商品销量统计</div>
            <div class="pull-right goods_div" style="padding-bottom: 10px;">
            	<button class="btn btn-danger" onclick="getOrderGoodsCount(1)" id="today_goods">今日</button>
                <button class="btn" onclick="getOrderGoodsCount(2)" id="yesterday_goods">昨日</button>
                <button class="btn" onclick="getOrderGoodsCount(3)" id="week_goods">本周</button>
                <button class="btn" onclick="getOrderGoodsCount(4)" id="month_goods">本月</button>
            </div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <div id="goods-graph" style="height: 230px;">
                
                </div>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>

<!-- morris bar & donut charts -->
<link rel="stylesheet" href="<?php echo AGENT_RESOURCE_URL?>/vendors/morris/morris.css">
<script src="<?php echo AGENT_RESOURCE_URL?>/vendors/raphael-min.js"></script>
<script src="<?php echo AGENT_RESOURCE_URL?>/vendors/morris/morris.min.js"></script>

<script>
$(function() {
	getOrderCount(1);
	getOrderGoodsCount(1);
	getCommissionCount(1);
});
function getOrderCount(type_id){
	if(type_id==1){
		$(".order_div button").removeClass("btn-danger");
		$("#today").addClass("btn btn-danger");
	}else if(type_id==2){
		$(".order_div button").removeClass("btn-danger");
		$("#yesterday").addClass("btn btn-danger");
	}else if(type_id==3){
		$(".order_div button").removeClass("btn-danger");
		$("#week").addClass("btn btn-danger");
	}else if(type_id==4){
		$(".order_div button").removeClass("btn-danger");
		$("#month").addClass("btn btn-danger");
	}
		
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('goods', 'getOrderCount');?>',  
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
	    	showChart('hero-graph', type_name, tax_data, '销量');
        }
    });
// 	showChart('hour', type_id);
}
function getCommissionCount(type_id){
	if(type_id==1){
		$(".commission_div button").removeClass("btn-danger");
		$("#today_commission").addClass("btn btn-danger");
	}else if(type_id==2){
		$(".commission_div button").removeClass("btn-danger");
		$("#yesterday_commission").addClass("btn btn-danger");
	}else if(type_id==3){
		$(".commission_div button").removeClass("btn-danger");
		$("#week_commission").addClass("btn btn-danger");
	}else if(type_id==4){
		$(".commission_div button").removeClass("btn-danger");
		$("#month_commission").addClass("btn btn-danger");
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
	    	showChart('commission-graph', type_name, tax_data, '佣金');
        }
    });
// 	showChart('hour', type_id);
}
function getOrderGoodsCount(type_id){
	if(type_id==1){
		$(".goods_div button").removeClass("btn-danger");
		$("#today_goods").addClass("btn btn-danger");
	}else if(type_id==2){
		$(".goods_div button").removeClass("btn-danger");
		$("#yesterday_goods").addClass("btn btn-danger");
	}else if(type_id==3){
		$(".goods_div button").removeClass("btn-danger");
		$("#week_goods").addClass("btn btn-danger");
	}else if(type_id==4){
		$(".goods_div button").removeClass("btn-danger");
		$("#month_goods").addClass("btn btn-danger");
	}
		
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('goods', 'getOrderGoodsCount');?>',  
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
	    	showChart('goods-graph', type_name, tax_data, '销量');
        }
    });
// 	showChart('hour', type_id);
}
function showChart(graph_name, type_name, tax_data, label_name){
	$("#"+graph_name).html("");
    Morris.Line({
        element: graph_name,
        data: tax_data,
        xkey: 'xdate',
        xLabels: type_name,
        ykeys: ['ydata'],
        labels: [label_name]
    });
}
</script>