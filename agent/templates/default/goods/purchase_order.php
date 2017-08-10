<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">进货单列表</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <div class="span6">
                <div class="dataTables_filter" id="example_filter">
                <label>
                <input type="text" aria-controls="example" placeholder="请输入订单号" id="search_name" value="<?php echo $output['search_name']?>">
                <button type="reset" class="btn" style="margin-bottom: 10px;" id="search_btn">搜索</button>
                </label>
                </div>
                </div>
                
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
					<thead>
						<tr>
							<th>进货信息</th>
							<th>进货价</th>
							<th>数量</th>
							<th>生成时间</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td colspan="6">
							<span style="color:gray;font-size:12px;">订单号：<?php echo $data['order_sn'];?></span>
							<div style="display: inline-block;float: right;color: gray;font-size: 12px;">订单总额：<span style="color:gray;">￥<?php echo $data['order_money']?></span></div>
							</td>
						</tr>
						<tr class="odd gradeX">
							<td colspan="1"><?php echo $data['goods_name'];?></td>
							<td>￥<?php echo $data['goods_price']?></td>
							<td><?php echo $data['goods_num']?></td>
							<td><?php echo date('Y-m-d H:m', $data['create_time']);?></td>
							<td class="center">
							<?php switch ($data['status_id']){
							    case(1):
							        echo '<span style="color:green;">已通过</span>';
							        break;
							    case(2):
							        echo '<span style="color:green;">已配送</span>';
							        break;
							    case(0):
							        echo '<span>未处理</span>';
							        break;
							    case(-1):
							        echo '<span style="color:red;">已关闭</span>';
							        break;
							}?>
							</td>
							<td>
							<?php if($data['status_id']==0){?>
							<a href="#addAlert" data-toggle="modal" class="btn btn-success" onclick="showAddAlert(<?php echo $data['id'];?>)">撤销</a>
							<?php }?>
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
<div id="addAlert" class="modal hide">
	<div class="modal-header">
		<button data-dismiss="modal" class="close" type="button">&times;</button>
		<h4><span id="options_name">您确定要撤销吗？</span></h4>
	</div>
	<div class="modal-footer">
		<a class="btn btn-primary" id="submit-btn" href="#" onclick="add()">确认</a>
		<a data-dismiss="modal" class="btn" href="#">取消</a>
	</div>
</div>
<script>
$(function() {
    $("#search_btn").click(function(){
        var search_name = $("#search_name").val();
        if(search_name == ''){
            return false;
        }
        window.location.href = "index.php?act=goods&op=purchase_order&top_code=10200&menu_code=10240&search_name="+search_name;
//         goDataList();
    })
});

function showAddAlert(id){
	  $("#submit-btn").data("data-id", id);
}

function add(){
	var id =  $("#submit-btn").data("data-id");;
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('goods', 'upd_purchase_order');?>',  
        async:false,  
        data:{'id': id},
        success: function(data){
	        if(data > 0){
				alert("撤销成功");
    		    location.reload();
		    }else{
			    alert("撤销失败");
		    }
        }  
    });  
}
</script>