<form class="form-group control-label" method="post" action="<?php echo site_url('admin/chagePass');?>">
    <div class="row">
        <div class="col-md-3">
            <label>Նոր գաղտնաբառ</label>
            <input type="password" class="form-control" name="pass" />                     
        </div>
        <div class="col-md-3">
            <label>Կրկնել գաղտնաբառը</label>
            <input type="password" class="form-control" name="pass2" />      
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <input class="btn btn-primary form-control" type="submit" name="changepass" value="Պահպանել">
        </div> 
    </div>
</form>

<div class="alert alert-warning"><?=$message?></div>

<br>
<hr />

<h3>Web service</h3>

<form autocomplete="false" novalidate="novalidate" id="barmaform" class="form-group control-label" method="post" action="<?php echo site_url('admin/chagePass');?>">
    <div class="row">
        <div class="col-md-3">
            <label>Նոր լոգին</label>
            <input autocomplete="off" type="text" class="form-control" value="" name="barmalogin" />                     
        </div>
        <div class="col-md-3">
            <label>Նոր գաղտնաբառ</label>
            <input autocomplete="off" type="password" class="form-control" name="barmapass" />      
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <input class="btn btn-primary form-control" type="submit" name="changebarmapass" value="Պահպանել">
        </div> 
    </div>
</form>

<div class="alert alert-warning"><?=$message2?></div>