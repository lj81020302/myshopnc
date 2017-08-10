<?php
/**
 * 订单项【订单里的商品项】
 *
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_order_goodsModel extends Model {
    /**
     * 订单项列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getOrderGoodsList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
                    'table'=>'fx_order_goods',
                    'field'=>'*',
                    'where'=>$condition_str
                );
        $result = Db::select($param,$obj_page);
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

        if ($condition['order_id'] != ''){
            $condition_str .= " and order_id = '". $condition['order_id'] ."'";
        }
        if ($condition['agent_id'] != ''){
            $condition_str .= " and agent_id = '". $condition['agent_id'] ."'";
        }
        if ($condition['goods_id'] != ''){
            $condition_str .= " and goods_id = '". $condition['goods_id'] ."'";
        }
        if ($condition['goods_name'] != ''){
            $condition_str .= " and goods_name = '". $condition['goods_name'] ."'";
        }
        if ($condition['goods_serial'] != ''){
            $condition_str .= " and goods_serial = '". $condition['goods_serial'] ."'";
        }
        if ($condition['type_str'] != ''){
            switch ($condition['type_str']){
                case 'today':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(add_time), '%Y-%m-%d %H') = '".$condition['xdate']."' ";//当天
                    break;
                case 'yesterday':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(add_time), '%Y-%m-%d %H') = '".$condition['xdate']."' ";//当天
                    break;
                case 'week':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(add_time), '%Y-%m-%d') = '".$condition['xdate']."' ";//当天
                    break;
                case 'month':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(add_time), '%Y-%m-%d') = '".$condition['xdate']."' ";//当天
                    break;
                case 'year':
                    $condition_str .= " and create_time = YEAR(FROM_UNIXTIME(add_time))=YEAR(NOW())";//本年
                    break;
            }
        }
        
        return $condition_str;
    }

    /**
     * 取单个的内容
     *
     * @param int $agent_id 代理商ID
     * @return array 数组类型的返回结果
     */
    public function getOneOrderGoods($rec_id){
        if (intval($rec_id) > 0){
            $param = array();
            $param['table'] = 'fx_order_goods';
            $param['field'] = 'rec_id';
            $param['value'] = intval($rec_id);
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
    public function infoOrderGoods($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_order_goods';
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
    public function addOrderGoods($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_order_goods',$tmp);
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
    public function updateOrderGoods($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " goods_id = '". $param['goods_id'] ."'";
            $result = Db::update('fx_order_goods',$tmp,$where);
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
    public function delOrderGoods($rec_id){
        if (intval($rec_id) > 0){
            $where = " rec_id = '". intval($rec_id) ."'";
            $result = Db::delete('fx_order_goods',$where);
            return $result;
        }else {
            return false;
        }
    }
    
    /**
     * 获取佣金统计
     *
     * @param   array $param 代理商条件
     * @param   string $field 显示字段
     * @return  array 数组格式的返回结果
     */
    public function getOrderGoodsCount($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
    
        //         $param  = array();
        $param['table'] = 'fx_order_goods';
        $param['field'] = $field;
        $time_data = array();
        if ($param['type_str'] != ''){
            switch ($param['type_str']){
                case 'today':
                    for($i=0;$i<24;$i++){
                        //得到条件语句
                        if($i <10){
                            $param['xdate'] = date("Y-m-d",time()).' '.'0'.$i;
                            $time_data[$i]['xdate'] = date("Y-m-d",time()).' '.'0'.$i.':00';
                        }else{
                            $param['xdate'] = date("Y-m-d",time()).' '.$i;
                            $time_data[$i]['xdate'] = date("Y-m-d",time()).' '.$i.':00';
                        }
                        $param['where'] = $this->_condition($param);
                        $time_data[$i]['ydata'] = Db::select($param)[0]['goods_num'];
                    };
                    break;
                case 'yesterday':
                    for($i=0;$i<24;$i++){
                        if($i <10){
                            $param['xdate'] = date("Y-m-d",strtotime("-1 day")).' '.'0'.$i;
                            $time_data[$i]['xdate'] = date("Y-m-d",strtotime("-1 day")).' '.'0'.$i.':00';
                        }else{
                            $param['xdate'] = date("Y-m-d",strtotime("-1 day")).' '.$i;
                            $time_data[$i]['xdate'] = date("Y-m-d",strtotime("-1 day")).' '.$i.':00';
                        }
                        $param['where'] = $this->_condition($param);
                        $time_data[$i]['ydata'] = Db::select($param)[0]['goods_num'];
    
                    };
                    break;
                case 'week':
                    for($i=0;$i<7;$i++){
                        $week = date('w');
                        $param['xdate'] = date("Y-m-d",strtotime('+'.($i+1-$week).'days'));
                        $param['where'] = $this->_condition($param);
                        $time_data[$i]['xdate'] = date("Y-m-d",strtotime('+'.($i+1-$week).'days'));
                        $time_data[$i]['ydata'] = Db::select($param)[0]['goods_num'];
                    };
                    break;
                case 'month':
                    for($i=0;$i<date("t");$i++){
                        $j = $i+1;
                        if($j<10){
                            $param['xdate'] = date("Y-m-",strtotime("now")).'0'.$j;
                            $time_data[$i]['xdate'] = date("Y-m-",strtotime("now")).'0'.$j;
                        }else{
                            $param['xdate'] = date("Y-m-",strtotime("now")).$j;
                            $time_data[$i]['xdate'] = date("Y-m-",strtotime("now")).$j;
                        }
                        $param['where'] = $this->_condition($param);
                        $time_data[$i]['ydata'] = Db::select($param)[0]['goods_num'];
                    };
                    break;
                case 'year':
                    for($i=1;$i<13;$i++){
                        $param['xdate'] = $i;
                        $param['where'] = $this->_condition($param);
                        $time_data['xdate'] = $i;
                        $time_data['ydata'] = Db::select($param);
                    };
                case 'total':
                    $time_data = Db::select($param)[0]['goods_num'];
                    break;
            }
        }
        return $time_data;
    }
}
