<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb\Driver;


class Connection {
    
    const HEADER_BLACKLIST = ['content-type'];
    
    private $endpoints = [
        'blocks' => 'blocks',
        'blocksDetail' => 'blocks/%(blockHeight)s',
        'outputs' => 'outputs',
        'transactions' => 'transactions',
        'transactionsSync' => 'transactions?mode=sync',
        'transactionsAsync' => 'transactions?mode=async',
        'transactionsCommit' => 'transactions?mode=commit',
        'transactionsDetail' => 'transactions/%(transactionId)s',
        'assets' => 'assets',
        'metadata' => 'metadata',
        'votes' => 'votes'
    ];
    
    private $headers = [];
    private $path = null;
    
    public function __construct($path, $headers = []) {
        $this->path = $path;
        $this->headers = $headers;
        
        foreach(self::HEADER_BLACKLIST as $header) {
            if ( in_array($header, $this->headers) 
              || array_key_exists($header, $this->headers))
            {
                throw new \Exception(
                    sprintf('Header %s is reserved and cannot be set', $header));
            }
        }
    }
    
    public function getApiUrls($endpoint) {
        return $this->path . $this->endpoints[$endpoint];
    }
    
    private function req($path, $options = []) {
        if (!array_key_exists($headers, $options)) {
            $headers = [];
        } else {
            $headers = $options->headers;
        }
        $options['headers'] = array_merge($this->headers, $headers);
        return request($path, $options);
    }
    
    /**
     * @param blockHeight
     */
    public function getBlock($blockHeight) {
        return $this->req($this->getApiUrls('blocksDetail'), [
            'urlTemplateSpec' => [
                'blockHeight'
            ]
        ]);
    }

    /**
     * @param transactionId
     */
    public function getTransaction($transactionId) {
        return $this->req($this->getApiUrls('transactionDetail'), [
            'urlTemplateSpec' => [
                'transactionId'
            ]
        ]);
    }

    /**
     * @param transactionId
     * @param status
     */
    public function listBlocks($transactionId) {
        return $this->req($this->getApiUrls('blocks'), [
            'query' => [
                'transaction_id' => $transactionId,
            ]
        ]);
    }

    /**
     * @param publicKey
     * @param spent
     */
    public function listOutputs($publicKey, $spent) {
        $query = [
            'public_key' => $publicKey
        ];
        // NOTE: If `spent` is not defined, it must not be included in the
        // query parameters.
        if ($spent) {
            $query['spent'] = (string) $spent;
        }
        return $this->req($this->getApiUrls('outputs'), $query);
    }

    /**
     * @param assetId
     * @param operation
     */
    public function listTransactions($assetId, $operation) {
        return $this->req($this->getApiUrls('transactions'), array_merge([
            'query' => [
                'asset_id' => $assetId,
            ]
        ], $operation));
    }

    /**
     * @param blockId
     */
    public function listVotes($blockId) {
        return $this->req($this->getApiUrls('votes'), [
            'query' => [
                'block_id' => $blockId
            ]
        ]);
    }

    /**
     * @param transaction
     */
    public function postTransaction($transaction) {
        return $this.postTransactionCommit($transaction);
    }

    /**
     * @param transaction
     */
    public function postTransactionSync($transaction) {
        return $this->req($this->getApiUrls('transactionsSync'), [
            'method' => 'POST',
            'jsonBody' => $transaction
        ]);
    }

    /**
     * @param transaction
     */
    public function postTransactionAsync($transaction) {
        return $this->req($this->getApiUrls('transactionsAsync'), [
            'method' => 'POST',
            'jsonBody' => $transaction
        ]);
    }

    /**
     * @param transaction
     */
    public function postTransactionCommit($transaction) {
        return $this->req($this->getApiUrls('transactionsCommit'), [
            'method' => 'POST',
            'jsonBody' => $transaction
        ]);
    }

    /**
     * @param search
     */
    public function searchAssets($search) {
        return $this->req($this->getApiUrls('assets'), [
            'query' => [
                $search
            ]
        ]);
    }

    /**
     * @param search
     */
    public function searchMetadata($search) {
        return $this->req($this->getApiUrls('metadata'), [
            'query' => [
                $search
            ]
        ]);
    }
}