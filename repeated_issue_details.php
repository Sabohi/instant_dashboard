<?php
    require_once("/var/www/html/CZCRM/dashboard_requirements.php");
?>
<html lang="en">
<style>
.highcharts-container
{
height:300px;
}
.highcharts-root
{
height:298px;
}
.panel-default
{
border:none!important;
box-shadow:6px 6px 5px -4px #ccc;
}
.ghcharts-contextbutton
{
display:none;
}
.highcharts-contextbutton
{
display:none;
}
</style>
<body>
    <div class="container-fluid" style="padding:0px;">
        <div id="repeated_issue" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
    </div>
</body>
<script>
$(function () {
    var processed_json = new Array();   
        $.get('repeated_issue_api.php?id='+ '1', function(maindata){
        console.log(maindata);  
                Highcharts.chart('repeated_issue', {
                    chart: {
                        type: 'column',
                                events: {
                         drilldown: function (e) {
                            if (!e.seriesOptions) {
                                    var chart = this;
                                    chart.showLoading('<img src="img/loader2.gif" alt="Loading....">');
                                    //alert( e.point.name);
                                    setTimeout(function () {
                                        $.get('repeated_issue_api.php?id='+ e.point.drilldown, function(data){
                                        console.log(data);    
                                        chart.hideLoading();
                                        chart.addSeriesAsDrilldown(e.point, JSON.parse(data));
                                        });
                                    }, 100);
                                }
                            }
                                }       
                    },
                    title: {
                        text: 'Repeated Issues-Past 24 Hours'
                    },
                    subtitle: {
                        //text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                    },
                    xAxis: {
                                type: 'category'
                    },
		    yAxis: {
                        title: {
                            text: 'Total number of Issues'
                        }

                    },
                    series: [{
                        name: 'Repeated Issue',
                                colorByPoint: true,
                        data: JSON.parse(maindata),
                    }],
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
			column: {
                dataLabels: {
                    enabled: true,
                    crop: false,
                    overflow: 'none'
                }
            },
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
                    },

                        drilldown: {
                        series: []
                    }
                });
        });
});

</script>
    
