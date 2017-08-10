<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<style>
.QRcode{
	background: #F5F5F5;
    display: none;
    width: 160px;
    padding: 5px;
    border: solid 1px #CCC;
    position: absolute;
    z-index: 99;
    box-shadow: 0 0 5px rgba(0,0,0,0.15);
}
</style>                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">商品列表</div>
        </div>
        <div class="block-content collapse in" style="min-height: 300px;">
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
							<th>二维码</th>
							<th>统一售价</th>
							<th>进价（元）</th>
							<th>剩余库存</th>
							<th>购买数量</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td>
							<a href="<?php echo SHOP_SITE_URL?>/index.php?act=goods&op=index&goods_id=<?php echo $data['goods_id']?>" target="_blank">
							<img src="<?php echo thumb($data, '60');?>">
							<?php echo $data['goods_name'];?>
							</a>
							</td>
							<td>
							<?php //echo $data['spec_name']?>
							<?php if(goodsFxQRCode(array('fx_goods_id' => $data['fx_goods_id'], 'agent_id' => $output['agent_info']['agent_id']))!=''){?>
							<div onmouseover="javascript: $(this).find('div').show();" onmouseout="javascript: $(this).find('div').hide();">
							<img src="<?php echo goodsFxQRCode(array('fx_goods_id' => $data['fx_goods_id'], 'agent_id' => $output['agent_info']['agent_id']));?>" width="20px"/>
    							<div class="QRcode" onmouseover="javascript: $(this).show();" onmouseout="javascript: $(this).hide();">
                                	<a target="_blank" href="<?php echo goodsFxQRCode(array('fx_goods_id' => $data['fx_goods_id'], 'agent_id' => $output['agent_info']['agent_id']));?>">下载标签</a>
                                  <p><img src="<?php echo goodsFxQRCode(array('fx_goods_id' => $data['fx_goods_id'], 'agent_id' => $output['agent_info']['agent_id']));?>"/></p>
                                </div>
							</div>
							<?php }?>
							</td>
							<td class="center"><?php echo $data['goods_price']?></td>
							<td><?php echo $data['goods_cost_price']?></td>
							<td class="center goods_storage"><?php echo $data['goods_storage']?></td>
							<td class="center">
								<input type="number" name="goods_num" style="width: 40px;" value="0" min="0">
								<button class="btn btn-primary" style="margin-bottom: 10px;" onclick="buy(<?php echo $data['fx_goods_id']?>, this)">购买</button>
								<!-- 
								<button class="btn" style="margin-bottom: 10px;padding:4px 8px;"><i class="icon-minus"></i></button>
								<button class="btn" style="margin-bottom: 10px;padding:4px 8px;"><i class="icon-plus"></i></button>
								 -->
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
function buy(goods_id, e){
	var goods_num =  parseInt($(e).prev().val());
	var goods_storage = parseInt($(e).parents("tr.odd").find(".goods_storage").html());
	if(goods_num <= 0){
		alert("购买数量不能小于0");
		return false;
	}
	if(goods_num > goods_storage){
		alert("购买数量不能大于商品库存数量");
		return false;
	}
	$.ajax({
        type:"POST",
        url:'<?php echo urlAgent('goods', 'createOrder');?>',  
        async:false,  
        data:{'goods_id': goods_id, 'goods_num' : goods_num},
        success: function(data){
	        if(data > 0){
				alert("已购买");
				location.reload();
		    }else{
			    alert("购买失败");
		    }
			
        }  
    });  
}
$(function() {
    $("#search_btn").click(function(){
        var search_name = $("#search_name").val();
        if(search_name == ''){
            return false;
        }
        window.location.href = "index.php?act=goods&op=index&top_code=10200&menu_code=10210&search_name="+search_name;
//         goDataList();
    })
});
</script>