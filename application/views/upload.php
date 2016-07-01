<form class="form-group" method="post" action="<?php echo site_url('admin/csvUpload');?>" enctype="multipart/form-data">
    <div class="row col-md-12">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Browse&hellip; <input type="file" name="userfile">
                    </span>
                </span>
                <input type="text" class="form-control" name="upload_file" readonly>            
            </div>
        </div>
        <div class="col-md-2">
           <input class="btn btn-primary form-control" type="submit" name="submit" value="Upload" />
           <input id="hidden_shop_name" type="hidden" name="shop_name" value=""/>
           <input id="hidden_currency" type="hidden" name="currency" value=""/>
        </div> 
        <div class="col-md-3">
            <label>
              <input type="checkbox" name="shopCheckbox"> Don't delete previous parts
            </label>
        </div>
        <div class="col-md-2">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                    <span class="upload_csv_from_header" data-from="">Choose shop</span>
                    <span class="caret"></span>
                 </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <?php 
                        foreach ($shops as $value) {
                            echo 
                                '<li class="upload_csv_from" role="presentation" data-from="'. $value['shop'] .'">'
                                    .'<a role="menuitem" tabindex="-1" href="#">'. $value['shop'] .'</a>'
                                .'</li>';
                        }
                    ?>                                        
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                    <span class="upload_csv_currency_header" data-from="">Выбрать вылюту</span>
                    <span class="caret"></span>
                 </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li class="upload_csv_currency" role="presentation" data-currency="usd">
                        <a role="menuitem" tabindex="-1" href="#">USD</a>
                    </li>
                    <li class="upload_csv_currency" role="presentation" data-currency="amd">
                        <a role="menuitem" tabindex="-1" href="#">AMD</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</form>


