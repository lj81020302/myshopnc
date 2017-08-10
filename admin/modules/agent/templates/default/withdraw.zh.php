<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>提现管理</h3>
        <h5>提现管理，提现审核及提现账号列表</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=withdraw&op=withdraw">提现列表</a></li>
        <li><a href="index.php?act=withdraw&op=withdraw_apply">待审核列表</a></li>
        <li><a href="JavaScript:void(0);" class="current">提现账户列表</a></li>
      </ul>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=withdraw&op=get_zhxml',
        colModel : [
            {display: '代理商名称', name : 'agent_name', width: 60, sortable : true, align : 'center'},
            {display: '银行名称', name : 'back_name', width : 120, sortable : true, align: 'center'},
            {display: '银行支行名称', name : 'back_child', width : 150, sortable : false, align: 'left'},
            {display: '银行账户', name : 'account_num', width : 150, sortable : true, align: 'center'},
            {display: '手机号', name : 'mobile', width : 120, sortable : false, align: 'left'},
            {display: '真实姓名', name : 'realname', width: 80, sortable : true, align : 'center'},
            {display: '是否默认', name : 'is_default', width : 60, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '代理商名称', name : 'agent_name', isdefault: true},
            ],
        sortname: "id",
        sortorder: "desc",
        title: '提现账户列表'
    });
});

</script>
