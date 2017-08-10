<?php
/**
 * 默认展示页面
 *
 *
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class indexControl extends BasicControl{
    public function __construct(){
        parent::__construct();
        Language::read('index');
    }
    public function indexOp(){
//         echo '3fds';exit();
        //输出管理员信息
        Tpl::output('agent_info',$this->getAgentInfo());
        
        Tpl::setLayout('index_layout');
        Tpl::showpage('index');
    }

    /**
     * 退出
     */
    public function logoutOp(){
        setNcCookie('sys_key','',-1,'',null);
        @header("Location: index.php");
        exit;
    }
    /**
     * 修改密码
     */
    public function modifypwOp(){
        if (chksubmit()){
            if (trim($_POST['new_pw']) !== trim($_POST['new_pw2'])){
                //showMessage('两次输入的密码不一致，请重新输入');
                showMessage(Language::get('index_modifypw_repeat_error'));
            }
            $agentinfo = $this->getAgentInfo();
            //查询管理员信息
            $agent_model = Model('agent');
            $agentinfo = $agent_model->getOneAdmin($agentinfo['id']);
            if (!is_array($agentinfo) || count($agentinfo)<= 0){
                showMessage(Language::get('index_modifypw_admin_error'));
            }
            //旧密码是否正确
            if ($agentinfo['agent_password'] != md5(trim($_POST['old_pw']))){
                showMessage(Language::get('index_modifypw_oldpw_error'));
            }
            $new_pw = md5(trim($_POST['new_pw']));
            $update = array();
            $update['agent_password'] = $new_pw;
            $update['agent_id'] = $agentinfo['agent_id'];
            $result = $agent_model->updateoAgent($update);
            if ($result){
                showDialog(Language::get('index_modifypw_success'), urlAdmin('index', 'logout'), 'succ');
            }else{
                showMessage(Language::get('index_modifypw_fail'));
                showDialog(Language::get('index_modifypw_fail'), '', '', 'CUR_DIALOG.click();');
            }
        }else{
            Language::read('admin');
            Tpl::showpage('admin.modifypw', 'null_layout');
        }
    }
    
    public function save_avatarOp() {
        $agentinfo = $this->getAgentInfo();
        $agent_model = Model('agent');
        $agentinfo = $agent_model->getOneAgent($agentinfo['id']);
        if ($_GET['avatar'] == '') {
            echo false;die;
        }
        @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_AGENT_AVATAR . '/' . cookie('agent_avatar'));
        $update['agent_avatar'] = $_GET['avatar'];
        $update['agent_id'] = $agentinfo['admin_id'];
        $result = $agent_model->updateAdmin($update);
        if ($result) {
            setNcCookie('agent_avatar',$_GET['avatar'],86400 * 365,'',null);
        }
        echo $result;die;
    }
    
}
