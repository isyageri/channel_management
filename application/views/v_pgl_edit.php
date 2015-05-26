<div class='title'>Edit Pengelola</div>
<div class='sub_title'></div>
<?php

echo form_open("/pgl/pgleditdo");
echo form_hidden("pgl_id", $dt[0]->PGL_ID);
echo "<table class='form'>";
echo "<tr><td>PGL ID</td><td>:</td><td><b>".$dt[0]->PGL_ID."</b></td></tr>";
echo "<tr><td>Nama Pengelola</td><td>:</td><td>".form_input("pgl_name", $dt[0]->PGL_NAME, "size='50'")."</td></tr>";
echo "<tr><td>Alamat</td><td>:</td><td>".form_input("pgl_addr", $dt[0]->PGL_ADDR, "size='100'")."</td></tr>";
echo "<tr><td>No. Telp.</td><td>:</td><td>".form_input("pgl_contact_no", $dt[0]->PGL_CONTACT_NO, "size='20'")."</td></tr>";
echo "<tr><td></td><td></td><td>".form_checkbox("enable_fee", "1", ($dt[0]->ENABLE_FEE==1))." Marketing Fee</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>