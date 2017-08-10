<?php
/**
 * 实物订单结算
 *
 * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');
class seller_billControl extends mobileSellerControl {
    public function __construct() {
        parent::__construct() ;
    }

    /**
     * 结算列表
     *
     */
    public function listOp() {
        $model_bill = Model('bill');
        $condition = array();
        $condition['ob_store_id'] = $this->store_info['store_id'];
        if (preg_match('/^\d+$/',$_POST['ob_id'])) {
            $condition['ob_id'] = intval($_POST['ob_id']);
        }
        if (is_numeric($_POST['bill_state'])) {
            $condition['ob_state'] = intval($_POST['bill_state']);
        }
        $bill_list = $model_bill->getOrderBillList($condition, '*', $this->page, 'ob_state asc,ob_id asc');

        $page_count = $model_bill->gettotalpage();
        output_data(array('bill_list' => $bill_list), mobile_page($page_count));
    }
}
