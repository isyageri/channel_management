$(document).ready(function() {
	$('#tb_pgl').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_ten').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_pgl_ten').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_ten_nd').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_comp_type').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_tier').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_npk').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_npk_calc').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_doc_npk').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_doc_ba').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
	$('#tb_c2bi').tablesorter({widthFixed: true, widgets: ['zebra'] })
    	.tablesorterPager({container: $("#pager"), size: 20});
});
