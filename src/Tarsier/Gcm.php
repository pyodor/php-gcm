<?php 

namespace Tarsier;

use Guzzle\Http\Client;

class Gcm
{
    private $client;
    private $response;
    private $apiKey;
    private $url = 'https://android.googleapis.com/';

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
        
        $request = $this->client->post('/gcm/send')
            ->addPostFields($fields);

        $this->response = $request->send();
    }

    public function getResponse()
    {
        return $this->response->getBody(true);
    }
}
