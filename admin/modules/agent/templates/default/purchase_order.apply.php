<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>进货单管理</h3>
        <h5>进货单管理，代理商申请进货，审核，发货</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=purchase_order&op=index">已配送列表</a></li>
        <li><a href="JavaScript:void(0);" class="current">待审核列表</a></li>
        <li><a href="index.php?act=purchase_order&op=fahuo">待发货列表</a></li>
        <li><a href="index.php?act=purchase_order&op=jujue">已拒绝列表</a></li>
      </ul>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=purchase_order&op=get_xml&type=0',
        colModel : [
            {display: '操作', name : 'operation', width : 120, sortable : true, align: 'left'},
            {display: '进货单ID', name : 'id', width : 60, sortable : true, align: 'left'},
            {display: '进货单编号', name : 'order_sn', width : 120, sortable : true, align: 'center'},
            {display: '代理商', name : 'agent_name', width : 100, sortable : true, align: 'center'},
            {display: '商品名称', name : 'goods_name', width : 150, sortable : true, align: 'center'},
            {display: '商品单价', name : 'goods_price', width : 120, sortable : true, align: 'center'},
            {display: '进货数量', name : 'goods_num', width : 60, sortable : true, align: 'center'},
            {display: '订单总额', name : 'order_money', width : 120, sortable : true, align: 'center'},
            {display: '当前状态', name : 'bank_name', width : 120, sortable : false, align: 'left'},
            {display: '申请时间', name : 'create_time', width: 120, sortable : true, align : 'center'},
            {display: '处理时间', name : 'deal_time', width : 120, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '代理商', name : 'agent_name', isdefault: true},
            ],
        sortname: "id",
        sortorder: "desc",
        title: '进货单列表'
    });
});
function tongguo(id){
	    	$.ajax({
	            type:"POST",
	            url:'index.php?act=purchase_order&op=tongguo',  
	            async:false,  
	            data:{
	                'id': id,
	                },
	    //         dataType: 'html',
	            success: function(data){
	                if(!data){
		                
	        		}else{
		        		alert("已通过");
	                	document.location.reload();
	        		}
	            }  
	        });  
}
function butongguo(id){
	$.ajax({
        type:"POST",
        url:'index.php?act=purchase_order&op=butongguo',  
        async:false,  
        data:{
            'id': id,
            },
//         dataType: 'html',
        success: function(data){
            if(!data){
                
    		}else{
        		alert("已拒绝");
            	document.location.reload();
    		}
        }  
    });  
}
</script>
