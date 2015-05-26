<div class='title'>Amandemen<span style='display:inline;float:right'><a href='<?=site_url("/pgl/mou/".$pgl[0]->PGL_ID); ?>'><< back</a></div>

<?php
echo "<div class='sub_title'>".$pgl[0]->PGL_NAME."</div>";
echo "<div class='sub_title'>".$mou_no."</div>";
echo "<div id='navigation'><ul>";
echo "<li><a href='".site_url('/pgl/amdadd/'.$pgl[0]->PGL_ID."/".str_replace("/", "slash", $mou_no))."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter'>
<thead><tr><th>AMD No.</th><th>Date</th><th>Description</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->AMD_NO."</td><td>".$r->AMD_DATE."</td><td>".$r->AMD_DESC."</td>";
	echo "<td nowrap><a href='".site_url("/pgl/amdedit/".$pgl[0]->PGL_ID."/".str_replace("/", "slash", $r->MOU_NO)."/".str_replace("/", "slash", $r->AMD_NO))."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/pgl/amddel/".$pgl[0]->PGL_ID."/".str_replace("/", "slash", $r->MOU_NO)."/".str_replace("/", "slash", $r->AMD_NO))."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$r->AMD_NO."' /></a>";
	echo "</td>";
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>
