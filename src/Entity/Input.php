<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb\Entity;

use KryuuCommon\BigChainDb\Entity\Fulfill;
/**
 * Description of Input
 *
 * @author spawn
 */
class Input {
    
    /**
     *
     * @var string
     */
    private $fullfillment = null;
    
    
    /**
     *
     * @var fulfill
     */
    private $fulfills = null;
    
    /**
     *
     * @var string[]
     */
    private $ownersBefore = [];
}
