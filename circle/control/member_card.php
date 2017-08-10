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

class member_cardControl extends BaseCircleControl{
    public function mcard_infoOp(){
        $uid    = intval($_GET['uid']);
        $member_list = Model()->table('circle_member')->field('member_id,circle_id,circle_name,cm_level,cm_exp')->where(array('member_id'=>$uid,'cm_state'=>1))->select();
        if(empty($member_list)){
            echo 'false';exit;
        }
        echo json_encode($member_list);exit;
    }
}
