<?php
/**
 * 图片空间操作
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');
class sns_settingControl extends BaseSNSControl {
    public function __construct() {
        parent::__construct();
        /**
         * 读取语言包
         */
        Language::read('sns_setting');
    }
    public function change_skinOp(){
        Tpl::showpage('sns_changeskin', 'null_layout');
    }
    public function skin_saveOp(){
        $insert = array();
        $insert['member_id']    = $_SESSION['member_id'];
        $insert['setting_skin'] = $_GET['skin'];

        Model()->table('sns_setting')->insert($insert,true);
    }
}
