<html>
    <head>
        <script type="text/javascript" src="<?= base_url() ?>public/js/jquery-min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>public/js/jquery-ui.min.js"></script>
        <link  href="<?= base_url() ?>public/css/jquery-ui.css"/>
        <style>
            .headers{
                
            }
            .headers_item{
                width: 100px;
                padding: 10px 0;
                text-align: center;
                border: 1px solid #cccccc;
                list-style: none;
                display: inline-block;
            }
            .user_upload_item{
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <ul class="headers">
            <?php 
                foreach ($template as $key=>$value) {
                    echo '<li class="headers_item template_headers_item" data-key="'.$key.'" >'.$value.'</li>';
                }
                echo '<br/>';
                foreach ($current_csv as $key=>$value) {
                    echo '<li class="headers_item user_upload_item draggable_item ui-state-default">'.$value.'</li>';
                }
                if(count($template) > count($current_csv)) {
                    $diff = count($template) - count($current_csv);
                    for($j = 0; $j < $diff; $j++) {
                        echo '<li class="headers_item user_upload_item draggable_item ui-state-default" style="color:rgb(255, 173, 173);">Empty</li>';
                    }
                }
            ?>
        </ul>
        <button id="apply_btn" type="button">Apply Changes</button>
        <script>
            $('#apply_btn').on('click', function(){
                //Get uploaded csv file's headers array after user sorting
                var uploadedHeadersArr = [];
                $('.user_upload_item').each(function(){
                    uploadedHeadersArr.push($(this).text());
                });
                //Create array for template headers
                var templateHeadersArr = [];
                $('.template_headers_item').each(function(){
                    templateHeadersArr.push($(this).data('key'));
                });
                //Creating assoc array from templateHeadersArr and uploadedHeadersArr
                var headersMap = new Object();
                for(var i = 0; i < uploadedHeadersArr.length; i++){
                    headersMap[templateHeadersArr[i]] = uploadedHeadersArr[i];
                }
                //console.log(headersMap);
                loadingOverlay(true, $('ul.headers'))
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/csvChangeApply",
                    type: 'POST',
                    dataType: 'json',
                    data: headersMap,
                    success: function(request){
                        loadingOverlay(false, $('ul.headers'))
                        if(request==1){
                            window.location.replace('/admin/upload');
                        }
                    }
                });
            });
            
            $( "ul.headers" ).sortable({
                items: "li.draggable_item",
                axis:'x',
                cursor:'pointer',
                revert: true,
                revertDuration:500
            });
            /*
            $('.draggable_item').draggable({
                axis:'x',
                cursor:'pointer',
                revert: true,
                revertDuration:1000
            });
            
            $('.draggable_item').droppable({
                over: function(event, ui){
                    $(this).css({borderColor:"green"});
                },
                out: function(event, ui){
                    $(this).css({borderColor:"red"});
                },
                drop: function(event, ui){
                    $(this).css({borderColor:"red"});
                    droppableObj = $(this);
                    draggableObj = ui.draggable;
                    //console.log(draggableObj.attr('num') +"-->"+ droppableObj.attr('num'));
                    //Change elements text between each other
                    tmpText = droppableObj.text();
                    droppableObj.text(draggableObj.text());
                    draggableObj.text(tmpText);
                    //Change elements num attr between each other
                    tmpNum = droppableObj.data('num');
                    droppableObj.data('num', draggableObj.data('num'));
                    draggableObj.data('num', tmpNum);
                }                
            });*/
        </script>
    </body>
</html>
