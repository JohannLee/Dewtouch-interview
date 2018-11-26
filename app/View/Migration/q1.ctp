<div>
	<div class="alert">
		<h3>Import Form</h3>
	</div>
    
    <?php
    echo $this->Form->create('MigrationFile',array('type'=>'file','class'=>'','method'=>'POST'));
    echo $this->Form->input('file', array('label' => 'File Upload', 'type' => 'file'));
    echo $this->Form->submit('Upload', array('class' => 'btn btn-primary', 'id' => 'uploadbtn'));
    echo $this->Form->end();
    ?>
</div>