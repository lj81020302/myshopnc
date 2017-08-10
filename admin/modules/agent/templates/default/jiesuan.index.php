<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>结算管理</h3>
        <h5>代理商订单佣金，产品利润</h5>
      </div>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=jiesuan&op=get_xml',
        colModel : [
            {display: '结算ID', name : 'id', width : 60, sortable : true, align: 'center'},
            {display: '订单号', name : 'order_sn', width : 120, sortable : true, align: 'center'},
            {display: '商品信息及佣金', name : 'goods', width : 270, sortable : false, align: 'left'},
            {display: '总佣金', name : 'money', width : 120, sortable : true, align: 'center'},
            {display: '代理商名称', name : 'agent_name', width : 120, sortable : true, align: 'center'},
            {display: '时间', name : 'create_time', width: 120, sortable : true, align : 'center'},
            ],
        searchitems : [
            {display: '代理商名称', name : 'agent_name', isdefault: true},
            ],
        sortname: "id",
        sortorder: "desc",
        title: '结算列表'
    });
});

</script>
