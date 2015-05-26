<div class='title'>Menu</div>
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

echo "<div id='navigation'><ul>";
echo "<li><a href='".site_url('/pr/menuadd')."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter'>
<thead><tr><th>Menu Name</th><th>Link Address</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td><a href='".site_url("/pr/menuchild/".$r->MENU_ID)."'>".$r->MENU_NAME."</a></td><td>".$r->MENU_LINK."</td>";
	echo "<td nowrap><a href='".site_url("/pr/menuedit/".$r->MENU_ID)."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/pr/menudel/".$r->MENU_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$r->MENU_NAME."' /></a></td>";
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>