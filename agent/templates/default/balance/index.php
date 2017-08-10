<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">提现记录</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <div class="span6">
                <div class="dataTables_filter" id="example_filter">
                <label>
                <input type="text" aria-controls="example" placeholder="请输入手机号" id="search_name" value="<?php echo $output['search_name']?>">
                <button type="reset" class="btn" style="margin-bottom: 10px;" id="search_btn">搜索</button>
                </label>
                </div>
                </div>
                
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
					<thead>
						<tr>
							<th>提现信息</th>
							<th>代理商</th>
							<th>提现金额（元）</th>
							<th>申请时间</th>
							<th>处理时间</th>
							<th>状态</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td>
							<?php echo $data['bank_name'];?>&nbsp;
							<?php echo $data['account_num'];?><br>
							<?php echo $data['realname'];?>&nbsp;
							<?php echo $data['mobile'];?>
							</td>
							<td class="center">
							<?php echo $data['agent_name'];?>
							</td>
							<td class="center"><?php echo $data['money'];?></td>
							<td class="center"><?php echo date('Y-m-d', $data['create_time']);?></td>
							<td class="center"><?php if($data['status_id']!=0){echo date('Y-m-d', $data['deal_time']);}?></td>
							<td class="center">
							<span
							<?php if($data['status_id'] == 2){ ?>
							style="color: green;"
							<?php }else{ ?>
							style="color: red;"
							<?php }?>
							>
							<?php echo $data['status_name']?>
							</span>
							</td>
						</tr>
					<?php }
					   }?>
					</tbody>
				</table>
				<div class="pagination">
				<?php echo $output['page']->show();?>
				</div>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>

<script>
$(function() {
    $("#search_btn").click(function(){
        var search_name = $("#search_name").val();
        if(search_name == ''){
            return false;
        }
        window.location.href = "index.php?act=balance&op=index&top_code=10400&menu_code=10430&search_name="+search_name;
//         goDataList();
    })
});
</script>