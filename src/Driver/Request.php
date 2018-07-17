<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb\Driver;

class Request {

    const DEFAULT_REQUEST_CONFIG = [
        'headers' => [
            'Accept' => 'application/json'
        ]
    ];

    public function baseRequest($url, $jsonBody, $query, $urlTemplateSpec, $fetchConfig) {
        $expandedUrl = $url;

        if ($urlTemplateSpec != null) {
            if (is_array($urlTemplateSpec) && count($urlTemplateSpec)) {
                // Use vsprintf for the array call signature
                $expandedUrl = vsprintf($url, $urlTemplateSpec);
            } else if ($urlTemplateSpec &&
                is_array($urlTemplateSpec) &&
                count($urlTemplateSpec)
            ) {
                $expandedUrl = format::filter($url, $urlTemplateSpec);
            }
        }

        if ($query != null) {
            if ($query === 'string') {
                $expandedUrl .= $query;
            } else if ($query && is_array($query)) {
                $expandedUrl += stringifyAsQueryParam($query);
            } 
//            else if (process . env . NODE_ENV !== 'production') {
//                // eslint-disable-next-line no-console
//                console.warn('Supplied query was not a string or object. Ignoring...')
//            }
        }

        if ($jsonBody != null) {
            $fetchConfig->body = json_encode($jsonBody);
        }

//        return fetch.fetch($expandedUrl, $fetchConfig)
//        .then(($res) => {
//// If status is not a 2xx (based on Response.ok), assume it's an error
//// See https://developer.mozilla.org/en-US/docs/Web/API/GlobalFetch/fetch
//            if (!($res && res . ok)) {
//                throw new \Exception(sprintf(
//                        'HTTP Error: Requested page not reachable; '
//                        . "Status: %s (%s); RequestUri: %s",
//                        $res->status, $res->statusText, $res->url));
//            }
//        return $res;
//        })
    }

    public function request($url, $config) {
        
    }

    private function getRequestConfig($config) {
        
    }

}
