<div id="message1">


    <?php echo $this->Form->create('Type',array('id'=>'form_type','type'=>'file','class'=>'','method'=>'POST','autocomplete'=>'off','inputDefaults'=>array(

    'label'=>false,'div'=>false,'type'=>'text','required'=>false)))?>

    <?php echo __("Hi, please choose a type below:")?>
    <br><br>

    <?php $options_new = array(
    'Type1' => __('<span class="showDialog" data-id="dialog_1" style="color:blue" title="'.$tooltipoption1.'">Type1</span>'),
    'Type2' => __('<span class="showDialog" data-id="dialog_2" style="color:blue" title="'.$tooltipoption2.'">Type2</span>')
);?>

    <?php echo $this->Form->input('type', array('legend'=>false, 'type' => 'radio', 'options'=>$options_new,'before'=>'<label class="radio line notcheck">','after'=>'</label>' ,'separator'=>'</label><label class="radio line notcheck">'));?>
    <?php echo $this->Form->submit('Save', array('class' => 'btn btn-success', 'id' => 'savebtn'));?>

    <?php echo $this->Form->end();?>

    <?php 
    if (empty($selectedType)) {
        echo "Nothing Selected!";
    } 
    else {
        echo "Type Selected: ".$selectedType;
    }
    ?>
</div>

<style>
    .showDialog:hover{
        text-decoration: underline;
    }

    #message1 .radio{
        vertical-align: top;
        font-size: 13px;
    }

    .control-label{
        font-weight: bold;
    }

    .wrap {
        white-space: pre-wrap;
    }

    .ui-tooltip {
        padding: 0;
        opacity: 1;
    }

    .ui-tooltip-content {
        position: relative;
        padding: 1em;
    }

    .ui-tooltip-content::after {
        content: '';
        position: absolute;
        border-style: solid;
        display: block;
        width: 0;
        top: 18px;
        left: -10px;
        border-color: transparent #fff;
        border-width: 10px 10px 10px 0;
    }
</style>

<?php $this->start('script_own')?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

    // I find the tooltip options are just not working so I imported jqueryui again in order to make it work.

    $(document).ready(function(){

        $(".showDialog").tooltip({
            content: function() {
                return $(this).attr('title');
            },
            position: {my: 'left center', at: 'right+10 center'},
        });

        /*
        $(".dialog").dialog({
            autoOpen: false,
            dialogClass: 'ui-dialog-blue'
        });

        $(".showDialog").hover(
            function(){ var id = $(this).data('id'); $("#"+id).dialog('open'); },
            function(){ var id = $(this).data('id'); $("#"+id).dialog('close'); }
        );
        */
    })


</script>
<?php $this->end()?>