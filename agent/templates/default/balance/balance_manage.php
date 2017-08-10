<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">结算列表</div>
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
							<th colspan="4">结算信息</th>
<!-- 							<th>产品利润</th> -->
<!-- 							<th>单品佣金</th> -->
<!-- 							<th>产生时间</th> -->
						</tr>
					</thead>
					<tbody>
					<?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td colspan="4">订单号：<?php echo $data['order_sn'];?>
							<span style="float: right;">
							<?php echo date('Y-m-d', $data['add_time']);?>
							</span>
							</td>
						</tr>
						<?php foreach ($data['order_goods_list'] as $order_goods){?>
						<tr class="odd gradeX">
							<td colspan="2">
							<a href="<?php echo SHOP_SITE_URL?>/index.php?act=goods&op=index&goods_id=<?php echo $data['goods_id']?>" target="_blank">
							<img src="<?php echo thumb($order_goods, '60');?>">
							<?php echo $order_goods['goods_name'];?>
							</a>
							</td>
							<td>产品利润：￥<?php echo $order_goods['goods_price']-$order_goods['goods_cost_price']?></td>
							<td class="center">单品佣金：￥<?php echo $order_goods['solo_commission']?></td>
							
						</tr>
						<?php }?>
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
        window.location.href = "index.php?act=agent&op=commission&top_code=10300&menu_code=10310&search_name="+search_name;
//         goDataList();
    })
});
</script>