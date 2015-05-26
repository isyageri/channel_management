<div class='title'>User C2Bi</div>
<div class='sub_title'></div>

<?php
/*echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/pr/profile'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>No. Anggota</td><td>:</td><td>".form_input('f_member_code', (isset($_POST['f_member_code']))?$_POST['f_member_code']:'', 'size=20')."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";*/

?>

<table class='tablesorter'>
<thead><tr><th>User_ID</th><th>Name</th><th>Loker</th><th>City</th><th>Email</th><th>Phone No.</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->NIK."</td><td>".$r->USER_NAME."</td>";
	echo "<td>".$r->LOKER."</td><td>".$r->ADDR_CITY."</td><td>".$r->EMAIL."</td><td>".$r->CONTACT_NO."</td>";
	echo "<td nowrap><a href='".site_url("/pr/userc2biedit/".$r->USER_ID)."'>C2Bi Profile</a></td>";
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>