<div class='title'>Component Fee Type</div>
<div class='sub_title'></div>

<?php
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
echo "<li><a href='".site_url('/comp/typeadd')."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter' id='tb_comp_type'>
<thead><tr><th>Component Name</th><th>Caption</th><th>Source Type</th><th>Formula String</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->CF_NAME."</td><td>".$r->CF_CAPTION."</td><td>".$this->M_compfee->cf_types[$r->CF_TYPE]."</td>";
	echo "<td>".$r->STR_FORMULA."</td>";
	if($r->CF_TYPE!="SDEF") {
		echo "<td nowrap><a href='".site_url("/comp/typeedit/".$r->CF_ID)."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
		echo "&nbsp;&nbsp;<a href='".site_url("/comp/typedel/".$r->CF_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='[".$r->CF_NAME."]' /></a></td>";
	} else {
		echo "<td></td>";
	}
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>
<?
$this->M_profiling->pagination();
?>