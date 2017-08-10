<?php
/**
 * The AJAX call member information
 *
 *
 *
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */

class member_cardControl extends MircroShopControl{
    public function mcard_infoOp(){
        $uid    = intval($_GET['uid']);
        if($uid <= 0) {
            echo 'false';exit;
        }
        $model_micro_member_info = Model('micro_member_info');
        $micro_member_info = $model_micro_member_info->getOneById($uid);
        if(empty($micro_member_info)){
            echo 'false';exit;
        }
        echo json_encode($micro_member_info);exit;
    }
}
