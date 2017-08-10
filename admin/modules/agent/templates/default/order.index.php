<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>订单管理</h3>
        <h5>订单管理，代理商销售订单</h5>
      </div>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=order&op=get_xml',
        colModel : [
            {display: '订单ID', name : 'id', width : 60, sortable : true, align: 'left'},
            {display: '订单编号', name : 'order_sn', width : 100, sortable : true, align: 'center'},
            {display: '支付单号', name : 'pay_sn', width : 100, sortable : true, align: 'center'},
            {display: '商品总价', name : 'goods_amount', width : 100, sortable : true, align: 'center'},
            {display: '订单总价', name : 'order_amount', width : 100, sortable : true, align: 'center'},
            {display: '退款金额', name : 'refund_amount', width : 100, sortable : true, align: 'center'},
            {display: '外部交易订单号', name : 'trade_no', width : 100, sortable : true, align: 'center'},
            {display: '代理商', name : 'agent_name', width : 100, sortable : true, align: 'center'},
            {display: '订单佣金', name : 'commission', width : 100, sortable : true, align: 'center'},
            {display: '下单时间', name : 'add_time', width : 100, sortable : true, align: 'center'},
            {display: '完成时间', name : 'finished_time', width : 100, sortable : true, align: 'center'},
            ],
        searchitems : [
            {display: '代理商', name : 'agent_name', isdefault: true},
            ],
        sortname: "id",
        sortorder: "desc",
        title: '订单列表'
    });
});
</script>
