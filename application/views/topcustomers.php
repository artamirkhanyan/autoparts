<h3>Գրանցված հաճախորդների TOP 10</h3>
<hr>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Անուն</th>
            <th>Հեռախոսահամար</th>
            <th>Պատվերների քանակ (հատ)</th>
            <th>Ընդհանուր արժեք ($)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($result as $customer):?>
        <tr>
            <td><?=$customer->f_name.' '.$customer->l_name?></td>
            <td><?=$customer->phone?></td>
            <td><?=$customer->orderscount?></td>
            <td><?=$customer->totalPrice?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
