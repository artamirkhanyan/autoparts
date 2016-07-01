
    <div id="container">
        <?php if($error): ?>
        <div class="col-sm-12 alert alert-danger">
                <?=$error?>
            </div>
        <?php endif;?>
        <div class="form-holder">
        <form method="post" action="<?=site_url('auth/registration');?>">
            <div class="form-group col-sm-12">
                <label class=""><?=$this->lang->line('name');?> </label>
                <input class="form-control" type="text" name="f_name" value="<?php if(empty($validation['f_name'])) echo $this->input->post('f_name');?>" size="50" />
                <p><?php echo $validation['f_name'];?></p>
            </div>
            <div class="form-group col-sm-12">
                <label><?=$this->lang->line('surname');?> </label>
                <input type="text" class="form-control" name="l_name" value="<?php if(empty($validation['l_name'])) echo $this->input->post('l_name');?>" size="50" />
                <p><?php echo $validation['l_name'];?></p>
            </div>
            <div class="form-group col-sm-12">
                <label>Email</label>
                <input type="text" class="form-control" name="email" value="<?php if(empty($validation['email'])) echo $this->input->post('email');?>" size="50" />
                <p><?php echo $validation['email'];?></p>
            </div>
            <div class="form-group col-sm-12">
                <label><?=$this->lang->line('phone');?> <span class="example_code" style="font-size: 14px;"> <?=$this->lang->line('example');?> 098XXXXXX</span></label>
                <input class="form-control" type="text" name="phone" value="<?php if(empty($validation['phone'])) echo $this->input->post('phone');?>" size="50" />
                <p><?php echo $validation['phone'];?></p>
            </div>
            <div class="form-group col-sm-12">
                <label><?=$this->lang->line('address');?></label>
                <input class="form-control" type="text" name="address" value="<?php if(empty($validation['address'])) echo $this->input->post('address');?>" size="50" />
                <p><?php echo $validation['address'];?></p>
            </div>
            <div class="form-group col-sm-6">
                <label><?=$this->lang->line('pass');?> </label>
                <input type="password" class="form-control" name="password" value="" size="50" />
                <p><?php echo $validation['password'];?></p>
            </div>
            <div class="form-group col-sm-6">
                <label><?=$this->lang->line('confirm_pass');?> </label>
                <input type="password" class="form-control" name="password_repeat" value="" size="50" />
                <p><?php echo $validation['password_repeat'];?></p>
            </div>
            <div class="col-sm-12 btn-group-sm">
                <input class="btn btn-default" type="submit" name="reg_submit" value="<?=$this->lang->line('reg');?>" />
            </div>
        </form>
            
        </div>
    </div>