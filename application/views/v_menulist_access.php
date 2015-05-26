<?php
echo "<div class='title'>Profile > Menu Access > ".$prof[0]->PROF_NAME;
echo "<span style='display:inline;float:right'><a href='".site_url("/pr/profile")."'><< back</a></span></div>";
echo "<div class='sub_title'>".$this->M_profiling->getBreadcrumb($parent)."</div>";
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
if($parent>0) {
	$grandparent = $this->M_profiling->getMenuAll("MENU_ID=".$parent);
	echo "<li><a href='".site_url('/pr/menuchildacc/'.$prof[0]->PROF_ID."/".$grandparent[0]->MENU_PARENT)."'>UP</a></li>";
	echo "<li><a href='".site_url('/pr/menuacc/'.$prof[0]->PROF_ID)."'>/ROOT</a></li>";
}
echo "</ul></div>";

echo form_open("/pr/setmenuacc");
echo form_hidden('parent', $parent);
echo form_hidden('prof_id', $prof[0]->PROF_ID);
echo form_hidden('menu_id', '');
echo form_hidden('is_checked', '');
?>

<table class='tablesorter'>
<thead><tr><th>Menu Name</th><th>Link Address</th><th></th></tr></thead>
<tbody>
<?php
$no = 1;
foreach($dt as $k => $r) {
	echo "<tr><td><a href='".site_url("/pr/menuchildacc/".$prof[0]->PROF_ID."/".$r->MENU_ID)."'>".$r->MENU_NAME."</a></td><td>".$r->MENU_LINK."</td>";
	echo "<td nowrap>".form_checkbox("menu_checked[]", 
		$r->MENU_ID, $this->M_profiling->getAssignMenu($r->MENU_ID, $prof[0]->PROF_ID), 
		"onclick=\"this.form.elements['menu_id'].value='".$r->MENU_ID."'; 
		this.form.elements['is_checked'].value=this.checked; this.form.submit();\"")."</td>";
	/*echo "<td align='center'><input type='checkbox' name='menu_checked[]' value='".$r->MENU_ID."' ";
			echo "onclick=\"this.form.elements['menu_id'].value='".$rec->MENU_ID."'; ";
			echo "this.form.elements['is_checked'].value=this.checked; this.form.submit();\" ".(($rec->CHECKED==1)?"checked":"")." />&nbsp;&nbsp;&nbsp;";
			echo "<td>";*/
	echo "</tr>";
	$no++;
}
?>
</tbody>
</table>

<?php
echo form_close();
?>