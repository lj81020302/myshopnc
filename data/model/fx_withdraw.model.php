<?php
/**
 * 提现管理
 *
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_withdrawModel extends Model {
    /**
     * 提现列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getWithdrawList($condition,$obj_page=0){
//         var_dump($condition);
        $condition_str = $this->_condition($condition);
//         var_dump($condition_str);
        $param = array(
                    'table'=>'fx_withdraw',
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

        if ($condition['id'] != ''){
            $condition_str .= " and id = '". $condition['id'] ."'";
        }
        if ($condition['agent_id'] != ''){
            $condition_str .= " and agent_id = '". $condition['agent_id'] ."'";
        }
        if ($condition['status_id'] === 0){
            $condition_str .= " and status_id < 1 ";
        }else if($condition['status_id'] == 1){
            $condition_str .= " and status_id >= 1 ";
        }
        if ($condition['mobile'] != ''){
            $condition_str .= " and mobile = '". $condition['mobile'] ."'";
        }
        if ($condition['account_num'] != ''){
            $condition_str .= " and account_num = '". $condition['account_num'] ."'";
        }
        if ($condition['agent_name'] != ''){
            $condition_str .= " and agent_name like '%". $condition['agent_name'] ."%'";
        }
        if ($condition['bank_name'] != ''){
            $condition_str .= " and bank_name = '". $condition['bank_name'] ."'";
        }
        if ($condition['realname'] != ''){
            $condition_str .= " and realname = '". $condition['realname'] ."'";
        }

        return $condition_str;
    }

    /**
     * 取单个的内容
     *
     * @param int $agent_id 代理商ID
     * @return array 数组类型的返回结果
     */
    public function getOneWithdraw($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'fx_withdraw';
            $param['field'] = 'id';
            $param['value'] = intval($id);
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
    public function infoWithdraw($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_withdraw';
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
    public function addWithdraw($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_withdraw',$tmp);
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
    public function updateWithdraw($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " id = '". $param['id'] ."'";
            $result = Db::update('fx_withdraw',$tmp,$where);
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
    public function delWithdraw($id){
        if (intval($id) > 0){
            $where = " id = '". intval($id) ."'";
            $result = Db::delete('fx_withdraw',$where);
            return $result;
        }else {
            return false;
        }
    }
    
}
