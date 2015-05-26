<div class='title'>Upload Data > Non POTS</div>
<div class='sub_title'></div>
<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;
$array_ten[""] = "-- Select Tenant --"; 
$pu_action = array(1=>"Backup to current period", 2=>"Backup to <i>previous</i> period", 3=>"No Backup");

echo form_open_multipart("/ten/ndnpuploaddo");
echo "<table class='form'>";
echo "<tr><td>Pengelola</td><td>:</td>";
echo "<td>".form_dropdown('pgl_id', $array_pgl, array() , "class='pgl_to_ten'")."</td></tr>";
echo "<tr><td>Tenant</td><td>:</td>";
echo "<td><span class='ct_ten_of_pgl'>".form_dropdown('ten_id', $array_ten, array() , "class='ten_of_pgl'")."</span></td></tr>";
/*echo "<tr><td>Produk</td><td>:</td>";
echo "<td>".form_dropdown('cprod', $cprod, array() , "")."</td></tr>";
echo "<tr><td valign='top'>Pre-upload Action</td><td valign='top'>:</td>";
echo "<td valign='top'>";
foreach($pu_action as $k => $v) 
	echo form_radio("pu_action", $k, ($k==1) )."&nbsp;&nbsp;".$v."<br>";
echo "</td></tr>";*/
echo "<tr><td>File Type</td><td>:</td><td>Ms. Excel or file text (comma delimited)</td></tr>";
echo "<tr><td valign='top'>Format Field</td><td valign='top'>:</td><td>";
echo "<i>Customer Reff,Account Num,GL Account</i><br><br>";
echo "<img src='".image_asset_url("format_upload.png")."' width='780' /></td></tr>";
echo "<tr><td>File Name</td><td>:</td><td>".form_upload("file_name", "", "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><br><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>