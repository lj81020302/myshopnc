<?php
namespace com\jdjr\pay\demo\action;

class Test{
	public function test($vv){
		echo $vv."\n";
		$param;
		$param["aaa"]="aaaaaaaa";
		$param["bbb"]=22222;
		$param["ccc"]=22.34567;
		var_dump($param);
	}
	
}


$tt = new Test();
$tt->test("ok");

?>