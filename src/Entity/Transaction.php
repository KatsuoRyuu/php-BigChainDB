<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb\Entity;

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
     *
     * @var type 
     */
    private $operation = null;
    
    /**
     *
     * @var type 
     */
    private $outputs = [];
    
    /**
     *
     * @var type 
     */
    private $inputs = [];
    
    /**
     *
     * @var type 
     */
    private $metadata = null;
    
    /**
     *
     * @var type 
     */
    private $asset = null;
    
    /**
     *
     * @var type 
     */
    private $version = '2.0';
    
    /**
     *
     * @var type 
     */
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setOperation($operation) {
        $this->operation = $operation;
        return $this;
    }
    
    public function setOutputs($outputs) {
        $this->outputs = $outputs;
        return $this;
    }
    
    public function setInputs($inputs) {
        $this->inputs = $inputs;
        return $this;
    }
    
    public function setMetadata($metadata) {
        $this->metadata = $metadata;
        return $this;
    }
    
    public function setAsset($asset) {
        $this->asset = $asset;
        return $this;
    }
    
    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getOperation() {
        return $this->opertaion;
    }
    
    public function getOutputs() {
        return $this->outputs;
    }
    
    public function getInputs() {
        return $this->inputs;
    }
    
    public function getMetadata() {
        return $this->metadata;
    }
    
    public function getAsset() {
        return $this->asset;
    }
    
    public function getVersion() {
        return $this->version;
    }
    
}
