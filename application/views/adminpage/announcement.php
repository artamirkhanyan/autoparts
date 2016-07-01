<form class="form-group control-label" method="post" action="<?php echo site_url('admin/announcement');?>">
    <div class="row">
        <div class="col-md-8">
            <textarea type="text" rows="6" class="form-control" name="announcement" placeholder="Հայտարարություն..."><?=$text?></textarea>                       
        </div>
        
        <div class="col-md-2">
            <input class="btn btn-primary form-control" type="submit" name="addAnnouncement" value="Պահպանել">
        </div>  
        <div class="col-md-2">
            <input class="btn btn-danger form-control" type="submit" name="deleteAnnouncement" value="Ջնջել">
        </div>
    </div>
</form>
<div class="alert alert-info"><?=$text?></div>

