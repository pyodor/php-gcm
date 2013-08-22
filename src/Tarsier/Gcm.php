<?php namespace Tarsier;

use Guzzle\Http\Client;

class Gcm {

    private $client;
    private $response;
    private $apiKey;
    private $url = 'https://android.googleapis.com/';
    private $queue = array();

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

        $this->client->setUserAgent('pyodor/php-gcm/0.1.0', true);
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
        }
    }

    public function getResponse()
    {
        return $this->response->getBody(true);
    }
}
