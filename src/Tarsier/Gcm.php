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
    }


    public function sendNotification($registrationIds, $message)
    {
	    $fields = array(
            'registration_ids' => $registrationIds,
            'data' => $message,
        );
        $headers = array(
            'Authorization: key=' . $this->apiKey,
            'Content-Type: application/json'
        );

        $this->client = new Client($this->url, array(
            'request.options' => array(
                'headers' => $headers
            )
        ));

        $request = $this->client->post('/gcm/send')
            ->addPostFields($fields);

        $this->response = $request->send();
    }

    public function getResponse()
    {
        return $this->response;
    }
}
