<?php

include_once 'include/vspec/const.inc';

$this->VSPEC = array(
    'id' => array(
        'type' => Tpid
    ),
    'name' => array(
        'type' => Talphanumeric,
        'minl' => 2,
        'maxl' => 100,
        'empty' => false
    ),
    'password' => array(
        'type' => Tpassword,
        'minl' => 4,
        'maxl' => 100,
        'empty' => false
    ),
    'locked' => array(
        'type' => Tcheckbox
    ),
    'added' => array(
        'type' => Tinsertdate
    )
);
        
?>