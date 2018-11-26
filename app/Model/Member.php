<?php
class Member extends AppModel{
    public $hasMany = array('Transaction' => array(
        'conditions' => array('Transaction.valid' => 1)
    ));
}