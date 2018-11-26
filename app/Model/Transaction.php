<?php
class Transaction extends AppModel{
    public $belongsTo = array('Member');
    public $hasMany = array('TransactionItem' => array(
        'conditions' => array('TransactionItem.valid' => 1)
    ));
}