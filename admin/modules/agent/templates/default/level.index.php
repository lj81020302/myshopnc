<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>代理商等级管理</h3>
        <h5>代理商等级收费及设置</h5>
      </div>
    </div>
  </div>
  <form id="form_grade" method='post' name="">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" class="handle" align="center"><?php echo $lang['nc_handle'];?></th>
          <th width="80" align="center">序号</th>
          <th width="80" align="center">等级名称</th>
          <th width="80" align="center">加盟费用</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['level_list']) && is_array($output['level_list'])){ ?>
        <?php foreach($output['level_list'] as $k => $v){ ?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
            <a class="btn red" href="javascript:if(confirm('确定要删除该等级吗'))window.location = 'index.php?act=level&op=level_del&id=<?php echo $v['id'];?>';"><i class="fa fa-trash-o"></i><?php echo $lang['nc_del'];?></a>
            <span class="btn"><em><i class="fa fa-cog"></i><?php echo $lang['nc_set'];?><i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?act=level&op=level_edit&id=<?php echo $v['id'];?>">编辑选项</a></li>
            </ul>
            </span></td>
          <td><?php echo $v['id'];?></td>
          <td><?php echo $v['level_name'];?></td>
          <td><?php echo $v['join_fee'];?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no-data">
          <td colspan="100" class="no-data"><i class="fa fa-lightbulb-o"></i><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script>
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '代理商等级列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', onpress : fg_operation }
               ]
		});
});

    function fg_operation(name, grid) {
        if (name == 'add') {
            window.location.href = 'index.php?act=level&op=level_add';
        }
    }
</script> 