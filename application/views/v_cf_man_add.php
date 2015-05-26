<div class='title'>Input Manual</div>
<div class='sub_title'></div>
<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;
$array_ten[""] = "-- Select Tenant --"; 
$array_cf[""] = "-- Select Component Fee --"; foreach($cf as $k => $v) $array_cf[$k] = $v;

echo form_open("/comp/manualadddo");
echo "<table class='form'>";
echo "<tr><td>Pengelola</td><td>:</td>";
echo "<td>".form_dropdown('pgl_id', $array_pgl, array() , "class='pgl_to_ten'")."</td></tr>";
echo "<tr><td>Tenant</td><td>:</td>";
echo "<td><span class='ct_ten_of_pgl'>".form_dropdown('ten_id', $array_ten, array() , "class='ten_of_pgl'")."</span></td></tr>";
echo "<tr><td>Component Fee</td><td>:</td><td>".form_dropdown("cf_id", $array_cf)."</td></tr>";
echo "<tr><td>Nominal</td><td>:</td><td>".form_input("cf_nom", "", "size='20'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>