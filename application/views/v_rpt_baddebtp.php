<div class='title'>Bad Debth Payment</div>

<?php
$array_period_m[""] = "-- Month --"; foreach($this->M_npk->months as $k => $v) $array_period_m[$k] = $v;
$array_period_y[""] = "-- Year --"; foreach($this->M_npk->years as $k => $v) $array_period_y[$k] = $v;

echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/rpt/baddebtp'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Period</td><td>:</td><td>".form_dropdown("f_period_m", $array_period_m, (isset($_POST['f_period_m']))?array($_POST['f_period_m']):array(date("m")) ).
" ".form_dropdown("f_period_y", $array_period_y, (isset($_POST['f_period_y']))?array($_POST['f_period_y']):array(date("Y")) )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";
/*if(count($dt) > 0) {
	echo "<div id='navigation'><ul>";
	echo "<li><a href='".site_url('/rpt/ndchurnsheet/'.$_POST['f_pgl_id'].'/'.$_POST['ten_id'].'/'.$_POST['f_period_y'].$_POST['f_period_m'])."'><img src='".image_asset_url('xls.gif')."' width=15 /></a></li>";
	echo "</ul></div><br>";
}*/

echo "<i>Total = </i><b>".number_format($total)."</b>";
?>

<table class='tablesorter' id='tb_c2bi'>
<thead><tr><th>GL Account</th><th>Jumlah</th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->GL_ACCOUNT."</td><td align='right'>".number_format($r->BILL_AMOUNT)."</td>";
	echo "</tr>";
	$no++;
}
	
?>

</tbody>
</table>
<?
$this->M_profiling->pagination();
?>