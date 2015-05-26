<div class='title'>Fastel Non POTS</div>
<div class='sub_title'></div>

<?php
/*echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/ten'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Nama Tenant</td><td>:</td><td>".form_input('f_ten_name', (isset($_POST['f_ten_name']))?$_POST['f_ten_name']:"", "size='30'" )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";*/

echo "<div id='navigation'><ul>";
echo "<li><a href='".site_url('/ten/tenadd')."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter' id='tb_ten'>
<thead><tr><th>TEN ID</th><th>Tenant</th><th>Address</th><th>Phone No.</th><th>LIS</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->TEN_ID."</td><td>".$r->TEN_NAME."</td><td>".$r->TEN_ADDR."</td><td>".$r->TEN_CONTACT_NO."</td>";
	echo "<td align='right'><a href='".site_url("/ten/nd/".$r->TEN_ID)."'>".number_format($r->JML_ND)."</a></td>";
	echo "<td nowrap><a href='".site_url("/ten/tenedit/".$r->TEN_ID)."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/ten/tendel/".$r->TEN_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$r->TEN_NAME."' /></a>";
	echo "</td>";
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>
<?
$this->M_profiling->pagination();
?>