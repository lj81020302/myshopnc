<?php
/**
 * 自定义属性模型
 *
 *
 *
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');

class type_customModel extends Model {
    const STATE1 = 1;       // 开启
    const STATE0 = 0;       // 关闭

    public function __construct() {
        parent::__construct('type_custom');
    }

    /**
     * 自定义属性列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getTypeCustomList($condition, $field = '*', $order = 'custom_id asc') {
        return $this->field($field)->where($condition)->order($order)->select();
    }

    /**
     * 保存自定义属性
     *
     * @param array $insert
     * @return boolean
     */
    public function addTypeCustomAll($insert) {
        return $this->insertAll($insert);
    }

    /**
     * 编辑自定义属性
     * @param array $update
     * @param array $condition
     * @return array
     */
    public function editTypeCustom($update, $condition) {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除自定义属性
     * @param array $condition
     * @return array
     */
    public function delTypeCustom($condition) {
        return $this->where($condition)->delete();
    }

}
