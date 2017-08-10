<?php
namespace com\jdjr\pay\demo\action;
use com\jdjr\pay\demo\common\ConfigUtil;
use com\jdjr\pay\demo\common\HttpUtils;
use com\jdjr\pay\demo\common\XMLUtil;
include '../lib/ConfigUtil.php';
include '../lib/HttpUtils.php';
include '../lib/XMLUtil.php';
class UnifiedOrder{
	public function execute(){
		$param["version"]=$_POST["version"];
		$param["merchant"]=$_POST["merchant"];
		$param["device"]=$_POST["device"];
		$param["tradeNum"]=$_POST["tradeNum"];
		$param["tradeName"]=$_POST["tradeName"];
		$param["tradeDesc"]=$_POST["tradeDesc"];
		$param["tradeTime"]= $_POST["tradeTime"];
		$param["amount"]= $_POST["amount"];
		$param["currency"]= $_POST["currency"];
		$param["note"]= $_POST["note"];

		$param["notifyUrl"]= $_POST["notifyUrl"];
		$param["ip"]= $_POST["ip"];
		$param["specCardNo"]= $_POST["specCardNo"];
		$param["specId"]= $_POST["specId"];
		$param["specName"]= $_POST["specName"];
		$param["userType"]= $_POST["userType"];
		$param["userId"]= $_POST["userId"];
		$param["expireTime"]= $_POST["expireTime"];
		$param["orderType"]= $_POST["orderType"];
		
		$param["industryCategoryCode"]= $_POST["industryCategoryCode"];
		$param["vendorId"]= $_POST["vendorId"];
		$param["goodsInfo"]= $_POST["goodsInfo"];
		$param["orderGoodsNum"]= $_POST["orderGoodsNum"];
		$param["receiverInfo"]= $_POST["receiverInfo"];
		$param["tradeType"]= $_POST["tradeType"];
		
		$reqXmlStr = XMLUtil::encryptReqXml($param);
		$url = ConfigUtil::get_val_by_key("uniorderUrl");
		$httputil = new HttpUtils();
		list ( $return_code, $return_content )  = $httputil->http_post_data($url, $reqXmlStr);
		$resData;
		$flag=XMLUtil::decryptResXml($return_content,$resData);
		if($flag){
			$_SESSION["resultData"]=$resData;
			header("location:../page/createOrderResult.php");
		}else{
			echo "验签失败";
			
		}
	}
}
error_reporting(0);
$m = new UnifiedOrder();
$m->execute()
?>