<?php
$method = "GET";
$data = false;
$url = "https://uc-ccx-pub.tele.iastate.edu:8445/finesse/api/User/sc48t5";
$curl = curl_init();

switch ($method)
{
	case "POST":
		curl_setopt($curl, CURLOPT_POST, 1);
		if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		break;
	case "PUT":
		curl_setopt($curl, CURLOPT_PUT, 1);
		break;
	default:
		if ($data)
			$url = sprintf("%s?%s", $url, http_build_query($data));
}

// Authentication:
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_USERPWD, "sc48t5:489336");

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($curl);

curl_close($curl);

$xml = new SimpleXMLElement($result);
echo "<pre>";
print_r($xml);
echo "<pre>";

echo $xml->firstName." ".$xml->lastName;

include('functions.php');
echo "<br>";
$hex = "#adc1ff";
list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
echo "$hex -> rgba($r,$g,$b,0.3)";
?>