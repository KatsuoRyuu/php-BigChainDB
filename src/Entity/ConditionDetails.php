<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDB\Entity;

use KryuuCommon\BigChainDB\Entity\Condition;

/**
 * Description of ConditionDetails
 *
 * @author spawn
 */
class ConditionDetails {
    
    /**
     *
     * @var string
     */
    private $publicKey = null;
    
    /**
     *
     * @var string
     */
    private $type = null;
    
    /**
     *
     * @var Condition[]
     */
    private $subconditions = [];
}
