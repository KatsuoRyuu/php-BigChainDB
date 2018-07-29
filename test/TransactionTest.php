<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace KryuuCommon\BigChainDBTest\Driver;

use KryuuCommon\BigChainDB\Driver\Transaction;

/**
 * Description of TransactionTest
 *
 * @author spawn
 */
class TransactionTest {
    
    use ConstantsTrait;
    
    /**
     * @test
     * @testdox Create TRANSFER transaction based on CREATE transaction
     */
    public function testTransactionCreateTransfer() {
        $result = Transaction::makeTransferTransaction(
            [[ "tx" => "createTx", "output_index" => 0 ]],
            [$this->aliceOutput()],
            $this->metaData
        );
        
        print_r($result);
    }
}
