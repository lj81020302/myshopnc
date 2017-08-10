<?php
/**
 * 代理商等级
 *
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');
class fx_agent_levelModel extends Model {
    /**
     * 代理商等级列表
     *
     * @param array $condition 检索条件
     * @param obj $obj_page 分页对象
     * @return array 数组类型的返回结果
     */
    public function getAgentLevelList($condition,$obj_page=0){
        $condition_str = $this->_condition($condition);
        $param = array(
                    'table'=>'fx_agent_level',
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

        if ($condition['id'] != ''){
            $condition_str .= " and id = '". $condition['id'] ."'";
        }

        return $condition_str;
    }

    /**
     * 取单个代理商等级的内容
     *
     * @param int $id 代理商等级ID
     * @return array 数组类型的返回结果
     */
    public function getOneAgentLevel($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'fx_agent_level';
            $param['field'] = 'id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 获取代理商等级信息
     *
     * @param   array $param 代理商等级条件
     * @param   string $field 显示字段
     * @return  array 数组格式的返回结果
     */
    public function infoAgentLevel($param, $field = '*') {
        if(empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str  = $this->_condition($param);
        $param  = array();
        $param['table'] = 'fx_agent_level';
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
    public function addAgentLevel($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('fx_agent_level',$tmp);
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
    public function updateAgentLevel($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " id = '". $param['id'] ."'";
            $result = Db::update('fx_agent_level',$tmp,$where);
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
    public function delAgentLevel($id){
        if (intval($id) > 0){
            $where = " id = '". intval($id) ."'";
            $result = Db::delete('fx_agent_level',$where);
            return $result;
        }else {
            return false;
        }
    }
}
