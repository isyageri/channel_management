</div>
</div><!-- /.main-content -->

<!-- Footer -->
<div class="footer">
    <div class="footer-inner">
        <!-- #section:basics/footer -->
        <div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">PT. Telekomunikasi Indonesia, Tbk</span>
                            &copy; 2015
						</span>

            &nbsp; &nbsp;

        </div>

        <!-- /section:basics/footer -->
    </div>
</div>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
</div><!-- /.main-container -->


<style>
    .loading-div{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.56);
        z-index: 999;
        display:none;
    }
    .loading-div img {
        margin-top: 20%;
        margin-left: 50%;
    }
</style>
<div class="loading-div"><img src="<?php echo base_url(); ?>assets/img/loading.gif"></div>

<!--Ajax Menu-->
<script type="text/javascript">
    $(document).ready(function(){
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url(); ?>/home/nav/user',
            data: {},
            success: function(data) {
                $("#contentSetting").html(data);
            }
        });
        $('.setting_nav').click(function(){

            var nav = $(this).attr('id');
            //alert(nav);
            // $(".setting_nav").attr('class', 'list-group-item setting_nav')
            // $(this).attr('class', 'setting_nav active')//alert(unit);
            if(!nav){
//                return false;
            }else{
                $("#nav_lvl1").removeClass('active');
                $(".setting_nav").removeClass('active');
                $(this).addClass('active');
                $(".loading-div").show(); //show loading element
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url(); ?>/home/nav/'+nav,
                    data: {},
                    success: function(data) {
                        $(".main-content").html(data);
                        $(".loading-div").hide();
                    }
                })
                return false;
            }

        })
    })
</script>

<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url();?>assets/js/jquery.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url();?>assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url();?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
</script>
<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="<?php echo base_url();?>assets/js/excanvas.js"></script>
<![endif]-->
<script src="<?php echo base_url();?>assets/js/jquery-ui.custom.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.ui.touch-punch.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.easypiechart.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.sparkline.js"></script>
<script src="<?php echo base_url();?>assets/js/flot/jquery.flot.js"></script>
<script src="<?php echo base_url();?>assets/js/flot/jquery.flot.pie.js"></script>
<script src="<?php echo base_url();?>assets/js/flot/jquery.flot.resize.js"></script>

<!-- ace scripts -->
<script src="<?php echo base_url();?>assets/js/ace/elements.scroller.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.colorpicker.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.fileinput.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.typeahead.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.wysiwyg.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.spinner.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.treeview.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.wizard.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/elements.aside.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.ajax-content.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.touch-drag.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.sidebar.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.sidebar-scroll-1.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.submenu-hover.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.widget-box.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.settings.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.settings-rtl.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.settings-skin.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.widget-on-reload.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.searchbox-autocomplete.js"></script>

<!-- high chart -->
<script src="<?php echo base_url(); ?>assets/js/Highcharts-4.0.4/js/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/Highcharts-4.0.4/js/modules/exporting.js"></script>


<!-- the following scripts are used in demo only for onpage help and you don't need them -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace.onpage-help.css" />
<link rel="stylesheet" href="<?php echo base_url();?>docs/assets/js/themes/sunburst.css" />

<!--<script type="text/javascript"> ace.vars['base'] = '..'; </script>-->
<script src="<?php echo base_url();?>assets/js/ace/elements.onpage-help.js"></script>
<script src="<?php echo base_url();?>assets/js/ace/ace.onpage-help.js"></script>
<script src="<?php echo base_url();?>docs/assets/js/rainbow.js"></script>
<script src="<?php echo base_url();?>docs/assets/js/language/generic.js"></script>
<script src="<?php echo base_url();?>docs/assets/js/language/html.js"></script>
<script src="<?php echo base_url();?>docs/assets/js/language/css.js"></script>
<script src="<?php echo base_url();?>docs/assets/js/language/javascript.js"></script>
</body>
</html>