<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace KryuuCommon\BigChainDbTest\Driver;

use KryuuCommon\BigChainDb\Driver\Transaction;
use KryuuCommon\BigChainDbTest\Driver\ConstantsTrait;
use PHPUnit\Framework\TestCase;

/**
 * Description of TransactionTest
 *
 * @author spawn
 */
class TransactionTest extends TestCase {
    
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
