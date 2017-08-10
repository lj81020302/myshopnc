<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
        
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">进货市场</div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <div class="span6">
                <div class="dataTables_filter" id="example_filter">
                <label>
                <input type="text" aria-controls="example" placeholder="请输入商品名称" id="search_name" value="<?php echo $output['search_name']?>">
                <button type="reset" class="btn" style="margin-bottom: 10px;" id="search_btn">搜索</button>
                </label>
                </div>
                </div>
                
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
					<thead>
						<tr>
							<th>商品名称</th>
							<th>统一售价</th>
							<th>进价（元）</th>
							<th>库存</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td>
    							<a href="<?php echo SHOP_SITE_URL?>/index.php?act=goods&op=index&goods_id=<?php echo $data['goods_id']?>" target="_blank">
    							<img src="<?php echo thumb($data, '60');?>">
    							<span class="goods_name"><?php echo $data['goods_name'];?></span>
    							</a>
							</td>
							<td class="goods_price"><?php echo $data['goods_price']?></td>
							<td class="center goods_costprice"><?php echo $data['goods_costprice']?></td>
							<td class="center goods_storage"><?php echo $data['goods_storage']?></td>
							<input type="hidden" class="goods_id" value="<?php echo $data['goods_id']?>">
							<td class="center"> <a href="#addAlert" data-toggle="modal" class="btn btn-success" onclick="showAddAlert(this)">进货</a> </td>
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
<!-- dialog start -->
				<div id="addAlert" class="modal hide">
					<div class="modal-header">
						<button data-dismiss="modal" class="close" type="button">&times;</button>
						<h3><span id="options_name">进货</span></h3>
					</div>
					<div class="modal-body">
						<form action="#" id="form_sample_1" class="form-horizontal" novalidate="novalidate">
						<fieldset>
							<input type="hidden" id="goods_id" value="0">
							<input type="hidden" id="goods_price" value="0">
    						<!-- alerts -->
    						<div style="height: 50px;" class="my-alert">
    							<div class="alert hide" id="alerts">
    								<button class="close" data-dismiss="alert">×</button>
    								<span id="tips_info"></span>
    							</div>
    						</div>
    						<!-- alerts end -->
  							<div class="control-group">
  								<label class="control-label">商品名称</label>
  								<div class="controls">
  									<span id="goods_name"></span>
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">商品单价</label>
  								<div class="controls">
  									<span id="goods_costprice"></span>
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">商品库存</label>
  								<div class="controls">
  									<input type="number" class="span6 m-wrap" id="goods_storage" value="0" disabled>
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">商品数量<span class="required">*</span></label>
  								<div class="controls">
  									<input type="number" class="span6 m-wrap" id="goods_num" value="0" onchange="check_goods_num(this)">
  								</div>
  							</div>
  							<div class="control-group">
  								<label class="control-label">商品总价</label>
  								<div class="controls">
  									<span id="all_price">0</span>
  								</div>
  							</div>
						</fieldset>
					</form>
					</div>
					<div class="modal-footer">
						<a class="btn btn-primary" href="#" onclick="add()">确认</a>
						<a data-dismiss="modal" class="btn" href="#">取消</a>
					</div>
				</div>
<script>
/*显示弹窗*/
function showAddAlert(e){
	$("#goods_name").html($(e).parents("tr.odd").find(".goods_name").html());
	$("#goods_price").val($(e).parents("tr.odd").find(".goods_price").html());
	$("#goods_costprice").html($(e).parents("tr.odd").find(".goods_costprice").html());
	$("#goods_storage").val($(e).parents("tr.odd").find(".goods_storage").html());
	$("#goods_id").val($(e).parents("tr.odd").find(".goods_id").val());
}
function add(){
	var goods_id =  parseInt($("#goods_id").val());
	var goods_num =  parseInt($("#goods_num").val());
	var goods_name = $("#goods_name").html();
	var goods_price = parseFloat($("#goods_price").val());
	var goods_costprice = parseFloat($("#goods_costprice").html());
	var goods_storage = parseInt($("#goods_storage").val());
	if(goods_num <= 0){
		alert("进货数量不能小于0");
		return false;
	}
	if(goods_num > goods_storage){
		alert("进货数量不能大于商品库存数量");
		return false;
	}
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('goods', 'createPurchaseOrders');?>',  
        async:false,  
        data:{'goods_id': goods_id, 'goods_num' : goods_num, 'goods_name' : goods_name, 'goods_price' : goods_price, 'goods_costprice' : goods_costprice},
        success: function(data){
	        if(data > 0){
				alert("进货申请已提交");
    		    location.reload();
		    }else{
			    alert("进货失败");
		    }
        }  
    });  
}
function check_goods_num(e){
	var goods_price = $("#goods_costprice").html();
	var goods_num = $(e).val();
	var all_price = goods_num * goods_price;
	$("#all_price").html(all_price);
}
$(function() {
    $("#search_btn").click(function(){
        var search_name = $("#search_name").val();
        if(search_name == ''){
            return false;
        }
        window.location.href = "index.php?act=goods&op=market&top_code=10200&menu_code=10230&search_name="+search_name;
//         goDataList();
    })
});
</script>