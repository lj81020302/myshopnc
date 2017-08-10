<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>代理商管理</h3>
        <h5>代理商管理，审核</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">代理商列表</a></li>
        <li><a href="index.php?act=agent&op=agent_apply">待审核列表</a></li>
      </ul>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=agent&op=get_xml&type=1',
        colModel : [
			{display: '操作', name : 'w_no', width : 160, sortable : true, align: 'left'},
            {display: '编号', name : 'agent_no', width : 120, sortable : true, align: 'left'},
            {display: '代理商', name : 'agent_name', width : 60, sortable : true, align: 'center'},
            {display: '手机号', name : 'mobile', width: 100, sortable : true, align : 'center'},
            {display: '等级', name : 'money', width : 60, sortable : true, align: 'center'},
            {display: '余额', name : 'status_name', width : 60, sortable : true, align: 'center'},
            {display: '状态', name : 'account_num', width : 150, sortable : true, align: 'center'},
            {display: '累计销售额', name : 'bank_name', width : 120, sortable : false, align: 'left'},
            {display: '累计进货额', name : 'realname', width : 80, sortable : false, align: 'left'},
            {display: '联系人', name : 'create_time', width: 80, sortable : true, align : 'center'},
            {display: '店铺地址', name : 'deal_timein', width : 80, sortable : true, align: 'center'},
            {display: '店铺图片', name : 'shop_img', width : 80, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '代理商', name : 'agent_name', isdefault: true},
            ],
        sortname: "id",
        sortorder: "desc",
        title: '代理商列表'
    });
});
</script>
