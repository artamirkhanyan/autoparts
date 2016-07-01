<html>
    <head>
        <title>Login page</title>
        <link href="<?=base_url()?>public/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="form-horizontal" style="text-align: center;">
            <form method="post" action="<?=site_url('admin');?>">
                <div style="width:400px; overflow: hidden; margin: 15% auto;">
                    <div class="col-sm-12">
                        <label>Username:</label>
                        <input class="form-control" autocomplete="false" type="text" name="adminname" value="" size="50" />
                    </div>
                    <div class="col-sm-12">
                        <label>Password</label>
                        <input class="form-control" autocomplete="false" type="password" name="adminpassword" value="" size="50" />
                    </div>
                    <div class="col-sm-12">
                        <input class="btn btn-default" type="submit" value="Submit" />
                    </div>
                    
                    <?php if($error || validation_errors()): ?>
                        <div class="col-sm-12 alert alert-danger">
                            <?=$error?>
                            <?php echo validation_errors(); ?>
                        </div>
                    <?php endif;?>   
                    
                </div>
            </form>
            
        </div>
    </body>
</html>
