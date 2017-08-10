<?php
/**
 * 代理商
 *
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_agentModel extends Model {
    /**
     * 代理商列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getAgentList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
                    'table'=>'fx_agent',
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

        if ($condition['agent_id'] != ''){
            $condition_str .= " and agent_id = '". $condition['agent_id'] ."'";
        }
        if ($condition['agent_name'] != ''){
            $condition_str .= " and agent_name like '%". $condition['agent_name'] ."%'";
        }
        if ($condition['agent_no'] != ''){
            $condition_str .= " and agent_no = '". $condition['agent_no'] ."'";
        }
        if ($condition['agent_password'] != ''){
            $condition_str .= " and agent_password = '". $condition['agent_password'] ."'";
        }
        if ($condition['parent_id'] != ''){
            $condition_str .= " and parent_id = '". $condition['parent_id'] ."'";
        }
        if ($condition['is_pass'] == 1){
            $condition_str .= " and is_pass = '". $condition['is_pass'] ."'";
        }else if($condition['is_pass'] === 0){
            $condition_str .= " and is_pass != 1 ";
        }

        return $condition_str;
    }

    /**
     * 取单个代理商的内容
     *
     * @param int $agent_id 代理商ID
     * @return array 数组类型的返回结果
     */
    public function getOneAgent($agent_id){
        if (intval($agent_id) > 0){
            $param = array();
            $param['table'] = 'fx_agent';
            $param['field'] = 'agent_id';
            $param['value'] = intval($agent_id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 获取代理商信息
     *
     * @param   array $param 代理商条件
     * @param   string $field 显示字段
     * @return  array 数组格式的返回结果
     */
    public function infoAgent($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_agent';
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
    public function addAgent($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_agent',$tmp);
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
    public function updateAgent($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " agent_id = '". $param['agent_id'] ."'";
            $result = Db::update('fx_agent',$tmp,$where);
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
    public function delAgent($id){
        if (intval($id) > 0){
            $where = " agent_id = '". intval($id) ."'";
            $result = Db::delete('fx_agent',$where);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 获取代理商团队人员
     * @param unknown $agent_id
     * @param unknown $obj_page
     */
    public function getAgentTeam($condition,$obj_page){
        $condition_str = $this->_condition($condition);
        $param = array(
            'table'=>'fx_agent',
            'field'=>'*',
            'where'=>$condition_str
        );
        $result = Db::select($param,$obj_page);
        return $result;
    }
}
