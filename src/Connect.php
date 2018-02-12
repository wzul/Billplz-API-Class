<?php

namespace Billplz;

class Connect
{
    private $api_key;
    private $x_signature_key;
    private $collection_id;

    private $process; //cURL or GuzzleHttp
    public $is_staging;
    public $url;

    public $header;

    const TIMEOUT = 10; //10 Seconds
    const PRODUCTION_URL = 'https://www.billplz.com/api/';
    const STAGING_URL = 'https://billplz-staging.herokuapp.com/api/';

    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;


        if (\class_exists('\GuzzleHttp\Client') && \class_exists('\GuzzleHttp\Exception\ClientException')) {
            $this->process = new \GuzzleHttp\Client();
            $this->header = array(
                'auth' => [$this->api_key, ''],
                'verify' => false
            );
        } else {
            $this->process = curl_init();
            $this->header = $api_key . ':';
            curl_setopt($this->process, CURLOPT_HEADER, 0);
            curl_setopt($this->process, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->process, CURLOPT_TIMEOUT, self::TIMEOUT);
            curl_setopt($this->process, CURLOPT_USERPWD, $this->header);
        }
    }

    public function setMode(bool $is_staging = false)
    {
        $this->is_staging = $is_staging;
        if ($is_staging) {
            $this->url = self::PRODUCTION_URL;
        } else {
            $this->url = self::STAGING_URL;
        }
    }

    public function detectMode()
    {
        $this->url = self::PRODUCTION_URL;
        $collection = $this->toArray($this->getCollectionIndex());
        if ($collection[0] === 200) {
            $this->is_staging = false;
            return $this;
        }
        $this->url = self::STAGING_URL;
        $collection = $this->toArray($this->getCollectionIndex());
        if ($collection[0] === 200) {
            $this->is_staging = true;
            return $this;
        }
        throw new Exception('The API Key is not valid. Check your API Key');
    }

    public function getCollectionIndex(array $parameter = array())
    {
        $url = $this->url . 'v4/collections';

        if (!empty($parameter)) {
            $url.='?page='.$parameter['page'].'&status='.$parameter['status'];
        }

        if ($this->process instanceof \GuzzleHttp\Client) {
            $return = $this->guzzleProccessRequest('GET', $url, $this->header);
        } else {
            curl_setopt($this->process, CURLOPT_URL, $url);
            $body = curl_exec($this->process);
            $header = curl_getinfo($this->process, CURLINFO_HTTP_CODE);
            $return = array($header,$body);
        }
        return $return;
    }

    public function createCollectionArray(array $parameter)
    {
        $url = $this->url . 'v4/collections';

        $return_array = array();

        foreach ($parameter as $title) {
            $data = ['title' => $title];

            if ($this->process instanceof \GuzzleHttp\Client) {
                $header = $this->header;
                $header['form_params'] = $data;
                $return = $this->guzzleProccessRequest('POST', $url, $header);
            } else {
                curl_setopt($this->process, CURLOPT_URL, $url);
                curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));
                $body = curl_exec($this->process);
                $header = curl_getinfo($this->process, CURLINFO_HTTP_CODE);
                $return = array($header,$body);
            }
            array_push($return_array, $return);
        }

        return $return_array;
    }

    public function createCollection(string $title)
    {
        $url = $this->url . 'v4/collections';

        $data = ['title' => $title];

        if ($this->process instanceof \GuzzleHttp\Client) {
            $header = $this->header;
            $header['form_params'] = $data;
            $return = $this->guzzleProccessRequest('POST', $url, $header);
        } else {
            curl_setopt($this->process, CURLOPT_URL, $url);
            curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));
            $body = curl_exec($this->process);
            $header = curl_getinfo($this->process, CURLINFO_HTTP_CODE);
            $return = array($header,$body);
        }

        return $return;
    }

    private function guzzleProccessRequest($requestType, $url, $header)
    {
        try {
            $response = $this->process->request($requestType, $url, $header);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
        } finally {
            $return = $response->getBody()->getContents();
        }
        return array($response->getStatusCode(),$return);
    }

    public function closeConnection()
    {
        if ($this->process instanceof \GuzzleHttp\Client) {
            // Do nothing
        } else {
            curl_close($this->process);
        }
    }

    public function toArray(array $json)
    {
        return array($json[0], \json_decode($json[1], true));
    }
}
