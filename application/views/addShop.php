<form class="form-group control-label" method="post" action="<?php echo site_url('admin/addShop');?>">
    <div class="row">
        <div class="col-md-4">
            <input type="text" class="form-control" name="shop_name" placeholder="Add new shop">                       
        </div>
        <div class="col-md-2">
            <input class="btn btn-primary form-control" type="submit" name="addShop" value="Add">
        </div>        
    </div>
    <div class="row col-md-4">
        <?php if($error):?>
            <div class="alert alert-danger"><?=$error?></div>
        <?php endif; ?>
    </div>
</form>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Խանութ</th>
            <th>Դետալների քանակ</th>
            <th>Գործողություն</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($shops as $value) {
                echo 
                    '<tr>
                        <td>'. $value['shop'] .'</td>
                        <td>'. $value['partsCount'] .'</td>
                        <td style="color:rgb(165, 4, 4)" class="shop_delete" id="'. $value['id'] .'" shop_name="'. $value['shop'] .'">ջնջել</td>
                    </tr>';
            }
        ?>        
    </tbody>
</table>