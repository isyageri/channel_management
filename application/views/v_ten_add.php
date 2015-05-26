<div class='title'>Input Tenant</div>
<div class='sub_title'></div>
<?php

echo form_open("/ten/tenadddo");
echo form_hidden("ncli", "0");
echo "<table class='form'>";
//echo "<tr><td>NCLI</td><td>:</td><td>".form_input("ncli", "", "size='8'")."</td></tr>";
echo "<tr><td>Tenant Name</td><td>:</td><td>".form_input("ten_name", "", "size='50'")."</td></tr>";
echo "<tr><td valign='top'>Pengelola</td><td valign='top'>:</td><td valign='top'>".form_multiselect("pgl_id[]", $pgl, array(), "size=4")."</td></tr>";
echo "<tr><td>Address</td><td>:</td><td>".form_input("ten_addr", "", "size='100'")."</td></tr>";
echo "<tr><td>Phone No.</td><td>:</td><td>".form_input("ten_contact_no", "", "size='20'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>