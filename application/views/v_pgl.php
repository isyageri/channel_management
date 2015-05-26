<div class='title'>Pengelola</div>

<?php
echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/pgl'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Nama Pengelola</td><td>:</td><td>".form_input('f_pgl_name', (isset($_POST['f_pgl_name']))?$_POST['f_pgl_name']:"", "size='30'" )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";


echo "<div id='navigation'><ul>";
echo "<li><a href='".site_url('/pgl/pgladd')."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter' id='tb_pgl'>
<thead><tr><th>PGL ID</th><th>Nama Pengelola</th><th>Alamat</th><th>No. Telp.</th><th align='center'>Marketing Fee</th><th>Count of Tenant</th><th>LIS</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	if($r->ENABLE_FEE==1) $icon_enable_fee="<img src='".image_asset_url("checky.jpg")."' />";
	else $icon_enable_fee="<img src='".image_asset_url("checkn.gif")."' width=8 />";
	echo "<tr><td>".$r->PGL_ID."</td><td>".$r->PGL_NAME."</td><td>".$r->PGL_ADDR."</td><td>".$r->PGL_CONTACT_NO."</td><td align='center'>".$icon_enable_fee."</td>";
	echo "<td align='right'><a href='".site_url("/pgl/ten/".$r->PGL_ID)."'>".number_format($r->JML_TEN)."</a></td>";
	echo "<td align='right'>".number_format($r->JML_ND)."</td>";
	echo "<td nowrap><a href='".site_url("/pgl/mou/".$r->PGL_ID)."'>PKS</a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/pgl/pgledit/".$r->PGL_ID)."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/pgl/pgldel/".$r->PGL_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='delpgl' title='".$r->PGL_NAME."' /></a>";
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