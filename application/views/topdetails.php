<div class="alert alert-info">Ներառված են նաև Չվճարված պատվերների դետալները:</div>
<form action method="post">
  <input type="number" value="<?=$top?>" name="topcount" class="form-control" min="1" style="width:70px;float:left" />
  <input type="submit" value="OK" class="btn btn-primary" style="width:50px;margin-left:5px" />
</form>

<h3>Վաճառված դետալների TOP <?=$top?></h3>
<hr>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Դետալի կոդ</th>
            <th>Վաճաովել է (հատ)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($topten as $detail):?>
        <tr>
            <td><?=$detail->part_number?></td>
            <td><?=$detail->totcount?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<hr />
<h3>Հայաստանից վաճառված դետալների TOP <?=$top?></h3>
<hr>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Դետալի կոդ</th>
            <th>Վաճաովել է (հատ)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($toptenArm as $detail):?>
        <tr>
            <td><?=$detail->part_number?></td>
            <td><?=$detail->totcount?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<hr />
<h3>Դուբայից վաճառված դետալների TOP <?=$top?></h3>
<hr>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Դետալի կոդ</th>
            <th>Վաճաովել է (հատ)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($toptenBarma as $detail):?>
        <tr>
            <td><?=$detail->part_number?></td>
            <td><?=$detail->totcount?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
