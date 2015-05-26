<div class='title'>Tenant's Fastel<span style='display:inline;float:right'><a href='<?=site_url("/ten"); ?>'><< back</a></span></div>

<?php
echo "<div class='sub_title'>".$ten[0]->TEN_NAME."</div>";
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
echo "<li><a href='".site_url('/ten/ndsheet/'.$ten[0]->TEN_ID)."'><img src='".image_asset_url('xls.gif')."' width=15 /></a></li>";
echo "</ul></div><br>";
echo "<i>Line In Service = </i>".count($dt);
?>

<table class='tablesorter' id='tb_ten_nd'>
<thead><tr><th>ND</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->ND."</td>";
	echo "<td nowrap><a href='".site_url("/ten/nddel/".$ten[0]->TEN_ID."/".$r->ND)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$r->ND."' /></a>";
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