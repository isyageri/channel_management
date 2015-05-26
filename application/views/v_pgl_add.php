<div class='title'>Input Pengelola</div>
<div class='sub_title'></div>
<?php

echo form_open("/pgl/pgladddo");
echo "<table class='form'>";
//echo "<tr><td>PGL ID</td><td>:</td><td>".form_input("pgl_id", "", "size='8'")."</td></tr>";
echo "<tr><td>Nama Pengelola</td><td>:</td><td>".form_input("pgl_name", "", "size='50'")."</td></tr>";
echo "<tr><td>Alamat</td><td>:</td><td>".form_input("pgl_addr", "", "size='100'")."</td></tr>";
echo "<tr><td>No. Telp.</td><td>:</td><td>".form_input("pgl_contact_no", "", "size='20'")."</td></tr>";
echo "<tr><td></td><td></td><td>".form_checkbox("enable_fee", "1", TRUE)." Marketing Fee</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>