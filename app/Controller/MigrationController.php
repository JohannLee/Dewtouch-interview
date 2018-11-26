<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require ROOT.DS.'vendors'.DS.'phpoffice/phpspreadsheet/src/Bootstrap.php';

class MigrationController extends AppController{

    public function q1(){

        $this->setFlash('Question: Migration of data to multiple DB table');

        echo "<pre>";

        $this->loadModel('Member');
        $this->loadModel('Transaction');
        $this->loadModel('TransactionItem');
        if ($this->request->is('POST')){
            if (!empty($this->request->data)){
                if(!empty($this->request->data['MigrationFile']['file']['name'])) {
                    $file = $this->request->data['MigrationFile']['file'];

                    if (is_file($file['tmp_name'])){
                        // check extension
                        $extension = substr(strtolower(strrchr($file['name'], '.')), 1);
                        // get the last id of Member and Transaction
                        $lastMember = $this->Member->find('first', array('order' => array('Member.id' => 'DESC'), 'recursive' => -1))['Member']['id'];
                        $currMemberID = $lastMember;
                        $lastTransaction = $this->Transaction->find('first', array('order' => array('Transaction.id' => 'DESC'), 'recursive' => -1))['Transaction']['id'];
                        $currTransactionID = $lastTransaction;

                        // initialize array
                        $member_to_save = array();
                        $transaction_to_save = array();
                        $transaction_item_to_save = array();

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
                                    $member_to_save[$i]['type'] = $membertype;
                                    $member_to_save[$i]['no'] = $memberno;
                                    $member_to_save[$i]['name'] = $membername;
                                    $member_to_save[$i]['company'] = $sheetdata[$i]['F'];
                                    
                                    $lastMember++;
                                    $currMemberID = $lastMember;
                                }
                                else {
                                    $currMemberID = $curr_member['id'];
                                    //print_r($curr_member);
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
                                    $transaction_to_save[$i]['member_id'] = 0;
                                    $transaction_to_save[$i]['member_name'] = $sheetdata[$i]['C'];
                                    $transaction_to_save[$i]['member_paytype'] = $sheetdata[$i]['E'];
                                    $transaction_to_save[$i]['member_company'] = $sheetdata[$i]['F'];

                                    $date = date('Y-m-d' ,strtotime($sheetdata[$i]['A']));
                                    $transaction_to_save[$i]['date'] = $date;
                                    $date_array = explode('/', $sheetdata[$i]['A']);
                                    $transaction_to_save[$i]['year'] = $date_array[0];
                                    $transaction_to_save[$i]['month'] = $date_array[1];

                                    $transaction_to_save[$i]['ref_no'] = $sheetdata[$i]['B'];
                                    $transaction_to_save[$i]['receipt_no'] = $receipt_no;
                                    $transaction_to_save[$i]['payment_method'] = $sheetdata[$i]['G'];
                                    $transaction_to_save[$i]['batch_no'] = $sheetdata[$i]['H'];
                                    $transaction_to_save[$i]['cheque_no'] = $sheetdata[$i]['J'];
                                    $transaction_to_save[$i]['payment_type'] = $sheetdata[$i]['K'];

                                    $transaction_to_save[$i]['subtotal'] = $sheetdata[$i]['M'];
                                    $transaction_to_save[$i]['tax'] = $sheetdata[$i]['N'];
                                    $transaction_to_save[$i]['total'] = $sheetdata[$i]['O'];

                                    
                                }
                                
                                /*
                                    // get transactionid for transaction

                                    $transaction_item_to_save[$i]['transaction_id'] = 0;

                                    $transaction_item_to_save[$i]['description'] = "Being Pament for:".$sheetdata[$i]['K'];
                                    $transaction_item_to_save[$i]['quantity'] = 1;
                                    $transaction_item_to_save[$i]['unit_price'] = $sheetdata[$i]['M'];
                                    $transaction_item_to_save[$i]['sum'] = $transaction_item_to_save[$i]['quantity'] * $transaction_item_to_save[$i]['unit_price'];

                                    $transaction_item_to_save[$i]['table'] = "Member";
                                    $transaction_item_to_save[$i]['table_id'] = 0;;

                                    */
                            }
                            
                            //print_r($member_to_save);
                            //print_r($transaction_to_save);
                            //print_r($transaction_item_to_save);
                        }
                    }
                    else {
                        $this->setFlash('File Extension should be .csv');
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