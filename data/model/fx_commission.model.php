<?php
/**
 * 佣金
*
*/
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_commissionModel extends Model {
    /**
     * 佣金列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getCommissionList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
            'table'=>'fx_commission',
            'field'=>'*',
            'where'=>$condition_str
        );
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

        if ($condition['agent_id'] != ''){
            $condition_str .= " and agent_id = '". $condition['agent_id'] ."'";
        }
        if ($condition['order_sn'] != ''){
            $condition_str .= " and order_sn = '". $condition['order_sn'] ."'";
        }
        if ($condition['type'] != ''){
            $condition_str .= " and type = '". $condition['type'] ."'";
        }
        if ($condition['agent_name'] != ''){
            $condition_str .= " and agent_name like '%". $condition['agent_name'] ."%' ";
        }
        if ($condition['type_str'] != ''){
            switch ($condition['type_str']){
                case 'today':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(create_time), '%Y-%m-%d %H') = '".$condition['xdate']."' ";//当天
                    break;
                case 'yesterday':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(create_time), '%Y-%m-%d %H') = '".$condition['xdate']."' ";//当天
                    break;
                case 'week':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(create_time), '%Y-%m-%d') = '".$condition['xdate']."' ";//当天
                    break;
                case 'month':
                    $condition_str .= " and STR_TO_DATE(FROM_UNIXTIME(create_time), '%Y-%m-%d') = '".$condition['xdate']."' ";//当天
                    break;
                case 'year':
                    $condition_str .= " and create_time = YEAR(FROM_UNIXTIME(create_time))=YEAR(NOW())";//本年
                    break;
            }
        }

        return $condition_str;
    }
    /**
     * 取单个佣金的内容
     *
     * @param int $agent_id 代理商ID
     * @return array 数组类型的返回结果
     */
    public function getOneCommission($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'fx_commission';
            $param['field'] = 'id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 获取佣金信息
     *
     * @param   array $param 代理商条件
     * @param   string $field 显示字段
     * @return  array 数组格式的返回结果
     */
    public function infoCommission($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_commission';
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
    public function addCommission($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_commission',$tmp);
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
    public function updateCommission($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " id = '". $param['id'] ."'";
            $result = Db::update('fx_commission',$tmp,$where);
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
    public function delCommission($id){
        if (intval($id) > 0){
            $where = " id = '". intval($id) ."'";
            $result = Db::delete('fx_commission',$where);
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
    public function getCommissionCount($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        
//         $param  = array();
        $param['table'] = 'fx_commission';
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
                        $time_data[$i]['ydata'] = Db::select($param)[0]['commission'];
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
                        $time_data[$i]['ydata'] = Db::select($param)[0]['commission'];
                        
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
                        $time_data[$i]['ydata'] = Db::select($param)[0]['commission'];
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
                        $time_data[$i]['ydata'] = Db::select($param)[0]['commission'];
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
                        $param['where'] = $this->_condition($param);
                        $time_data = Db::select($param)[0]['commission'];
                    break;
            }
        }
        return $time_data;
    }
}
