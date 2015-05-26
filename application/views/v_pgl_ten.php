<div class='title'>Pengelola > Tenant<span style='display:inline;float:right'><a href='<?=site_url("/pgl"); ?>'><< back</a></span></div>

<?php
echo "<div class='sub_title'>".$pgl[0]->PGL_NAME."</div>";
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

<table class='tablesorter' id='tb_pgl_ten'>
<thead><tr><th>TEN ID</th><th>Tenant</th><th>Address</th><th>Phone No.</th><th>LIS</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->TEN_ID."</td><td>".$r->TEN_NAME."</td><td>".$r->TEN_ADDR."</td><td>".$r->TEN_CONTACT_NO."</td>";
	echo "<td align='right'><a href='".site_url("/ten/nd/".$r->TEN_ID)."'>".number_format($r->JML_ND)."</a></td>";
	echo "<td nowrap><a href='".site_url("/pgl/tendel/".$pgl[0]->PGL_ID."/".$r->TEN_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$r->TEN_NAME."' /></a>";
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