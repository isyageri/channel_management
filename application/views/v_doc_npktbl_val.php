<div class='title'>Arrange NPK Table > Data<span style='display:inline;float:right'><a href='<?=site_url("/doc/npk"); ?>'><< back</a></span></div>


<?php
echo "<div class='sub_title'>NPK : <b>".$pgl[0]->PGL_NAME." - ".$dt[0]->PERIOD." - ".$this->M_npk->methods[$dt[0]->METHOD]."</b></div>";
//echo "<div class='sub_title'>Period : <b>".$dt[0]->PERIOD."</b></div>";
//echo "<div class='sub_title'>Method : <b>".$this->M_npk->methods[$dt[0]->METHOD]."</b></div>";
echo "<br>";//echo "<pre>"; print_r($tbl); echo "</pre>";

$colnum = $this->M_npk->getColNum($dt[0]->NPK_ID);
$rownum = $this->M_npk->getRowNum($dt[0]->NPK_ID);
$stepnum = $this->M_npk->getStepNum($dt[0]->NPK_ID);
$colval_0 = $this->M_npk->getColsData($dt[0]->NPK_ID, 0);
$rowval_1 = $this->M_npk->getRowsData($dt[0]->NPK_ID, 1);
$rowcalctd = round($stepnum/3);
if(($stepnum%3)>0) $rowcalctd = $rowcalctd + 1;
$rowcalctd = ($rowcalctd*2) -1 ; 

echo form_open("/doc/npktbldatado/".$dt[0]->NPK_ID);
echo form_hidden("npk_id", $dt[0]->NPK_ID);
echo "<input type='submit' name='logical' value='<< Arrange Logical' />";
echo "&nbsp;&nbsp;<input type='submit' name='submit' value='Submit' /><hr><br>";

	echo "<u>Data Tabular</u><br>";
	echo "<table class='tablesorter'>";
	echo "<thead><tr>";
	for($i=1; $i<=$colnum; $i++)	
		echo "<th align='center'>Col #".$i."<br>".$colval_0[$i]["CF_LABEL"]."</th>";
	echo "</tr><thead>";
	echo "<tbody>";
	for($i=1; $i<count($rowval_1); $i++) {
		if($rowval_1[$i]["VALUE_AS"]=="LCF") {
			$pcomp_1 = $this->M_npk->getProcessComView($dt[0]->NPK_ID, $rowval_1[$i]["CF_ID"], $rowval_1[$i]["VALUE_AS"]);
			$pcomp_2 = $this->M_npk->getProcessComView($dt[0]->NPK_ID, $rowval_1[$i]["CF_ID"], "NOM");
			echo "<tr>";
			echo "<td>".$pcomp_1."</td><td align='right'>".number_format($pcomp_2)."</td>";
			for($j=3; $j<=$colnum; $j++) {
				$cellattr = $this->M_npk->getCell($dt[0]->NPK_ID, $j, $i);
				if(isset($cellattr[0]->VALUE_AS) && $cellattr[0]->VALUE_AS!="") {
					if($cellattr[0]->VALUE_AS=="LBL") {
						$cellv = $cellattr[0]->CF_LABEL;
					} else {
						$cf = $this->M_compfee->getLists("CF_ID=".$cellattr[0]->CF_ID);
						$cellv = $cf[0]->CF_NAME;
					}
					echo "<td>".form_input("cell[".$i."][".$j."]", $cellv, "size=15");
					echo " ".form_dropdown("typecell[".$i."][".$j."]", $this->M_npk->cellvaluetypes, array($cellattr[0]->VALUE_AS))."</td>";
				} else {
					echo "<td>".form_input("cell[".$i."][".$j."]", "", "size=15")." ".form_dropdown("typecell[".$i."][".$j."]", $this->M_npk->cellvaluetypes)."</td>";
				}
			}
			echo "</tr>";
		} else {
			echo "<tr>";
			echo "<td>".$rowval_1[$i]["CF_LABEL"]."</td>";
			for($j=2; $j<=$colnum; $j++) {
				//echo "<td>".form_input("cell[".$i."][".$j."]", "", "size=15")." ".form_dropdown("typecell[".$i."][".$j."]", $this->M_npk->cellvaluetypes)."</td>";
				$cellattr = $this->M_npk->getCell($dt[0]->NPK_ID, $j, $i);
				if(isset($cellattr[0]->VALUE_AS) && $cellattr[0]->VALUE_AS!="") {
					if($cellattr[0]->VALUE_AS=="LBL") {
						$cellv = $cellattr[0]->CF_LABEL;
					} else {
						$cf = $this->M_compfee->getLists("CF_ID=".$cellattr[0]->CF_ID);
						$cellv = $cf[0]->CF_NAME;
					}
					echo "<td>".form_input("cell[".$i."][".$j."]", $cellv, "size=15");
					echo " ".form_dropdown("typecell[".$i."][".$j."]", $this->M_npk->cellvaluetypes, array($cellattr[0]->VALUE_AS))."</td>";
				} else {
					echo "<td>".form_input("cell[".$i."][".$j."]", "", "size=15")." ".form_dropdown("typecell[".$i."][".$j."]", $this->M_npk->cellvaluetypes)."</td>";
				}
			}
			echo "</tr>";
		}
	}
	echo "</tbody>";
	echo "</table><br>";

	echo "<u>Calculation</u><br>";
	echo "<table class='tablesorter'>";
	echo "<thead><tr>";
	echo "<th align='center'>Data Billing</th>";
	echo "<th align='center'>User Defined</th>";
	for($i=1; $i<=$stepnum; $i++) {
		echo "<th align='center'>Calculation - ".$i."</th>";
		if($i==3) break;
	}
	echo "</tr><thead>";
	echo "<tbody><tr>";
	echo "<td rowspan=".$rowcalctd.">";
	$step = $this->M_npk->getProcess($dt[0]->NPK_ID, 0);
	foreach($step as $k => $v) {
		echo "<button class='btntbl' title='".$v->STR_FORMULA."' cf_id='".$v->CF_ID."'>".$v->CF_NAME."</button><br>";
	}
	echo "</td>";
	echo "<td rowspan=".$rowcalctd.">";
	foreach($udef as $k=>$r) {
		echo "<button class='btntbl' title='".$r->STR_FORMULA."' cf_id='".$r->CF_ID."'>".$r->CF_NAME."</button><br>";
	}
	echo "</td>";
	for($i=1; $i<=$stepnum; $i++) {
		echo "<td>";
		$step = $this->M_npk->getProcess($dt[0]->NPK_ID, $i);
		foreach($step as $k => $v) {
			echo "<button class='btntbl' title='".$v->STR_FORMULA."' cf_id='".$v->CF_ID."'>".$v->CF_NAME."</button><br>";
			echo "~".$v->STR_FORMULA."<br><br>";
		}
		echo "</td>";
		if(($i%3)==0 && $i<$stepnum) {
			echo "</tr><tr>";
			for($j=($i+1); $j<=($i+3); $j++) {
				echo "<td align='center' height=20><b>Calculation - ".$j."</b></td>";
			}
			echo "</tr><tr>";
		}
	}
	echo "</tr>";
	echo "</tbody>";
	echo "</table>";
echo "<br><hr><input type='submit' name='logical' value='<< Arrange Logical' />";
echo "&nbsp;&nbsp;<input type='submit' name='submit' value='Submit' />";
echo form_close();
?>
