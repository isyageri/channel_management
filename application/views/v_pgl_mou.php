<div class='title'>PKS<span style='display:inline;float:right'><a href='<?=site_url("/pgl"); ?>'><< back</a></span></div>

<?php
echo "<div class='sub_title'>".$pgl[0]->PGL_NAME."</div>";
echo "<div id='navigation'><ul>";
echo "<li><a href='".site_url('/pgl/mouadd/'.$pgl[0]->PGL_ID)."'><img src='".image_asset_url('add.gif')."' width=24 /></a></li>";
echo "</ul></div>";
?>

<table class='tablesorter'>
<thead><tr><th>PKS No.</th><th>START DATE</th><th>END DATE</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td>".$r->MOU_NO."</td><td>".$r->START_DATE."</td><td>".$r->END_DATE."</td>";
	echo "<td nowrap><a href='".site_url("/pgl/mouedit/".$r->PGL_ID."/".str_replace("/", "slash", $r->MOU_NO))."'><img src='".image_asset_url('edit.gif')."' width=15 /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/pgl/moudel/".$r->PGL_ID."/".str_replace("/", "slash", $r->MOU_NO))."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$r->MOU_NO."' /></a>";
	echo "&nbsp;&nbsp;<a href='".site_url("/pgl/amd/".$r->PGL_ID."/".str_replace("/", "slash", $r->MOU_NO))."'>amandemen</a>";
	echo "</td>";
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>
