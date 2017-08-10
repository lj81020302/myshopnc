<?php
/**
 * 购买
 *
 * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class fx_member_buyControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 购物车、直接购买第一步:选择收获地址和配置方式
     */
    public function buy_step1Op() {
        
        $model_cart = Model('fx_cart');
        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list  = $model_cart->listCart('db', $condition);
        
        $model_goods = Model('fx_goods');
        foreach ($cart_list as $k=>$goods){
            $data['totals'] += $goods['goods_price']*$goods['goods_num'];
            
            $condition['fx_goods_id'] = $goods['fx_goods_id'];
            $goods_info = $model_goods->infoGoods($condition);
            
            $cart_list[$k]['goods_image_url'] = cthumb($goods_info['goods_image']);
        }
        
        $data['goods_list'] = $cart_list;
        output_data($data);
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function buy_step2Op() {
        $model_cart = Model('fx_cart');
        $condition_cart = array('buyer_id' => $this->member_info['member_id']);
        
        $cart_list  = $model_cart->listCart('db', $condition_cart);
        if(empty($cart_list)){
            output_error('您的订单已生成，请到个人订单中支付');
        }
        //添加订单
        $order['order_sn'] = $this->getOrderSnOp();
        $order['add_time'] = time();
        $order['finnshed_time'] = time();
        $order['buyer_id'] = $this->member_info['member_id'];
        
        //循环商品
        
        $fx_order = Model('fx_orders');
        $res = $fx_order->addOrders($order);
        
        $goods_amount = 0;
        $order_amount = 0;
        $commission = 0;
        $money = 0;
        if($res > 0){
            
            $model_goods = Model('goods');
            $model_fx_goods = Model('fx_goods');
            $model_agent = Model('fx_agent');
            $agent_info = $model_agent->getOneAgent($cart_list[0]['agent_id']);
            $fx_order_goods = Model('fx_order_goods');
            
            foreach ($cart_list as $k=>$goods){
                $data['totals'] += $goods['goods_price'];
            
                $condition['fx_goods_id'] = $goods['fx_goods_id'];
            
                //查询商品
                $fx_goods_id = $goods['fx_goods_id'];     //这个商品id是商品分销id
                $goods_num = $goods['goods_num'];
            
                //根据商品id查询商品详情
                //         $goods_info = $model_fx_goods->getOneGoods($goods_id);
                $condition['fx_goods_id'] = $fx_goods_id;
                $goods_info = $model_fx_goods->infoGoods($condition);
                //查询商品售价  与   商品成本价
                $goods_price = $goods_info['goods_price'];
                $goods_costprice = $goods_info['goods_cost_price'];
            
                $goods_amount += $goods_price * $goods_num;
                $order_amount += $goods_price * $goods_num;
                $commission += ($goods_price - $goods_costprice)*$goods_num;
                $money += $goods_price > 100 ? 10 : 5;
                
                //添加订单项
                $order_goods['order_id'] = $res;
                $order_goods['goods_id'] = $fx_goods_id;
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
                
                //代理商减库存
                $this->jianAgentKucun($fx_goods_id, $goods['agent_id'], $goods_num);
                
            }
            $param['goods_amount'] = $goods_amount;
            $param['order_amount'] = $order_amount;
            $param['commission'] = $commission;
            $param['agent_id'] = $agent_info['agent_id'];
            $param['agent_name'] = $agent_info['agent_name'];
            $param['order_id'] = $res;
            $fx_order->updateOrders($param);
            
//             //产品利润
//             $this->addCommission($res, $order['order_sn'], 1, '产品利润', $commission, $agent_info['agent_id'], $agent_info['agent_name']);
//             //团队提成
//             $up_agent_id = $agent_info['parent_id'];
//             if($up_agent_id != 0){
//                 $up_agent_info = $model_agent->getOneAgent($up_agent_id);
//                 $this->addCommission($res, $order['order_sn'], 2, '单品提成', $money, $up_agent_id, $up_agent_info['agent_name']);
//             }
//             /*计算佣金*/
//             //计算代理商个人销售总额
//             $this->updateAgentSaleMoney($agent_info['agent_id'], $order_amount);
            $model_cart->delCart('db', $condition_cart);
        }
        
        
        
        output_data(array('pay_sn' => $order['order_sn'],'payment_code'=>'online'));
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
    /**
     * 验证密码
     */
    public function check_passwordOp() {
        if(empty($_POST['password'])) {
            output_error('参数错误');
        }

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if($member_info['member_paypwd'] == md5($_POST['password'])) {
            output_data('1');
        } else {
            output_error('密码错误');
        }
    }

    /**
     * 更换收货地址
     */
    public function change_addressOp() {
        $logic_buy = Logic('buy');
        if (empty($_POST['city_id'])) {
            $_POST['city_id'] = $_POST['area_id'];
        }
        
        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);
        if(!empty($data) && $data['state'] == 'success' ) {
            output_data($data);
        } else {
            output_error('地址修改失败');
        }
    }

    /**
     * 实物订单支付(新接口)
     */
    public function payOp() {
        $pay_sn = $_POST['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            output_error('该订单不存在');
        }

        //查询支付单信息
        $model_order= Model('fx_orders');
        $pay_info = $model_order->infoOrders(array('order_sn'=>$pay_sn,'buyer_id'=>$this->member_info['member_id']));
        if(empty($pay_info)){
            output_error('该订单不存在');
        }

        //定义输出数组
        $pay = array();
        //支付提示主信息
        //订单总支付金额(不包含货到付款)
        $pay['pay_amount'] = 0;
        //充值卡支付金额(之前支付中止，余额被锁定)
        $pay['payed_rcb_amount'] = 0;
        //预存款支付金额(之前支付中止，余额被锁定)
        $pay['payed_pd_amount'] = 0;
        //还需在线支付金额(之前支付中止，余额被锁定)
        $pay['pay_diff_amount'] = 0;
        //账户可用金额
        $pay['member_available_pd'] = 0;
        $pay['member_available_rcb'] = 0;

        
        $pay['pay_amount'] = $pay_info['order_amount'];
        
        $payment_list = Model('mb_payment')->getMbPaymentOpenList();
        
        if(!empty($payment_list)) {
            foreach ($payment_list as $k => $value) {
                if ($value['payment_code'] == 'wxpay') {
                    unset($payment_list[$k]);
                    continue;
                }
                unset($payment_list[$k]['payment_id']);
                unset($payment_list[$k]['payment_config']);
                unset($payment_list[$k]['payment_state']);
                unset($payment_list[$k]['payment_state_text']);
            }
        }
        
        $pay['payment_list'] = $payment_list ? array_values($payment_list) : array();
        
        output_data(array('pay_info'=>$pay));
    }

    /**
     * AJAX验证支付密码
     */
    public function check_pd_pwdOp(){
        if (empty($_POST['password'])) {
            output_error('支付密码格式不正确');
        }
        $buyer_info = Model('member')->getMemberInfoByID($this->member_info['member_id'],'member_paypwd');
        if ($buyer_info['member_paypwd'] != '') {
            if ($buyer_info['member_paypwd'] === md5($_POST['password'])) {
                output_data('1');
            }
        }
        output_error('支付密码验证失败');
    }

    /**
     * F码验证
     */
    public function check_fcodeOp() {
        $goods_id = intval($_POST['goods_id']);
        if ($goods_id <= 0) {
            output_error('商品ID格式不正确');
        }
        if ($_POST['fcode'] == '') {
            output_error('F码格式不正确');
        }
        $result = logic('buy')->checkFcode($goods_id, $_POST['fcode']);
        if ($result['state']) {
            output_data('1');
        } else {
            output_error('F码验证抢购');
        }
    }
    public function getOrderSnOp(){
        $str = date("Ymd", time());
        $str .= 1000+$this->agent_info['agent_id'];
        $str .= mt_rand(100000, 999999);
        return $str;
    }
}

