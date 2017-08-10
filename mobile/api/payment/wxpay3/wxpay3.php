<?php
/**
 * 微信支付接口类
 *
 * @运维舫 (c) 2015-2018 ywf Inc. (http://www.shopnc.club)
 * @license    http://www.sho p.club
 * @link       唯一论坛：www.shopnc.club
 * @since      运维舫提供技术支持 授权请购买shopnc授权
 */
defined('ShopNC_CLUB') or exit('Access Invalid!');

/**
 * @todo TEST 传递的URL参数是否冲突
 * @todo 后续接收通知
 * @todo 后续页面显示 以及异步结果提示
 */
class wxpay3
{

    /**
     * 获取notify信息
     */
    public function getNotifyInfo($payment_config) {
        $result = $this->_verify3($payment_config);

        if ($result) {
            return array(
                //商户订单号
                'out_trade_no' => $result['out_trade_no'],
                //微信支付交易号
                'trade_no' => $result['transaction_id'],
            );
        }

        return false;
    }

    /**
     * 验证返回信息(v3)
     */
    private function _verify3($payment_config) {
        if(empty($payment_config)) {
            return false;
        }

        $xml = file_get_contents("php://input");
        $array = simplexml_load_string($xml);
        $param = array();
        foreach ($array as $key => $value) {
            $param[$key] = (string)$value;
        }

        ksort($param);
        $hash_temp = '';
        foreach ($param as $key => $value) {
            if($key != 'sign') {
                $hash_temp .= $key . '=' . $value . '&';
            }
        }

        $hash_temp .= 'key' . '=' . $payment_config['wxpay_partnerkey'];

        $hash = strtoupper(md5($hash_temp));

        if($hash == $param['sign']) {
            return array(
                'out_trade_no' => $param['out_trade_no'],
                'transaction_id' => $param['transaction_id'],
            );
        } else {
            return false;
        }
    }
}
