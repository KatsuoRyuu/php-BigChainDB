<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb;

/**
 * Description of Module
 *
 * @author spawn
 */
class Module {
    //put your code here
    
    public function getConfig() {
        return include __DIR__ . '/../config/config.module.php';
    }
    
}
