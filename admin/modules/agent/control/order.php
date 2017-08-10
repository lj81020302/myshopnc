<?php
/**
 * 代理商等级管理
 *
 *
 *
 *
 * @运维舫提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopnc.club
 * @link       唯一论坛：www.shopnc.club
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class orderControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->orderOp();
    }

    /**
     * 结算列表
     */
    public function orderOp(){
        
        Tpl::setDirquna('agent');
        Tpl::showpage('order.index');   
    }

    
    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_commission = Model('fx_order');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        
        $page = $_POST['rp'];

        $commission_list = $model_commission->getOrdersList($condition, $page);
        
        $data = array();
        $data['now_page'] = $model_commission->shownowpage();
        $data['total_num'] = $model_commission->gettotalnum();
        foreach ($commission_list as $value) {
            $param = array();
            $param['id'] = $value['id'];
            $param['order_sn'] = $value['order_sn'];
            $param['pay_sn'] = $value['pay_sn'];
            $param['goods_amount'] = $value['goods_amount'];
            $param['order_amount'] = $value['order_amount'];
            $param['refund_amount'] = $value['refund_amount'];
            $param['trade_no'] = $value['trade_no'];
            $param['agent_name'] = $value['agent_name'];
            $param['commission'] = $value['commission'];
            $param['add_time'] = time('y-m-d H:i:s', $value['add_time']);
            $param['finished_time'] = time('y-m-d H:i:s', $value['finished_time']);
            $data['list'][$value['id']] = $param;
        }
        
        echo Tpl::flexigridXML($data);exit();
    }

}
