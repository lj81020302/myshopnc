<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
                    	
                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">团队人员列表</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <div class="span6">
                <div class="dataTables_filter" id="example_filter">
                <label>
                <input type="text" aria-controls="example" placeholder="请输入代理商名称" id="search_name" value="<?php echo $output['search_name']?>">
                <button type="reset" class="btn" style="margin-bottom: 10px;" id="search_btn">搜索</button>
                </label>
                </div>
                </div>
                
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
					<thead>
						<tr>
							<th>代理商名称</th>
							<th>联系方式</th>
							<th>代理商编号</th>
							<th>累计销售额</th>
							<th>累计进货额</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td><?php echo $data['agent_name'];?></td>
							<td><?php echo $data['mobile']?></td>
							<td><?php echo $data['agent_no']?></td>
							<td class="center"><?php echo $data['total_sale_money']?></td>
							<td class="center"><?php echo $data['total_buy_goods']?></td>
							<td class="center"><a href="#addAlert" data-toggle="modal" class="btn btn-success" onclick="showAlert(<?php echo $data['agent_id']?>)">详细</a></td>
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
    <!-- dialog start -->
				<div id="addAlert" class="modal hide">
					<div class="modal-header">
						<button data-dismiss="modal" class="close" type="button">&times;</button>
						<h3><span id="options_name">代理商</span>详情</h3>
					</div>
					<div class="modal-body">
						<table class="table table-bordered">
			              <tbody>
			                <tr>
			                  <td>代理商名称</td>
			                  <td id="agent_name"></td>
			                  <td>代理商编号</td>
			                  <td id="agent_no"></td>
			                </tr>
			                <tr>
			                  <td>联系方式</td>
			                  <td id="mobile"></td>
			                  <td>状态</td>
			                  <td id="status"></td>
			                </tr>
			                <tr>
			                  <td>累计销售额</td>
			                  <td id="total_sale_money"></td>
			                  <td>累计进货额</td>
			                  <td id="total_buy_goods"></td>
			                </tr>
			                <tr>
			                  <td>店铺地址</td>
			                  <td id="shop_address"></td>
			                  <td>余额</td>
			                  <td id="balance"></td>
			                </tr>
			              </tbody>
			            </table>
					</div>
				</div>
				<!-- dialog end -->
    <!-- /block -->
</div>

<script>
$(function() {
    $("#search_btn").click(function(){
        var search_name = $("#search_name").val();
        if(search_name == ''){
            return false;
        }
        window.location.href = "index.php?act=agent&op=order&top_code=10300&menu_code=10310&search_name="+search_name;
//         goDataList();
    })
});

function showAlert(agent_id){
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('agent', 'getTeamAgentInfo');?>',  
        async:false,  
        data:{'agent_id': agent_id},
        success: function(data){
	        var agent = JSON.parse(data);
            if(agent){
                $("#agent_name").html(agent['agent_name']);
                $("#agent_no").html(agent['agent_no']);
                $("#mobile").html(agent['mobile']);
                $("#status").html(agent['is_pass']==1?'<span style="color:green;">正常</span>':'<span style="color:red;">未通过</span>');
                $("#total_sale_money").html(agent['total_sale_money']);
                $("#total_buy_goods").html(agent['total_buy_goods']);
                $("#shop_address").html(agent['shop_address']);
                $("#balance").html(agent['balance']);
    		}
        }  
    });
}
</script>