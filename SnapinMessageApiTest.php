<?php
/**
 * SnapinApiMessage
 * PHP version 5
 *
 * @category Class
 * @author   Snapinlab team
 * @link     https://github.com/ibnu/snapinMessage
 */
 
/**
 * SnapinMessage
 *
 * This is a sample for send message via sms to snapin server.  
 * For this sample, you can use the api identity and privatekey to test the authorization.
 * Please update the test case below to test the endpoint.
 * version: 1.0.0
 * Contact: ibnu.munandar@snapinlab.com
 *
 */

if (!function_exists('curl_init')) {
	die('Curl module not installed!' . PHP_EOL); }

$api = new snapinMessage();
$api->setHost('<HOST>'); // API endpoint
$api->setApiId('<API_ID>'); // API_ID that has been registered by SNAPIN
$api->setPrivateKey('<API_PRIVATEKEY>'); // API_PRIVATEKEY that has been registered by SNAPIN
$api->setSid('<SID>'); // SID that has been registered by SNAPIN
$api->setTo('<TO>'); // example format 62816123456
$api->setMsg('<MSG>'); // content msg max 5 segment (760 char)
$api->sendSMS();

class snapinMessage {
	protected $host;
	protected $privateKey;
	protected $api_id;
	protected $sid;
	protected $to;
	protected $msg;
	
	
	public function setHost($host){
		return $this->host = $host;
	}
	
	public function setApiId($apiId){
		return $this->apiId = $apiId;
	}
	
	public function setPrivateKey($privateKey){
		return $this->privateKey = $privateKey;
	}
	
	public function setSid($sid){
		return $this->sid = $sid;
	}
	
	public function setTo($to){
		return $this->to = $to;
	}
	
	public function setMsg($msg){
		return $this->msg = $msg;
	}
	
	public function sendSms(){
		$time = time();
		
		$data = array( 
					'to' => $this->to, 
					'sid' => $this->sid, 
					'msg' => $this->msg
		);

		$message = $time . $this->apiId . http_build_query($data, '', '&');
		
		$hash = hash_hmac('sha256', $message, $this->privateKey);
		$headers = ['API_ID: ' . $this->apiId, 'API_TIME: ' . $time, 'API_HASH: ' . $hash];
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
		curl_setopt($ch, CURLOPT_URL, $this->host);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
		
		$result = curl_exec($ch);
		
		echo "<pre>";
		
		if ($result === FALSE) {
			echo "Curl Error: " . curl_error($ch);
		} else {
			echo PHP_EOL;
			echo "Request: " . PHP_EOL;
			echo curl_getinfo($ch, CURLINFO_HEADER_OUT);	
			echo PHP_EOL;
		
			echo "Response:" . PHP_EOL;
			echo $result; 
			echo PHP_EOL;
		}
		
		curl_close($ch);		
	}
}
?>
