<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<!-- morris graph chart -->
<div class="row-fluid section">
     <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">商品销量统计</div>
            <div class="pull-right" style="padding-bottom: 10px;">
            	<button class="btn btn-danger" onclick="getOrderGoodsCount(1)" id="today">今日</button>
                <button class="btn" onclick="getOrderGoodsCount(2)" id="yesterday">昨日</button>
                <button class="btn" onclick="getOrderGoodsCount(3)" id="week">本周</button>
                <button class="btn" onclick="getOrderGoodsCount(4)" id="month">本月</button>
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
<link rel="stylesheet" href="<?php echo AGENT_RESOURCE_URL?>/vendors/morris/morris.css">
<script src="<?php echo AGENT_RESOURCE_URL?>/vendors/raphael-min.js"></script>
<script src="<?php echo AGENT_RESOURCE_URL?>/vendors/morris/morris.min.js"></script>

<script>
$(function() {
	getOrderGoodsCount(1);
});
function getOrderGoodsCount(type_id){
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
        labels: ['销量']
    });
}
</script>