<div class="form-group text-center" style="overflow: hidden">
    <div class="alert alert-info">Հաշվարկը՝ ըստ ՎՃԱՐՎԱԾ պատվերների:</div>
    <form method="post" style="overflow: hidden">
        <div class="col-sm-2">
            <input placeholder="Սկսած" class="datepick form-control" value="<?=$from?>" name="date_from" id="date_from" />
        </div>

        <div class="col-sm-2">
            <input placeholder="Մինչև (ներառյալ)" class="datepick form-control" value="<?=$to?>" name="date_to" id="date_to" />   
        </div>
        <div class="col-sm-4">
            <input class="form-control btn btn-primary" name="count" type="submit" value="Հաշվել ըստ ժամանակահատվածի" />   
        </div>
    </form>   
    
</div>
<hr />


<h4>Վճարված պատվերներ</h4>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Քանակ</th>
            <th>Ընդհանուր վճարվել է ($)</th>
            <th>Իրական արժեք ($)</th>
            <th>Եկամուտ ($)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php if(!empty($payedOrders)):?>
            <td><?=$payedOrders->totCount?></td>
            <td><?=$payedOrders->totPrice?></td>
            <td><?=$originOrders->totOrigin?></td>
            <td><?=$payedOrders->totPrice - $originOrders->totOrigin?></td>
            <?php endif;?>
        </tr>
    </tbody>
</table>
<hr />

<h4>Դետալներ Հայաստանից</h4>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Քանակ</th>
            <th>Իրական գումարային արժեք ($)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php if(!empty($totalArm)):?>
            <td><?=$totalArm->totCount?></td>
            <td><?=$totalArm->totOrigin?></td>
            <?php endif;?>
        </tr>
    </tbody>
</table>
<hr />
<h4>Դետալներ Դուբայից</h4>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Քանակ</th>
            <th>Իրական գումարային արժեք ($)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php if(!empty($totalBarma)):?>
            <td><?=$totalBarma->totCount?></td>
            <td><?=$totalBarma->totOrigin?></td>
            <?php endif;?>
        </tr>
    </tbody>
</table>


<script>
    $(document).ready(function(){
        
       $('.datepick').datepicker({
           dateFormat: 'yy-mm-dd'
       }); 
        
    });
    
</script>
<?php
//var_dump($totalArm);