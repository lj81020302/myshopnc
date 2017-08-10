<?php
/**
 * 商品管理
 *
 *
 *
 *
 * @运维舫提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopnc.club
 * @link       唯一论坛：www.shopnc.club
 */



defined('ShopNC_CLUB') or exit('Access Invalid!');

class goodsControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->goodsOp();
    }

    /**
     * 结算列表
     */
    public function goodsOp(){
        Tpl::setDirquna('agent');
        Tpl::showpage('goods.index');   
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_fx_goods = Model('fx_goods');
        $model_goods = Model('goods');
        $condition = array();
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        if ($_GET['goods_commonid'] != '') {
            $condition['goods_commonid'] = array('like', '%' . $_GET['goods_commonid'] . '%');
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['brand_name'] != '') {
            $condition['brand_name'] = array('like', '%' . $_GET['brand_name'] . '%');
        }
        if (intval($_GET['cate_id']) > 0) {
            $condition['gc_id'] = intval($_GET['cate_id']);
        }
        if ($_GET['goods_state'] != '') {
            $condition['goods_state'] = $_GET['goods_state'];
        }
        if ($_GET['goods_verify'] != '') {
            $condition['goods_verify'] = $_GET['goods_verify'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('goods_commonid', 'goods_name', 'goods_price', 'goods_state', 'goods_verify', 'goods_image', 'goods_jingle', 'gc_id'
                , 'gc_name', 'store_id', 'store_name', 'is_own_shop', 'brand_id', 'brand_name', 'goods_addtime', 'goods_marketprice', 'goods_costprice'
                , 'goods_freight', 'is_virtual', 'virtual_indate', 'virtual_invalid_refund', 'is_fcode'
                , 'is_presell', 'presell_deliverdate'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];

        $goods_list = $model_goods->getGoodsList($condition, $page);

        // 库存
        $storage_array = $model_goods->calculateStorage($goods_list);

        // 商品状态
        $goods_state = $this->getGoodsState();

        // 审核状态
        $verify_state = $this->getGoodsVerify();

        $data = array();
        $data['now_page'] = $model_goods->shownowpage();
        $data['total_num'] = $model_goods->gettotalnum();
        foreach ($goods_list as $value) {
            $param = array();
            $operation = '';
            switch ($_GET['type']) {
                // 禁售
                case 'lockup':
                    $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('" . $value['goods_commonid'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
                    break;
                    // 等待审核
                case 'waitverify':
                    $operation .= "<a class='btn orange' href='javascript:void(0);' onclick=\"fg_verify('" . $value['goods_commonid'] . "')\"><i class='fa fa-check-square'></i>审核</a>";
                    break;
                    // 全部商品
                default:
                    $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_lonkup('" . $value['goods_commonid'] . "')\"><i class='fa fa-ban'></i>下架</a>";
                    break;
            }
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='" . urlShop('goods', 'index', array('goods_id' => $storage_array[$value['goods_commonid']]['goods_id'])) . "' target=\"_blank\">查看商品详细</a></li>";
            $operation .= "<li><a href='javascript:void(0)' onclick=\"fg_sku('" . $value['goods_commonid'] . "')\">查看商品SKU</a></li>";
            $operation .= "</ul>";
            $param['operation'] = $operation;
            $param['goods_commonid'] = $value['goods_commonid'];
            $param['goods_name'] = $value['goods_name'];
            $param['goods_price'] = ncPriceFormat($value['goods_price']);
            $param['goods_state'] = $goods_state[$value['goods_state']];
            $param['goods_verify'] = $verify_state[$value['goods_verify']];
            $param['goods_image'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".thumb($value,'60').">\")'><i class='fa fa-picture-o'></i></a>";
            $param['goods_jingle'] = $value['goods_jingle'];
            $param['gc_id'] = $value['gc_id'];
            $param['gc_name'] = $value['gc_name'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['is_own_shop'] = $value['is_own_shop'] == 1 ? '平台自营' : '入驻商户';
            $param['brand_id'] = $value['brand_id'];
            $param['brand_name'] = $value['brand_name'];
            $param['goods_addtime'] = date('Y-m-d', $value['goods_addtime']);
            $param['goods_marketprice'] = ncPriceFormat($value['goods_marketprice']);
            $param['goods_costprice'] = ncPriceFormat($value['goods_costprice']);
            $param['goods_freight'] = $value['goods_freight'] == 0 ? '免运费' : ncPriceFormat($value['goods_freight']);
            $param['goods_storage'] = $storage_array[$value['goods_commonid']]['sum'];
            $param['is_virtual'] = $value['is_virtual'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['virtual_indate'] = $value['is_virtual'] == '1' && $value['virtual_indate'] > 0 ? date('Y-m-d', $value['virtual_indate']) : '--';
            $param['virtual_invalid_refund'] = $value['is_virtual'] ==  '1' ? ($value['virtual_invalid_refund'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>') : '--';
            $data['list'][$value['goods_commonid']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
}
