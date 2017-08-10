<?php
/**
 * 属性模型
 *
 *
 *
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');

class attributeModel extends Model {
    const SHOW0 = 0;    // 不显示
    const SHOW1 = 1;    // 显示
    public function __construct() {
        parent::__construct();
    }

    /**
     * 属性列表
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getAttributeList($condition, $field = '*') {
        return $this->table('attribute')->where($condition)->field($field)->order('attr_sort asc')->select();
    }

    /**
     * 属性列表
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getAttributeShowList($condition, $field = '*') {
        $condition['attr_show'] = self::SHOW1;
        return $this->getAttributeList($condition, $field);
    }

    /**
     * 属性值列表
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getAttributeValueList($condition, $field = '*') {
        return $this->table('attribute_value')->where($condition)->field($field)->order('attr_value_sort asc,attr_value_id asc')->select();
    }

    /**
     * 保存属性值
     * @param array $insert
     * @return boolean
     */
    public function addAttributeValueAll($insert) {
        return $this->table('attribute_value')->insertAll($insert);
    }

    /**
     * 保存属性值
     * @param array $insert
     * @return boolean
     */
    public function addAttributeValue($insert) {
        return $this->table('attribute_value')->insert($insert);
    }

    /**
     * 编辑属性值
     * @param array $update
     * @param array $condition
     * @return boolean
     */
    public function editAttributeValue($update, $condition) {
        return $this->table('attribute_value')->where($condition)->update($update);
    }
}
