<?php
/**
 * 代理商
 *
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_goodsModel extends Model {
    /**
     * 代理商列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getGoodsList($condition, $pageObj=0){
        $condition_str = $this->_condition($condition);
        $param = array(
                    'table'=>'fx_goods',
                    'field'=>'*',
                    'where'=>$condition_str
                );
        $result = Db::select($param, $pageObj);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param array $condition 检索条件
     * @return string 字符串类型的返回结果
     */
    public function _condition($condition){
        $condition_str = '';

        if ($condition['fx_goods_id'] != ''){
            $condition_str .= " and fx_goods_id = '". $condition['fx_goods_id'] ."'";
        }
        if ($condition['goods_id'] != ''){
            $condition_str .= " and goods_id = '". $condition['goods_id'] ."'";
        }
        if ($condition['agent_id'] != ''){
            $condition_str .= " and agent_id = '". $condition['agent_id'] ."'";
        }
        if ($condition['agent_name'] != ''){
            $condition_str .= " and agent_name = '". $condition['agent_name'] ."'";
        }
        if ($condition['goods_name'] != ''){
            $condition_str .= " and goods_name LIKE '%". $condition['goods_name'] ."%'";
        }
        if ($condition['goods_serial'] != ''){
            $condition_str .= " and goods_serial = '". $condition['goods_serial'] ."'";
        }
        if ($condition['goods_price'] != ''){
            $condition_str .= " and goods_price = '". $condition['goods_price'] ."'";
        }
        if ($condition['goods_cost_price'] != ''){
            $condition_str .= " and goods_cost_price = '". $condition['goods_cost_price'] ."'";
        }
        if ($condition['goods_storage'] == 'not null'){
            $condition_str .= " and goods_storage > 0";
        }

        return $condition_str;
    }
    /**
     * 取单个的内容
     *
     * @param int $agent_id 代理商ID
     * @return array 数组类型的返回结果
     */
    public function getOneGoods($goods_id){
        if (intval($goods_id) > 0){
            $param = array();
            $param['table'] = 'fx_goods';
            $param['field'] = 'goods_id';
            $param['value'] = intval($goods_id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 获取信息
     *
     * @param   array $param 代理商条件
     * @param   string $field 显示字段
     * @return  array 数组格式的返回结果
     */
    public function infoGoods($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_goods';
        $param['where'] = $condition_str;
        $param['field'] = $field;
        $agent_info = Db::select($param);
        return $agent_info[0];
    }
    
    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addGoods($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_goods',$tmp);
            return $result;
        }else {
            return false;
        }
    }
    
    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updateGoods($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " fx_goods_id = '". $param['fx_goods_id'] ."'";
            $result = Db::update('fx_goods',$tmp,$where);
            return $result;
        }else {
            return false;
        }
    }
    
    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function delGoods($goods_id){
        if (intval($goods_id) > 0){
            $where = " goods_id = '". intval($goods_id) ."'";
            $result = Db::delete('fx_goods',$where);
            return $result;
        }else {
            return false;
        }
    }
    
    public function checkGoodsIshave($agent_id, $goods_id){
        $param['agent_id'] = $agent_id;
        $param['goods_id'] = $goods_id;
        $info = $this->infoGoods($param);
        if(!empty($info)){
            return $info;
        }else{
            return false;
        }
    }
    
}
