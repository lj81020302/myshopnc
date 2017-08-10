<?php
/**
 * 通知模板表
 *
 *
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');

class mail_templatesModel extends Model {

    public function __construct(){
        parent::__construct('mail_msg_temlates');
    }

    /**
     * 取单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getTplInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }

    /**
     * 模板列表
     *
     * @param array $condition 检索条件
     * @return array 数组形式的返回结果
     */
    public function getTplList($condition = array()){
        return $this->where($condition)->select();
    }

    public function editTpl($data = array(), $condition = array()) {
        return $this->where($condition)->update($data);
    }

}
