<div class='title'>Edit Menu</div>
<div class='sub_title'></div>
<?php 
echo form_open("/pr/menueditdo");
echo form_hidden("menu_id", $dt[0]->MENU_ID);
echo form_hidden("menu_parent", $dt[0]->MENU_PARENT);
echo "<table class='form'>";
echo "<tr><td>Menu Name</td><td>:</td><td>".form_input("menu_name", $dt[0]->MENU_NAME, "size='50'")."</td></tr>";
echo "<tr><td>Link Address</td><td>:</td><td>".form_input("menu_link", $dt[0]->MENU_LINK, "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>