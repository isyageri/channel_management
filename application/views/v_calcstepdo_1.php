<div class='title'>Calculate Step by Step<span style='display:inline;float:right'><a href='<?=site_url("/calc/step"); ?>'><< back</a></span></div>


<?php
echo "<div class='sub_title'>NPK : <b>".$pgl[0]->PGL_NAME." - ".$dt[0]->PERIOD." - ".$this->M_npk->methods[$dt[0]->METHOD]."</b></div>";
//echo "<div class='sub_title'>Period : <b>".$dt[0]->PERIOD."</b></div>";
//echo "<div class='sub_title'>Method : <b>".$this->M_npk->methods[$dt[0]->METHOD]."</b></div>";
if($dt[0]->FEE_CF_ID!=0 && $dt[0]->FEE_CF_ID!="") 
	echo "<div class='sub_title'>Marketing Fee + PPN = <b>".number_format($dt[0]->FEE_TOTAL, 2)."</b></div>";
echo "<br>";

echo form_open("/calc/tostep1do/".$dt[0]->NPK_ID);
echo form_hidden("npk_id", $dt[0]->NPK_ID);
echo form_hidden("step", 1);
echo "<br><a href='".site_url("/npk/loadfml/".$dt[0]->NPK_ID)."'>Load Formula</a>";
echo "&nbsp;&nbsp;<input type='submit' name='submit' value='Submit Formula' />";
echo "&nbsp;&nbsp;<input type='submit' name='calculate' value='Calculate' />";
if(count($proc_exist)>0) { echo "&nbsp;&nbsp;<input type='submit' name='next' value='Next Step >>' />"; }
echo "<hr><br>";

echo "<table>";
echo "<tr><td valign='top'>";
	echo "<u>Data Billing</u>";
	echo "<table>";
	echo "<tbody>";
	foreach($proc as $k=>$r) {
		echo "<tr><td><button class='btnfml'>[".$r->CF_NAME."]</button></td><td>= </td><td align='right'>".number_format($r->CF_NOM, 2)."</td>";
		echo "<td><a href='".site_url("/calc/compdel/".$dt[0]->NPK_ID."/".$r->CF_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='[".$r->CF_NAME."]' /></a></td></tr>";
	}
	echo "<tr><td><a href='".site_url("/calc/addcomp0/".$dt[0]->NPK_ID)."'>Add Component Fee</a></td><td colspan=3></td></tr>";
	echo "</tbody>";
	echo "</table>";
echo "</td><td valign='middle'>";
	echo "<img src='".image_asset_url("right_arrow.png")."' width=50 />";
echo "</td><td valign='top'>";
	echo "<u><b>Calculation - 1</b></u>";
	echo "<table>";
	echo "<tbody>";
	$i = 1;
	if(count($proc_exist)>0) {
		foreach($proc_exist as $k=> $v) {
			echo "<tr><td valign='top'>".form_textarea(array("name"=>"str_formula_exist[".$v->CF_ID."]", "cols"=>30, "rows"=>3), $v->STR_FORMULA)."</td><td valign='top'>=</td>";
			echo "<td valign='top' align='right'>".number_format($v->CF_NOM, 2)."</td>";
			echo "<td valign='top'><a href='".site_url("/calc/setasfee/".$dt[0]->NPK_ID."/1/".$v->CF_ID)."' class='setasfee'><img src='".image_asset_url("fee.jpg")."' width=18 /></a>";
			echo (($dt[0]->FEE_CF_ID==$v->CF_ID)?"<img src='".image_asset_url("checky.jpg")."' width=15 />":"")."</td></tr>";
			$i++;
		}
	}
	while($i<=count($proc)) {
		echo "<tr><td>".form_textarea(array("name"=>"str_formula[]", "cols"=>30, "rows"=>3), "")."</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		$i++;
	}
	for($i=1; $i<=2; $i++) {
		echo "<tr><td>".form_textarea(array("name"=>"str_formula[]", "cols"=>30, "rows"=>3), "")."</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
	}
	echo "</tbody>";
	echo "</table>";
echo "</td><td valign='middle'>&nbsp;&nbsp;&nbsp;&nbsp;";
echo "</td><td valign='top'>";
	echo "<u>Tools</u>";
	echo "<table>";
	echo "<tbody>";
	echo "<tr><td><button class='btnfml'>1</button></td><td><button class='btnfml'>2</button></td><td><button class='btnfml'>3</button></td></tr>";
	echo "<tr><td><button class='btnfml'>4</button></td><td><button class='btnfml'>5</button></td><td><button class='btnfml'>6</button></td></tr>";
	echo "<tr><td><button class='btnfml'>7</button></td><td><button class='btnfml'>8</button></td><td><button class='btnfml'>9</button></td></tr>";
	echo "<tr><td><button class='btnfml'>*</button></td><td><button class='btnfml'>0</button></td><td><button class='btnfml'>/</button></td></tr>";
	echo "<tr><td><button class='btnfml'>+</button></td><td><button class='btnfml'>%</button></td><td><button class='btnfml'>-</button></td></tr>";
	echo "</tbody>";
	echo "</table>";
	echo "<br><u>User Defined</u><br>";
	foreach($udef as $k=>$r) {
		echo "<button class='btnfml' title='".$r->STR_FORMULA."'>[".$r->CF_NAME."]</button><br>";
	}
	echo "<br><u>Tiering</u><br>";
	foreach($tier as $k=>$r) {
		echo "<button class='btnfml'>{".$r->TIER_NAME.":".$r->TIER_PARAMS."}</button><br>";
	}
echo "</td></tr>";
echo "</table>";
echo "<br><hr><a href='".site_url("/npk/loadfml/".$dt[0]->NPK_ID)."'>Load Formula</a>";
echo "&nbsp;&nbsp;<input type='submit' name='submit' value='Submit Formula' />";
echo "&nbsp;&nbsp;<input type='submit' name='calculate' value='Calculate' />";
if(count($proc_exist)>0) { echo "&nbsp;&nbsp;<input type='submit' name='next' value='Next Step >>' />"; }
echo form_close();
?>
