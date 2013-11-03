<?php namespace Tarsier;

use Guzzle\Http\Client;

class Gcm {

    private $client;
    private $response;
    private $apiKey;
    private $url = 'https://android.googleapis.com/';
    private $queue = array();
    private $responses = array();

    public function __construct($googleApiKey)
    {
        $this->apiKey = $googleApiKey;
        
        $headers = array(
            'Authorization' => 'key='.$this->apiKey,
        );

        $this->client = new Client($this->url, array(
            'request.options' => array(
                'headers' => $headers
            )
        ));

        $this->client->setUserAgent('pyodor/php-gcm/0.2.0', true);
    }


    public function sendNotification($registrationIds, $message)
    {
	$fields = array(
            'registration_ids' => $registrationIds,
            'data' => $message,
        );

        $this->send($fields);   
    }

    private function send($fields) 
    {
        $request = $this->jsonify($fields);
        $this->response = $request->send();
    }

    private function jsonify($fields)
    {
        return $this->client->post(
            array('/gcm/send', $fields),
            array('Content-Type' => 'application/json; charset=utf-8'),
            json_encode($fields)
        );
    }

    public function add($registrationId, $message) {
        $this->queue[] = array(
            'registration_ids' => $registrationId,
            'data' => $message,
        );
    }

    public function sendQueue() {
        foreach($this->queue as $q) {
            $this->send($q);
            $regId = $q['registration_ids'];
            $res = $this->decodedResponse();
            $res['registration_id'] = $regId;
            $this->responses[] = $res;
        }
    }

    public function getResponse()
    {
        if(!empty($this->responses)) 
        {
            return $this->responses;
        }

        return $this->decodedResponse(); 
    }

    private function decodedResponse()
    {
        return json_decode($this->response->getBody(true), true);
    }
}
