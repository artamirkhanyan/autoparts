<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>          
            <th>Անուն</th>
            <th>Հեռախոս</th>
            <th>Հասցե</th>
            <th>էլ. հասցե</th>
            <th>Վերջին մուտք</th>
            <th>Ակտիվ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $key => $value):?>
                <tr>
                    <td class='user_info'><a href="<?=site_url('admin/userOrder')."/".$value['id']?>"><?=$value['f_name'].' '.$value['l_name']?></td>
                    <td><?=$value['phone']?></td>
                    <td><?=$value['address']?></td>
                    <td><?=$value['email']?></td>
                    <td><?=$value['last_login']?></td>
                    <td>
                        <select id="active_user" data-id="<?=$value['id']?>">
                            <option <?=$value['is_active']==1 ? 'selected' : ''?> value="1">Ակտիվ</option>
                            <option <?=$value['is_active']==0 ? 'selected' : ''?> value="0">Ոչ ակտիվ</option>
                        </select>
                    </td>
                </tr>
           <?php endforeach;?>
    </tbody>
</table>

