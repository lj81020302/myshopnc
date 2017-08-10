<?php
/**
 * 订单列表
 *
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_ordersModel extends Model {
    /**
     * 订单列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getOrdersList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
                    'table'=>'fx_orders',
                    'field'=>'*',
                    'where'=>$condition_str
                );
        if(!empty($condition['order'])){
            $param['order'] = $condition['order'];
        }
        $result = Db::select($param, $obj_page);
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

        if ($condition['buyer_id'] != ''){
            $condition_str .= " AND fx_orders.buyer_id = ". $condition['buyer_id'] ."";
        }
        if ($condition['agent_id'] != ''){
            $condition_str .= " AND fx_orders.agent_id = ". $condition['agent_id'] ."";
        }
        if ($condition['order_id'] != ''){
            $condition_str .= " AND fx_orders.order_id = ". $condition['order_id'] ."";
        }
        if ($condition['order_sn'] != ''){
            $condition_str .= " AND fx_orders.order_sn LIKE '%". $condition['order_sn'] ."%'";
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
    public function getOneOrders($order_id){
        if (intval($order_id) > 0){
            $param = array();
            $param['table'] = 'fx_orders';
            $param['field'] = 'order_id';
            $param['value'] = intval($order_id);
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
    public function infoOrders($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_orders';
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
    public function addOrders($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_orders',$tmp);
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
    public function updateOrders($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " order_id = '". $param['order_id'] ."'";
            $result = Db::update('fx_orders',$tmp,$where);
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
    public function delOrders($order_id){
        if (intval($order_id) > 0){
            $where = " order_id = '". intval($order_id) ."'";
            $result = Db::delete('fx_orders',$where);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 订单项列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getOrdersGoodsList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
            'table'=>'fx_orders,fx_order_goods',
            'field'=>'fx_orders.order_id,fx_orders.order_sn,fx_orders.add_time,fx_orders.order_amount,fx_orders.agent_name,fx_orders.state,
                    fx_order_goods.goods_name,fx_order_goods.solo_commission,fx_order_goods.goods_price,fx_order_goods.goods_cost_price,fx_order_goods.goods_image,
                    fx_order_goods.goods_num',
            'where'=>$condition_str,
            'join_type'=>empty($condition['join_type'])?'left join':$condition['join_type'],
            'join_on'=>array('fx_orders.order_id=fx_order_goods.order_id')
        );
//         $param['order'] = 'fx_orders.add_time desc';
        $result = Db::select($param, $obj_page);
        return $result;
    }
    /**
     * 订单列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getOrdersDetailList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
            'table'=>'fx_orders,member',
            'field'=>'fx_orders.*,member.member_name',
            'where'=>$condition_str,
            'join_type'=>empty($condition['join_type'])?'left join':$condition['join_type'],
            'join_on'=>array('fx_orders.buyer_id=member.member_id')
        );
        if(!empty($condition['order'])){
            $param['order'] = $condition['order'];
        }
        $result = Db::select($param, $obj_page);
        return $result;
    }
    /**
     * 获取佣金统计
     *
     * @param   array $param 代理商条件
     * @param   string $field 显示字段
     * @return  array 数组格式的返回结果
     */
    public function getOrderCount($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
    
        //         $param  = array();
        $param['table'] = 'fx_orders';
        $param['field'] = $field;
        $time_data = array();
        if ($param['type_str'] != ''){
            switch ($param['type_str']){
                case 'today':
                    for($i=0;$i<24;$i++){
                        //得到条件语句
                        if($i <10){
                            $param['xdate'] = date("Y-m-d",time()).' '.'0'.$i;
                            //                             $time_data[$i]['xdate'] = ' '.'0'.$i.':00';
                            $time_data[$i]['xdate'] = date("Y-m-d",time()).' '.'0'.$i.':00';
                        }else{
                            $param['xdate'] = date("Y-m-d",time()).' '.$i;
                            //                             $time_data[$i]['xdate'] = ' '.$i.':00';
                            $time_data[$i]['xdate'] = date("Y-m-d",time()).' '.$i.':00';
                        }
                        $param['where'] = $this->_condition($param);
                        $time_data[$i]['ydata'] = Db::select($param)[0]['order_num'];
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
                        $time_data[$i]['ydata'] = Db::select($param)[0]['order_num'];
    
                    };
                    break;
                case 'week':
                    for($i=0;$i<7;$i++){
                        $week = date('w');
                        $param['xdate'] = date("Y-m-d",strtotime('+'.($i+1-$week).'days'));
                        //                         $param['xdate'] = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+$i-1-date('w'),date('Y')));
                        $param['where'] = $this->_condition($param);
    
                        //                         $time_data[$i]['xdate'] = date("Y-m-d",strtotime("-".($i+1)." day"));
                        //                         $time_data[$i]['xdate'] = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+$i-1-date('w'),date('Y')));
                        $time_data[$i]['xdate'] = date("Y-m-d",strtotime('+'.($i+1-$week).'days'));
                        $time_data[$i]['ydata'] = Db::select($param)[0]['order_num'];
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
                        $time_data[$i]['ydata'] = Db::select($param)[0]['order_num'];
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
                    $time_data = Db::select($param)[0]['order_num'];
                    break;
            }
        }
        return $time_data;
    }
}
