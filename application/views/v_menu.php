<?php
echo "<ul class='sf-menu'>";
echo "<li><a href='".site_url("")."'><img src='".image_asset_url("home_icon.png")."' width='20' style='margin-top:-5px; margin-bottom:-5px;' /> Home</a>";
echo "</li>";
foreach($this->M_profiling->getMenuByProf($this->session->userdata("d_prof_id"),0) as $k => $v) {
	echo "<li><a href='".site_url($v->MENU_LINK)."'>".$v->MENU_NAME."</a>";
	echo showSubMenu($this->M_profiling, $this->session->userdata("d_prof_id") ,$v->MENU_ID);
	echo "</li>";
}
echo "</ul>";

function showSubMenu($objprof, $prof_id, $parent) {
	$sub = $objprof->getMenuByProf($prof_id, $parent);
	if(count($sub) > 0) {
		echo "<ul>";
		foreach($sub as $k => $v) {
			echo "<li><a href='".site_url($v->MENU_LINK)."'>".$v->MENU_NAME."</a>";
			echo showSubMenu($objprof, $prof_id,$v->MENU_ID);
			echo "</li>";
		}
		echo "</ul>";
	}
}

