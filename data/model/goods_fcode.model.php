<?php
/**
 * 商品F码模型
 * * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class goods_fcodeModel extends Model {
    public function __construct(){
        parent::__construct('goods_fcode');
    }
    /**
     * 插入数据
     *
     * @param unknown $insert
     * @return boolean
     */
    public function addGoodsFCodeAll($insert) {
        return $this->insertAll($insert);
    }
    /**
     * 取得F码列表
     *
     * @param array $condition
     * @param string $order
     */
    public function getGoodsFCodeList($condition, $order = 'fc_state asc,fc_id asc') {
        return $this->where($condition)->order($order)->select();
    }

    /**
     * 删除F码
     */
    public function delGoodsFCode($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * 取得F码
     */
    public function getGoodsFCode($condition) {
        return $this->where($condition)->find();
    }

    /**
     * 更新F码
     */
    public function editGoodsFCode($data, $condition) {
        return $this->where($condition)->update($data);
    }
}
