<?php
	class FormatController extends AppController{
		
		public function q1(){
			$this->setFlash('Question: Please change Pop Up to mouse over (soft click)');
            
            $this->set('tooltipoption1', '<span style=\'display:inline-block\'>
                        <ul>
                            <li>Description .......</li>
 				           <li>Description 2</li>
                        </ul>
                    </span>');
            $this->set('tooltipoption2', '<span style=\'display:inline-block\'>
                        <ul>
                            <li>Desc 1 .....</li>
 				           <li>Desc 2...</li>
                        </ul>
                    </span>');
				
			
//            $this->set('title',__('Question: Please change Pop Up to mouse over (soft click)'));
                
            if ($this->request->is('POST')){
                $this->set('selectedType', $this->request->data['Type']['type']);
            }
		}
		
		public function q1_detail(){

			$this->setFlash('Question: Please change Pop Up to mouse over (soft click)');
				
			
			
// 			$this->set('title',__('Question: Please change Pop Up to mouse over (soft click)'));
		}
		
	}