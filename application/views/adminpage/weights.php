<div class="page-header">Նոր քաշ</div>
<form class="form-group control-label" method="post" action="<?php echo site_url('admin/weights');?>">
    
    <div class="row">
        <div class="col-md-3">
            <input type="text" class="form-control" name="part_number" placeholder="Դետալի կոդ">                       
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="weight" placeholder="Քաշ (կգ)">                       
        </div>
        <div class="col-md-2">
            <input class="btn btn-primary form-control" type="submit" name="addWeight" value="Ավելացնել">
        </div>        
    </div>
    <div class="row col-md-4">
        <?php if($error):?>
            <div class="alert alert-danger"><?=$error?></div>
        <?php endif; ?>
    </div>
</form>

<div class="page-header">Փնտրել</div>
<div class="row">
    <div class="col-md-3">
        <input type="text" class="form-control" id="search_key" placeholder="Դետալի կոդ">                       
    </div>
    <div class="col-md-2" id="search_start_wrap">
        <button class="btn btn-primary form-control" id="search_start">Փնտրել</button>
    </div> 
</div>
<hr/>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Դետալի կոդ</th>
            <th>Քաշ (կգ)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($result as $value):?>
            <tr>
                <td><?=$value['part_number']?></td>
                <td><?=$value['weight']?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?php if($count > $limit): ?>

<ul class="pagination">
    
   <?php if($this->uri->segment(3) > 1):?> 
   <li><a href="<?=$this->uri->segment(3)-1?>">&laquo;</a></li>
   <?php else: ?>
   <li class="disabled"><a href="javascript:void(0)">&laquo;</a></li>
   <?php endif;?>
   
    <?php for($i=1; $i <= ceil($count/$limit); $i++):?> 

        <li <?php echo ($this->uri->segment(3) == $i)?'class="active"':'' ?>><a href="/admin/weights/<?=$i?>"><?=$i?></a></li>

    <?php endfor;?>
    
  <?php if($this->uri->segment(3) < ceil($count/$limit)):?>  
  <li><a href="<?=$this->uri->segment(3)+1?>">&raquo;</a></li>
  <?php else: ?>
  <li class="disabled"><a href="javascript:void(0)">&raquo;</a></li>
  <?php endif;?>
  
</ul>

<?php endif ?>

<script>

$('button#search_start').click(function(){
    var key = $.trim($('#search_key').val());
    if(key.length > 2){
        startSearch(key);
    }
});


function startSearch(key){
    loadingOverlay(true, $('table.table'));
    $.ajax({
        url: '/admin/weightsSearch/'+key,
        dataType: 'json',
        type: 'get',
        success: function(response){
            loadingOverlay(false, $('table.table'));
            $('button#clean').parent().remove();
            $('#search_start_wrap').after('<div class="col-md-2"><button onclick="javascript:location.reload()" class="btn btn-danger form-control" id="clean">Մաքրել</button></div>');
            
            $('table.table').find('tbody').empty();
            $('ul.pagination').remove();
            var html = '';
            if(response.length < 1){
                html = '<tr><td style="color:red">Ոչինչ չի գտնվել։</td></tr>';
            }else{
                $.each(response, function(){
                    html += '<tr><td>'+this.part_number+'</td><td>'+this.weight+'</td></tr>';
                });
            }
            
            $('table.table').find('tbody').html(html);
            
        },
        error: function(e){
            loadingOverlay(false, $('table.table'))
            console.log(e);
        }
    });
}


</script>