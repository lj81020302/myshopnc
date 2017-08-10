<?php
/**
 * 代理商等级管理
 *
 *
 *
 *
 * @运维舫提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopnc.club
 * @link       唯一论坛：www.shopnc.club
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class purchase_orderControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->purchase_orderOp();
    }

    /**
     * 结算列表
     */
    public function purchase_orderOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('purchase_order.index');   
    }
    /**
     * 待审核列表
     */
    public function applyOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('purchase_order.apply');
    }
    /**
     * 待发货列表
     */
    public function fahuoOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('purchase_order.fahuo');
    }
    /**
     * 已拒绝列表
     */
    public function jujueOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('purchase_order.jujue');
    }
    
    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_commission = Model('fx_purchase_orders');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        
        $page = $_POST['rp'];
        $condition['status_id'] = isset($_GET['type']) ? $_GET['type'] : 2;
        $condition['order'] = 'create_time desc';
        $commission_list = $model_commission->getPurchaseOrdersList($condition, $page);
        
        $data = array();
        $data['now_page'] = $model_commission->shownowpage();
        $data['total_num'] = $model_commission->gettotalnum();
        if($commission_list == null){
            $commission_list = array();
            $data['now_page'] = 0;
            $data['total_num'] = 0;
        }
        foreach ($commission_list as $value) {
            $param = array();
            if($value['status_id'] == 0 || $value['status_id'] == 1 || $value['status_id'] == -1){
                $operation = '';
                switch ($value['status_id']) {
                    // 禁售
                    case -1:
                        $operation .= "<a class='btn red' href='javascript:if(confirm(\"确定要删除该进货单吗？\"))window.location = \"index.php?act=purchase_order&op=purchase_order_del&id=" . $value['id'] . "\";'><i class='fa fa-trash-o'></i>删除</a>";
                        $param['operation'] = $operation;
                        break;
                    case 0:
                        $operation .= "<a class='btn orange' href='javascript:void(0);' onclick=\"tongguo(" . $value['id'] . ")\">通过</a>";
                        $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"butongguo(" . $value['id'] . ")\">拒绝</a>";
                        break;
                        // 全部商品
                    case 1:
                        $operation = "<a class='btn orange' href='javascript:void(0);' onclick=\"peisong(" . $value['id'] . ", " . $value['goods_id'] . ")\"><i class='fa fa-check-square'></i>配送</a>";
                        $param['operation'] = $operation;
                        // 等待审核
                    default:
                        break;
                }
                $param['operation'] = $operation;
            }
            $param['id'] = $value['id'];
            $param['order_sn'] = $value['order_sn'];
            $param['agent_name'] = $value['agent_name'];
            $param['goods_name'] = $value['goods_name'];
            $param['goods_price'] = $value['goods_price'];
            $param['goods_num'] = $value['goods_num'];
            $param['order_money'] = $value['order_money'];
            $param['status_id'] = $value['status_id'];
            if($value['status_id'] == 0){
                $param['status_id'] = '未处理';
            }else if($value['status_id'] == 1){
                $param['status_id'] = '已通过';
            }else if($value['status_id'] == 2){
                $param['status_id'] = '已配送';
            }else if($value['status_id'] == -1){
                $param['status_id'] = '已拒绝';
            }
            $param['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            $param['deal_time'] = date("Y-m-d H:i:s", $value['deal_time']);
            $data['list'][$value['id']] = $param;
        }
        
        echo Tpl::flexigridXML($data);exit();
    }
    
    public function tongguoOp(){
        $data['id'] = $_POST['id'];
        $model_commission = Model('fx_purchase_orders');
        $data['status_id'] = 1;
        $data['deal_time'] = time();
        $res = $model_commission->updatePurchaseOrders($data);
        //通过成功之后 判断当前代理商是否有该商品，没有添加商品
        $order_info = $model_commission->getOnePurchaseOrders($_POST['id']);
        $model_fx_goods = Model('fx_goods');
        $bool = $model_fx_goods->checkGoodsIshave($order_info['agent_id'], $order_info['goods_id']);
        //$bool 不为空 的时候 商品已经存在 否则不存在
        if(empty($bool)){
            //添加商品
            $goods = Model('goods');
            $goods_info = $goods->getGoodsInfo(array('goods_id' => $order_info['goods_id']), $field = 'goods_id,goods_commonid,goods_name,goods_jingle,gc_id,gc_id_1,gc_id_2,gc_id_3,brand_id,goods_price,goods_marketprice,goods_serial,goods_storage_alarm,goods_barcode,spec_name,goods_spec,goods_image,goods_body,mobile_body,goods_state,goods_addtime,goods_edittime,areaid_1,areaid_2,color_id,goods_stcids,evaluation_good_star,evaluation_count,invite_rate');
            //查询成本价
            $common_id = $goods_info['goods_commonid'];
            $chenben = $goods->getGoodsCommonInfo(array('goods_commonid' => $common_id), $field = 'goods_costprice');
            $goods_info['agent_id'] = $order_info['agent_id'];
            $goods_info['agent_name'] = $order_info['agent_name'];
            $goods_info['goods_storage'] = 0;
            $goods_info['goods_cost_price'] = $chenben['goods_costprice'];
            $model_fx_goods->addGoods($goods_info);
        }else{
            //判断商品的进价是否相同
            $goods = Model('goods');
            $goods_info = $goods->getGoodsInfo(array('goods_id' => $order_info['goods_id']), $field = 'goods_id,goods_commonid,goods_name,goods_jingle,gc_id,gc_id_1,gc_id_2,gc_id_3,brand_id,goods_price,goods_marketprice,goods_serial,goods_storage_alarm,goods_barcode,spec_name,goods_spec,goods_image,goods_body,mobile_body,goods_state,goods_addtime,goods_edittime,areaid_1,areaid_2,color_id,goods_stcids,evaluation_good_star,evaluation_count,invite_rate');
            //查询成本价
            $common_id = $goods_info['goods_commonid'];
            
            $chenben = $goods->getGoodsCommonInfo(array('goods_commonid' => $common_id), $field = 'goods_costprice');
            $arr['goods_cost_price'] = $chenben['goods_costprice'];
            $arr['goods_price'] = $goods_info['goods_price'];
            $arr['agent_id'] = $order_info['agent_id'];
            
            $info = $model_fx_goods->infoGoods($arr, 'fx_goods_id');
            if(empty($info)){
                $goods_info['agent_id'] = $order_info['agent_id'];
                $goods_info['agent_name'] = $order_info['agent_name'];
                $goods_info['goods_storage'] = 0;
                $goods_info['goods_cost_price'] = $chenben['goods_costprice'];
                $model_fx_goods->addGoods($goods_info);
            }
//             if(($bool['goods_cost_price'] != $chenben['goods_costprice']) || ($bool['goods_price'] != $goods_info['goods_price'])){
                
//             }
        }
        //通过之后给代理商添加累计进货额
        //计算代理商个人销售总额
        $this->updateAgentBuyGoods($order_info['agent_id'], $order_info['goods_price'] * $order_info['goods_num']);
        echo $res;
    }
    protected function updateAgentBuyGoods($agent_id, $buy_goods_money){
        $model_agent = Model('fx_agent');
        $info = $model_agent->getOneAgent($agent_id);
        $total_buy_goods = $info['total_buy_goods'];
        $array['agent_id'] = $agent_id;
        $array['total_buy_goods'] = $buy_goods_money + $total_buy_goods;
        $res = $model_agent->updateAgent($array);
        return $res;
    }
    
    public function butongguoOp(){
        $data['id'] = $_POST['id'];
        $model_commission = Model('fx_purchase_orders');
        $data['status_id'] = -1;
        $data['deal_time'] = time();
        $res = $model_commission->updatePurchaseOrders($data);
        echo $res;
    }
    
    public function peisongOp(){
        $data['id'] = $_POST['id'];
        $model_commission = Model('fx_purchase_orders');
        $data['status_id'] = 2;
        $data['deal_time'] = time();
        $res = $model_commission->updatePurchaseOrders($data);
        $param['id'] = $_POST['id'];
        $goods_num = $model_commission->infoPurchaseOrders($param, $field = 'goods_num,goods_id,agent_id,goods_price,goods_cost_price');
        //代理商增加库存
        $model_fx_goods = Model('fx_goods');
        $where['goods_id'] = $goods_num['goods_id'];
        $where['agent_id'] = $goods_num['agent_id'];
        $where['goods_price'] = $goods_num['goods_price'];
        $where['goods_cost_price'] = $goods_num['goods_cost_price'];
        
        $fx_goods_info = $model_fx_goods->infoGoods($where, $field = 'fx_goods_id,goods_storage');
        $fxparam['fx_goods_id'] = $fx_goods_info['fx_goods_id'];
        $fxparam['goods_storage'] = $fx_goods_info['goods_storage'] + $goods_num['goods_num'];
        $model_fx_goods->updateGoods($fxparam);
        
        //直营店减少库存
        $model_goods = Model('goods');
        $condition['goods_id'] = $goods_num['goods_id'];
        $info = $model_goods->getGoodsInfo($condition, $field = 'goods_storage');
        $update['goods_storage'] = $info['goods_storage'] - $goods_num['goods_num'];
        $model_goods->editGoodsById($update, $goods_num['goods_id']);
        echo $res;
    }
    
    public function purchase_order_delOp(){
        $lang   = Language::getLangContent();
        $model_level = Model('fx_purchase_orders');
        if (!$_GET['id']){
            showMessage("id不存在",'index.php?act=purchase_order&op=index');
        }
    
        $result = $model_level->delPurchaseOrders($_GET['id']);
    
        if ($result){
            showMessage('操作成功','index.php?act=purchase_order&op=index');
        }else {
            showMessage($lang['nc_common_save_fail']);
        }
    }
    /**
     * 批量生成更新商品二维码
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @param boolean $allow_promotion
     * @return
     */
    public function maker_qrcodeOp()
    {
//         $store_id=$_SESSION['store_id'];
        // 生成商店二维码
        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
        $PhpQRCode = new PhpQRCode();
        //print_r($PhpQRCode);
//         $fx_purchase_orders = Model('fx_purchase_orders');
        $fx_goods = Model('fx_goods');
//         $where=array();
//         $count=$model_goods->getGoodsCount($where);
//         $lst=$fx_purchase_orders->getPurchaseOrdersList(null);
        $lst=$fx_goods->getGoodsList(null);
        if(empty($lst))
        {
            echo '未找到商品信息';
            return;
        }
        foreach($lst as $k=>$v)
        {
            $fx_goods_id=$v['fx_goods_id'];
            $qrcode_path = BASE_UPLOAD_PATH.DS.'agent/goods'.DS.$v['agent_id'].DS;
            $qrcode_filename = $fx_goods_id.'.png';
            $qrcode_filepath = $qrcode_path.$qrcode_filename;
            if(!file_exists($qrcode_filepath)){
                //生成二维码
                $qrcode_url=WAP_SITE_URL . '/tmpl/fx_member/product_detail.html?goods_id='.$fx_goods_id;
                $PhpQRCode->set('pngTempDir', $qrcode_path);
                $PhpQRCode->set('date',$qrcode_url);
                $PhpQRCode->set('pngTempName', $fx_goods_id . '.png');
                $PhpQRCode->set('matrixPointSize',5);
                $PhpQRCode->init();
            }
            
//             imagecreatefromstring();
            if(file_exists($qrcode_filepath)){
                
//                 $this->dealImgBackground($qrcode_filepath);
                $this->dealImg($qrcode_filepath, $qrcode_filepath,$v['goods_name'],'￥'.$v['goods_price']);
            }
        }
    
        showMessage('操作成功','index.php?act=purchase_order&op=index');
//         showDialog(L('nc_common_op_succ'), $_POST['ref_url'], 'succ');
    }
    
    /**
     * 批量配送
     */
    public function purchase_order_peisongOp(){
        if ($_GET['id'] != ''){
            $gcids = explode(',', $_GET['id']);
            if (empty($gcids)) {
                return false;
            }
            $model_commission = Model('fx_purchase_orders');
            $model_fx_goods = Model('fx_goods');
            $model_goods = Model('goods');
            foreach ($gcids as $gc_id) {
                $data['id'] = $gc_id;
                $data['status_id'] = 2;
                $data['deal_time'] = time();
                $res = $model_commission->updatePurchaseOrders($data);
                $param['id'] = $gc_id;
                $goods_num = $model_commission->infoPurchaseOrders($param, $field = 'goods_num,goods_id,agent_id,goods_price,goods_cost_price');
                //代理商增加库存
                $where['goods_id'] = $goods_num['goods_id'];
                $where['agent_id'] = $goods_num['agent_id'];
                $where['goods_price'] = $goods_num['goods_price'];
                $where['goods_cost_price'] = $goods_num['goods_cost_price'];
                
                $fx_goods_info = $model_fx_goods->infoGoods($where, $field = 'fx_goods_id,goods_storage');
                $fxparam['fx_goods_id'] = $fx_goods_info['fx_goods_id'];
                $fxparam['goods_storage'] = $fx_goods_info['goods_storage'] + $goods_num['goods_num'];
                $model_fx_goods->updateGoods($fxparam);
                
                //直营店减少库存
                $condition['goods_id'] = $goods_num['goods_id'];
                $info = $model_goods->getGoodsInfo($condition, $field = 'goods_storage');
                $update['goods_storage'] = $info['goods_storage'] - $goods_num['goods_num'];
                $res = $model_goods->editGoodsById($update, $goods_num['goods_id']);
            }
            exit(json_encode(array('state'=>true,'msg'=>'配送成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'配送失败')));
        }
    }
    
    /**
     * 添加文字水印
     */
    public function dealImgWord(){
        $bigImgPath = 'backgroud.png';
        $img = imagecreatefromstring(file_get_contents($bigImgPath));
        
        $font = 'msyhl.ttc';//字体
        $black = imagecolorallocate($img, 0, 0, 0);//字体颜色 RGB
        
        $fontSize = 20;   //字体大小
        $circleSize = 60; //旋转角度
        $left = 50;      //左边距
        $top = 150;       //顶边距
        
        imagefttext($img, $fontSize, $circleSize, $left, $top, $black, $font, 'Rhythmk| 坤');
        
        list($bgWidth, $bgHight, $bgType) = getimagesize($bigImgPath);
        switch ($bgType) {
            case 1: //gif
                header('Content-Type:image/gif');
                imagegif($img);
                break;
            case 2: //jpg
                header('Content-Type:image/jpg');
                imagejpeg($img);
                break;
            case 3: //jpg
                header('Content-Type:image/png');
                imagepng($img);
                break;
            default:
                break;
        }
        imagedestroy($img);
    }
    
    /**
     * 合成背景图
     */
    public function dealImgBackground($qCodePath=''){
        $bigImgPath = BASE_UPLOAD_PATH.DS.'agent'.DS.'qrcode_background.png';
//         $qCodePath = 'qcode.png';
        
        $bigImg = imagecreatefromstring(file_get_contents($bigImgPath));
        $qCodeImg = imagecreatefromstring(file_get_contents($qCodePath));
        
        list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($qCodePath);
        // imagecopymerge使用注解
        imagecopymerge($bigImg, $qCodeImg, 0, 0, 0, 0, $qCodeWidth, $qCodeHight, 100);
        
        list($bigWidth, $bigHight, $bigType) = getimagesize($bigImgPath);
//         file_put_contents(time().'dd.txt', $bigImgPath);
        
        switch ($bigType) {
            case 1: //gif
                header('Content-Type:image/gif');
                imagegif($bigImg);
                break;
            case 2: //jpg
                header('Content-Type:image/jpg');
                imagejpeg($bigImg);
                break;
            case 3: //png
                header('Content-Type:image/png');
                imagepng($bigImg);
                break;
            default:
                # code...
                break;
        }
        
        imagedestroy($bigImg);
        imagedestroy($qCodeImg);
    }
    public function dealImg($qCodePath,$imageurlname,$text1,$text2){
        $fonturl = BASE_UPLOAD_PATH.DS.'agent'.DS.'dongqing.otf';
        $bigImgPath = BASE_UPLOAD_PATH.DS.'agent'.DS.'qrcode_background.png';
        /* 图片背景 */
        $image=imagecreatefrompng($bigImgPath);//背景图
        $qrimg_image=imagecreatefrompng($qCodePath);//二维码
        
        /* 添加图片 */
        imagecopy($image, $qrimg_image, 680, 230, 0, 0, 185, 185);//二维码放到背景图
        /* 添加文字 */
        $fontcolor1 = imagecolorallocate($image, 0, 0, 0);
        $fontcolor2 = imagecolorallocate($image, 255, 0, 0);
        //     $fontcolor = imagecolorallocate($image, 234, 86, 20);
        imagettftext ( $image, 26, 0, 210, 290, $fontcolor1, $fonturl, mb_substr($text1, 0, 15, 'utf-8'));//文字放到背景图
        imagettftext ( $image, 26, 0, 211, 290, $fontcolor1, $fonturl, mb_substr($text1, 0, 15, 'utf-8'));//文字放到背景图
        imagettftext ( $image, 26, 0, 212, 290, $fontcolor1, $fonturl, mb_substr($text1, 0, 15, 'utf-8'));//文字放到背景图
        imagettftext ( $image, 50, 0, 270, 430, $fontcolor2, $fonturl, $text2);//文字放到背景图
        imagettftext ( $image, 50, 0, 271, 430, $fontcolor2, $fonturl, $text2);//文字放到背景图
        imagettftext ( $image, 50, 0, 272, 430, $fontcolor2, $fonturl, $text2);//文字放到背景图
        
        header('Content-Type: image/png');
        imagepng($image,$imageurlname,2);//生成图片
        imagedestroy($image);
        imagedestroy($qrimg_image);
    }
}
