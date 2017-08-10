<?php
/**
 * 文章 
 * @运维舫b1 (c) 2005-2016 ywf Inc.
 * @license    http://www.shopnc.club
 * @link       运维舫
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 * 
 **/

defined('ShopNC_CLUB') or exit('Access Invalid!');
class article_classControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }
    
    public function indexOp() {
			$article_class_model	= Model('article_class');
			$article_model	= Model('article');
			$condition	= array();
			
			$article_class = $article_class_model->getClassList($condition);
			output_data(array('article_class' => $article_class));		
    }
}
