<div class='title'>Edit Profile</div>
<div class='sub_title'></div>
<?php
echo form_open("/pr/profileeditdo");
echo form_hidden("prof_id", $dt[0]->PROF_ID);
echo form_hidden("prof_name", $dt[0]->PROF_NAME);
echo "<table class='form'>";
echo "<tr><td>Profile Name</td><td>:</td><td>".$dt[0]->PROF_NAME."</td></tr>";
echo "<tr><td>Description</td><td>:</td><td>".form_input("prof_desc", $dt[0]->PROF_DESC, "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>