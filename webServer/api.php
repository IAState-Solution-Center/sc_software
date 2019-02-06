<?php
include('functions.php');
echo basename($_SERVER['PHP_SELF']);
$xmlRequest = simplexml_load_string("<PhoneBooks><PhoneBook><uri>/finesse/api/PhoneBook/1</uri></PhoneBook></PhoneBooks>");
function TestAPI($method, $data = false)
{
// 	$url = "https://uc-ccx-pub.tele.iastate.edu:8445/finesse/api/PhoneBook/1/Contacts/";
$url = "https://uc-ccx-pub.tele.iastate.edu:8445/finesse/api/User/sc55t3";
// 	$url = "https://uc-ccx-pub.tele.iastate.edu:8445/finesse/api/Team/5/PhoneBooks";
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "sc55t5:219756");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $info = curl_getinfo($curl);

    curl_close($curl);
	echo "<pre>";
	print_r($info);
	echo "<hr>";
// 	print_r($result);
	echo "</pre>";
    return $result;
}



echo "<pre>";
$xml = simplexml_load_string(TestAPI("GET"));
print_r($xml);
// print_r($xmlRequest);
echo "</pre>";
?>