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

class jiesuanControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->jiesuanOp();
    }

    /**
     * 结算列表
     */
    public function jiesuanOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('jiesuan.index');   
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_commission = Model('fx_commission');
        $model_order_goods = Model('fx_order_goods');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $condition['type'] = 1;
        $page = $_POST['rp'];
    
        $commission_list = $model_commission->getCommissionList($condition, $page);
        
        $data = array();
        $data['now_page'] = $model_commission->shownowpage();
        $data['total_num'] = $model_commission->gettotalnum();
        if($commission_list == null){
            $commission_list = array();
            $data['now_page'] = 0;
            $data['total_num'] = 0;
        }
        foreach ($commission_list as $value) {
            $order_goods_list = $model_order_goods->getOrderGoodsList(array( 'order_id' => $value['order_id']));
            $str = '';
            foreach ($order_goods_list as $k => $v){
                $str .= $v['goods_name'] . ' -- ￥' . $v['solo_commission'] . ' ; ';
            }
            $param = array();
            $param['id'] = $value['id'];
            $param['order_sn'] = $value['order_sn'];
            $param['goods'] = $str;
            $param['money'] = $value['money'];
            $param['agent_name'] = $value['agent_name'];
            $param['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
}
