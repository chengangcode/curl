<?php

function MakeSign($data_sgin,$key)
{
	//签名步骤一：按字典序排序参数
	ksort($data_sgin);
	$string = "";
	foreach ($data_sgin as $k => $v)
	{
		if($k != "sign"){
            $string .= $k . "=" . $v . "&";
		}
	}
	$string = trim($string, "&");

	//签名步骤二：在string后加入KEY
	$string = $string . "&key=".$key;
	//签名步骤三：MD5加密或者HMAC-SHA256
	$string = hash_hmac("sha256",$string ,$key);
	//签名步骤四：所有字符转为大写
	$result = strtoupper($string);
	return $result;
}