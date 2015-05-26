<div class='title'>Tier's Conditional<span style='display:inline;float:right'><a href='<?=site_url("/comp/tier"); ?>'><< back</a></span></div>

<?php
echo "<div class='sub_title'>Tier: ".$tier[0]->TIER_NAME."</div>";

/*echo "<div id='filterform' style='width:800px'>";
echo form_open(site_url('/pr/profile'));
echo form_fieldset("<span style='cursor:hand' onclick=\"toggleFieldset(document.getElementById('filtertable'));\">Filter</span>");
echo "<table id='filtertable'>";
echo "<tr><td>No. Anggota</td><td>:</td><td>".form_input('f_member_code', (isset($_POST['f_member_code']))?$_POST['f_member_code']:'', 'size=20')."</td></tr>";
echo "<tr><td colspan=3 align='right'>".form_submit('filter', 'OK','')."</td></tr>";
echo "</table>";
echo form_fieldset_close('');
echo form_close();
echo "</div>";*/

echo "<div id='navigation'><ul>";
echo "<li><a href='".site_url('/comp/tiercondadd/'.$tier[0]->TIER_ID)."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter'>
<thead><tr><th>Seq.</th><th>Conditional Logic</th><th>Result</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->SEQ_NO."</td><td>".$r->STR_COND."</td><td>".$r->NRESULT."</td>";
	echo "<td nowrap><a href='".site_url("/comp/tiercondedit/".$r->TIER_ID."/".$r->SEQ_NO)."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/comp/tierconddel/".$r->TIER_ID."/".$r->SEQ_NO)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='{".$tier[0]->TIER_NAME."} - ".$r->SEQ_NO."' /></a></td>";
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>