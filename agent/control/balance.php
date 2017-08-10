<?php
/**
 * 结算控制器
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');

class balanceControl extends BasicControl{
    public function __construct(){
        parent::__construct();
        Language::read('index');
        Tpl::setDir('balance');
    }
    /**
     * 结算管理
     */
    public function balance_manageOp(){
        
        
        $model_order = Model('fx_orders');
        
        $condition['agent_id'] = $this->agent_info['agent_id'];
        if(isset($_GET['search_name'])){
            $condition['order_sn'] = $_GET['search_name'];
        }
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_order->getOrdersList($condition, $page);
        $condition['join_type'] = 'right join';
        $orderGoodsList = $model_order->getOrdersGoodsList($condition, $page);
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
        
        Tpl::showpage('balance_manage');
    }
    /**
     * 账号余额
     */
    public function balanceOp(){

        //商品余额
        $model_agent = Model('fx_agent');
        
        $agent_account = $model_agent->getOneAgent($this->agent_info['agent_id']);
        Tpl::output('agent_account', $agent_account);
        //可提现余额
        
        //佣金总额
        $model_commission = Model('fx_commission');
        $condition['type_str'] = 'total';
        $condition['agent_id'] = $this->agent_info['agent_id'];
        $commission_total = $model_commission->getCommissionCount($condition, "IFNULL(SUM(money),0) as commission");
        Tpl::output('commission_total', $commission_total);
        
        //提现账号
        $model_bankaccount = Model('fx_bankaccount');
        $condition_bankaccount['agent_id']=$this->agent_info['agent_id'];
        $bankaccountList = $model_bankaccount->getBankaccountList($condition_bankaccount);
        Tpl::output('bankaccountList', $bankaccountList);
        
        
        Tpl::showpage('balance');
    }
    /**
     * 获取佣金统计数据
     */
    public function getCommissionCountOp(){

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
        $model_commission = Model('fx_commission');
        $condition['type_str'] = $type_name;
        $condition['agent_id'] = $this->agent_info['agent_id'];
        $commissionCount = $model_commission->getCommissionCount($condition, "IFNULL(SUM(money),0) as commission");
        
        echo json_encode($commissionCount);
    }
    /**
     * 提现管理
     */
    public function indexOp(){

        $model_withdraw = Model('fx_withdraw');
        
        $condition['agent_id'] = $this->agent_info['agent_id'];
        if(isset($_GET['search_name'])){
            $condition['order_sn'] = $_GET['search_name'];
        }
        $condition['order'] = ' create_time desc ';
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_withdraw->getWithdrawList($condition, $page);
        
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        
        Tpl::showpage('index');
    }
    /**
     * 提现账号管理
     */
    public function bankaccountOp(){

        $model_bankaccount = Model('fx_bankaccount');
        
        $condition['agent_id'] = $this->agent_info['agent_id'];
        if(isset($_GET['search_name'])){
            $condition['mobile'] = $_GET['search_name'];
        }
        $page = new Page();
        $page->setNowPage(empty($_GET['curpage'])?1:$_GET['curpage']);
        $page->setEachNum(10);
        $page->setStyle(6);
        $dataList = $model_bankaccount->getBankAccountList($condition, $page);
        
        Tpl::output('search_name', empty($_GET['search_name'])?'':$_GET['search_name']);
        Tpl::output('page', $page);
        Tpl::output('dataList', $dataList);
        
        Tpl::showpage('bankaccount');
    }
    /**
     * 添加修改提现账号
     */
    public function addUpdateBankaccountOp(){
        $model_bankaccount = Model('fx_bankaccount');
        
        $data = array(
            'bank_name' => $_POST['bankname'],
            'bank_child' => $_POST['bankchild'],
            'account_num' => $_POST['accountnumber'],
            'mobile' => $_POST['mobile'],
            'realname' => $_POST['realname'],
            'agent_id' => $this->agent_info['agent_id'],
            'agent_name' => $this->agent_info['agent_id']
        );
        if(empty($_POST['account_id'])){
            $res = $model_bankaccount->addBankaccount($data);
        }else {
            $data['id'] = $_POST['account_id'];
            $res = $model_bankaccount->updateBankaccount($data);
        }
        
        echo $res;
    }
    /**
     * 删除提现账号
     */
    public function delBankaccountOp(){
        $model_bankaccount = Model('fx_bankaccount');
        
        $res = false;
        if(!empty($_POST['account_id'])){
            $res = $model_bankaccount->delBankaccount($_POST['account_id']);
        }
        
        echo $res;
    }
    /**
     * 设置默认 
     */
    public function defaultBankaccountOp(){
        $model_bankaccount = Model('fx_bankaccount');
    
        $data = array(
            'is_default' => false
        );
        $model_bankaccount->updateBankaccount($data);
        $data = array(
            'is_default' => true,
            'id' => $_POST['account_id']
        );
        $res = $model_bankaccount->updateBankaccount($data);
    
        echo $res;
    }
    /**
     * 获取银行信息
     */
    public function getBankaccountOp(){
        $model_bankaccount = Model('fx_bankaccount');
    
        $res = false;
        
        if(!empty($_POST['account_id'])){
            $res = $model_bankaccount->getOneBankaccount($_POST['account_id']);
        }
    
        echo json_encode($res);
    }
    /**
     * 获取银行信息
     */
    public function applyWithdrawOp(){
        
        //获取银行信息
        $model_bankaccount = Model('fx_bankaccount');
        $bankaccount = $model_bankaccount->getOneBankaccount($_POST['bankaccount_id']);
        
        //提现记录新增
        $model_withdraw = Model('fx_withdraw');
        $withdraw['money'] = $_POST['money'];
        $withdraw['status_id'] = 0;
        $withdraw['status_name'] = '未审核';
        $withdraw['create_time'] = time();
        $withdraw['agent_id'] = $this->agent_info['agent_id'];
        $withdraw['agent_name'] = $this->agent_info['agent_name'];
        $withdraw['bank_name'] = $bankaccount['bank_name'];
        $withdraw['bank_child'] = $bankaccount['bank_child'];
        $withdraw['account_num'] = $bankaccount['account_num'];
        $withdraw['mobile'] = $bankaccount['mobile'];
        $withdraw['realname'] = $bankaccount['realname'];
        $new_id = $model_withdraw->addWithdraw($withdraw);
        $res = false;
        if($new_id){
            //个人帐户减去
            $model_agent = Model('fx_agent');
            $agent = $model_agent->getOneAgent($this->agent_info['agent_id']);
            $param['balance']=$agent['balance']-$_POST['money'];
            $param['agent_id']=$this->agent_info['agent_id'];
            $res = $model_agent->updateAgent($param);
        }
    
        echo json_encode($res);
    }
}
