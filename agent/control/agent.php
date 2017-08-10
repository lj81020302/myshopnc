<?php
/**
 * 代理商控制器
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');

class agentControl extends BasicControl{
    public function __construct(){
        parent::__construct();
        Language::read('index');
        Tpl::setDir('agent');
    }
    /**
     * 订单管理
     */
    public function orderOp(){
        
        $model_order = Model('fx_orders');
        
        $condition_order['agent_id'] = $this->agent_info['agent_id'];
        if(isset($_GET['search_name'])){
            $condition_order['order_sn'] = $_GET['search_name'];
        }
        $condition_order['order'] = 'add_time desc';
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $condition_order['join_type'] = 'left join';
        $dataList = $model_order->getOrdersDetailList($condition_order, $page);
        $condition['agent_id'] = $this->agent_info['agent_id'];
        if(isset($_GET['search_name'])){
            $condition['order_sn'] = $_GET['search_name'];
        }
        $condition['join_type'] = 'inner join';
        $orderGoodsList = $model_order->getOrdersGoodsList($condition);
//         var_dump($orderGoodsList);
        if(is_array($dataList)){
            foreach ($dataList as $key=>$data){
                
                $dataList[$key]['order_goods_list'] = array();
                foreach ($orderGoodsList as $orderGoods){
                    if($data['order_id'] == $orderGoods['order_id']){
                        $dataList[$key]['order_goods_list'][] = $orderGoods;
                    }
                }
            }
        }
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        
        Tpl::showpage('order');
    }
    /**
     * 佣金列表
     */
    public function commissionOp(){

        $model_commission = Model('fx_commission');
        
        $condition['agent_id'] = $this->agent_info['agent_id'];
        if(isset($_GET['search_name'])){
            $condition['order_sn'] = $_GET['search_name'];
        }
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_commission->getCommissionList($condition, $page);
        
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        
        Tpl::showpage('commission');
    }
    /**
     * 团队管理
     */
    public function team_manageOp(){
        
        $model_agent = Model('fx_agent');
        
        $condition['parent_id'] = $this->agent_info['agent_id'];
        $condition['is_pass'] = 1;
        if(isset($_GET['search_name'])){
            $condition['agent_name'] = $_GET['search_name'];
        }
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_agent->getAgentTeam($condition,$page);
        
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        Tpl::showpage('team_manage');
    }
    /**
     * 获取团队代理商信息
     */
    public function getTeamAgentInfoOp(){
        
        $model_agent = Model('fx_agent');
        
        if(!empty($_POST['agent_id'])){
            $data = $model_agent->getOneAgent($_POST['agent_id']);
        }
        
        echo json_encode($data);
    }
    /**
     * 更新代理商信息
     */
    public function updateAgentInfoOp(){
        
        $model_agent = Model('fx_agent');
        
        $param = array('agent_id' => $this->agent_info['agent_id']);
        if(!empty($_POST['mobile'])){
            $param['mobile'] = $_POST['mobile'];
        }
        if(!empty($_POST['agent_password'])){
            $param['agent_password'] = md5(trim($_POST['agent_password']));
        }
        $ret = $model_agent->updateAgent($param);
        
        if($ret){
            showMessage('更新成功！');
        }else{
            showMessage('更新失败！');
        }
    }
    /**
     * 获取代理商信息
     */
    public function getAgentInfoOp(){
        
        $model_agent = Model('fx_agent');
        $agent = $model_agent->getOneAgent($this->agent_info['agent_id']);
        
        echo json_encode($agent);
    }

}
