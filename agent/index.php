<?php
/**
 * 商城板块初始化文件
 *
 *
 *
 * @运维舫提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopnc.club
 * @link       唯一论坛：www.shopnc.club
 */

define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/ywf.php')) exit('ywf.php isn\'t exists!');

define('APP_SITE_URL', AGENT_SITE_URL);
define('TPL_NAME',TPL_AGENT_NAME);
define('AGENT_TEMPLATES_URL',AGENT_SITE_URL.'/templates/'.TPL_NAME);
define('AGENT_RESOURCE_URL',AGENT_SITE_URL.'/resource');
define('SHOP_TEMPLATES_URL',SHOP_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();
