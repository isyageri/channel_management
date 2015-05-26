<div class="main-content">
<div class="main-content-inner">
<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
    <script type="text/javascript">
        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
    </script>

    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="#">Channel Management</a>
        </li>
        <li class="active">Management Mitra</li>
    </ul><!-- /.breadcrumb -->



    <!-- /section:basics/content.searchbox -->
</div>

<!-- /section:basics/content.breadcrumbs -->
<div class="page-content">

<div class="row">
<div class="col-xs-12">

<div class="row">
    <div class="vspace-12-sm"></div>
    <div class="col-sm-12">
        <div class="widget-box transparent">
            <div class="widget-header red widget-header-flat">
                <h4 class="widget-title lighter">
<!--                    <i class="ace-icon fa fa-money orange"></i>-->
                    Daftar Mitra
                </h4>

                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="widget-body">
            <br>
            <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form">
            <!-- #section:elements.form -->
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Nama Segment </label>

                    <div class="col-sm-4">
                        <select class="form-control" id="form-field-select-1">
                            <option value="">Pilih Segment</option>
                            <option value="1">Segment 1</option>
                            <option value="2">Segment 2</option>
                            <option value="3">Segment 3</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Nama Mitra </label>

                    <div class="col-sm-4">
                        <input type="text" id="form-field-1-1" placeholder="Text Field" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Nama Lokasi Kontrak</label>

                    <div class="col-sm-4">
                        <input type="text" id="form-field-1-1" placeholder="Text Field" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Sewa </label>

                    <div class="col-sm-4">
                        <input type="text" id="form-field-1-1" placeholder="Text Field" class="form-control" />
                    </div>
                </div>
            </form>

            </div><!-- PAGE CONTENT ENDS -->
            </div>
            </div>
        </div><!-- /.widget-box -->
    </div><!-- /.col -->



</div><!-- /.row -->

<!-- #section:custom/extra.hr -->
<div class="hr hr32 hr-dotted"></div>


<!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->
<script type="text/javascript">
    jQuery(function () {
        var industryChart;
        jQuery(document).ready(function() {
            Highcharts.setOptions({
                colors: ['#007FFF','#FF5555','#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4']
            });
            $('#charts').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    layout: 'vertical',
                    x: 0,
                    y:50
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

        $('#chartcolumn').highcharts({
            chart: {
                type: 'column'
            },
            credits: {
                enabled: false
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Rp '
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} jt</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Kontrak Sewa',
                color: '#90ED7D',
                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

            }, {
                name: 'Tagihan Listrik',
                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

            }]
        });
    });
</script>