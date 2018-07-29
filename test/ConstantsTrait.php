<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDBTest\Driver;

/**
 * Description of ConstantsTrait
 *
 * @author spawn
 */
Trait ConstantsTrait {
    // NOTE: It's safer to cast `Math.random()` to a string, to avoid differences
    // in "float interpretation" between languages (e.g. JavaScript and Python)
    private $API_PATH = 'http://localhost:9984/api/v1/';

    public function asset() { return [ message => rand() ]; }
    private $metaData = [ 'message' => 'metaDataMessage' ];

    private function alice() {
        return new Ed25519Keypair();
    }
    
    private function aliceCondition() {
        return Transaction::makeEd25519Condition($this->alice()->publicKey);
    }
    
    private function aliceOutput() {
        return Transaction::makeOutput($this->aliceCondition());
    }
    
    private function createTx() {
        return Transaction::makeCreateTransaction(
            $this->asset,
            $this->metaData,
            [$this->aliceOutput()],
            $this->alice()->publicKey
        );
    }
    
    private function transferTx() {
        return Transaction.makeTransferTransaction(
            [[ 'tx' => 'createTx', 'output_index' => 0 ]],
            [$this->aliceOutput()],
            $this->metaData
        );
    }

    private function bob() { return new Ed25519Keypair(); }
    private function bobCondition() { return Transaction::makeEd25519Condition($this->bob()->publicKey); }
    private function bobOutput() { return Transaction::makeOutput($this->bobCondition()); } //put your code here
}
