<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Channel Management</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <script src="<?php echo base_url();?>assets/js/jquery.js"></script>

    <!-- Favicon -->
    <link type="image/x-icon" href="<?php echo base_url(); ?>favicon.png" rel="shortcut icon"/>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.css" />

    <!--JqGrid-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ui.jqgrid.css" />

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace-ie.css" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="<?php echo base_url();?>assets/js/ace-extra.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="<?php echo base_url();?>assets/js/html5shiv.js"></script>
    <script src="<?php echo base_url();?>assets/js/respond.js"></script>
    <![endif]-->
</head>

<body class="no-skin">
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default">
<script type="text/javascript">
    try{ace.settings.check('navbar' , 'fixed')}catch(e){}
</script>

<div class="navbar-container" id="navbar-container">
<!-- #section:basics/sidebar.mobile.toggle -->
<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
    <span class="sr-only">Toggle sidebar</span>

    <span class="icon-bar"></span>

    <span class="icon-bar"></span>

    <span class="icon-bar"></span>
</button>

<!-- /section:basics/sidebar.mobile.toggle -->
<div class="navbar-header pull-left">
    <!-- #section:basics/navbar.layout.brand -->
    <a href="#" class="navbar-brand">
        <small>
            <i class="fa fa-leaf"></i>
            Channel Management
        </small>
    </a>

    <!-- /section:basics/navbar.layout.brand -->

    <!-- #section:basics/navbar.toggle -->

    <!-- /section:basics/navbar.toggle -->
</div>

<!-- #section:basics/navbar.dropdown -->
<div class="navbar-buttons navbar-header pull-right" role="navigation">
<ul class="nav ace-nav">

<!-- #section:basics/navbar.user_menu -->
<li class="light-blue">
    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
        <img class="nav-user-photo" src="<?php echo base_url();?>assets/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
                                    <?php echo $this->session->userdata("d_user_name"); ?>
								</span>

        <i class="ace-icon fa fa-caret-down"></i>
    </a>

    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
        <li>
            <a href="#">
                <i class="ace-icon fa fa-cog"></i>
                Settings
            </a>
        </li>

        <li>
            <a href="<?php echo site_url("/pr/myprof");?>">
                <i class="ace-icon fa fa-user"></i>
                Profile
            </a>
        </li>

        <li class="divider"></li>

        <li>
            <a href="<?php echo site_url("/home/logout");?>">
                <i class="ace-icon fa fa-power-off"></i>
                Logout
            </a>
        </li>
    </ul>
</li>

<!-- /section:basics/navbar.user_menu -->
</ul>
</div>

<!-- /section:basics/navbar.dropdown -->
</div><!-- /.navbar-container -->
</div>

<!-- /section:basics/navbar.layout -->
<div class="main-container" id="main-container">
<script type="text/javascript">
    try{ace.settings.check('main-container' , 'fixed')}catch(e){}
</script>

<!-- #section:basics/sidebar -->
<div id="sidebar" class="sidebar                  responsive">
<script type="text/javascript">
    try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
</script>

<div class="sidebar-shortcuts" id="sidebar-shortcuts">
    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
        <button class="btn btn-success">
            <i class="ace-icon fa fa-signal"></i>
        </button>

        <button class="btn btn-info">
            <i class="ace-icon fa fa-pencil"></i>
        </button>

        <!-- #section:basics/sidebar.layout.shortcuts -->
        <button class="btn btn-warning">
            <i class="ace-icon fa fa-users"></i>
        </button>

        <button class="btn btn-danger">
            <i class="ace-icon fa fa-cogs"></i>
        </button>

        <!-- /section:basics/sidebar.layout.shortcuts -->
    </div>

    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
        <span class="btn btn-success"></span>

        <span class="btn btn-info"></span>

        <span class="btn btn-warning"></span>

        <span class="btn btn-danger"></span>
    </div>
</div><!-- /.sidebar-shortcuts -->

<!-- List Menu -->
<ul class="nav nav-list">
    <li class="active" id="nav_lvl1">
        <a href="<?php echo base_url();?>">
            <i class="menu-icon fa fa-home"></i>
            <span class="menu-text"> Home </span>
        </a>

        <b class="arrow"></b>
    </li>
    <?php
    foreach($this->M_profiling->getMenuByProf($this->session->userdata("d_prof_id"),0) as $k => $v) {
        ?>

        <li class=""><a href="<?php echo site_url($v->MENU_LINK);?>" class="dropdown-toggle"><i class="<?php echo $v->MENU_ICON;?>"> </i>
                <span class="menu-text"> <?php echo $v->MENU_NAME  ;?> </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>

            <?php echo showSubMenu($this->M_profiling, $this->session->userdata("d_prof_id") ,$v->MENU_ID);?>

        </li>
    <?php } ;?>

</ul><!-- /.nav-list -->

<?php
function showSubMenu($objprof, $prof_id, $parent) {
    $sub = $objprof->getMenuByProf($prof_id, $parent);
    if(count($sub) > 0) {
        echo "<ul class='submenu'>";
        foreach($sub as $k => $v) {
            if($v->MENU_LINK == "#"){
                echo "<li class='' id='".$v->FILE_NAME."' ><a href='".site_url($v->MENU_LINK)."' class='dropdown-toggle'><i class='menu-icon fa fa-caret-right'></i>".$v->MENU_NAME." <b class='arrow fa fa-angle-down'></b> </a>";
            }else{
                echo "<li class='setting_nav' id='".$v->FILE_NAME."' href='".site_url($v->MENU_LINK)."'><a href='".site_url($v->MENU_LINK)."'><i class='menu-icon fa fa-caret-right'></i>".$v->MENU_NAME." </a>";
            }

            echo "<b class='arrow'></b>";
            echo showSubMenu($objprof, $prof_id,$v->MENU_ID);
            echo "</li>";
        }

        echo "</ul>";
    }
}
?>



<!-- #section:basics/sidebar.layout.minimize -->
<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
    <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>

<!-- /section:basics/sidebar.layout.minimize -->
<script type="text/javascript">
    try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
</script>
</div>
    <div class="main-content">
