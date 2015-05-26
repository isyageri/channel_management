<div id="charts"></div>
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