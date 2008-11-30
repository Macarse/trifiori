<?php
// include the configuration and the class file
require_once('./tera_wurfl_config.php');
require_once(WURFL_CLASS_FILE);

// instantiate the class
$wurflObj = new tera_wurfl();

// get the capabilites from the user agent and take a look at the
// HTTP-ACCEPT headers in case the user agent is not found
$wurflObj->GetDeviceCapabilitiesFromAgent($_SERVER['HTTP_USER_AGENT'],true);
$cap = $wurflObj->capabilities;

// check if this device has an image associated with it
if($wurflObj->device_image != ""){
	$image = '<p>Here is an image of your device: <br/><img src="'.$wurflObj->device_image.'" /></p><br />';
}else{
	$image = "";
}

$devicename = $cap['product_info']['brand_name'] . " " . $cap['product_info']['model_name'];

// check if this device is mobile
if($cap['product_info']['is_wireless_device']){
	// this IS a mobile device, let's see if it likes basic XHTML-MP
	if($cap['markup']['html_wi_w3_xhtmlbasic']){
		header("Content-Type: application/vnd.wap.xhtml+xml");
		echo '<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><title>Tera-WURFL Demo</title></head><body>';
		echo "<p>This is XHTML-MP<br/>Your device: $devicename</p>$image";
		echo "<pre>".print_r($cap['product_info'],true)."</pre>";
		echo '</body></html>';
		exit;
	}else{ // looks like this device is old school - let's give it some wml
		header("Content-Type: text/vnd.wap.wml");
		echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org//DTD//wml_1.1.xml">
<wml><card>';
		echo "<p>This is WML<br/>Your device: $devicename</p>$image";
		echo "<pre>".print_r($cap['product_info'],true)."</pre>";
		echo '</card></wml>';
		exit;
	}
	
}else{
	// this is NOT a mobile device - give it some HTML
	echo "<html><head><title>Tera-WURFL Demo</title></head><body>";
	echo "<p>This is HTML (your device is NOT mobile)<br/>Your device: $devicename</p>$image";
	echo "<pre>".print_r($cap['product_info'],true)."</pre>";
	echo "</body></html>";
	exit;
}

?>