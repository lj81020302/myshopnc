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

class withdrawControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->withdrawOp();
    }

    /**
     * 结算列表
     */
    public function withdrawOp(){
//         $fx_withdraw = Model('fx_withdraw');
//         $condition['status_id'] = 0;
//         $withdraw_list = $fx_withdraw->getWithdrawList($condition);
//         var_dump($withdraw_list);
        Tpl::setDirquna('agent');
        Tpl::showpage('withdraw.index');   
    }

    public function withdraw_applyOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('withdraw.apply');
    }
    
    public function withdraw_zhOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('withdraw.zh');
    }
    
    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $fx_withdraw = Model('fx_withdraw');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $page = $_POST['rp'];

        // 品牌列表
        if ($_GET['type'] == 0) {
            $condition['status_id'] = 0;//待审核的
        } else {
            $condition['status_id'] = 1;//除了待审核的
        }
        $withdraw_list = $fx_withdraw->getWithdrawList($condition, $page);

        $data = array();
        $data['now_page'] = $fx_withdraw->shownowpage();
        $data['total_num'] = $fx_withdraw->gettotalnum();
        if($withdraw_list == null){
            $withdraw_list = array();
            $data['now_page'] = 0;
            $data['total_num'] = 0;
        }
        foreach ($withdraw_list as $value) {
            $param = array();
            if($value['status_id'] == 0){
                $operation = '';
                $operation .= "<a class='btn orange' href='javascript:void(0);' onclick=\"tongguo(" . $value['id'] . ")\">通过</a>";
                $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"butongguo(" . $value['id'] . ")\">拒绝</a>";
                $param['operation'] = $operation;
            }else if($value['status_id'] == -1){
                $operation = "<a class='btn red' href='javascript:if(confirm(\"确定要删除该提现记录吗？\"))window.location = \"index.php?act=withdraw&op=withdraw_del&id=" . $value['id'] . "\";'><i class='fa fa-trash-o'></i>删除</a>";
                $param['operation'] = $operation;
            }
            $param['agent_name'] = $value['agent_name'];
            $param['money'] = $value['money'];
            $param['status_name'] = $value['status_name'];
            $param['account_num'] = $value['account_num'];
            $param['bank_name'] = $value['bank_name'];
            $param['realname'] = $value['realname'];
            $param['mobile'] = $value['mobile'];
            $param['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
            $param['deal_timein'] = date('Y-m-d H:i:s', $value['deal_timein']);
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
    public function tongguoOp(){
        $fx_withdraw = Model('fx_withdraw');
        $param['id'] = $_POST['id'];
        $param['status_id'] = 1;
        $param['status_name'] = '已通过';
        $res = $fx_withdraw->updateWithdraw($param);
        echo $res;
    }
    
    public function butongguoOp(){
        $fx_withdraw = Model('fx_withdraw');
        $param['id'] = $_POST['id'];
        $param['status_id'] = -1;
        $param['status_name'] = '已拒绝';
        $res = $fx_withdraw->updateWithdraw($param);
        //拒绝提现申请之后将金额退回用户余额
        $info = $fx_withdraw->getOneWithdraw($_POST['id']);
        $money = $info['money'];
        $fx_agent = Model('fx_agent');
        $agent_info = $fx_agent->getOneAgent($info['agent_id']);
        $arr['balance'] = $agent_info['balance'] + $money;
        $arr['agent_id'] = $info['agent_id'];
        $fx_agent->updateAgent($arr);
        echo $res;
    }
    
    public function get_zhxmlOp(){
        $fx_withdraw = Model('fx_bankaccount');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $page = $_POST['rp'];
        
        $withdraw_list = $fx_withdraw->getBankaccountList($condition, $page);
        
        $data = array();
        $data['now_page'] = $fx_withdraw->shownowpage();
        $data['total_num'] = $fx_withdraw->gettotalnum();
        if($withdraw_list == null){
            $withdraw_list = array();
            $data['now_page'] = 0;
            $data['total_num'] = 0;
        }
        foreach ($withdraw_list as $value) {
            $param = array();
            $param['agent_name'] = $value['agent_name'];
            $param['bank_name'] = $value['bank_name'];
            $param['bank_child'] = $value['bank_child'];
            $param['account_num'] = $value['account_num'];
            $param['mobile'] = $value['mobile'];
            $param['realname'] = $value['realname'];
            $param['is_default'] = $value['is_default'] == 1 ? '是' : '否';
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
    public function withdraw_delOp(){
        $lang   = Language::getLangContent();
        $model_level = Model('fx_withdraw');
        if (!$_GET['id']){
            showMessage("id不存在",'index.php?act=withdraw&op=withdraw_apply');
        }
    
        $result = $model_level->delWithdraw($_GET['id']);
    
        if ($result){
            showMessage('操作成功','index.php?act=withdraw&op=withdraw_apply');
        }else {
            showMessage($lang['nc_common_save_fail']);
        }
    }
}
