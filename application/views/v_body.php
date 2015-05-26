<body>
<?php
$ct["slice-right-acc"] = "v_account";
$ct["bot-footer"] = "v_footer";
?>
<div id='ct-top' align='center'>
	<div id='top-banner' align='left'>
		<div id='banner-slice-left'><? 
		if( isset($ct["banner-slice-left"]) ) {
			if( isset($pm["banner-slice-left"]) )
				$this->load->view($ct["banner-slice-left"], $pm["banner-slice-left"]); 
			else 
				$this->load->view($ct["banner-slice-left"]); 
		}
		?>
			<div class="navbar-brand" style="margin-top:20px; margin-left:20px;">
			<!--	<img src="<?php echo base_url();?>assets/img/mfee_title.png"> -->
			Channel Management
			</div>
		
		</div>
		<div id='banner-slice-right'>
			<div id='slice-right-acc'>
			<? 
			if( isset($ct["slice-right-acc"]) ) {
				if( isset($pm["slice-right-acc"]) )
					$this->load->view($ct["slice-right-acc"], $pm["slice-right-acc"]); 
				else 
					$this->load->view($ct["slice-right-acc"]); 
			}
			?>
			</div>
			<div id='slice-right-bot'><? 
			if( isset($ct["slice-right-bot"]) ) { 
				if( isset($pm["slice-right-bot"]) ) 
					$this->load->view($ct["slice-right-bot"], $pm["slice-right-bot"]); 
				else
					$this->load->view($ct["slice-right-bot"]); 
			}
			?></div>
		</div>
	</div>
</div>
<div style='border: 1px dotted #e2e2e2'></div>
<div id='ct-mid' align='center'>
	<div id='mid-line' align='left'></div>
	<div id='mid-menu' align='left'><? 
	if( isset($ct["mid-menu"]) ) {
		if( isset($pm["mid-menu"]) ) 
			$this->load->view($ct["mid-menu"], $pm["mid-menu"]); 
		else 
			$this->load->view($ct["mid-menu"]); 
	}
	?></div>
	<div id='mid-content' align='left'>
		<div id="chasrt"></div>
		<?php if( isset($this->charts)){
			echo "<div id='charts'></div>";
			}
			?>
	<? 
	if( isset($ct["mid-content"]) ) {
		if( isset($pm["mid-content"]) ) 
			$this->load->view($ct["mid-content"], $pm["mid-content"]); 
		else
			$this->load->view($ct["mid-content"]); 
	}
	?></div>
</div>
<div style='border: 1px dotted #e2e2e2'></div>
<div id='ct-bot' align='center'>
	<div id='bot-footer' align='left'><? 
	if( isset($ct["bot-footer"]) ) {
		if( isset($pm["bot-footer"]) ) 
			$this->load->view($ct["bot-footer"], $pm["bot-footer"]); 
		else
			$this->load->view($ct["bot-footer"]); 
	}
	?></div>
</div>

<script type="text/javascript">
    jQuery(function () {
        var industryChart;
        jQuery(document).ready(function() {
			Highcharts.setOptions({
     colors: ['#FF5555', '#007FFF', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263',      '#6AF9C4']
    });
            $('#charts').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '<b>Sumarry (For TELKOM internal only)</b> <br><br> Jumlah PKS'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Jumlah PKS',
                data: [
                    ['Aktif',   45.0],
 
                    ['Tidak Aktif',    55.0]
                ]
            }]
        });
        });
    });
</script>
</body>
</html>