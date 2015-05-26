<div class='title'>Input User</div>
<div class='sub_title'></div>
<?php
echo form_open("/pr/useradddo");
echo "<table class='form'>";
echo "<tr><td>NIK</td><td>:</td><td>".form_input("nik", "", "size='6'")."</td></tr>";
echo "<tr><td>Name</td><td>:</td><td>".form_input("user_name", "", "size='35'")."</td></tr>";
echo "<tr><td valign='top'>Profile</td><td valign='top'>:</td><td valign='top'>".form_multiselect("prof_id[]", $prof, array(), "size=4")."</td></tr>";
echo "<tr><td colspan=3><br><u>Authentication</u></td></tr>";
echo "<tr><td>Password</td><td>:</td><td>".form_password("passwd", "", "size='35'")."</td></tr>";
echo "<tr><td>Confirm Password</td><td>:</td><td>".form_password("confirm_passwd", "", "size='35'")."</td></tr>";
echo "<tr><td colspan=3>&nbsp;</td></tr>";
echo "<tr><td>Loker</td><td>:</td><td>".form_input("loker", "", "size='50'")."</td></tr>";
echo "<tr><td>Address</td><td>:</td><td>".form_input("addr_street", "", "size='50'")."</td></tr>";
echo "<tr><td>City</td><td>:</td><td>".form_input("addr_city", "", "size='35'")."</td></tr>";
echo "<tr><td>Email</td><td>:</td><td>".form_input("email", "", "size='35'")."</td></tr>";
echo "<tr><td>Phone No.</td><td>:</td><td>".form_input("contact_no", "", "size='20'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>