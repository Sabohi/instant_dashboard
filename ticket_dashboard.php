
<?php
	require_once ("/var/www/html/CZCRM/configs/config.php");
	require_once ("/var/www/html/CZCRM/configs/dashboard_config.php");
    require_once("/var/www/html/CZCRM/dashboard_requirements.php");

    //FILE TO FETCH DATA FOR DASHBOARD
    include("single_graph.php");
    $total_tickets = isset($totalArr["Total_Tickets"])?$totalArr["Total_Tickets"]:0;
    $closed = isset($totalArr["CLOSED"])?$totalArr["CLOSED"]:0;
    $resolved = isset($totalArr["RESOLVED"])?$totalArr["RESOLVED"]:0;  
?>
<html lang="en">
<style>
    .ranges li:last-child { display: none; }
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

<div class="container-fluid">
    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 22%;float: right;margin-top:24px;margin-right:11px;">
        <i class="fa fa-calendar"></i>&nbsp;
        <span></span> <i class="fa fa-caret-down"></i>
    </div>
        <div class="col-md-12" style="margin-top:11px;">
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
                            <div id="ticket_status_count" style="min-width: 310px; height: 400px; margin: 0 auto">
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="col-md-12">
                    <div class="panel panel-default"i>
                        <div class="panel-body">
                            <div id="department_wise_ticket_status" style="min-width: 310px; height:400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default"i>
                            <div class="panel-body">
                                <div id="column_inprogress" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                <!-- <iframe src='<?//=_CALL_API_DNS."/open_ticket.php"?>' width=100% height=400 style="border:none;"></iframe> -->
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="ticket_status_count" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div> -->
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
<script>
// Create the chart
// var data = '<?php echo $data?>';
// //alert('data'+data);
// var data = JSON.parse(data)

//alert(data);
// Make monochrome colors
// var pieColors = (function () {
//     var colors = [],
//         base = Highcharts.getOptions().colors[0],
//         i;

//     for (i = 0; i < 10; i += 1) {
//         // Start out with a darkened base color (negative brighten), and end
//         // up with a much brighter color
//         colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
//     }
//     return colors;
// }());

// Multi-Channel Ticket Traffic Analysis-Past 24 Hours - GRAPH 1
// Highcharts.chart('traffic_analysis', {
//     chart: {
//         plotBackgroundColor: null,
//         plotBorderWidth: null,
//         plotShadow: false,
//         type: 'pie'
//     },
//     title: {
//         text: 'Multi-Channel Ticket Traffic Analysis-Past 24 Hours'
//     },
//     tooltip: {
//         //pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
// 	pointFormat: '<b>{point.name}: <b>{point.percentage:.1f}%</b>',
//     },
//     plotOptions: {
//         pie: {
//             allowPointSelect: true,
//             cursor: 'pointer',
//             dataLabels: {
//                 enabled: false
//             },
//             showInLegend: true
//         }
//     },
//     series: [{
//         name: '',
//         data: data
//     }]
// });
</script>

<script>
//Not required - DUPLICATE GRAPH
// var data_key_2015 = '<?php echo $data_key_2015?>';
// var data_key_2015 = JSON.parse(data_key_2015);
// var data_val_2015 = '<?php echo $data_val_2015?>';
// var data_val_2015 = JSON.parse(data_val_2015)
// console.log('HERERRE',data_key_2015);
// console.log('HERERRE',data_val_2015);
// //Ticket status details since 2015 - GRAPH 2
// var chart = Highcharts.chart('ticket_status_count_2015', {
//     title: {
//         text: 'Ticket Status Details Since 2015'
//     },

//     subtitle: {
//         text:''
//     },

//     xAxis: {
//         categories: data_key_2015,
//         labels: {
//                 rotation: -45,
// },
//     },
//     yAxis: {
//         title: {
//             text: 'Total number of tickets'
//         }

//     },

// tooltip: {
//     //    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
//         pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
//     },

// plotOptions: {
// column: {
//                 dataLabels: {
//                     enabled: true,
//                     crop: false,
//                     overflow: 'none',
//                 }
//             }
//         },

//     series: [{
//         type: 'column',
//         colorByPoint: true,
//         data: data_val_2015,
//         showInLegend: false
//     }]

// });
</script>

<script>
// var data_key = '<?php echo $data_key?>';
// var data_key = JSON.parse(data_key);
// var data_val = '<?php echo $data_val?>';
// var data_val = JSON.parse(data_val)

//Ticket Status Details-Past 24 Hour - GRAPH 3
// var chart = Highcharts.chart('ticket_status_count', {
//     title: {
//         text: 'Ticket Status Details-Past 24 Hour'
//     },

//     subtitle: {
//         text:''
//     },

//     xAxis: {
//         categories: data_key,
//         labels: {
//             rotation: -45,
//         },
//     },
//     yAxis: {
//         title: {
//             text: 'Total number of tickets'
//         }
//     },
//     tooltip: {
//         //headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
//         pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
//     },
//     plotOptions: {
//         column: {
//             dataLabels: {
//                 enabled: true,
//                 crop: false,
//                 overflow: 'none',
//             }
//         }
//     },
//     series: [{
//         type: 'column',
//         colorByPoint: true,
//         data: data_val,
//         showInLegend: false
//     }]
// });
</script>

<script>
//DUPLICATE GRAPH
// var data_close_key_2015 = '<?php echo $data_close_key_2015?>';
// var data_close_key_2015 = JSON.parse(data_close_key_2015);
// var data_close_val_2015 = '<?php echo $data_close_val_2015?>';
// var data_close_val_2015 = JSON.parse(data_close_val_2015)

// //Department-wise Close percentage-since 2015 - GRAPH 4
// var chart = Highcharts.chart('department_closing_percent_2015', {

//     title: {
//         text: 'Department-wise Close percentage-since 2015'
//     },

//     subtitle: {
//         text:''
//     },
        
//     xAxis: {
//         categories: data_close_key_2015,
//         labels : {
//                 rotation: -45, 
// },
//     },
//      yAxis: {
//                title: {
//                             text: 'Total number of tickets'
//                       }

//                     },

// tooltip: {
//                    //    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
//                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}%</b> <br/>'
//                     },

// plotOptions: {
// column: {
//                 dataLabels: {
//                     enabled: true,
//                     crop: false,
//                     overflow: 'none',

//                 pointFormat: '<span style="color:{point.color}">{point.name}</span> <b>{point.y}%</b> <br/>'

//                 }
//             }
//         },

//     series: [{
//         type: 'column',
//         colorByPoint: true,
//         data: data_close_val_2015,
//         showInLegend: false,

//     }]

// });

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
var default_range = '<?=_DEFAULT_RANGE?>';
var default_graph = '<?=_DEFAULT_GRAPH?>';

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

function getData(range='',graph=''){
    var range = (range != '')?range:default_range;
    var graph = (graph != '')?graph:default_graph;
    $.ajax({
        type: "POST",
        url: "single_graph_v2.php",
        data: "range="+range+"&graph="+graph,
        success: function(result){
            console.log("result is ======",result);
            
            if(result != null){
                var resultStr = atob(result);
                console.log("resultStr",resultStr);
                console.log("typeof",typeof(resultStr));
                var resultObj = JSON.parse(resultStr);
                console.log("resultObj",typeof(resultObj));
                console.log(resultObj);

                //ticket status graph
                var ticket_status_string = (resultObj.ticket_status_string != null)?atob(resultObj.ticket_status_string):'';
                if(ticket_status_string != ''){
                    ticket_status_string = JSON.parse(ticket_status_string);
                }
                
                var ticket_status_graph = (ticket_status_string.ticket_status_graph != null)?ticket_status_string.ticket_status_graph:'';

                if(ticket_status_graph != null){
                    var data_key = ticket_status_graph.data_key;
                    data_key = JSON.parse(data_key);
                    var data_val = ticket_status_graph.data_val;
                    data_val = JSON.parse(data_val);

                    var chart = Highcharts.chart('ticket_status_count', {
                        title: {
                            text: 'Ticket Status Details-'+range
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
                }


                //Channel wise traffic graph
                 var channel_wise_traffic_string = (resultObj.channel_wise_traffic_string != null)?atob(resultObj.channel_wise_traffic_string):'';
                if(channel_wise_traffic_string != ''){
                    channel_wise_traffic_string = JSON.parse(channel_wise_traffic_string);
                }

                var channel_wise_traffic_graph = (channel_wise_traffic_string.channel_wise_traffic_graph != null)?channel_wise_traffic_string.channel_wise_traffic_graph:'';

                if(channel_wise_traffic_graph != null){
                    var data = channel_wise_traffic_graph.data;
                    data = JSON.parse(data);
                    // console,log(channel_wise_traffic_graph);
                    // console,log(data);
                   
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

                    Highcharts.chart('traffic_analysis', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Multi-Channel Ticket Traffic Analysis-'+range
                        },
                        tooltip: {
                            //pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
                        pointFormat: '<b>{point.name}: <b>{point.percentage:.1f}%</b>',
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
                            name: '',
                            data: data
                        }]
                    });
                }

                //Department wise ticket status graph
                var department_ticket_status_string = (resultObj.department_ticket_status_string != null)?atob(resultObj.department_ticket_status_string):'';
                if(department_ticket_status_string != ''){
                    department_ticket_status_string = JSON.parse(department_ticket_status_string);
                }

                var department_ticket_status_graph = (department_ticket_status_string.department_ticket_status_graph != null)?department_ticket_status_string.department_ticket_status_graph:'';

                if(department_ticket_status_graph != null){
                    var totalVal = department_ticket_status_graph.ttl;
                    totalVal = JSON.parse(totalVal);
                    var jsonStr = department_ticket_status_graph.jsonStr;
                    jsonStr = JSON.parse(jsonStr);
                    var jsonDlStr = department_ticket_status_graph.jsonDlStr;
                    jsonDlStr = JSON.parse(jsonDlStr);
                    var jsonDtc = department_ticket_status_graph.dtc;
                    jsonDtc = JSON.parse(jsonDtc);

                    Highcharts.chart("department_wise_ticket_status", {
                        chart: {
                            type: 'column',
                                events: {
                                    drillup: function (e) {
                                        $("#nameDept").html("Total");
                                        $("#valueDept").html(totalVal);
                                    },
                                    drilldown: function (e) {
                                $("#nameDept").html(e.point.name);
                                $("#valueDept").html(jsonDtc[e.point.name]);
                                    }
                                }
                        },
                        title: {
                            text: 'Department-Wise Ticket Status-'+range
                        },
                        // subtitle: {
                        // text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                        // },
                        accessibility: {
                            announceNewData: {
                                enabled: true
                            }
                        },
                        xAxis: {
                                type: 'category',
                                labels: {
                                rotation: -45
                            },
                        },
                        yAxis: {
                            title: {
                                text: 'Total numbers of tickets'
                            }

                        },
                        legend: {
                            enabled: false
                        },
                        plotOptions: {
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

                        series: [{
                            name: "Department",
                            colorByPoint: true,
                            data: jsonStr["data"],
                        }],
                        drilldown: {
                            series: jsonDlStr
                        }
                    });
                }
            }else{
                console.log("Blank result");
            }


            //Inprogress/Open tickets graph
            // $(function () {
            $.get('dashboard_data.php?id='+ '1', function(mainData){
            // console.log(mainData);
                var maindata=JSON.parse(mainData);
                console.log(maindata);

                    $("#column_inprogress").highcharts({
                        chart: {
                            type: 'column',
                            events: {
                                drilldown: function (e) {
                                    if (!e.seriesOptions) {
                                            var chart = this;
                                            chart.showLoading('<img src="images/loader2.gif" alt="Loading....">');
                                            setTimeout(function () {

                                                var openData = 'range='+range+'&id='+ e.point.drilldown;
                                                $.get( "dashboard_data.php", function( openData ) {
                                                    console.log(data);
                                                    chart.hideLoading();
                                                    chart.addSeriesAsDrilldown(e.point, JSON.parse(data));
                                                });

                                                // $.get('dashboard_data.php?id='+ e.point.drilldown+'&range='+range, function(data){
                                                //     console.log(data);
                                                //     chart.hideLoading();
                                                //     chart.addSeriesAsDrilldown(e.point, JSON.parse(data));
                                                // });
                                            }, 100);
                                        }
                                    }
                                }
                            },
                            title: {
                                text: 'Open/Inprogress Ticket Details-'+range
                            },
                            xAxis: {
                                type: 'category',
                                labels: {
                                    rotation: -60
                                }
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Total number of tickets'
                                }
                            },
                            tooltip: {
                                headerFormat: '<span style="font-size:10px">{point.key.name}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
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
                            //series: maindata,
                            series: [{
                                name: 'Open Tickets',
                                data: maindata["open"] 
                            }, {
                                name: 'InProgress Tickets',
                                data : maindata["inprogress"]    
                            }],
                        });
                    });
            // });  
        },
        error: function(){
            console.log(error);
        },
    });
}
$(function() {
    // console.log('jjjj');
    // console.log(moment().format('MMM DD'))

    // var start = moment().subtract(29, 'days');
    var start = moment();
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        //    'This Month': [moment().startOf('month'), moment().endOf('month')],
        //    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    $('li').click(function(){
        var range = $(this).attr('data-range-key');
        console.log('case for dashboard',range);
        if(range != null){
            getData(range);
        }
    });
});

$(document).ready(function(){
    // getData('',ticket_status_count);
    getData();
});

</script>    
                 
