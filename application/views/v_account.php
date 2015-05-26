<div style='padding: 5px; text-align: right; font-family: "Century Gothic", Arial;'>
<?php
echo "<b style='color: #fff; text-decoration:none'>".$this->session->userdata("d_user_name")." </b> ".
"&nbsp;&nbsp;&nbsp;&nbsp;<a href='".site_url("/pr/myprof")."'><img src='".image_asset_url("setting.png")."' title='my profile' width=16 /></a>".
"&nbsp;&nbsp;&nbsp;&nbsp;<a href='".site_url("/home/logout")."'><img src='".image_asset_url("logout.png")."' title='log out' /></a>";
//" | <a href='".site_url("/home/logout")."'>log out</a>";
?>
</div>