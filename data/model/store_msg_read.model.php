<?php
/**
 * 店铺消息阅读模板模型
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class store_msg_readModel extends Model{
    public function __construct() {
        parent::__construct('store_msg_read');
    }
    /**
     * 新增店铺纤细阅读
     * @param unknown $insert
     */
    public function addStoreMsgRead($insert) {
        $insert['read_time'] = TIMESTAMP;
        return $this->insert($insert);
    }

    /**
     * 查看店铺消息阅读详细
     * @param unknown $condition
     * @param string $field
     */
    public function getStoreMsgReadInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 店铺消息阅读列表
     * @param unknown $condition
     * @param string $field
     * @param string $order
     */
    public function getStoreMsgReadList($condition, $field = '*', $order = 'read_time desc') {
        return $this->field($field)->where($condition)->order($order)->select();
    }

    /**
     * 删除店铺消息阅读记录
     * @param unknown $condition
     */
    public function delStoreMsgRead($condition) {
        $this->where($condition)->delete();
    }
}
