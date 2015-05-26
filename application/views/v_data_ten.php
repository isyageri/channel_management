<div class='title'>Upload Data > Tenant</div>
<div class='sub_title'></div>
<?php
$pu_action = array(1=>"Backup to current period", 2=>"Backup to <i>previous</i> period", 3=>"No Backup");

echo form_open_multipart("/ten/tenuploaddo");
echo "<table class='form'>";
echo "<tr><td valign='top'>Pre-upload Action</td><td valign='top'>:</td>";
echo "<td valign='top'>";
foreach($pu_action as $k => $v) 
	echo form_radio("pu_action", $k, ($k==3) )."&nbsp;&nbsp;".$v."<br>";
echo "</td></tr>";
echo "<tr><td>File Type</td><td>:</td><td>Ms. Excel or file text (comma delimited)</td></tr>";
echo "<tr><td valign='top'>Format Field</td><td valign='top'>:</td><td>";
echo "[xxx], [yyy]<br><br>";
echo "<img src='".image_asset_url("format_upload.png")."' width='780' /></td></tr>";
echo "<tr><td>File Name</td><td>:</td><td>".form_upload("file_name", "", "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>