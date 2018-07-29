<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace KryuuCommon\BigChainDB\Entity;

use KryuuCommon\BigChainDB\Entity\Asset;
use KryuuCommon\BigChainDB\Entity\Input;
use KryuuCommon\BigChainDB\Entity\Metadata;
use KryuuCommon\BigChainDB\Entity\Output;

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
