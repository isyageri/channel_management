<div class='title'>Rincian Tagihan Per Nomor</div>

<?php
$array_period_m[""] = "-- Month --"; foreach($this->M_npk->months as $k => $v) $array_period_m[$k] = $v;
$array_period_y[""] = "-- Year --"; foreach($this->M_npk->years as $k => $v) $array_period_y[$k] = $v;

echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/rpt/c2birintano'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Search ND</span>");
echo "<table id='filtertable'>";
echo "<tr><td>ND / Telp No.</td><td>:</td><td>".form_input('f_nd', (isset($_POST['f_nd']))?$_POST['f_nd']:'', 'size=20')."</td></tr>";
echo "<tr><td>Period</td><td>:</td><td>".form_dropdown("f_period_m", $array_period_m, (isset($_POST['f_period_m']))?array($_POST['f_period_m']):array(date("m")) ).
" ".form_dropdown("f_period_y", $array_period_y, (isset($_POST['f_period_y']))?array($_POST['f_period_y']):array(date("Y")) )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";
if(count($dt) > 0) {
	echo "<div id='navigation'><ul>";
	echo "<li><a href='".site_url('/rpt/rintanosheet/'.$_POST['f_nd'].'/'.$_POST['f_period_y'].$_POST['f_period_m'])."'><img src='".image_asset_url('xls.gif')."' width=15 /></a></li>";
	echo "</ul></div><br>";
}
?>

<table class='tablesorter' id='tb_c2bi'>
<thead><tr><th>No.</th><th>Pemanggil</th><th>No. Dipanggil</th><th>Tujuan</th><th>Tanggal / Jam</th><th>Durasi (detik)</th><th>Biaya</th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$no."</td><td>".$r->ND."</td><td>".$r->ND_APPELE."</td><td>".$r->TUJUAN."</td><td>".$r->TGL_JAM."</td>";
	echo "<td align='right'>".$r->DURASI."</td><td align='right'>".number_format($r->BIAYA)."</td>";
	echo "</tr>";
	$no++;
}
?>

</tbody>
</table>
<?
$this->M_profiling->pagination();
?>