<?php

class Datadiff {

	// credentials
    private $accessToken;
    
    // oauth urls
    private $apiUrl = 'http://diff.datadiff.co/';
    
    public function __construct($client_id, $client_secret) {
    	$this->accessToken = base64_encode($client_id.':'.$client_secret);
    }

    public static function factory($client_id, $client_secret) {
        $instance = new Datadiff($client_id, $client_secret);
        return $instance;
    }

    public function commit($data, $collection, $cmd, $identifier = '_id', $meta = array()) {

    	$this->call('commit', 'POST', array(
    		'data'=>$data,
    		'meta_data' => $meta,
    		'collection' => $collection,
    		'identifier' => $identifier,
    		'cmd' => $cmd
    	));

    }

	private function call($endpoint, $type, $data=array()){

        $ch = curl_init();
        
        // Setup curl options
        $curl_options = array(
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_USERAGENT      => 'Appointedd'
        );
                
  
        $curlURL = $this->apiUrl.$endpoint;
        $curl_options += array(
            CURLOPT_HTTPHEADER => array(
                'Authorization: BASIC '.$this->accessToken,
                'Accept: application/json',
                'Content-Type: application/json',
            ),
            CURLOPT_HEADER => 0
        );
                                                        
        // type of request determines our headers
        switch(strtolower($type)){
        
            case 'post':
                $curl_options = $curl_options + array(
                    CURLOPT_POST        => 1,
                    CURLOPT_POSTFIELDS  => json_encode($data)
                );
            break;
                
            case 'put':
                $curl_options = $curl_options + array(
                    CURLOPT_CUSTOMREQUEST => 'PUT',
                    CURLOPT_POSTFIELDS  => json_encode($data)
                );
            break;
                         
            case 'delete':
                $curl_options = $curl_options + array(
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                    CURLOPT_POSTFIELDS  => $data
                );
            break;
                                                
            case 'get':
                $curlURL .= '?'.http_build_query($data);
                $curl_options = $curl_options + array();
            break;            
        }
                
        // add url
        $curl_options = $curl_options + array(
            CURLOPT_URL => $curlURL
        );
                                                                
        // Set curl options
        curl_setopt_array($ch, $curl_options);
        
        // Send the request
        $this->result = curl_exec($ch);
        
        // curl info
        $this->info = curl_getinfo($ch);
        
        // Close the connection
        curl_close($ch);
        
        return json_decode($this->result);
    }
}