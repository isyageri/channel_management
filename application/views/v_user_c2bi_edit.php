<div class='title'>Set User C2Bi</div>
<div class='sub_title'></div>
<?php
$array_pgl[""] = "-- Select Pengelola --"; foreach($pgl as $k => $v) $array_pgl[$v->PGL_ID] = $v->PGL_NAME;

echo form_open("/pr/userc2bieditdo");
echo form_hidden("user_id", $dt[0]->USER_ID);
echo form_hidden("nik", $dt[0]->NIK);
echo "<table class='form'>";
echo "<tr><td>NIK</td><td>:</td><td><b>".$dt[0]->NIK."</b></td></tr>";
echo "<tr><td>Name</td><td>:</td><td>".$dt[0]->USER_NAME."</td></tr>";
echo "<tr><td valign='top'>Pilihan Pengelola</td><td valign='top'>:</td>";
echo "<td valign='top'>".form_dropdown("pgl_id", $array_pgl)."&nbsp;<input type='submit' name='submit' value='Add' /></td></tr>";
echo "<tr><td valign='top'>Pengelola yang Dipilih</td><td valign='top'>:</td>";
echo "<td valign='top'><div style='line-height:180%'>";
foreach($pgl_select as $k => $v) {
	echo "<a href='".site_url("/pr/userc2bidel/".$dt[0]->USER_ID."/".$v->PGL_ID)."'><img src='".image_asset_url('del.gif')."' width=15 class='del' title='".$v->PGL_NAME."' /></a>&nbsp;&nbsp;".$v->PGL_NAME."<br>";
}
echo "</div></td></tr>";

echo "<tr><td></td><td></td><td><br><input type='submit' name='reset' value='OK' /></td></tr>";
echo "</table>";
echo form_close();
?>