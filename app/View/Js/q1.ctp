<div class="alert  ">
    <button class="close" data-dismiss="alert"></button>
    Question: Advanced Input Field</div>

<p>
    1. Make the Description, Quantity, Unit price field as text at first. When user clicks the text, it changes to input field for use to edit. Refer to the following video.

</p>


<p>
    2. When user clicks the add button at left top of table, it wil auto insert a new row into the table with empty value. Pay attention to the input field name. For example the quantity field

    <?php echo htmlentities('<input name="data[1][quantity]" class="">')?> ,  you have to change the data[1][quantity] to other name such as data[2][quantity] or data["any other not used number"][quantity]

</p>



<div class="alert alert-success">
    <button class="close" data-dismiss="alert"></button>
    The table you start with</div>

<table id="table-appendible" class="table table-striped table-bordered table-hover">
    <thead>
        <th><span id="add_item_button" class="btn mini green addbutton" onclick="addToObj=false">
            <i class="icon-plus"></i></span></th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit Price</th>
    </thead>

    <tbody>
        <tr>
            <td><span class="btn mini deletebtn"><i class="icon-delete"></i></span></td>
            <td class="textarea-wrapper"><p name="data[1][description]" class="changable-description description required">12345</p></td>
            <td class="input-wrapper"><p name="data[1][quantity]" class="changable-quantity"></p></td>
            <td class="input-wrapper"><p name="data[1][unit_price]"  class="changable-unit-price"></p></td>

        </tr>

    </tbody>

</table>


<p></p>
<div class="alert alert-info ">
    <button class="close" data-dismiss="alert"></button>
    Video Instruction</div>

<p style="text-align:left;">
    <video width="78%"   controls>
        <source src="/video/q3_2.mov">
        Your browser does not support the video tag.
    </video>
</p>

<style>
    #table-appendible {
        table-layout: fixed;
    }
    
    .textarea-wrapper, .input-wrapper {
        
    }
    
    .changable-description, .changable-quantity, changable-unit-price {
        width: 100%;
        height: 100%;
        margin: 0px;
        padding: 5px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -o-box-sizing: border-box;
        -ms-box-sizing: border-box;
        box-sizing: border-box;
    }
</style>

<?php $this->start('script_own');?>
<script>
    $(document).ready(function(){
        var row_counter = 1;
        var max_counter = 1;

        $("#add_item_button").click(function(){
            //alert("suppose to add a new row");

            max_counter++;
            var newRow = jQuery('<tr>' +
                                '<td><span class="btn mini deletebtn"><i class="icon-delete"></i></span></td>' +
                                '<td class="textarea-wrapper"><p name="data[' + max_counter +'][description]" class="changable-description m-wrap  description required" rows="2" ></p></td>' +
                                '<td class="input-wrapper"><p name="data[' + max_counter +'][quantity]" class="changable-quantity"></p></td>' +
                                '<td class="input-wrapper"><p name="data[' + max_counter +'][unit_price]"  class="changable-unit-price"></p></td>' +
                                '</tr>');
            $('#table-appendible').append(newRow);
        });

        $('#table-appendible').on('click', '.deletebtn', function() {
            $(this).closest('tr').remove();
        });
        
        $('#table-appendible').on('click', '.textarea-wrapper', function() {
            // get the data index number of current row
            var $el = $(this).children();
            var data_index = $el.attr('name').match(/\[(.*?)\]/)[1];
            
            var $replacement = jQuery('<textarea name="data[' + data_index +'][description]" class="changable-description m-wrap  description required" rows="2">'+$el.text()+'</textarea>');
            
            if ($el.is('p')){
                $newel = $replacement.replaceAll($el);
                $newel.focus();
                $newel.select();
            }
        });
        
        $('#table-appendible').on('click', '.input-wrapper', function() {
            // get the data index number of current row
            var $el = $(this).children();
            var data_index = $el.attr('name').match(/\[(.*?)\]/)[1];
            
            if ($el.attr('class').includes('changable-quantity')){
                var $replacement = jQuery('<input name="data[' + data_index +'][quantity]" class="changable-quantity" value="'+$el.text()+'">');
            } else if ($el.attr('class').includes('changable-unit-price')){
                var $replacement = jQuery('<input name="data[' + data_index +'][unit_price]" class="changable-unit-price" value="'+$el.text()+'">');
            }
            
            if ($el.is('p')){
                $newel = $replacement.replaceAll($el);
                $newel.focus();
                $newel.select();
            }
        });
        
        /*
        $('#table-appendible').on('click', '.changable-textarea', function() {
            var $el = $(this);
            var $replacement = jQuery('<textarea name="data[' + row_counter +'][description]" class="changable-textarea m-wrap  description required" rows="2">'+$el.text()+'</textarea>');
            
            if ($el.is('p')){
                $newel = $replacement.replaceAll($el);
                $newel.focus();
                $newel.select();
            }
        });
        */

        $('#table-appendible').on('focusout', '.changable-description', function() {
            var data_index = $(this).attr('name').match(/\[(.*?)\]/)[1];
            if ($(this).is('textarea')){
                $(this).replaceWith('<p name="data[' + data_index +'][description]" class="changable-textarea m-wrap  description required">'+$(this).val()+'</p>');
            }
        });
        
        $('#table-appendible').on('focusout', '.changable-quantity', function() {
            var data_index = $(this).attr('name').match(/\[(.*?)\]/)[1];
            if ($(this).is('input')){
                $(this).replaceWith('<p name="data[' + data_index +'][quantity]" class="changable-quantity">'+$(this).val()+'</p>');
            }
        });
        
        $('#table-appendible').on('focusout', '.changable-unit-price', function() {
            var data_index = $(this).attr('name').match(/\[(.*?)\]/)[1];
            if ($(this).is('input')){
                $(this).replaceWith('<p name="data[' + data_index +'][unit_price]" class="changable-unit-price">'+$(this).val()+'</p>');
            }
        });
    })
</script>
<?php $this->end();?>

