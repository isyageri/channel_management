<div class='title'>Input Profile</div>
<div class='sub_title'></div>
<?php

echo form_open("/pr/profileadddo");
echo "<table class='form'>";
echo "<tr><td>Profile Name</td><td>:</td><td>".form_input("prof_name", "", "size='50'")."</td></tr>";
echo "<tr><td>Description</td><td>:</td><td>".form_input("prof_desc", "", "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>