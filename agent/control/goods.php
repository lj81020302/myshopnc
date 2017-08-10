<?php
/**
 * 商品控制器
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');

class goodsControl extends BasicControl{
    public function __construct(){
        parent::__construct();
        Language::read('index');
        Tpl::setDir('goods');
    }
    /**
     * 商品管理
     */
    public function indexOp(){
        
        
        $model_agent = Model('fx_goods');
        
        $condition['agent_id'] = $this->agent_info['agent_id'];
        $condition['goods_storage'] = 'not null';
        if(isset($_GET['search_name'])){
            $condition['goods_name'] = $_GET['search_name'];
        }
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_agent->getGoodsList($condition, $page);
        
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        
        Tpl::showpage('index');
    }
    /**
     * 进货市场
     */
    public function marketOp(){
        
        $model_goods = Model('goods');
        
        $condition['store_id'] = 1;
        if(isset($_GET['search_name'])){
            $condition['goods_name'] = $_GET['search_name'];
        }
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_goods->getGoodsMarketList($condition, $page);
        
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        
        Tpl::showpage('market');
    }
    /**
     * 商品统计
     */
    public function statisticsOp(){
        
        Tpl::setDir('goods');
        Tpl::setLayout('index_layout');
        Tpl::showpage('statistics');
    }
    
    /**
     * 进货单
     */
    public function purchase_orderOp(){
        $model_purchase_orders = Model('fx_purchase_orders');
        
        $condition['agent_id'] = $this->agent_info['agent_id'];
        if(isset($_GET['search_name'])){
            $condition['order_sn'] = $_GET['search_name'];
        }
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_purchase_orders->getPurchaseOrdersList($condition, $page);
        
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        
        Tpl::showpage('purchase_order');
    }
    /**
     * 创建订单
     */
    public function createOrderOp(){
        $model_fx_goods = Model('fx_goods');
        $model_goods = Model('goods');
        $fx_order = Model('fx_orders');
        $fx_order_goods = Model('fx_order_goods');
        
        $model_agent = Model('fx_agent');
        
        $goods_id = $_POST['goods_id'];     //这个商品id是商品分销id
        $goods_num = $_POST['goods_num'];
        //根据商品id查询商品详情
//         $goods_info = $model_fx_goods->getOneGoods($goods_id);
        $condition['fx_goods_id'] = $goods_id;
        $goods_info = $model_fx_goods->infoGoods($condition);
        
        //查询商品售价  与   商品成本价
        $goods_price = $goods_info['goods_price'];
//         $common_id = $goods_info['goods_commonid'];
//         $chenben = $model_goods->getGoodsCommonInfo(array('goods_commonid' => $common_id), $field = 'goods_costprice');
//         $goods_costprice = $chenben['goods_costprice'];
        $goods_costprice = $goods_info['goods_cost_price'];
        //添加订单
        $order['order_sn'] = $this->getOrderSnOp();
        $order['add_time'] = time();
        $order['finnshed_time'] = time();
        $order['goods_amount'] = $goods_price * $goods_num;
        $order['order_amount'] = $goods_price * $goods_num;
        $order['agent_id'] = $this->agent_info['agent_id'];
        $order['agent_name'] = $this->agent_info['agent_name'];
        $order['commission'] = ($goods_price - $goods_costprice)*$goods_num;
        $order['state'] = 10;
        
        $res = $fx_order->addOrders($order);
        
        if($res > 0){
            $order_info = $fx_order->getOneOrders($res);
            //添加订单项
            $order_goods['order_id'] = $order_info['order_id'];
            $order_goods['goods_id'] = $goods_id;
            $order_goods['goods_name'] = $goods_info['goods_name'];
            $order_goods['goods_price'] = $goods_price;
            $order_goods['goods_num'] = $goods_num;
            $order_goods['goods_image'] = $goods_info['goods_image'];
            $order_goods['goods_pay_price'] = $goods_price;
            $order_goods['gc_id'] = $goods_info['gc_id_3'];
            $order_goods['goods_spec'] = $goods_info['goods_spec'];
            $order_goods['solo_commission'] = ($goods_price - $goods_costprice);
            $order_goods['commission'] = ($goods_price - $goods_costprice)*$goods_num;
            $order_goods['goods_cost_price'] = $goods_costprice;
            $order_goods['add_time'] = time();
            $fx_order_goods->addOrderGoods($order_goods);
            //计算代理商个人销售总额
            $this->updateAgentSaleMoney($this->agent_info['agent_id'], $goods_price * $goods_num);
            //给当前 代理商添加佣金
            $this->addCommission($order_info['order_id'], $order_info['order_sn'], 1, '产品利润', ($goods_price - $goods_costprice)*$goods_num, $this->agent_info['agent_id'], $this->agent_info['agent_name']);
            //查找当前代理商是否有上级
            $up_agent = $model_agent->getOneAgent($this->agent_info['agent_id']);
            $up_agent_id = $up_agent['parent_id'];
            if($up_agent_id != 0){
                $up_agent_info = $model_agent->getOneAgent($up_agent_id);
                $money = $goods_price > 100 ? 10 : 5;
                $this->addCommission($order_info['order_id'], $order_info['order_sn'], 2, '单品提成', $money, $up_agent_id, $up_agent_info['agent_name']);
            }
            $this->jianAgentKucun($goods_id, $this->agent_info['agent_id'], $goods_num);
        }
        echo $res;
        exit();
    }
    //减少当前代理商的库存
    protected function jianAgentKucun($goods_id, $agent_id, $num){
        $model_fx_goods = Model('fx_goods');
        $where['goods_id'] = $goods_id;
        $where['agent_id'] = $agent_id;
        $fx_goods_info = $model_fx_goods->infoGoods($where, $field = 'fx_goods_id,goods_storage');
        
        $fxparam['fx_goods_id'] = $fx_goods_info['fx_goods_id'];
        $fxparam['goods_storage'] = $fx_goods_info['goods_storage'] - $num;
        $model_fx_goods->updateGoods($fxparam);
    }
    //添加佣金记录
    protected function addCommission($order_id, $order_sn, $type, $type_name, $money, $agent_id, $agent_name){
        $fx_commossion = Model('fx_commission');
        $commission['order_id'] = $order_id;
        $commission['order_sn'] = $order_sn;
        $commission['type'] = $type;
        $commission['type_name'] = $type_name;
        $commission['money'] = $money;
        $commission['create_time'] = time();
        $commission['agent_id'] = $agent_id;
        $commission['agent_name'] = $agent_name;
        $res = $fx_commossion->addCommission($commission);
        $this->updateAgentBalance($agent_id, $money);
        return $res;
    }
    //修改 代理商余额
    protected function updateAgentBalance($agent_id, $commission){
        $model_agent = Model('fx_agent');
        $info = $model_agent->getOneAgent($agent_id);
        $balance = $info['balance'];
        $array['agent_id'] = $agent_id;
        $array['balance'] = $balance + $commission;
        $res = $model_agent->updateAgent($array);
        return $res;
    }
    //修改代理商销售额
    protected function updateAgentSaleMoney($agent_id, $sale_money){
        $model_agent = Model('fx_agent');
        $info = $model_agent->getOneAgent($agent_id);
        $total_sale_money = $info['total_sale_money'];
        $array['agent_id'] = $agent_id;
        $array['total_sale_money'] = $total_sale_money + $sale_money;
        $res = $model_agent->updateAgent($array);
        return $res;
    }
    
    //创建进货单
    public function createPurchaseOrdersOp(){
        $model_purchase_orders = Model('fx_purchase_orders');
        $data = array(
            'goods_id' => $_POST['goods_id'],
            'goods_name' => $_POST['goods_name'],
            'goods_price' => $_POST['goods_price'],
            'goods_cost_price' => $_POST['goods_costprice'],
            'goods_num' => $_POST['goods_num'],
            'create_time' => time(),
            'deal_time' => time(),
            'status_id' => 0,
            'agent_id' => $this->agent_info['agent_id'],
            'agent_name' => $this->agent_info['agent_name'],
            'order_sn' => $this->getOrderSnOp(),
            'order_money' => $_POST['goods_price'] * $_POST['goods_num']
        );
        $res = $model_purchase_orders->addPurchaseOrders($data);
        echo $res;
        exit();
    }
    /**
     * 获取订单数量
     */
    public function getOrderCountOp(){
        //佣金统计
        $type_id = $_POST['type_id'];
        $type_name = '';
        switch ($type_id){
            case 1:
                $type_name = 'today';
                break;
            case 2:
                $type_name = 'yesterday';
                break;
            case 3:
                $type_name = 'week';
                break;
            case 4:
                $type_name = 'month';
                break;
        }
        $model_orders = Model('fx_orders');
        $condition['type_str'] = $type_name;
        $orderCount = $model_orders->getOrderCount($condition, "COUNT(order_id) as order_num");
        echo json_encode($orderCount);
    }
    /**
     * 获取商品销量
     */
    public function getOrderGoodsCountOp(){
        //佣金统计
        $type_id = $_POST['type_id'];
        $type_name = '';
        switch ($type_id){
            case 1:
                $type_name = 'today';
                break;
            case 2:
                $type_name = 'yesterday';
                break;
            case 3:
                $type_name = 'week';
                break;
            case 4:
                $type_name = 'month';
                break;
        }
        $model_order_goods = Model('fx_order_goods');
        $condition['type_str'] = $type_name;
        $orderCount = $model_order_goods->getOrderGoodsCount($condition, "COUNT(rec_id) as goods_num");
        echo json_encode($orderCount);
    }

    public function getOrderSnOp(){
        $str = date("Ymd", time());
        $str .= 1000+$this->agent_info['agent_id'];
        $str .= mt_rand(1000, 9999);
        return $str;
    }
    
    public function upd_purchase_orderOp(){
        $model_commission = Model('fx_purchase_orders');
        $data['id'] = $_POST['id'];
        $data['status_id'] = -1;
        $data['deal_time'] = time();
        $res = $model_commission->updatePurchaseOrders($data);
        echo $res;
        exit();
    }
}
