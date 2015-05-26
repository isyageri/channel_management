<div class='title'>BA Document</div>

<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;
$array_period_m[""] = "-- Month --"; foreach($this->M_npk->months as $k => $v) $array_period_m[$k] = $v;
$array_period_y[""] = "-- Year --"; foreach($this->M_npk->years as $k => $v) $array_period_y[$k] = $v;

echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/doc/ba'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Pengelola</td><td>:</td><td>".form_dropdown('f_pgl_id', $array_pgl, (isset($_POST['f_pgl_id']))?array($_POST['f_pgl_id']):array() )."</td></tr>";
echo "<tr><td>Period</td><td>:</td><td>".form_dropdown("f_period_m", $array_period_m, (isset($_POST['f_period_m']))?array($_POST['f_period_m']):array() ).
" ".form_dropdown("f_period_y", $array_period_y, (isset($_POST['f_period_y']))?array($_POST['f_period_y']):array() )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";

?>

<table class='tablesorter' id='tb_doc_ba'>
<thead><tr><th>No.</th><th>Pengelola</th><th>Period</th><th>No. PKS</th><th>Date of PKS</th><th>Status</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$no."</td><td>".$pgl[$r->PGL_ID]."</td><td>".$r->PERIOD."</td><td>".$r->MOU_NO."</td>";
	echo "<td>".$r->MOU_DATE."</td>";
	echo "<td>".$this->M_npk->status[$r->STATUS]."</td>";
	echo "<td nowrap><a href='".site_url("/doc/badoc/".$r->NPK_ID)."'><img src='".image_asset_url('msword.png')."' width=18 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/doc/basetmou/".$r->NPK_ID)."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
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