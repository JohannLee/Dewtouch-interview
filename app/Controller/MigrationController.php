<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require ROOT.DS.'vendors'.DS.'phpoffice/phpspreadsheet/src/Bootstrap.php';

class MigrationController extends AppController{

    public function q1(){

        $this->setFlash('Question: Migration of data to multiple DB table');

        $this->loadModel('Member');
        $this->loadModel('Transaction');
        $this->loadModel('TransactionItem');
        if ($this->request->is('POST')){
            if (!empty($this->request->data)){
                if(!empty($this->request->data['MigrationFile']['file']['name'])) {
                    $file = $this->request->data['MigrationFile']['file'];

                    if (is_file($file['tmp_name'])){
                        // get the last id of Member and Transaction
                        $lastMember = $this->Member->find('first', array('order' => array('Member.id' => 'DESC'), 'recursive' => -1))['Member']['id'];
                        $currMemberID = $lastMember;
                        $lastTransaction = $this->Transaction->find('first', array('order' => array('Transaction.id' => 'DESC'), 'recursive' => -1))['Transaction']['id'];
                        $currTransactionID = $lastTransaction;

                        // initialize array
                        $member_to_save = array();
                        $transaction_to_save = array();
                        $transaction_item_to_save = array();

                        // check extension
                        $extension = substr(strtolower(strrchr($file['name'], '.')), 1);
                        if ($extension == 'xlsx') {
                            $reader = IOFactory::createReader("Xlsx");
                            $spreadsheet = $reader->load($file['tmp_name']);
                            $sheetdata = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
                            $total_item = count($sheetdata);

                            for ($i = 2; $i <= $total_item; $i++) {
                                $memberNo = explode(' ', $sheetdata[$i]['D']);

                                $member_type = $memberNo[0];
                                $member_no = $memberNo[1];
                                $membername = $sheetdata[$i]['C'];
                                //check if current member exists (assuming member name and member number combination is unique)
                                $curr_member = $this->Member->find('first', array(
                                    'conditions' => array(
                                        'Member.type' => $member_type,
                                        'Member.no' => $member_no,
                                        'Member.name' => $membername,
                                    ),
                                    'recursive' => -1,
                                ));
                                if (empty($curr_member)) {
                                    $member_to_save[$i]['type'] = $member_type;
                                    $member_to_save[$i]['no'] = $member_no;
                                    $member_to_save[$i]['name'] = $membername;
                                    $member_to_save[$i]['company'] = $sheetdata[$i]['F'];

                                    $lastMember++;
                                    $currMemberID = $lastMember;
                                }
                                else {
                                    $currMemberID = $curr_member['Member']['id'];
                                }

                                $receipt_no = $sheetdata[$i]['I'];
                                //check if current transaction exists (assuming receipt number is unique)
                                $curr_transaction = $this->Transaction->find('first', array(
                                    'conditions' => array(
                                        'Transaction.receipt_no' => $receipt_no,
                                    ),
                                    'recursive' => -1,
                                ));
                                if (empty($curr_transaction)) {
                                    $transaction_to_save[$i]['member_id'] = $currMemberID;
                                    $transaction_to_save[$i]['member_name'] = $sheetdata[$i]['C'];
                                    $transaction_to_save[$i]['member_paytype'] = $sheetdata[$i]['E'];
                                    $transaction_to_save[$i]['member_company'] = $sheetdata[$i]['F'];

                                    $date = date('Y-m-d' ,strtotime($sheetdata[$i]['A']));
                                    $transaction_to_save[$i]['date'] = $date;
                                    $date_array = explode('/', $sheetdata[$i]['A']);
                                    $transaction_to_save[$i]['year'] = $date_array[2];
                                    $transaction_to_save[$i]['month'] = $date_array[0];

                                    $transaction_to_save[$i]['ref_no'] = $sheetdata[$i]['B'];
                                    $transaction_to_save[$i]['receipt_no'] = $receipt_no;
                                    $transaction_to_save[$i]['payment_method'] = $sheetdata[$i]['G'];
                                    $transaction_to_save[$i]['batch_no'] = $sheetdata[$i]['H'];
                                    $transaction_to_save[$i]['cheque_no'] = $sheetdata[$i]['J'];
                                    $transaction_to_save[$i]['payment_type'] = $sheetdata[$i]['K'];

                                    $transaction_to_save[$i]['subtotal'] = $sheetdata[$i]['M'];
                                    $transaction_to_save[$i]['tax'] = $sheetdata[$i]['N'];
                                    $transaction_to_save[$i]['total'] = $sheetdata[$i]['O']; 

                                    $lastTransaction++;
                                    $currTransactionID = $lastTransaction;
                                }
                                else {
                                    $currTransactionID = $curr_transaction['Transaction']['id'];
                                }

                                //check if current transaction id exists (item already written to database)
                                $curr_transaction_item = $this->TransactionItem->find('first', array(
                                    'conditions' => array(
                                        'TransactionItem.transaction_id' => $currTransactionID,
                                    ),
                                    'recursive' => -1,
                                ));
                                if (empty($curr_transaction_item)) {
                                    $transaction_item_to_save[$i]['transaction_id'] = $currTransactionID;

                                    $transaction_item_to_save[$i]['description'] = "Being Pament for:".$sheetdata[$i]['K'];
                                    $transaction_item_to_save[$i]['quantity'] = 1;
                                    $transaction_item_to_save[$i]['unit_price'] = $sheetdata[$i]['M'];
                                    $transaction_item_to_save[$i]['sum'] = $transaction_item_to_save[$i]['quantity'] * $transaction_item_to_save[$i]['unit_price'];

                                    $transaction_item_to_save[$i]['table'] = "Member";
                                    $transaction_item_to_save[$i]['table_id'] = $currMemberID;;
                                }
                                else {

                                }
                            }

                            if (empty($member_to_save)){
                                //print_r('No Member added!');  //debug use
                            } else {
                                if ($this->Member->saveAll($member_to_save)){
                                    //print_r(count($member_to_save).' Members added!');  //debug use
                                }
                                else {
                                    //print_r('No Member added!');  //debug use
                                }
                            }

                            if (empty($transaction_to_save)){
                                //print_r('No Transaction added!');
                            } else {
                                if ($this->Transaction->saveAll($transaction_to_save)){
                                    //print_r(count($transaction_to_save).' Transanctions added!');  //debug use
                                }
                                else {
                                    //print_r('No Transaction added!');  //debug use
                                }
                            }

                            if (empty($transaction_item_to_save)) {
                                //print_r('No Transaction Item added!');  //debug use
                            } else {
                                if ($this->TransactionItem->saveAll($transaction_item_to_save)){
                                    //print_r(count($transaction_item_to_save).' TransactionItems added!');  //debug use
                                }
                                else {
                                    //print_r('No Transaction Item added!');  //debug use
                                }
                            }

                        }
                    }
                    else {
                        $this->setFlash('File Extension should be .xlsx');
                    }
                }
            }
        }

        // 			$this->set('title',__('Question: Please change Pop Up to mouse over (soft click)'));
    }

    public function q1_instruction(){

        $this->setFlash('Question: Migration of data to multiple DB table');



        // 			$this->set('title',__('Question: Please change Pop Up to mouse over (soft click)'));
    }

}