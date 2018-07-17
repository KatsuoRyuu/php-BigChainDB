<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb;

use KryuuCommon\BigChainDb\Driver\Transaction;

/**
 * Description of Driver
 *
 * @author spawn
 */
class Driver {
    
    private $transaction = null;
    //put your code here
    
    public function getTransaction() {
        if (!$this->transaction) {
            $this->transaction = new Transaction();
        }
        
        return $this->transaction;
    }
}
