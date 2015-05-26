<div class='title'>Tagihan Non POTS</div>

<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;
//$array_ten[""] = "-- Select Tenant --"; foreach($ten as $k => $v) $array_ten[$k] = $v;
$array_period_m[""] = "-- Month --"; foreach($this->M_npk->months as $k => $v) $array_period_m[$k] = $v;
$array_period_y[""] = "-- Year --"; foreach($this->M_npk->years as $k => $v) $array_period_y[$k] = $v;

echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/rpt/nonpots'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Pengelola</td><td>:</td>";
echo "<td>".form_dropdown('f_pgl_id', $array_pgl, array() , "class='pgl_to_ten'")."</td></tr>";
/*echo "<tr><td>Tenant</td><td>:</td>";
echo "<td><span class='ct_ten_of_pgl'>".form_dropdown('ten_id', $array_ten, array() , "class='ten_of_pgl'")."</span></td></tr>";*/
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

//echo "<i>Count = </i>".count($dt);
?>

<table class='tablesorter' id='tb_c2bi'>
<thead><tr><th>No.</th><th>Pengelola</th><th>Product</th><th>Contract No.</th><th>Contract - Start</th><th>Contract - End</th><th>Abonemen</th><th>Tagihan</th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$no."</td><td>".$r->PGL_NAME."</td><td>".$r->PRODUCT_NAME."</td><td>".$r->CONTRACT_NO."</td>";
        echo "<td>".$r->START_DAT."</td><td>".$r->END_DAT."</td>";
        echo "<td align='right'>".number_format($r->ABONEMEN)."</td><td align='right'>".number_format($r->BILL_MNY)."</td>";
	echo "</tr>";
	$no++;
}
	
?>

</tbody>
</table>
<?
$this->M_profiling->pagination();
?>