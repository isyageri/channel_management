<div class='title'>Edit Tiering</div>
<div class='sub_title'></div>
<?php

echo form_open("/comp/tiereditdo");
echo form_hidden("tier_id", $dt[0]->TIER_ID);
echo form_hidden("tier_name", $dt[0]->TIER_NAME);
echo "<table class='form'>";
echo "<tr><td>Tier Name</td><td>:</td><td>".$dt[0]->TIER_NAME."</td></tr>";
echo "<tr><td>Parameter</td><td>:</td><td>".form_input("tier_params", $dt[0]->TIER_PARAMS, "size='50'")."<span style='font-size:10px'> separated by comma (,)</span></td></tr>";
echo "<tr><td>Description</td><td>:</td><td>".form_input("tier_desc", $dt[0]->TIER_DESC, "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>