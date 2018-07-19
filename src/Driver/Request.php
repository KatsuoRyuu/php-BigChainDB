    <?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb\Driver;

use KryuuCommon\BigChainDb\Driver\BaseRequest;

/**
 * Description of Request
 *
 * @author spawn
 */
class Request extends BaseRequest {
    //put your code here
    public function request($url, $config = []) {
    // Load default fetch configuration and remove any falsy query parameters
    $requestConfig = Object.assign({}, DEFAULT_REQUEST_CONFIG, config, {
        query: config.query && sanitize(config.query)
    })
    const apiUrl = url

    if (requestConfig.jsonBody) {
        requestConfig.headers = Object.assign({}, requestConfig.headers, {
            'Content-Type': 'application/json'
        })
    }

    if (!url) {
        return Promise.reject(new Error('Request was not given a url.'))
    }

    return baseRequest(apiUrl, requestConfig)
        .then(res => res.json())
        .catch(err => {
            console.error(err)
            throw err
        })
}
}
