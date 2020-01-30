
<?php
	require_once ("/var/www/html/CZCRM/configs/config.php");
    require_once("/var/www/html/CZCRM/dashboard_requirements.php");

    //FILE TO FETCH DATA FOR DASHBOARD
    include("single_graph.php");
    $total_tickets = isset($totalArr["Total_Tickets"])?$totalArr["Total_Tickets"]:0;
    $closed = isset($totalArr["CLOSED"])?$totalArr["CLOSED"]:0;
    $resolved = isset($totalArr["RESOLVED"])?$totalArr["RESOLVED"]:0;
?>
<html lang="en">
<style>
    .clock {
    float:right; 
    }

    .highcharts-container
    {
    height:400px;
    }
    .highcharts-root
    {
    height:398px;
    }
    .panel-default
    {
    border:none!important;
    box-shadow:6px 6px 5px -4px #ccc;
    }
    .highcharts-contextbutton
    {
    display:none;
    }
    .ticket-graphTable {
        width: 200px;
        height: auto;
        position: absolute;
        z-index: 999;
        background-color: #fff;
        right:3%;
    }
    .ticket-graphTable th, td
    {
        padding:3px!important;
    }
    </style>
<body style="background:#f2f0f1;">
    <div class="container-fluid">
        <div class="col-md-12" style="margin-top:24px;">
            <div class="row">
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="ticket-graphTable">
                                <table class="table table-bordered" style="font-size:12px;margin-bottom:0;">
                                    <thead style="background:#ddd">
                                        <tr>
                                            <th colspan="2" style="text-align:center;">Ticket Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color:#FFF;background-color:blue;border:1px solid blue;">Total</td>
                                                <td style="color:#FFF;background-color:blue;border:1px solid blue;"><?php echo $total_tickets; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="color:#FFF;background-color:green;border:1px solid green;">Closed</td>
                                            <td style="color:#FFF;background-color:green;border:1px solid green;"><?php echo $closed; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="color:#FFF;background-color:orange;border:1px solid orange;">Resolved</td>
                                            <td style="color:#FFF;background-color:orange;border:1px solid orange;"><?php echo $resolved; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="ticket_status_count_2015" style="min-width: 310px; height: 400px; margin: 0 auto">
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="col-md-12">
                    <div class="panel panel-default"i>
                        <div class="panel-body">	
                            <!--<div id="department_closing_percent_2015" style="min-width: 310px; height: 300px; margin: 0 auto"></div>-->
                            <iframe src='<?=_CALL_API_DNS."/department_wise_close_percent.php"?>' width=100% height=400 style="border:none;"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default"i>
                            <div class="panel-body">
                                <!--<div id="department_closing_percent_2015" style="min-width: 310px; height: 300px; margin: 0 auto"></div>-->
                                <iframe src='<?=_CALL_API_DNS."/open_ticket.php"?>' width=100% height=400 style="border:none;"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="ticket_status_count" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="department_closing_percent" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="traffic_analysis" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="process_ticket_count" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <!--GRAPH 7-->
                                <iframe src='<?=_CALL_API_DNS."/company_wise_ticket.php"?>' width=100% height=310 style="border:none;"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">	
                    <div class="panel panel-default">
                            <!--GRAPH 8-->
                            <div class="panel-body">
                                <iframe src='<?=_CALL_API_DNS."/repeated_issue_details.php"?>' width=100% height=310 style="border:none;"></iframe>
                            </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</body>
<script>
// Create the chart
var data = '<?php echo $data?>';
//alert('data'+data);
var data = JSON.parse(data)

//alert(data);
// Make monochrome colors
var pieColors = (function () {
    var colors = [],
        base = Highcharts.getOptions().colors[0],
        i;

    for (i = 0; i < 10; i += 1) {
        // Start out with a darkened base color (negative brighten), and end
        // up with a much brighter color
        colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
    }
    return colors;
}());

// Multi-Channel Ticket Traffic Analysis-Past 24 Hours - GRAPH 1
Highcharts.chart('traffic_analysis', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Multi-Channel Ticket Traffic Analysis-Past 24 Hours'
    },
    tooltip: {
        //pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
	pointFormat: '<b>{point.name}: <b>{point.percentage:.1f}%</b>',
    },
   // plotOptions: {
     //   pie: {

       //     allowPointSelect: true,
         //   cursor: 'pointer',
           // colors: pieColors,
           // dataLabels: {
             //  enabled: true,
                //format: '<b>{point.y}</b><br>',
               // distance: -50,
               // filter: {
                 //   property: 'percentage',
                   // operator: '>',
                   // value: 4
               // }
           // }
       // }
   // },
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
        name: '',
        data: data
    }]
});
</script>

<script>
var data_key_2015 = '<?php echo $data_key_2015?>';
var data_key_2015 = JSON.parse(data_key_2015);
var data_val_2015 = '<?php echo $data_val_2015?>';
var data_val_2015 = JSON.parse(data_val_2015)
console.log('HERERRE',data_key_2015);
console.log('HERERRE',data_val_2015);
//Ticket status details since 2015 - GRAPH 2
var chart = Highcharts.chart('ticket_status_count_2015', {
    title: {
        text: 'Ticket Status Details Since 2015'
    },

    subtitle: {
        text:''
    },

    xAxis: {
        categories: data_key_2015,
        labels: {
                rotation: -45,
},
    },
    yAxis: {
        title: {
            text: 'Total number of tickets'
        }

    },

tooltip: {
    //    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
    },

plotOptions: {
column: {
                dataLabels: {
                    enabled: true,
                    crop: false,
                    overflow: 'none',
                }
            }
        },

    series: [{
        type: 'column',
        colorByPoint: true,
        data: data_val_2015,
        showInLegend: false
    }]

});
</script>

<script>
var data_key = '<?php echo $data_key?>';
var data_key = JSON.parse(data_key);
var data_val = '<?php echo $data_val?>';
var data_val = JSON.parse(data_val)

//Ticket Status Details-Past 24 Hour - GRAPH 3
var chart = Highcharts.chart('ticket_status_count', {

    title: {
        text: 'Ticket Status Details-Past 24 Hour'
    },

    subtitle: {
        text:''
    },

    xAxis: {
        categories: data_key,
	labels: {
		rotation: -45,
},
    },
    yAxis: {
                        title: {
                            text: 'Total number of tickets'
                        }

                    },

tooltip: {
                   //    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                       pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
                    },

plotOptions: {
           column: {
                dataLabels: {
                    enabled: true,
                    crop: false,
                    overflow: 'none',
                }
            }
        },

    series: [{
        type: 'column',
        colorByPoint: true,
        data: data_val,
        showInLegend: false
    }]

});
</script>

<script>
var data_close_key_2015 = '<?php echo $data_close_key_2015?>';
var data_close_key_2015 = JSON.parse(data_close_key_2015);
var data_close_val_2015 = '<?php echo $data_close_val_2015?>';
var data_close_val_2015 = JSON.parse(data_close_val_2015)

//Department-wise Close percentage-since 2015 - GRAPH 4
var chart = Highcharts.chart('department_closing_percent_2015', {

    title: {
        text: 'Department-wise Close percentage-since 2015'
    },

    subtitle: {
        text:''
    },
        
    xAxis: {
        categories: data_close_key_2015,
        labels : {
                rotation: -45, 
},
    },
     yAxis: {
               title: {
                            text: 'Total number of tickets'
                      }

                    },

tooltip: {
                   //    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                       pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}%</b> <br/>'
                    },

plotOptions: {
column: {
                dataLabels: {
                    enabled: true,
                    crop: false,
                    overflow: 'none',

                pointFormat: '<span style="color:{point.color}">{point.name}</span> <b>{point.y}%</b> <br/>'

                }
            }
        },

    series: [{
        type: 'column',
        colorByPoint: true,
        data: data_close_val_2015,
        showInLegend: false,

    }]

});

</script>

<script>
var data_close_key = '<?php echo $data_close_key?>';
var data_close_key = JSON.parse(data_close_key);
var data_close_val = '<?php echo $data_close_val?>';
var data_close_val = JSON.parse(data_close_val)

//Department-wise Close Percentage-Past 24 Hour - GRAPH 5
var chart = Highcharts.chart('department_closing_percent', {

    title: {
        text: 'Department-wise Close Percentage-Past 24 Hour'
    },

    subtitle: {
        text:''
    },
	
    xAxis: {
        categories: data_close_key,
	labels : {
		rotation: -45, 
},
    },
     yAxis: {
               title: {
                            text: 'Total number of tickets'
                      }

                    },

tooltip: {
                   //    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                       pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}%</b> <br/>'
                    },

plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    crop: false,
                    overflow: 'none',

		pointFormat: '<span style="color:{point.color}">{point.name}</span> <b>{point.y}%</b> <br/>'

                }
            }
        },

    series: [{
        type: 'column',
        colorByPoint: true,
        data: data_close_val,
        showInLegend: false,

    }]

});


</script>
<script>
var data_process_key = '<?php echo $data_process_key?>';
        //var data_process_key = JSON.parse(data_process_key);
        //alert("amit"+data_process_key);
        var data_process_val = '<?php echo $data_process_val?>';
        var data_process_val = JSON.parse(data_process_val);
        //alert("data_process_val"+data_process_val);
        var data_process_key = JSON.parse(data_process_key);

//Department-Wise Close Percentage-Below 90% Since 2015 - GRAPH 6
var chart = Highcharts.chart('process_ticket_count', {
    title: {
        text: 'Department-Wise Close Percentage-Below 90% Since 2015'
    },

    subtitle: {
        text:''
    },

    xAxis: {
        categories: data_process_key,
        labels: {
        rotation: -45,
},
    },
    yAxis: {
             title: {
                        text: 'Total number of tickets'
                    }

           },

plotOptions: {
            column: {
                dataLabels: {
                   enabled: true,
                    crop: false,
                    overflow: 'none',
                
                pointFormat: '<span style="color:{point.color}">{point.name}</span> <b>{point.y}%</b> <br/>'

                }
                }
        },
 tooltip: {
                   //    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                       pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}%</b> <br/>'
                    },

    series: [{
        name: '',
        type: 'column',
        colorByPoint: true,
        data:data_process_val,
        showInLegend: false
    }]

});
</script>      
                 
