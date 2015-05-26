<div class='title'>Edit User</div>
<div class='sub_title'></div>
<?php

if(isset($warning))
	echo "<span style='font-weight:bold; color:red; padding:2px; '>".$warning."</span><br>";
echo form_open("/pr/usereditdo");
echo form_hidden("user_id", $dt[0]->USER_ID);
echo form_hidden("nik", $dt[0]->NIK);
echo "<table class='form'>";
echo "<tr><td>NIK</td><td>:</td><td><b>".$dt[0]->NIK."</b></td></tr>";
echo "<tr><td>Name</td><td>:</td><td>".form_input("user_name", $dt[0]->USER_NAME, "size='35'")."</td></tr>";
echo "<tr><td valign='top'>Profile</td><td valign='top'>:</td><td valign='top'>".form_multiselect("prof_id[]", $prof, $prof_select, "size=4")."</td></tr>";
echo "<tr><td colspan=3><br><u>Authentication</u></td></tr>";
echo "<tr><td>Password</td><td>:</td><td>".form_password("passwd", "", "size='35'")."</td></tr>";
echo "<tr><td>Confirm Password</td><td>:</td><td>".form_password("confirm_passwd", "", "size='35'")."</td></tr>";
echo "<tr><td colspan=3>&nbsp;</td></tr>";
echo "<tr><td>Loker</td><td>:</td><td>".form_input("loker", $dt[0]->LOKER, "size='50'")."</td></tr>";
echo "<tr><td>Address</td><td>:</td><td>".form_input("addr_street", $dt[0]->ADDR_STREET, "size='50'")."</td></tr>";
echo "<tr><td>City</td><td>:</td><td>".form_input("addr_city", $dt[0]->ADDR_CITY, "size='35'")."</td></tr>";
echo "<tr><td>Email</td><td>:</td><td>".form_input("email", $dt[0]->EMAIL, "size='35'")."</td></tr>";
echo "<tr><td>Phone No.</td><td>:</td><td>".form_input("contact_no", $dt[0]->CONTACT_NO, "size='20'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>