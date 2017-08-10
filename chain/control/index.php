<?php
/**
 * 物流自提服务站首页
 *
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class indexControl extends BaseChainCenterControl{
    public function __construct(){
        parent::__construct();
        redirect('index.php?act=goods');
    }

}
