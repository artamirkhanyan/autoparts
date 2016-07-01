<form method="post" action="<?php echo site_url('admin/setKoef');?>">
    <h4>Հայաստանյան գին</h4>
    <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
              <th style="text-align: center;"> Հայաստան Income (%) </th>
              <th style="text-align: center;"> Հայաստան - զեղչ (%) </th>
          </tr>
        </thead>
        <tbody>
            <tr class="koeficients_tr">
                <td style="text-align: center;"><input type="text"   name="income_arm"          value="<?=$koeficients['income_arm']?>"/></td>
                <td style="text-align: center;"><input type="text"   name="arm_discount"  value="<?=$koeficients['arm_discount']?>"/></td>
            </tr>
        </tbody>
    </table>
    <p>
        <span>Result = ( Price ) + <b><?=$koeficients['income_arm']?>%</b></span>
    </p>
    <hr />
    <h4>Դուբայի գին</h4>
    <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
              <!-- <th style="text-align: center;"> Price (%) </th> -->
              <th style="text-align: center;"> Weight </th>
              <th style="text-align: center;"> Delivery</th>
              <th style="text-align: center;"> Income (%) </th>
              <th style="text-align: center;"> Barma - Զեղչ (%) </th>
          </tr>
        </thead>
        <tbody>
            <tr class="koeficients_tr">
                <!--<td style="text-align: center;"><input type="text"   name="price"           value="<?php //$koeficients['price']?>"/></td> -->
                <td style="text-align: center;"><input type="text"   name="weight"          value="<?=$koeficients['weight']?>"/></td>
                <td style="text-align: center;"><input type="text"   name="delivery"      value="<?=$koeficients['delivery']?>"/></td>
                <td style="text-align: center;"><input type="text"   name="income"          value="<?=$koeficients['income']?>"/></td>
                <td style="text-align: center;"><input type="text"   name="barma_discount"   value="<?=$koeficients['barma_discount']?>"/></td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" id="heavyBarma"  name="heavyBarma" <?=$koeficients['heavyBarma'] ? 'checked' :''?>/>
                    <label class="text-muted" for="heavyBarma">Զեղչ 5կգ-ից ծանր դետալների համար։</label>
                </td>
            </tr>
        </tbody>
    </table>
    <hr />
    <h4>Ռուսաստանի գին</h4>
    <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
              <!-- <th style="text-align: center;"> Price (%) </th> -->
              <th style="text-align: center;"> Weight </th>
          </tr>
        </thead>
        <tbody>
            <tr class="koeficients_tr">
                <td style="text-align: center;"><input type="text"   name="weight_russia"          value="<?=$koeficients['weight_russia']?>"/></td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" id=""  name="heavyRussia" <?=$koeficients['heavyRussia'] ? 'checked' :''?>/>
                    <label class="text-muted" for="heavyRussia">Զեղչ 5կգ-ից ծանր դետալների համար։</label>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="submit" name="change_koef"     value="Պահպանել" class="btn btn-primary">
</form>
<hr />՚
<p>
    <span>Result = ( Price + Weight * <b><?=$koeficients['weight']?></b> ) + <b><?=$koeficients['income']?>%</b></span>
</p>