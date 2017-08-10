<?php
/**
 * 我的购物车
 *
 *
 * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class fx_member_cartControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 购物车列表
     */
    public function cart_listOp() {
        $model_cart = Model('fx_cart');

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list  = $model_cart->listCart('db', $condition);


        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $store_cart_list = array();
        $sum = 0;
        foreach ($cart_list as $cart) {
            $store_cart_list[$cart['agent_id']]['agent_id'] = $cart['agent_id'];
            $store_cart_list[$cart['agent_id']]['agent_name'] = $cart['agent_name'];
            $cart['goods_image_url'] = cthumb($cart['goods_image']);
            $cart['goods_total'] = ncPriceFormat($cart['goods_price'] * $cart['goods_num']);
            $cart['xianshi_info'] = $cart['xianshi_info'] ? $cart['xianshi_info'] : array();
            $cart['groupbuy_info'] = $cart['groupbuy_info'] ? $cart['groupbuy_info'] : array();
            $store_cart_list[$cart['agent_id']]['goods'][] = $cart;
            $sum += $cart['goods_total'];
        }
        

        output_data(array('cart_list' => array_values($store_cart_list), 'sum' => ncPriceFormat($sum), 'cart_count' => count($cart_list)));
    }

    /**
     * 购物车列表
     */
    public function cart_list_oldOp() {
        $model_cart = Model('cart');
    
        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list  = $model_cart->listCart('db', $condition);
    
        // 购物车列表 [得到最新商品属性及促销信息]
        $cart_list = logic('buy_1')->getGoodsCartList($cart_list, $jjgObj);
        $sum = 0;
        foreach ($cart_list as $key => $value) {
            $cart_list[$key]['goods_image_url'] = cthumb($value['goods_image'], $value['agent_id']);
            $cart_list[$key]['goods_sum'] = ncPriceFormat($value['goods_price'] * $value['goods_num']);
            $sum += $cart_list[$key]['goods_sum'];
        }
    
        output_data(array('cart_list' => $cart_list, 'sum' => ncPriceFormat($sum)));
    }

    /**
     * 购物车添加
     */
    public function cart_addOp() {
        $goods_id = intval($_POST['goods_id']);
        $quantity = intval($_POST['quantity']);
        if($goods_id <= 0 || $quantity <= 0) {
            output_error('参数错误');
        }

        $model_goods = Model('fx_goods');
        $model_cart = Model('fx_cart');
        $logic_buy_1 = Logic('buy_1');

        $condition['fx_goods_id'] = $goods_id;
        $goods_info = $model_goods->infoGoods($condition);

        if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $quantity) {
            output_error('库存不足');
        }

        $param = array();
        $param['buyer_id']  = $this->member_info['member_id'];
        $param['agent_id']  = $goods_info['agent_id'];
        $param['goods_id']  = $goods_info['fx_goods_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['goods_image'] = $goods_info['goods_image'];
        $param['agent_name'] = $goods_info['agent_name'];

        $result = $model_cart->addCart($param, 'db', $quantity);
        if($result) {
            output_data('1');
        } else {
            output_error('添加失败');
        }
    }

    /**
     * 购物车删除
     */
    public function cart_delOp() {
        $cart_id = intval($_POST['cart_id']);

        $model_cart = Model('fx_cart');

        if($cart_id > 0) {
            $condition = array();
            $condition['buyer_id'] = $this->member_info['member_id'];
            $condition['fx_cart_id'] = $cart_id;

            $model_cart->delCart('db', $condition);
        }

        output_data('1');
    }

    /**
     * 更新购物车购买数量
     */
    public function cart_edit_quantityOp() {
        $cart_id = intval(abs($_POST['cart_id']));
        $quantity = intval(abs($_POST['quantity']));
        if(empty($cart_id) || empty($quantity)) {
            output_error('参数错误');
        }

        $model_cart = Model('fx_cart');

        $cart_info = $model_cart->getCartInfo(array('fx_cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));
        
        //检查是否为本人购物车
        if($cart_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        //检查库存是否充足
        if(!$this->_check_goods_storage($cart_info, $quantity, $this->member_info['member_id'])) {
            output_error('库存不足');
        }

        $data = array();
        $data['goods_num'] = $quantity;
        $update = $model_cart->editCart($data, array('fx_cart_id'=>$cart_id));
        
        if ($update) {
            $return = array();
            $return['quantity'] = $quantity;
            $return['goods_price'] = ncPriceFormat($cart_info['goods_price']);
            $return['total_price'] = ncPriceFormat($cart_info['goods_price'] * $quantity);
            output_data($return);
        } else {
            output_error('修改失败');
        }
    }

    /**
     * 检查库存是否充足
     */
    private function _check_goods_storage($cart_info, $quantity, $member_id) {
        $model_goods= Model('fx_goods');
        //普通商品
        $condition['fx_goods_id'] = $cart_info['fx_goods_id'];
        $goods_info = $model_goods->infoGoods($condition);
        
        if (intval($goods_info['goods_storage']) < $quantity) {
            return false;
        }
        
        $goods_info['cart_id'] = $cart_info['cart_id'];
        $cart_info = $goods_info;
        return true;
    }
    
    /**
     * 查询购物车商品数量
     */
    function cart_countOp() {
        $param['cart_count'] = Model('fx_cart')->countCartByMemberId($this->member_info['member_id']);
        output_data($param);
    }

    /**
     * 批量添加购物车
     * cartlist 格式为goods_id1,num1|goods_id2,num2
     */
    public function cart_batchaddOp(){
        $param = $_POST;
        $cartlist_str = trim($param['cartlist']);
        $cartlist_arr = $cartlist_str?explode('|',$cartlist_str):array();
        if(!$cartlist_arr) {
            output_error('参数错误');
        }

        $cartlist_new =  array();
        foreach($cartlist_arr as $k=>$v){
            $tmp = $v?explode(',',$v):array();
            if (!$tmp) {
                continue;
            }
            if($v && !empty($v) && $v != 'null'){
                $cartlist_new[$tmp[0]]['goods_num'] = $tmp[1];
            }
        }
        Model('fx_cart')->batchAddCart($cartlist_new, $this->member_info['member_id']);
        output_data('1');
    }
}
