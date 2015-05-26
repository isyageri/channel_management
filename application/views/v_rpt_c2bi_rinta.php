<div class='title'>Rekap Rincian Tagihan</div>

<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$k] = $v;
$array_ten[""] = "-- Select Tenant --"; foreach($ten as $k => $v) $array_ten[$k] = $v;
$array_period_m[""] = "-- Month --"; foreach($this->M_npk->months as $k => $v) $array_period_m[$k] = $v;
$array_period_y[""] = "-- Year --"; foreach($this->M_npk->years as $k => $v) $array_period_y[$k] = $v;

echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/rpt/c2birinta'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>Pengelola</td><td>:</td>";
echo "<td>".form_dropdown('f_pgl_id', $array_pgl, array() , "class='pgl_to_ten'")."</td></tr>";
echo "<tr><td>Tenant</td><td>:</td>";
echo "<td><span class='ct_ten_of_pgl'>".form_dropdown('ten_id', $array_ten, array() , "class='ten_of_pgl'")."</span></td></tr>";
echo "<tr><td>Period</td><td>:</td><td>".form_dropdown("f_period_m", $array_period_m, (isset($_POST['f_period_m']))?array($_POST['f_period_m']):array(date("m")) ).
" ".form_dropdown("f_period_y", $array_period_y, (isset($_POST['f_period_y']))?array($_POST['f_period_y']):array(date("Y")) )."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";
if(count($dt) > 0) {
	echo "<div id='navigation'><ul>";
	echo "<li><a href='".site_url('/rpt/rintasheet/'.$_POST['f_pgl_id'].'/'.$_POST['ten_id'].'/'.$_POST['f_period_y'].$_POST['f_period_m'])."'><img src='".image_asset_url('xls.gif')."' width=15 /></a></li>";
	echo "</ul></div><br>";
}

echo "<i>Line In Service = </i>".count($dt);
?>

<table class='tablesorter' id='tb_c2bi'>
<thead><tr><th>ND</th><th>ABONEMEN</th><th>KREDIT</th><th>DEBET</th><th>LOKAL</th><th>INTERLOKAL</th><th>SLJJ</th>
<th>SLI007</th><th>SLI001</th><th>SLI008</th><th>SLI009</th><th>TELKOM GLOBAL 017</th>
<th>TELKOMNET INSTAN</th><th>TELKOMSAVE</th><th>STB</th><th>JAPATI</th><th>SPEEDY USAGE</th>
<th>NON JASTEL</th><th>ISDN DATA</th><th>ISDN VOICE</th><th>KONTEN</th><th>PORTWHOLESALES</th><th>METERAI</th><th>PPN</th>
<th>TOTAL RINCIAN</th><th>GRAND TOTAL</th><th>STATUS BAYAR</th><th>TGL BAYAR</th><th>NAMA PLG</th></tr></thead>
<tbody>
<?php
$no = 1;
$jml_abon = $jml_tckc = $jml_tckd = $jml_lokal = $jml_inter = $jml_sljj = $jml_sli007 = $jml_sli001 = $jml_sli008 = $jml_sli009 = $jml_sli017 = $jml_tinstan = $jml_tsave = $jml_stb = $jml_japati = $jml_speedy = $jml_nonjastel = $jml_isdn_data = $jml_isdn_voice = $jml_konten = $jml_pws = $jml_mtr =$jml_ppn = $jml_rinta = $jml_gto = 0;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->ND1."</td><td align='right'>".number_format(@$r->ABONEMEN)."</td>";
	echo "<td align='right'>".number_format(@$r->MNT_TCK_C)."</td><td align='right'>".number_format(@$r->MNT_TCK_D)."</td>";
	echo "<td align='right'>".number_format(@$r->LOKAL)."</td><td align='right'>".number_format(@$r->INTERLOKAL)."</td>";
	echo "<td align='right'>".number_format(@$r->SLJJ)."</td><td align='right'>".number_format(@$r->SLI007)."</td>";
	echo "<td align='right'>".number_format(@$r->SLI001)."</td><td align='right'>".number_format(@$r->SLI008)."</td>";
	echo "<td align='right'>".number_format(@$r->SLI009)."</td><td align='right'>".number_format(@$r->SLI_017)."</td>";
	echo "<td align='right'>".number_format(@$r->TELKOMNET_INSTAN)."</td><td align='right'>".number_format(@$r->TELKOMSAVE)."</td>";
	echo "<td align='right'>".number_format(@$r->STB)."</td><td align='right'>".number_format(@$r->JAPATI)."</td>";
	echo "<td align='right'>".number_format(@$r->USAGE_SPEEDY)."</td><td align='right'>".number_format(@$r->NON_JASTEL)."</td>";
	echo "<td align='right'>".number_format(@$r->ISDN_DATA)."</td><td align='right'>".number_format(@$r->ISDN_VOICE)."</td>";
	echo "<td align='right'>".number_format(@$r->KONTEN)."</td><td align='right'>".number_format(@$r->PORTWHOLESALES)."</td>";
  echo "<td align='right'>".number_format(@$r->METERAI)."</td><td align='right'>".number_format(@$r->PPN)."</td>";
	echo "<td align='right'>".number_format(@$r->TOTAL)."</td><td align='right'><b>".number_format(@$r->GRAND_TOTAL)."</b></td>";
	echo "<td align='right'>".$r->STATUS_PEMBAYARAN."</td>";
	echo "<td align='right'>".$r->TGL_BYR."</td>";
	echo "<td align='right'>".$r->NOM."</b></td>";
	
	//echo "<td></td>";
	$jml_abon += @$r->ABONEMEN;
	$jml_tckc += @$r->MNT_TCK_C;
	$jml_tckd += @$r->MNT_TCK_D;
	$jml_lokal += @@$r->LOKAL;
	$jml_inter += @$r->INTERLOKAL;
	$jml_sljj += @$r->SLJJ;
	$jml_sli007 += @$r->SLI007;
	$jml_sli001 += @$r->SLI001;
	$jml_sli008 += @$r->SLI008;
	$jml_sli009 += @$r->SLI009;
	$jml_sli017 += @$r->SLI_017;
	$jml_tinstan += @$r->TELKOMNET_INSTAN; 
	$jml_tsave += @$r->TELKOMSAVE;
	$jml_stb += @$r->STB;
	$jml_japati += @$r->JAPATI;
	$jml_speedy += @$r->USAGE_SPEEDY;
	$jml_nonjastel += @$r->NON_JASTEL;
	$jml_isdn_data += @$r->ISDN_DATA;
	$jml_isdn_voice += @$r->ISDN_VOICE;
	$jml_konten += @$r->KONTEN;
	$jml_pws += @$r->PORTWHOLESALES;
	$jml_mtr += @$r->METERAI;
	$jml_ppn += @$r->PPN;
	$jml_rinta += @$r->TOTAL;
	$jml_gto += @$r->GRAND_TOTAL;


	echo "</tr>";
	$no++;
}
	echo "<tr><td><b>TOTAL</b></td>";
	echo "<td align='right'><b>".number_format($jml_abon)."</b></td><td align='right'><b>".number_format($jml_tckc)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_tckd)."</b></td><td align='right'><b>".number_format($jml_lokal)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_inter)."</b></td><td align='right'><b>".number_format($jml_sljj)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_sli007)."</b></td><td align='right'><b>".number_format($jml_sli001)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_sli008)."</b></td><td align='right'><b>".number_format($jml_sli009)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_sli017)."</b></td><td align='right'><b>".number_format($jml_tinstan)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_tsave)."</b></td><td align='right'><b>".number_format($jml_stb)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_japati)."</b></td><td align='right'><b>".number_format($jml_speedy)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_nonjastel)."</b></td><td align='right'><b>".number_format($jml_isdn_data)."</b></td>";
	echo "<td align='right'><b>".number_format($jml_isdn_voice)."</b></td><td align='right'><b>".number_format($jml_konten)."</b></td>";
	echo "<td align='right'>".number_format($jml_pws)."</td><td align='right'>".number_format($jml_mtr)."</td>";
	echo "<td align='right'>".number_format($jml_ppn)."</td><td align='right'>".number_format($jml_rinta)."</td>";
	echo "<td align='right'><b>".number_format($jml_gto)."</b></td><td align='right'>".number_format(0)."</td>";
	echo "<td align='right'>".number_format(0)."</td><td align='right'>".number_format(0)."</td>";
	
?>

</tbody>
</table>
<?
$this->M_profiling->pagination();
?>