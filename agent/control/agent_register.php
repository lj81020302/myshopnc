<?php
/**
 * 代理商注册
 *
 *
 * @运维舫提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopnc.club
 * @link       唯一论坛：www.shopnc.club
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');
class agent_registerControl extends BasicControl {

    /**
     * 不进行父类的登录验证，所以增加构造方法重写了父类的构造方法
     */
    public function __construct(){
        Language::read('common,layout,login,agent');
        Tpl::setLayout('agent_apply_layout');
    }
    /**
     * 判断当前代理商的状态
     */
    protected function checkAgentStatus(){
        if(!empty($this->agent_info)){
            redirect('index.php?act=agent_register&op=step3');
        }else{
            showMessage('帐户或密码错误','index.php?act=agent_register&op=show_join');
        }
    }
    /**
     * 代理商申请页面（代理商登录或者直接进行注册）
     */
    public function show_joinOp(){
        Language::read("home_login_index");
        $code_info = C('store_joinin_pic');
        $info['pic'] = array();
        if(!empty($code_info)) {
            $info = unserialize($code_info);
        }
        Tpl::output('pic_list',$info['pic']);//首页图片
        Tpl::output('show_txt',$info['show_txt']);//贴心提示
        Tpl::setLayout('store_joinin_layout');
        Tpl::showpage('store_joinin');
    }
    /**
     * 签署协议页面
     */
    public function step0Op(){
        $model_document = Model('document');
        $document_info = $model_document->getOneByCode('open_store');
        Tpl::output('agreement', $document_info['doc_content']);
        Tpl::output('step', '0');
        Tpl::output('sub_step', 'step0');
        Tpl::setLayout('store_joinin_layout');
        Tpl::showpage('store_joinin_apply');
        exit;
    }
    /**
     * 输入信息页面
     */
    public function step1Op(){
        $model_agent = Model('fx_agent_level');
        $condition['order'] = 'id';
        $level_list = $model_agent->getAgentLevelList($condition);
        Tpl::output('level_list',$level_list);
        Tpl::output('step', '1');
        Tpl::output('sub_step', 'step1');
        Tpl::setLayout('store_joinin_layout');
        Tpl::showpage('store_joinin_apply');
        exit;
    }
    
    /**
     *添加成功页面
     * @param
     * @return
     */
    public function step2Op() {
        Tpl::output('step', '2');
        Tpl::output('sub_step', 'step2');
        Tpl::output('agent_no', $_GET['agent_no']);
        Tpl::setLayout('store_joinin_layout');
        Tpl::showpage('store_joinin_apply');
    }
    /**
     * 待审核 或者 已审核页面
     */
    public function step3Op(){
        Tpl::output('step', '3');
        Tpl::output('sub_step', 'step3');
        Tpl::output('agent_no', $this->agent_info['agent_no']);
        Tpl::output('is_pass', $this->agent_info['is_pass']);
        Tpl::setLayout('store_joinin_layout');
        Tpl::showpage('store_joinin_apply');
    }
    
    public function apply_agent_saveOp(){
        $model_agent = Model('fx_agent');
        $data = array();
        $data['agent_name'] = $_POST['agent_name'];
        $data['agent_password'] = md5($_POST['agent_password']);
        $data['mobile'] = $_POST['mobile'];
        $data['level_id'] = $_POST['level_id'];
        $data['consigner'] = $_POST['consigner'];
        $data['shop_address'] = $_POST['shop_address'];
        $data['shop_img'] = isset($_POST['shop_img1']) ? "shop/agent_joinin/".$_POST['shop_img1'] : '';
        $where['agent_no'] = $_POST['parent_no'];
        $agent_info = $model_agent->infoAgent($where, $field = 'agent_id');
        $data['parent_id'] = $agent_info['agent_id'];
        $res = $model_agent->addAgent($data);
        if($res > 0){
            $agent_no = $this->get_agent_no($res);
            $param['agent_id'] = $res;
            $param['agent_no'] = $agent_no;
            $update_res = $model_agent->updateAgent($param);
            redirect('index.php?act=agent_register&op=step2&agent_no='.$agent_no);
        }
    }
    
    public function apply_agentOp(){
        $model_agent = Model('fx_agent_level');
        $condition['order'] = 'id';
        $level_list = $model_agent->getAgentLevelList($condition);
        Tpl::output('level_list',$level_list);
        Tpl::showpage('apply_agent');
    }
    
    /**
     * 自动生成代理商编号
     */
    private function get_agent_no($id){
        $no = 'G';
        $number = 10000 + $id;
        return $no.$number;
    }
    

    public function ajax_upload_imageOp() {
    
        $pic_name = '';
        $upload = new UploadFile();
        $file = current($_FILES);
        $uploaddir = ATTACH_PATH.DS.'agent_joinin'.DS;
        $upload->set('max_size',C('image_max_filesize'));
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($file['tmp_name'])){
            $result = $upload->upfile(key($_FILES));
            if ($result){
                echo json_encode(array('state'=>true,'pic_name'=>$upload->file_name,'pic_url'=>UPLOAD_SITE_URL.DS.ATTACH_PATH.DS.'agent_joinin'.DS.$upload->file_name));
            } else {
                echo json_encode(array('state'=>false,'message'=>$upload->error));
            }
        }
    }
    
}
