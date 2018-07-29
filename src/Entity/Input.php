<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDB\Entity;

use KryuuCommon\BigChainDB\Entity\Fulfill;
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
