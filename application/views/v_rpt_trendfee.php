<div class='title'>Trend Marketing Fee</div>

<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;
$array_period_y[""] = "-- Year --"; foreach($this->M_npk->years as $k => $v) $array_period_y[$k] = $v;

echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/rpt/trendfee'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Year</td><td>:</td><td>".form_dropdown("f_period_y", $array_period_y, (isset($_POST['f_period_y']) && $_POST['f_period_y']!="" )?array($_POST['f_period_y']):array(date("Y")) )."</td></tr>";
echo "<tr><td>Pengelola</td><td>:</td>";
echo "<td>".form_dropdown('f_pgl_id', $array_pgl, array() )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div><br>";
//echo "<pre>";print_r($dt); echo "</pre>";

$js_data = array(); $js_label = array(); 
foreach($dt as $k => $r) {
	$mon = substr($r->PERIOD, 4,2);
	if(isset($rev[$mon])) $nrev = ($rev[$mon]->CF_NOM/1000000);
	else $nrev = 0;
	//$js_data[] = "[".($r->FEE_TOTAL/1000000)."]"; // jutaan
	$js_data[] = "[".($r->FEE_TOTAL/1000000).", ".($nrev)."]"; // jutaan
	$js_label[] = "'".$this->M_npk->months[$mon]."'";
}

echo "<canvas id='cvs_fee' width='800' height='250'>[No canvas support]</canvas>";
echo "<br><i>*) Angka dalam satuan juta rupiah</i><br><br>";

echo "<table class='tablesorter' style='width: 450px;'>";
echo "<thead><tr><th align='center'>Period</th><th align='center'>Revenue</th><th align='center'>Marketing Fee</th><th align='center'>Prosentase</th></tr></thead>";
echo "<tbody>";

$no = 1;
foreach($dt as $k => $r) {
	$mon = substr($r->PERIOD, 4,2);
	if(isset($rev[$mon])) $nrev = ($rev[$mon]->CF_NOM);
	else $nrev = 0;
	echo "<tr><td>".$r->PERIOD."</td><td align='right'>".number_format($nrev)."</td>";
	echo "<td align='right'>".number_format($r->FEE_TOTAL)."</td>";
        echo "<td align='right'>".number_format(($r->FEE_TOTAL/$nrev)*100, 2)." %</td>";
	echo "</tr>";
	$no++;
}

echo "</tbody>";
echo "</table>";

echo "<script language='javascript'>";
echo "var data = [".implode(", ", $js_data)."];";
echo "var bar = new RGraph.Bar('cvs_fee', data);";
echo "bar.Set('chart.labels', [".implode(", ", $js_label)."]);";
echo "bar.Set('chart.key', ['Marketing Fee', 'Revenue']);";
echo "bar.Set('chart.position.y', 35);";
echo "bar.Set('chart.key.position', 'gutter');";
echo "bar.Set('chart.gutter.left', 80);";
echo "bar.Set('chart.colors', ['#77f', '#7f7']);";
/*echo "bar.Set('chart.shadow', true);";
echo "bar.Set('chart.shadow.blur', 15);";
echo "bar.Set('chart.shadow.offsetx', 0);";
echo "bar.Set('chart.shadow.offsety', 0);";
echo "bar.Set('chart.shadow.color', '#aaa');";*/
echo "bar.Set('chart.strokestyle', 'rgba(0,0,0,0)');";
echo "bar.Set('chart.variant', '3d');";
echo "bar.Draw();";
echo "</script>";

?>

