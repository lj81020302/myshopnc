<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>佣金管理</h3>
        <h5>佣金列表</h5>
      </div>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=commission&op=get_xml',
        colModel : [
            {display: 'ID', name : 'id', width : 40, sortable : true, align: 'center'},
            {display: '代理商', name : 'agent_name', width : 120, sortable : false, align: 'left'},
            {display: '金额', name : 'money', width : 60, sortable : true, align: 'center'},
            {display: '佣金类型', name : 'type_name', width : 120, sortable : false, align: 'left'},
            {display: '时间', name : 'create_time', width: 120, sortable : true, align : 'center'},
            ],
        searchitems : [
            {display: '代理商', name : 'agent_name'},
            ],
        sortname: "id",
        sortorder: "desc",
        title: '佣金列表'
    });
});

</script>
