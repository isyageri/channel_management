<div class='title'>Edit Input Manual</div>
<div class='sub_title'></div>
<?php
$array_cf[""] = "-- Select Component Fee --"; foreach($cf as $k => $v) $array_cf[$k] = $v;

echo form_open("/comp/manualeditdo");
echo form_hidden("pgl_id", $dt[0]->PGL_ID);
echo form_hidden("ten_id", $dt[0]->TEN_ID);
echo form_hidden("cf_id", $dt[0]->CF_ID);
echo "<table class='form'>";
echo "<tr><td>Pengelola</td><td>:</td><td><b>".$dt[0]->PGL_NAME."</b></td></tr>";
echo "<tr><td>Tenant</td><td>:</td><td><b>".$dt[0]->TEN_NAME."</b></td></tr>";
echo "<tr><td>Component Fee</td><td>:</td><td>".$dt[0]->CF_NAME."</td></tr>";
echo "<tr><td>Nominal</td><td>:</td><td>".form_input("cf_nom", $dt[0]->CF_NOM, "size='20'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>