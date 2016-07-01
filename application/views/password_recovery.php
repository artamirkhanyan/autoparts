<?php if($error):?>
    <div class="col-sm-12 alert alert-danger">
        <?=$error;?>
    </div>
<?php endif;?>

<div class="form-holder">
    <form method="post" action="<?=site_url('auth/passRecovery');?>">
        <div class="form-group col-sm-6">
            <label><?=$this->lang->line('email'); ?> <?=$this->lang->line('or'); ?> <?=$this->lang->line('phone'); ?></label>
            <input type="text" class="form-control" name="pass_rec_email" value="" size="50" />
            <p></p>
        </div>       
        <div class="col-sm-12 btn-group-sm">
            <input class="btn btn-default" type="submit" name="pass_rec_submit" value="Submit" />
        </div>
    </form>            
</div>