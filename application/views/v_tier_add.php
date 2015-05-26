<div class='title'>Input Tiering</div>
<div class='sub_title'></div>
<?php

echo form_open("/comp/tieradddo");
echo "<table class='form'>";
echo "<tr><td>Tier Name</td><td>:</td><td>".form_input("tier_name", "", "size='35'")."</td></tr>";
echo "<tr><td>Parameter</td><td>:</td><td>".form_input("tier_params", "", "size='50'")."<span style='font-size:10px'> separated by comma (,)</span></td></tr>";
echo "<tr><td>Description</td><td>:</td><td>".form_input("tier_desc", "", "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>