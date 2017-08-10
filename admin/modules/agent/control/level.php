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

class levelControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('agent,agent');
    }

    public function indexOp() {
        $this->levelOp();
    }

    /**
     * 店铺等级
     */
    public function levelOp(){
        /**
         * 读取语言包
         */
        $lang   = Language::getLangContent();
        $model_agent_level = Model('fx_agent_level');

        $condition['order'] = 'id';

        $level_list = $model_agent_level->getAgentLevelList($condition);
        Tpl::output('level_list',$level_list);
		Tpl::setDirquna('agent');
        Tpl::showpage('level.index');   
    }

    /**
     * 新增等级
     */
    public function level_addOp(){
        $lang   = Language::getLangContent();
    
        $model_level = Model('fx_agent_level');
        if (chksubmit()){
    
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["join_fee"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['allow_pubilsh_product_num_only_lnteger']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array = array();
                $insert_array['join_fee'] = abs(floatval($_POST['join_fee']));
                $insert_array['level_name'] = $_POST['level_name'];
    
                $result = $model_level->addAgentLevel($insert_array);
                if ($result){
                    showMessage($lang['nc_common_save_succ'],'index.php?act=level&op=level');
                }else {
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }
        Tpl::setDirquna('agent');
        Tpl::showpage('level.add');
    }
    
    /**
     * 等级编辑
     */
    public function level_editOp(){
        $lang   = Language::getLangContent();
    
        $model_level = Model('fx_agent_level');
        if (chksubmit()){
            if (!$_POST['id']){
                showMessage("id不存在",'index.php?act=level&op=level');
            }
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["join_fee"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['allow_pubilsh_product_num_only_lnteger']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['id'] = intval($_POST['id']);
                $update_array['join_fee'] = abs(floatval($_POST['join_fee']));
                $update_array['level_name'] = $_POST['level_name'];
                $result = $model_level->updateAgentLevel($update_array);
    
                if ($result){
                    showMessage($lang['nc_common_save_succ'],'index.php?act=level&op=level');
                }else {
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }
    
        $level_array = $model_level->getOneAgentLevel(intval($_GET['id']));
        if (empty($level_array)){
            showMessage($lang['illegal_parameter']);
        }
    
        Tpl::output('level_array',$level_array);
        Tpl::setDirquna('agent');
        Tpl::showpage('level.edit');
    }
    public function level_delOp(){
        $lang   = Language::getLangContent();
        $model_level = Model('fx_agent_level');
            if (!$_GET['id']){
                showMessage("id不存在",'index.php?act=level&op=level');
            }
            
            $result = $model_level->delAgentLevel($_GET['id']);
    
            if ($result){
                showMessage($lang['nc_common_save_succ'],'index.php?act=level&op=level');
            }else {
                showMessage($lang['nc_common_save_fail']);
            }
    }
    
    
    
}