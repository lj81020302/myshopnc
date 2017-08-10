<?php
/**
 * 系统后台公共方法
 *
 * 包括系统后台父类
 *
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');
class BasicControl{

    /**
     * 管理员资料 name id group
     */
    protected $agent_info;

    /**
     * 权限内容
     */
    protected $permission;

    /**
     * 菜单
     */
    protected $menu;

    /**
     * 常用菜单
     */
    protected $quick_link;
    protected function __construct() {
        Language::read('common,layout');
        /**
         * 验证用户是否登录
         * $agent_info 管理员资料 name id
         */
        $this->agent_info = $this->systemLogin();
        //转码  防止GBK下用ajax调用时传汉字数据出现乱码
        if (($_GET['branch']!='' || $_GET['op']=='ajax') && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
//         var_dump($this->agent_info);
        //输出菜单
        $menu = $this->getNav();
        list($top_menu, $left_menu) = $menu;
        Tpl::output('agent_info',$this->agent_info);
        Tpl::output('top_menu',$top_menu);
        Tpl::output('left_menu',$left_menu);
        Tpl::setLayout('index_layout');
    }

    /**
     * 取得当前管理员信息
     *
     * @param
     * @return 数组类型的返回结果
     */
    protected final function getAgentInfo() {
        return $this->agent_info;
    }

    /**
     * 系统后台登录验证
     *
     * @param
     * @return array 数组类型的返回结果
     */
    protected final function systemLogin() {
        //取得cookie内容，解密，和系统匹配
        $user = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
        if (!key_exists('agent_no',(array)$user) || (empty($user['agent_name']) || empty($user['agent_id']))){
            @header('Location: '. AGENT_SITE_URL .'/index.php?act=login&op=login');exit;
            
        }else {
            $this->systemSetKey($user);
        }
        return $user;
    }

    /**
     * 系统后台 会员登录后 将会员验证内容写入对应cookie中
     *
     * @param string $name 用户名
     * @param int $id 用户ID
     * @return bool 布尔类型的返回结果
     */
    protected final function systemSetKey($user, $avatar = '', $avatar_compel = false) {
        setNcCookie('sys_key',encrypt(serialize($user),MD5_KEY),3600,'',null);
        if ($avatar_compel || $avatar != '') {
            setNcCookie('agent_avatar',$avatar,86400 * 365,'',null);
        }
    }

    /**
     * 取得后台菜单
     *
     * @param string $permission
     * @return
     */
    protected final function getNav() {
        $_menu = $this->getMenu();
        $top_menu = array();
        $left_menu = array();
        foreach ($_menu as $key=>$menu){
            $top_menu[] =
            array(
                'name'=>$menu['name'],
                'url'=>$menu['url'],
                'is_select'=>($_GET['top_code'] == $menu['top_code']?1:0)
            );
            if($_GET['top_code'] == $menu['top_code']){
                foreach ($menu['child'] as $k=>$child){
                    $left_menu[] =
                    array(
                        'name'=>$child['name'],
                        'url'=>$child['url'],
                        'is_select'=>($_GET['menu_code']==$child['menu_code']?1:0)
                    );
                }
            }
        }
        return array($top_menu,$left_menu);
    }

    /**
     * 获取菜单
     */
    protected final function getMenu() {
        if (empty($this->menu)) {
            $this->menu  = rkcache('agent_menu', true);
        }
        return $this->menu;
    }

    /**
     * 获取快捷操作
     */
    protected final function getQuickLink() {
        if ($this->agent_info['qlink'] != '') {
            return explode(',', $this->agent_info['qlink']);
        } else {
            return array();
        }
    }

    /**
     * 取得顶部小导航
     *
     * @param array $links
     * @param 当前页 $actived
     */
    protected final function sublink($links = array(), $actived = '', $file='index.php') {
        $linkstr = '';
        foreach ($links as $k=>$v) {
            parse_str($v['url'],$array);
            if (empty($array['op'])) $array['op'] = 'index';
            $href = ($array['op'] == $actived ? null : "href=\"{$file}?{$v['url']}\"");
            $class = ($array['op'] == $actived ? "class=\"current\"" : null);
            $lang = $v['text'] ? $v['text'] : L($v['lang']);
            $linkstr .= sprintf('<li><a %s %s><span>%s</span></a></li>',$href,$class,$lang);
        }
        return "<ul class=\"tab-base nc-row\">{$linkstr}</ul>";
    }

    /**
     * 输出JSON
     *
     * @param string $errorMessage 错误信息 为空则表示成功
     */
    protected function jsonOutput($errorMessage = false)
    {
        $data = array();

        if ($errorMessage === false) {
            $data['result'] = true;
        } else {
            $data['result'] = false;
            $data['message'] = $errorMessage;
        }

        $jsonFlag = C('debug') && version_compare(PHP_VERSION, '5.4.0') >= 0
            ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            : 0;

        echo json_encode($data, $jsonFlag);
        exit;
    }
    
    /**
     * 修改密码
     * 
     */
    public function update_passwordOp(){
        $model_agent = Model('fx_agent');
        $condition['agent_id'] = $this->agent_info['agent_id'];
        $condition['agent_password'] = md5($_POST['oldpassword']);
        $condition['is_pass'] = 1;
        $agent = $model_agent->infoAgent($condition);
        
        $res = false;
        if(!empty($agent)){
            $param['agent_password'] = md5($_POST['password2']);
            $param['agent_id'] = $this->agent_info['agent_id'];
            $res = $model_agent->updateAgent($param);
        }
        
        echo $res;
    }

}
