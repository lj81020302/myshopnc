<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');
class LoginControl extends BasicControl {

    /**
     * 不进行父类的登录验证，所以增加构造方法重写了父类的构造方法
     */
    public function __construct(){
        Language::read('common,layout,login');
        $result = chksubmit(true,false,'num');
        if ($result){
            if ($result === -11){
                showMessage('非法请求');
            }elseif ($result === -12){
                showMessage(L('login_index_checkcode_wrong'));
            }
//             if (process::islock('agent')) {
// //                 process::clear('agent');
//                 showMessage('您的操作过于频繁，请稍后再试');
//             }
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["user_name"], "require"=>"true", "message"=>L('login_index_username_null')),
                array("input"=>$_POST["password"],  "require"=>"true", "message"=>L('login_index_password_null')),
                array("input"=>$_POST["captcha"],   "require"=>"true", "message"=>L('login_index_checkcode_null')),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(L('error').$error);
            } else {
                $model_agent = Model('fx_agent');
//                 $condition = null;
//                 $agentList = $model_agent->getAgentList($condition, null);
//                 var_dump($agentList);exit();
                $array  = array();
                $array['agent_no']    = $_POST['user_name'];
//                 $array['agent_password']= md5(trim($_POST['password']));
                $array['agent_password']= md5(trim($_POST['password']));
//                 if(empty($_POST['ref_url'])){
                    $array['is_pass']= 1;
//                 }
                $agent_info = $model_agent->infoAgent($array);
                if(is_array($agent_info) and !empty($agent_info)) {
                    $array = array();
                    $array['agent_name']  = $agent_info['agent_name'];
                    $array['agent_id']    = $agent_info['agent_id'];
                    $array['agent_no']    = $agent_info['agent_no'];
                    $array['is_pass']    = $agent_info['is_pass'];
                    $array['ip']    = getIp();
                    $this->systemSetKey($array, $agent_info['agent_avatar'], true);
//                     $this->log(L('nc_login'),1);
//                     process::clear('agent');
                    if(empty($_POST['ref_url'])){
                        redirect('index.php?act=index&op=index&top_code=10100');
                    }else{
                        redirect($_POST['ref_url'].'&ag='.$array['agent_id']);
                    }
//                     @header('Location: INDEX.php');
                    exit;
                } else {
//                     process::addprocess('agent');
                    showMessage('帐户或密码错误','index.php?act=login&op=login');
                }
            }
        }
//         Tpl::output('html_title',L('login_index_need_login'));
        Tpl::showpage('login','login_layout');
    }
    public function loginOp(){}
    public function indexOp(){}
}
