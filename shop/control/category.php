<?php
/**
 * 前台分类
 *
 *
 *
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class categoryControl extends BaseHomeControl {
    /**
     * 分类列表
     */
    public function indexOp(){
        Language::read('home_category_index');
        $lang   = Language::getLangContent();
        //导航
        $nav_link = array(
            '0'=>array('title'=>$lang['homepage'],'link'=>SHOP_SITE_URL),
            '1'=>array('title'=>$lang['category_index_goods_class'])
        );
        Tpl::output('nav_link_list',$nav_link);

        Tpl::output('html_title',C('site_name').' - '.Language::get('category_index_goods_class'));
        Tpl::showpage('category');
    }
}
