<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace KryuuCommon\BigChainDb\Entity;

use KryuuCommon\BigChainDb\Entity\Asset;
use KryuuCommon\BigChainDb\Entity\Input;
use KryuuCommon\BigChainDb\Entity\Metadata;
use KryuuCommon\BigChainDb\Entity\Output;

/**
 * Description of Transaction
 *
 * @author spawn
 */
class Transaction {
   
    /**
     *
     * @var type 
     */
    private $id = null;
    
    /**
     * @var Asset
     */
    private $asset = null;
    
    /**
     * @var string
     */
    private $id = null;
    
    /**
     * @var Input[]
     */
    private $inputs = [];
    
    /**
     *
     * @var Metadata
     */
    private $metadata = null;
    
    /**
     *
     * @var string 
     */
    private $operation = null;
    
    /**
     *
     * @var Output[]
     */
    private $outputs = [];
    
    /**
     *
     * @var string
     */
    private $version = '2.0';

}
