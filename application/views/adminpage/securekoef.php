<form class="form-group control-label" method="post" action="<?php echo site_url('admin/securekoef');?>">
    <div class="row">
        <div class="col-md-3">
            <label>Գաղտնաբառ</label>
            <input type="password" class="form-control" autocomplete="false" name="securePass" />                     
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <input class="btn btn-primary form-control" type="submit" name="secure" value="Մուտք">
        </div> 
    </div>
</form>

<div class="alert alert-danger"><?=$error?></div>