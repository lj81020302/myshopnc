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

class commissionControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->commissionOp();
    }

    /**
     * 结算列表
     */
    public function commissionOp(){
        
        Tpl::setDirquna('agent');
        Tpl::showpage('yongjin.index');   
    }

    
    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_commission = Model('fx_commission');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] =  $_POST['query'];
        }
        
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
            $param = array();
            if($value['type'] == 1){
                $type_name = '产品利润';
            }else if($value['type'] == 2){
                $type_name = '单品提成';
            }else if($value['type'] == 3){
                $type_name = '销售总额提成';
            }
            $param['id'] = $value['id'];
            $param['agent_name'] = $value['agent_name'];
            $param['money'] = $value['money'];
            $param['type_name'] = $type_name;
            $param['create_time'] = date('Y-m-d h:i:s', $value['create_time']);
            $data['list'][$value['id']] = $param;
        }
        
        echo Tpl::flexigridXML($data);exit();
    }

}
