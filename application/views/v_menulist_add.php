<div class='title'>Input Menu</div>
<div class='sub_title'></div>
<?php
echo form_open("/pr/menuadddo");
echo form_hidden("menu_parent", $parent);
echo "<table class='form'>";
echo "<tr><td>Menu Name</td><td>:</td><td>".form_input("menu_name", "", "size='50'")."</td></tr>";
echo "<tr><td>Link Address</td><td>:</td><td>".form_input("menu_link", "", "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>