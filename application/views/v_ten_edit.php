<div class='title'>Edit Tenant</div>
<div class='sub_title'></div>
<?php

echo form_open("/ten/teneditdo");
echo form_hidden("ten_id", $dt[0]->TEN_ID);
echo form_hidden("ncli", $dt[0]->NCLI);
echo "<table class='form'>";
//echo "<tr><td>NCLI</td><td>:</td><td>".$dt[0]->NCLI."</td></tr>";
echo "<tr><td>Tenant Name</td><td>:</td><td>".form_input("ten_name", $dt[0]->TEN_NAME, "size='50'")."</td></tr>";
echo "<tr><td valign='top'>Pengelola</td><td valign='top'>:</td><td valign='top'>".form_multiselect("pgl_id[]", $pgl, $pgl_select, "size=4")."</td></tr>";
echo "<tr><td>Address</td><td>:</td><td>".form_input("ten_addr", $dt[0]->TEN_ADDR, "size='100'")."</td></tr>";
echo "<tr><td>Phone No.</td><td>:</td><td>".form_input("ten_contact_no", $dt[0]->TEN_CONTACT_NO, "size='20'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>