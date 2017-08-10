<?php
/**
 * 代理商管理
 *
 *
 *
 *
 * @运维舫提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopnc.club
 * @link       唯一论坛：www.shopnc.club
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class agentControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->agentOp();
    }

    /**
     * 结算列表
     */
    public function agentOp(){
//         $model_commission = Model('fx_agent');
//         $condition['is_pass'] = 0;
//         $commission_list = $model_commission->getAgentList($condition);
//         var_dump($commission_list);
        Tpl::setDirquna('agent');
        Tpl::showpage('agent.index');   
    }
    /**
     * 结算列表
     */
    public function agent_applyOp(){
        
        Tpl::setDirquna('agent');
        Tpl::showpage('agent.apply');   
    }

    
    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_commission = Model('fx_agent');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        
        $page = $_POST['rp'];
        $condition['is_pass'] = isset($_GET['type']) ? $_GET['type'] : 0;

        $commission_list = $model_commission->getAgentList($condition, $page);
        
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
            $model_level = Model('fx_agent_level');
            $level_info = $model_level->getOneAgentLevel($value['level_id']);
            if($value['is_pass'] == 0){
                $operation = "<a class='btn orange' href='javascript:void(0);' onclick=\"tongguo(" . $value['agent_id'] . ")\">通过</a>";
                $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"butongguo(" . $value['agent_id'] . ")\">拒绝</a>";
                $operation .= "<a class='btn orange' href='javascript:if(confirm(\"确定要重置密码吗？重置后密码为123456\"))window.location = \"index.php?act=agent&op=agent_reset_pass&agent_id=" . $value['agent_id'] . "\";'>密码重置</a>";
                $param['operation'] = $operation;
                $is_pass = '待审核';
            }else if($value['is_pass'] == 1){
                $operation = "<a class='btn orange' href='javascript:if(confirm(\"确定要重置密码吗？重置后密码为123456\"))window.location = \"index.php?act=agent&op=agent_reset_pass&agent_id=" . $value['agent_id'] . "\";'>密码重置</a>";
                $operation .= "<a class='btn red' href='javascript:if(confirm(\"确定要下载吗？\"))window.location = \"index.php?act=agent&op=down_qrcode&agent_id=" . $value['agent_id'] . "\";'>商品二维码</a>";
                $param['operation'] = $operation;
                $is_pass = '已通过';
            }else if($value['is_pass'] == -1){
                $operation = "<a class='btn red' href='javascript:if(confirm(\"确定要删除该代理商吗？\"))window.location = \"index.php?act=agent&op=agent_del&agent_id=" . $value['agent_id'] . "\";'><i class='fa fa-trash-o'></i>删除</a>";
                $param['operation'] = $operation;
                $is_pass = '已拒绝';
            }
//             $param['agent_id'] = $value['agent_id'];
            $param['agent_no'] = $value['agent_no'];
            $param['agent_name'] = $value['agent_name'];
            $param['mobile'] = $value['mobile'];
            $param['level_id'] = $level_info['level_name'];
            $param['balance'] = $value['balance'];
            $param['is_pass'] = $is_pass;
            $param['total_sale_money'] = $value['total_sale_money'];
            $param['total_buy_goods'] = $value['total_buy_goods'];
            $param['consigner'] = $value['consigner'];
            $param['shop_address'] = $value['shop_address'];
            $param['shop_img'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".UPLOAD_SITE_URL.'/'.$value['shop_img'].">\")'><i class='fa fa-picture-o'></i></a>";
            $data['list'][$value['agent_id']] = $param;
        }
        
        echo Tpl::flexigridXML($data);exit();
    }

    public function tongguoOp(){
        $fx_withdraw = Model('fx_agent');
        $param['agent_id'] = $_POST['agent_id'];
        $param['is_pass'] = 1;
        $res = $fx_withdraw->updateAgent($param);
        echo $res;
    }
    
    public function butongguoOp(){
        $fx_withdraw = Model('fx_agent');
        $param['agent_id'] = $_POST['agent_id'];
        $param['is_pass'] = -1;
        $res = $fx_withdraw->updateAgent($param);
        echo $res;
    }
    
    public function agent_delOp(){
        $lang   = Language::getLangContent();
        $model_level = Model('fx_agent');
        if (!$_GET['agent_id']){
            showMessage("id不存在",'index.php?act=agent&op=agent_apply');
        }
    
        $result = $model_level->delAgent($_GET['agent_id']);
    
        if ($result){
            showMessage('操作成功','index.php?act=agent&op=agent_apply');
        }else {
            showMessage($lang['nc_common_save_fail']);
        }
    }
    
    public function agent_reset_passOp(){
        $lang   = Language::getLangContent();
        if (!$_GET['agent_id']){
            showMessage("id不存在",'index.php?act=agent&op=agent_index');
        }
        $fx_withdraw = Model('fx_agent');
        $param['agent_id'] = $_GET['agent_id'];
        $param['agent_password'] = md5(123456);
        $res = $fx_withdraw->updateAgent($param);
        if ($res){
            showMessage('操作成功','index.php?act=agent&op=agent_index');
        }else {
            showMessage($lang['nc_common_save_fail']);
        }
    }
    public function down_qrcodeOp(){
        if (!$_GET['agent_id']){
            showMessage("id不存在",'index.php?act=agent&op=agent_index');
        }
        $agent_id = $_GET['agent_id'];
        $down_path = BASE_UPLOAD_PATH.DS.'agent/goods'.DS.$agent_id;
        
        if (!file_exists($down_path)) {
            showMessage("暂无二维码",'index.php?act=agent&op=agent_index');
        }
        require_once "./include/zip.php";
        $zip = new PHPZip();
        //$zip -> createZip("要压缩的文件夹目录地址", "压缩后的文件名.zip");　　 //只生成不自动下载
        $zip -> downloadZip($down_path, $agent_id.time().".zip");
    }
}
