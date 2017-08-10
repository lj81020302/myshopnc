<?php
/**
 * 前台品牌分类
 *
 *
 * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');
class brandControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function recommend_listOp() {
        $brand_list = Model('brand')->getBrandPassedList(array('brand_recommend' => '1'), 'brand_id,brand_name,brand_pic');
        if (!empty($brand_list)) {
            foreach ($brand_list as $key => $val) {
                $brand_list[$key]['brand_pic'] = brandImage($val['brand_pic']);
            }
        }
        output_data(array('brand_list' => $brand_list));
    }
}
