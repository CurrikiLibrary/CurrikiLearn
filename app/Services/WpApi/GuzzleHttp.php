<?php

namespace App\Services\WpApi;

use App\Contracts\Services\WpApiInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class GuzzleHttp implements WpApiInterface
{

    /**
     * GuzzleHttp Client
     * @var type
     */
    protected $client;

    /**
     * Create a new instance of user & interpreter.
     */
    public function __construct()
    {
        $options = [
            'http_errors' => false
        ];

        if (Auth::check()) {
            // Get the currently authenticated user...
            $user = Auth::user();

            $options['headers'] = [
                'Authorization' => 'Bearer ' . $user->token
            ];
        }

        $this->client = new Client($options); //GuzzleHttp\Client
    }

    /**
     * Request WordPress Api
     *
     * @param type $method
     * @param type $url
     * @param type $params
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $url, $params = [])
    {
        $url = env('CURRIKI_API_URL') . $url;

        try {
            if ($method == 'post')
                $response = $this->post($url, $params);
            elseif ($method == 'delete')
                $response = $this->delete($url);
        } catch (\Exception $ex) {
            return false;
        }

        return $response;
    }

    /**
     * Post Request WordPress Api
     *
     * @param type $url
     * @param type $params
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post($url, $params)
    {
        $response = $this->client->request('POST', $url, $params);

        return $response;
    }

    /**
     * Post Request WordPress Api
     *
     * @param type $url
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete($url)
    {
        $response = $this->client->delete($url);

        return $response;
    }
}