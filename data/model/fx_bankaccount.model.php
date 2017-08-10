<?php
/**
 * 提现帐户
 *
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_bankaccountModel extends Model {
    /**
     * 提现帐户列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getBankaccountList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
                    'table'=>'fx_bankaccount',
                    'field'=>'*',
                    'where'=>$condition_str
                );
        if(!empty($condition['order'])){
            $param['order'] = $condition['order'];
        }
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
    
        if ($condition['id'] != ''){
            $condition_str .= " and id = '". $condition['id'] ."'";
        }
        if ($condition['agent_id'] != ''){
            $condition_str .= " and agent_id = '". $condition['agent_id'] ."'";
        }
        if ($condition['bank_name'] != ''){
            $condition_str .= " and bank_name = '". $condition['bank_name'] ."'";
        }
        if ($condition['agent_name'] != ''){
            $condition_str .= " and agent_name like '%". $condition['agent_name'] ."%'";
        }
        if ($condition['account_num'] != ''){
            $condition_str .= " and account_num = '". $condition['account_num'] ."'";
        }
        if ($condition['mobile'] != ''){
            $condition_str .= " and mobile = '". $condition['mobile'] ."'";
        }
    
        return $condition_str;
    }
    /**
     * 取单个提现帐户的内容
     *
     * @param int $agent_id 代理商ID
     * @return array 数组类型的返回结果
     */
    public function getOneBankaccount($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'fx_bankaccount';
            $param['field'] = 'id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 获取提现帐户信息
     *
     * @param   array $param 代理商条件
     * @param   string $field 显示字段
     * @return  array 数组格式的返回结果
     */
    public function infoBankaccount($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_bankaccount';
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
    public function addBankaccount($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_bankaccount',$tmp);
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
    public function updateBankaccount($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = '';
            if(!empty($param['id'])){
                $where = " id = '". $param['id'] ."'";
            }
            $result = Db::update('fx_bankaccount',$tmp,$where);
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
    public function delBankaccount($id){
        if (intval($id) > 0){
            $where = " id = '". intval($id) ."'";
            $result = Db::delete('fx_bankaccount',$where);
            return $result;
        }else {
            return false;
        }
    }
}
