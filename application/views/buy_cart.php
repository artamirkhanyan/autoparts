<?php
    if(!$this->session->userdata('user_id')){
        $info['f_name'] = '';
        $info['l_name'] = '';
        $info['email'] = '';
        $info['phone'] = '';
        $info['address'] = '';
    }
?>
<div class="form-holder">
    <form method="post" action="<?=site_url('/buyCart');?>">
        <div class="form-group col-sm-12">
            <label class=""><?=$this->lang->line('name');?> </label>
            <input class="form-control" type="text" name="f_name" value="<?php echo $info['f_name'];?>" size="50" />
            <p><?php echo $validation['f_name'];?></p>
        </div>
        <div class="form-group col-sm-12">
            <label><?=$this->lang->line('surname');?> </label>
            <input type="text" class="form-control" name="l_name" value="<?php echo $info['l_name'];?>" size="50" />
            <p><?php echo $validation['l_name'];?></p>
        </div>
        <div class="form-group col-sm-12">
            <label>Email</label>
            <input type="text" class="form-control" name="email" value="<?php echo $info['email'];?>" size="50" />
            <p><?php echo $validation['email'];?></p>
        </div>
        <div class="form-group col-sm-12">
            <label><?=$this->lang->line('phone');?> <span class="example_code" style="font-size: 14px;"> <?=$this->lang->line('example');?> 091XXXXXX</span></label>
            <input class="form-control" type="text" name="phone" value="<?php echo $info['phone'];?>" size="50" />
            <p><?php echo $validation['phone'];?></p>
        </div>
        <div class="form-group col-sm-12">
            <label><?=$this->lang->line('address');?></label>
            <input class="form-control" type="text" name="address" value="<?php echo $info['address'];?>" size="50" />
            <p><?php echo $validation['address'];?></p>
        </div>       
        <div class="col-sm-12 btn-group-sm">
            <input class="btn btn-primary" type="submit" name="buy_submit" value="<?=$this->lang->line('go2pay');?>" />
        </div>
    </form>
</div>
