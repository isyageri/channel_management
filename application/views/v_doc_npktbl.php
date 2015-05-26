<div class='title'>Arrange NPK Table > Logical<span style='display:inline;float:right'><a href='<?=site_url("/doc/npk"); ?>'><< back</a></span></div>


<?php
echo "<div class='sub_title'>NPK : <b>".$pgl[0]->PGL_NAME." - ".$dt[0]->PERIOD." - ".$this->M_npk->methods[$dt[0]->METHOD]."</b></div>";
//echo "<div class='sub_title'>Period : <b>".$dt[0]->PERIOD."</b></div>";
//echo "<div class='sub_title'>Method : <b>".$this->M_npk->methods[$dt[0]->METHOD]."</b></div>";
echo "<br>";

echo form_open("/doc/npktbldo/".$dt[0]->NPK_ID);
echo form_hidden("npk_id", $dt[0]->NPK_ID);
echo form_hidden("header[1]", "Komponen Tagihan");
echo form_hidden("header[2]", "Tagihan");

echo "<a href='".site_url("/doc/loadtbl/".$dt[0]->NPK_ID)."'>Load Table Format</a>";
echo "&nbsp;&nbsp;<input type='submit' name='submit' value='Submit' />";
echo "&nbsp;&nbsp;<input type='submit' name='data' value='Arrange Data >>' /><hr><br>";

	echo "<u>Column</u><br>";
	echo "Table has ".form_dropdown("ncol", array(3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8), array($colnum))." columns<br><br>";

	echo "<u>Header</u><br>";
	echo "<table class='tablesorter'>";
	echo "<thead><tr>";
	for($i=1; $i<=8; $i++)	
		echo "<th align='center'>Col #".$i."</th>";
	echo "</tr><thead>";
	echo "<tbody>";
	echo "<td nowrap>Komponen Tagihan</td><td>Tagihan</td>";
	for($i=3; $i<=8; $i++)	
		echo "<td>".form_input("header[".$i."]", ((isset($headers[$i]["CF_LABEL"]))?$headers[$i]["CF_LABEL"]:""), "size=15")."</td>";
	echo "</tbody>";
	echo "</table><br>";

	$nr = 0;
	$rowcomp = array();	$rowagg = array();
	foreach($row1 as $k => $r) {
		if($r["VALUE_AS"] == "LCF") {
			$rowcomp[$r["CF_ID"]] = $r["CF_ID"];
			$nr++;
		} elseif($r["VALUE_AS"] == "LBL" && $k!=0)  {
			$rowagg[$k] = $r["CF_LABEL"];
		}
	}
	echo "<u>Views Component</u>";
	echo "<table>";
	echo "<tbody>";
	foreach($proc as $k=>$r) {
		echo "<tr><td>".form_checkbox("comp[]", $r->CF_ID, (isset($rowcomp[$r->CF_ID])) )."[".$r->CF_NAME."]</td><td>= </td><td align='right'>".number_format($r->CF_NOM, 2)."</td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table><br>";

	echo "<u>Aggregate Rows</u><br>";
	echo "Table has ".form_dropdown("nrow", array(0=>0, 1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8), array(count($rowagg)) )." aggregate row(s)<br><br>";

	echo "<u>Name of Aggregate Rows</u><br>";
	echo "<table class='tablesorter'>";
	echo "<thead><tr>";
	for($i=1; $i<=8; $i++)	
		echo "<th align='center'>Row #Y+".$i."</th>";
	echo "</tr><thead>";
	echo "<tbody>";
	for($i=1; $i<=8; $i++)	
		echo "<td>".form_input("aggrow[".$i."]", (isset($rowagg[$nr+$i])?$rowagg[$nr+$i]:""), "size=14")."</td>";
	echo "</tbody>";
	echo "</table>";
echo "<br><hr>";
echo "<a href='".site_url("/doc/loadtbl/".$dt[0]->NPK_ID)."'>Load Table Format</a>";
echo "&nbsp;&nbsp;<input type='submit' name='submit' value='Submit' />";
echo "&nbsp;&nbsp;<input type='submit' name='data' value='Arrange Data >>' />";
echo form_close();
?>
