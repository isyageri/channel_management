<div class='title'>Input Manual</div>
<div class='sub_title'></div>

<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;

echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/comp/manual'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Pengelola</td><td>:</td><td>".form_dropdown('f_pgl_id', $array_pgl, (isset($_POST['f_pgl_id']))?array($_POST['f_pgl_id']):array() )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";

echo "<div id='navigation'><ul>";
echo "<li><a href='".site_url('/comp/manualadd')."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter' id='tb_ten'>
<thead><tr><th>Pengelola</th><th>Tenant</th><th>Comp. Fee</th><th>Nominal</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->PGL_NAME."</td><td>".$r->TEN_NAME."</td><td>".$r->CF_NAME." </td>";
	echo "<td align='right'>".number_format($r->CF_NOM)."</td>";
	echo "<td nowrap><a href='".site_url("/comp/manualedit/".$r->PGL_ID."/".$r->TEN_ID."/".$r->CF_ID)."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/comp/manualdel/".$r->TEN_ID."/".$r->CF_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$r->TEN_NAME."' /></a>";
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