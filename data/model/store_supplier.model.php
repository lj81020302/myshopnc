<?php
/**
 * 供货商模型
 *
 *
 *
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class store_supplierModel extends Model{

    public function __construct(){
        parent::__construct('store_supplier');
    }

    /**
     * 读取列表
     * @param array $condition
     *
     */
    public function getStoreSupplierList($condition, $page = '', $order = '', $field = '*') {
        return $this->field($field)->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 读取单条记录
     * @param array $condition
     *
     */
    public function getStoreSupplierInfo($condition) {
        return $this->where($condition)->find();
    }

    /*
     * 增加
     * @param array $data
     * @return bool
     */
    public function addStoreSupplier($data){
        return $this->insert($data);
    }

    public function editStoreSupplier($data,$condition) {
        return $this->where($condition)->update($data);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function delStoreSupplier($condition){
        return $this->where($condition)->delete();
    }

}
