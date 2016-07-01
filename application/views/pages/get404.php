<?php header("HTTP/1.1 404 Not Found"); ?>
<div id="container" style="overflow: hidden;  padding-bottom: 20px;">
    <h1>404 error</h1>
    <div class="col-md-4" style="margin-top: 10px">
        <?=$this->lang->line('err404');?> &nbsp;&nbsp;&nbsp;<a href="<?=base_url()?>"><?=$this->lang->line('homepage');?></a>
    </div>
</div>