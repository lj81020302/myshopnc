<?php
/**
 * 支付回调
 *
 * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class fx_paymentControl extends mobileHomeControl{

    private $payment_code;

    public function __construct() {
        parent::__construct();

        $this->payment_code = $_GET['payment_code'];
        
    }

    /**
     * 支付回调
     */
    public function returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);
        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getReturnInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                Tpl::output('result', 'success');
                Tpl::output('message', '支付成功');
            } else {
                Tpl::output('result', 'fail');
                Tpl::output('message', '支付失败');
            }
        } else {
            //验证失败
            Tpl::output('result', 'fail');
            Tpl::output('message', '支付失败');
        }

        Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {

        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $api = $this->_get_payment_api();
            $params = $this->_get_payment_config();
            $api->setConfigs($params);

            list($result, $output) = $api->notify();
            if ($result) {
//                 file_put_contents(time().'ssdfs.txt', $result['out_trade_no']);
                $internalSn = $result['out_trade_no'];
                $externalSn = $result['transaction_id'];
                $updateSuccess = $this->_update_order($internalSn, $externalSn);

                if (!$updateSuccess) {
                    // @todo
                    // 直接退出 等待下次通知
                    exit;
                }
            }

            echo $output;
            exit;
        }

        // 恢复框架编码的post值
        $_POST['notify_data'] = html_entity_decode($_POST['notify_data']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getNotifyInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                echo 'success';die;
            }
        }

        //验证失败
        echo "fail";die;
    }

    /**
     * 支付宝移动支付
     */
    public function notify_alipay_nativeOp() {
        $this->payment_code = 'alipay_native';
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }
        $payment_config = $this->_get_payment_config();
        $payment_api = new $this->payment_code();
        $payment_api->payment_config = $payment_config;
        $payment_api->alipay_config['partner'] = $payment_config['alipay_partner'];
        
        if ($payment_api->verify_notify()) {
            
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];

            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                $result = $this->_update_order($out_trade_no, $trade_no);
                if(!$result['state']) {
                    logResult("订单状态更新失败".$out_trade_no);
                }
            }
            exit("success");
        } else {
            logResult("verifyNotify验证失败".$out_trade_no);
            exit("fail");
        }
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.'fx_'.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }
        if($this->payment_code == 'wxpay_jsapi'){
            $payment_api = new fx_wxpay_jsapi();
        }else {
            $payment_api = new $this->payment_code();
        }

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_mb_payment = Model('mb_payment');

        //读取接口配置信息
        $condition = array();
        if($this->payment_code == 'wxpay3') {
            $condition['payment_code'] = 'wxpay';
        } else {
            $condition['payment_code'] = $this->payment_code;
        }
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);

        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no) {
        $fx_orders = Model('fx_orders');
        $order_pay_info = $fx_orders->infoOrders(array('order_sn'=> $out_trade_no));

        $result = $fx_orders->updateOrders(array('order_id'=>$order_pay_info['order_id'],'state'=>10));
        
        if($result){
            
            $model_fx_goods = Model('fx_order_goods');
            $model_agent = Model('fx_agent');
            //查询订单
            $order = $fx_orders->getOneOrders($order_pay_info['order_id']);
            
            if($order){
                $agent_info = $model_agent->getOneAgent($order['agent_id']);
                
                $condition['agent_id'] = $agent_info['agent_id'];
                $condition['order_id'] = $order['order_id'];
                $fx_goods_list = $model_fx_goods->getOrderGoodsList($condition);
                foreach ($fx_goods_list as $k=>$goods){
                    $goods_price = $goods['goods_price'];
                    $goods_costprice = $goods['goods_cost_price'];
                    $goods_num = $goods['goods_num'];
                    $commission += ($goods_price - $goods_costprice)*$goods_num;
                    $money += $goods_price > 100 ? 10 : 5;
                }
                //产品利润
                $this->addCommission($res, $order['order_sn'], 1, '产品利润', $commission, $agent_info['agent_id'], $agent_info['agent_name']);
                //团队提成
                $up_agent_id = $agent_info['parent_id'];
                if($up_agent_id != 0){
                    $up_agent_info = $model_agent->getOneAgent($up_agent_id);
                    $this->addCommission($res, $order['order_sn'], 2, '单品提成', $money, $up_agent_id, $up_agent_info['agent_name']);
                }
                /*计算佣金*/
                //计算代理商个人销售总额
                $this->updateAgentSaleMoney($agent_info['agent_id'], $order['order_amount']);
            }
        }


        return $result;
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
}
