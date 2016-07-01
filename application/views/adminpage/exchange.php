<form class="form-group control-label" method="post" action="<?php echo site_url('admin/exchange');?>">
    <div class="row">
        <div class="col-md-3">
            <label>Նոր փոխարժեք</label>
            <input type="text" class="form-control" autocomplete="false" name="exchangeRate" />                     
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <input class="btn btn-primary form-control" type="submit" name="rate" value="Պահպանել">
        </div> 
    </div>
</form>

<div class="alert alert-danger"><?=$error?></div>

<hr>
<h5>Կայքի ներկա փոխարժեք</h5>
<code>1$ = <b><?=$result?></b> դրամ</code>
