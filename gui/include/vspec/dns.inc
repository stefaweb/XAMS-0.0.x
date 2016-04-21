<?php

include_once 'include/vspec/const.inc';

$this->VSPEC = array(
    'id' => array(
        'type' => Tpid
    ),
    'name' => array(
        'type' => Talphanumeric,
        'minl' => 4,
        'maxl' => 150,
        'empty' => false
    ),
    'zonetype' => array(
        'type' => Talphanumeric,
        'minl' => 1,
        'maxl' => 1,
        'empty' => false
    ),
    'masterdns' => array(
        'type' => Talphanumeric,
        'minl' => 4,
        'maxl' => 150,
        'empty' => false 
    ),
    'zoneadmin' => array(
        'type' => Talphanumeric,
        'minl' => 6,
        'maxl' => 150,
        'empty' => false 
    ),
    'serial' => array(
        'type' => Tnumeric,
        'min' => 1,
        'maxl' => 10,
        'init' => date('Ymd01'),
        'empty' => true
    ),
    'serialautomatic' => array(
        'type' => Tcheckbox,
        'init' => 'true'
    ),
    'ttl' => array(
        'type' => Tnumeric,
        'min' => 1,
        'init' => '86400',
        'empty' => false
    ),
    'refresh' => array(
        'type' => Tnumeric,
        'min' => 1,
        'init' => '10800',
        'empty' => false
    ),
    'retry' => array(
        'type' => Tnumeric,
        'min' => 1,
        'init' => '3600',
        'empty' => false
    ),
    'expire' => array(
        'type' => Tnumeric,
        'min' => 1,
        'init' => '604800',
        'empty' => false
    ),
    'nttl' => array(
        'type' => Tnumeric,
        'min' => 1,
        'init' => '86400',
        'empty' => false
    ),
    'changed' => array(
        'type' => Talphanumeric,
        'init' => 'false'
    ),
    'added' => array(
        'type' => Tinsertdate
    )
);
        
?>