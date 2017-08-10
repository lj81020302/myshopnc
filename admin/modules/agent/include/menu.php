<?php
/**
 * 菜单
 *
 * @运维舫提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopnc.club
 * @link       唯一论坛：www.shopnc.club
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
$_menu['agent'] = array (
        'name' => '代理商',
        'child' => array (
                array(
                        'name' => '代理商',
                        'child' => array(
                                'agent' => '代理商管理',
                                'level' => '等级管理',
								
                        )
                ),
                array(
                        'name' => '订单',
                        'child' => array(
                                'order' => '订单管理',
                                'purchase_order' => '进货单管理',
                        )
                ),
				 array(
                        'name' => '账户',
                        'child' => array(
								'commission' => '佣金管理',
								'withdraw' => '提现管理',
								'jiesuan' => '结算管理',
                        )
                )
        ) 
);
