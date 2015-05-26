<div class='title'>Restore Data > Fastel</div>
<div class='sub_title'></div>
<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;
$array_ten[""] = "-- Select Tenant --"; 
$array_period_m[""] = "-- Month --"; foreach($this->M_npk->months as $k => $v) $array_period_m[$k] = $v;
$array_period_y[""] = "-- Year --"; foreach($this->M_npk->years as $k => $v) $array_period_y[$k] = $v;

echo form_open("/ten/ndrestdo");
echo "<table class='form'>";
echo "<tr><td>Pengelola</td><td>:</td>";
echo "<td>".form_dropdown('pgl_id', $array_pgl, array() , "class='pgl_to_ten'")."</td></tr>";
echo "<tr><td>Tenant</td><td>:</td>";
echo "<td><span class='ct_ten_of_pgl'>".form_dropdown('ten_id', $array_ten, array() , "class='ten_of_pgl'")."</span></td></tr>";
echo "<tr><td>From Period</td><td>:</td><td>".form_dropdown("period_m", $array_period_m, date("m") ).
" ".form_dropdown("period_y", $array_period_y, date("Y") )."</td></tr>";

echo "<tr><td></td><td></td><td><br><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>