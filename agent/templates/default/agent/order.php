<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
                    	
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">订单列表</div>
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
							<th colspan="2">商品信息</th>
<!-- 							<th>规格</th> -->
							<th>市场价（元）</th>
							<th>进价（元）</th>
							<th>数量</th>
							<th>会员</th>
							<th>订单总额</th>
						</tr>
					</thead>
					<tbody>
				   <?php if(count($output['dataList']) > 0){
					    foreach ($output['dataList'] as $k=>$data){?>
						<tr class="odd gradeX">
							<td colspan="6">订单号：<?php echo $data['order_sn'];?>
							<span style="float: right;color:gray;">
							产生时间：<?php echo date('Y-m-d H:i:s', $data['add_time']);?>
							</span>
							</td>
							<td>
							<?php if($data['state'] == 0){?>
							<span style="color: red;">待支付</span>
							<?php }elseif($data['state'] == 10){?>
							<span style="color: green;">已完成</span>
							<?php }?>
							</td>
						</tr>
						<?php foreach ($data['order_goods_list'] as $key=>$order_goods){?>
						<tr class="odd gradeX">
							<td colspan="2">
							<a href="<?php echo SHOP_SITE_URL?>/index.php?act=goods&op=index&goods_id=<?php echo $data['goods_id']?>" target="_blank">
							<img src="<?php echo thumb($order_goods, '60');?>">
							<?php echo $order_goods['goods_name'];?>
							</a>
							</td>
							<!-- 
							<td><?php //echo $order_goods['goods_spec']?></td>
							 -->
							<td>￥<?php echo $order_goods['goods_price']?></td>
							<td class="center">￥<?php echo $order_goods['goods_cost_price']?></td>
							<td><?php echo $order_goods['goods_num']?></td>
							
							<?php if($key == 0){?>
							<td rowspan="<?php echo count($data['order_goods_list']);?>">
							<span>
							<?php echo $data['member_name']?>
							</span>
							</td>
							<td rowspan="<?php echo count($data['order_goods_list']);?>">
							<span style="color: red;">
							￥<?php echo $data['order_amount']?>
							</span>
							</td>
							<?php }?>
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
        window.location.href = "index.php?act=agent&op=order&top_code=10200&menu_code=10220&search_name="+search_name;
//         goDataList();
    })
});
</script>