<?php
namespace com\jdjr\pay\demo\action;
use com\jdjr\pay\demo\common\ConfigUtil;
use com\jdjr\pay\demo\common\HttpUtils;
use com\jdjr\pay\demo\common\XMLUtil;
include '../lib/ConfigUtil.php';
include '../lib/HttpUtils.php';
include '../lib/XMLUtil.php';
class QueryRefund{
	public function execute(){
		$param["version"]=$_POST["version"];
		$param["merchant"]=$_POST["merchantNum"];
		$param["oTradeNum"]=$_POST["oTradeNum"];
		$param["tradeType"]=$_POST["tradeType"];
		
		$queryRefundUrl=ConfigUtil::get_val_by_key("queryRefundUrl");
		$reqXmlStr = XMLUtil::encryptReqXml($param);
		//echo $reqXmlStr."<br/>";
		$httputil = new HttpUtils();
		list ( $return_code, $return_content )  = $httputil->http_post_data($queryRefundUrl, $reqXmlStr);
		echo $return_content."<br/>";
		$resData1;
		$flag=XMLUtil::decryptResXml($return_content,$resData1);
		if($flag){
			$_SESSION["rresp"]=$resData1;
			$htmlStr="";
			$htmlStr="";
			$htmlStr=$htmlStr."<br /><lable>版本号:</lable>";
			$htmlStr=$htmlStr."<lable>".$resData1['version']."</lable><br />";
			$htmlStr=$htmlStr."<lable>商户号:</lable>";
			$htmlStr=$htmlStr."<lable >".$resData1['merchant']."</lable><br />";
			$htmlStr=$htmlStr."<lable>交易返回码:</lable>";
			$htmlStr=$htmlStr."<lable>".$resData1['result']['code']."</lable><br />";
			$htmlStr=$htmlStr."<lable>交易返回描述:</lable>";
			$htmlStr=$htmlStr."<lable>".$resData1['result']['desc']."</lable><br />";

			$refArray = $resData1['refList'];
			echo count($refArray);
			foreach ($refArray as $value){
				$htmlStr=$htmlStr."<lable>原流水号:</lable>";
				$htmlStr=$htmlStr."<lable >".$value['tradeNum']."</lable><br />";
				
				$htmlStr=$htmlStr."<lable>流水号:</lable>";
				$htmlStr=$htmlStr."<lable >".$value['oTradeNum']."</lable><br />";
				
				$htmlStr=$htmlStr."<lable>金额:</lable>";
				$htmlStr=$htmlStr."<lable >".$value['amount']."</lable><br />";
				
				$htmlStr=$htmlStr."<lable>交易时间:</lable>";
				$htmlStr=$htmlStr."<lable >".$value['tradeTime']."</lable><br />";
				
				$htmlStr=$htmlStr."<lable>状态:</lable>";
				$htmlStr=$htmlStr."<lable >".$value['status']."</lable><br />";
			}
			echo $htmlStr;
			$_SESSION['subhtml']=$htmlStr;
			header("location:../page/queryNewFefundResult.php");
		}else{
			echo "验签失败";
		}
	}
}
error_reporting(0);
$m = new QueryRefund();
$m->execute()
?>