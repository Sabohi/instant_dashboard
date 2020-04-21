
<?php
	require_once ("/var/www/html/CZCRM/configs/config.php");
	require_once ("/var/www/html/CZCRM/configs/dashboard_config.php");
    require_once("/var/www/html/CZCRM/dashboard_requirements.php");

    $client_id = isset($_SESSION['CLIENT_ID'])?$_SESSION['CLIENT_ID']:'';
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
        /*width: 200px;*/
        display: inline-block;
        height: auto;
        /*position: absolute;*/
        z-index: 999;
        /*background-color: #fff;*/
        /*right:3%;*/
        margin-left: 11px;
        margin-top: 10px;
    }
    .ticket-graphTable li
    {
        padding:3px 12px;
        display: inline-block;
        border: 1px solid #ddd;
        border-radius: 3px;
        text-align: center;
    }
    </style>

<div class="container-fluid">
    <div style="position: sticky;top: 45px;z-index: 11;background: whitesmoke;padding-bottom: 5px;">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 270px;float: right;margin-top:24px;margin-right:11px;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        <div class="ticket-graphTable">
            <ul style="list-style-type: none;padding-left: 0;margin-bottom: 0;">
                <li>
                    <p style="margin-bottom: 0;">Total Tickets</p>
                    <p style="margin-bottom: 0;" id="total_tickets">0</p>
                </li>
                <li>
                    <p style="margin-bottom: 0;">Closed Tickets</p>
                    <p style="margin-bottom: 0;" id="closed_tickets">0</p>
                </li>
                <li>
                    <p style="margin-bottom: 0;">Resolved Tickets</p>
                    <p style="margin-bottom: 0;" id="resolved_tickets">0</p>
                </li>
            </ul>
            <!-- <table class="table table-bordered" style="font-size:12px;margin-bottom:0;">
                <thead>
                    <tr>
                        <th style="text-align:center;height: 30px;">Total Tickets</th>
                        <th style="text-align:center;height: 30px;">Closed Tickets</th>
                        <th style="text-align:center;height: 30px;">Resolved Tickets</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    <tr>
                        <td style="" id="total_tickets">0</td>
                        <td style="" id="closed_tickets">0</td>
                        <td style="" id="resolved_tickets">0</td>
                    </tr>
                </tbody>
            </table> -->
        </div>
        <div class="clearfix"></div>
    </div>
        <div class="col-md-12" style="margin-top:11px;">
            <div class="row">
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="ticket_status_count" style="min-width: 310px; height: 400px; margin: 0 auto">
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="department_wise_ticket_status" style="min-width: 310px; height:400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                            <div class="panel-body">
                                <div id="new_inprogress_graph" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
                            <div id="repeated_issue" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
<script>
var default_range = '<?=_DEFAULT_RANGE?>';
var default_graph = '<?=_DEFAULT_GRAPH?>';
var client_id = '<?=$client_id?>';

function getData(range='',graph=''){
    console.log('======graph range======',range);
   
    range = (range != '')?range:default_range;
    graph = (graph != '')?graph:default_graph;
    $.ajax({
        type: "POST",
        url: "graph_data.php",
        data: "range="+range+"&graph="+graph+'&client_id='+client_id,
        success: function(result){
            console.log("result is ======",result);
            
            if(result != null){
                var resultStr = '';
                try {
                    resultStr = atob(result);
                }
                catch(err) {
                    console.log('Error in base64 decode');
                    console.log(err.message);
                }
                
                console.log("resultStr",resultStr);
                console.log("typeof",typeof(resultStr));

                var resultObj  = null;
                try {
                    var resultObj = JSON.parse(resultStr);
                } catch (e) {
                    console.log('Invalid json for open issues');
                }

                console.log("resultObj",typeof(resultObj));
                console.log(resultObj);

                //ticket status graph
                var ticket_status_string = ((resultObj != null) && (resultObj.ticket_status_string != null))?atob(resultObj.ticket_status_string):'';
                if(ticket_status_string != ''){
                    ticket_status_string = JSON.parse(ticket_status_string);
                }
                
                var ticket_status_graph = (ticket_status_string.ticket_status_graph != null)?ticket_status_string.ticket_status_graph:'';

                if(ticket_status_graph != null){
                    var data_key = ticket_status_graph.data_key;
                    data_key = (data_key != null)?JSON.parse(data_key):'';
                    var data_val = ticket_status_graph.data_val;
                    data_val = (data_val != null)?JSON.parse(data_val):'';
                    var totalArr = ticket_status_graph.totalArr;
                    totalArr = (totalArr != null)?JSON.parse(totalArr):'';

                    var total_tickets = (totalArr.Total_Tickets != undefined)?totalArr.Total_Tickets:0;
                    var closed_tickets = (totalArr.CLOSED != undefined)?totalArr.CLOSED:0;
                    var resolved_tickets = (totalArr.RESOLVED != undefined)?totalArr.RESOLVED:0;

                    $('#total_tickets').html(total_tickets);
                    $('#closed_tickets').html(closed_tickets);
                    $('#resolved_tickets').html(resolved_tickets);

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
                            allowDecimals: false,
                            title: {
                                text: 'Total number of tickets'
                            }
                        },
                        tooltip: {
                            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
                        },
                        plotOptions: {
                            series: {
                                maxPointWidth: 50
                            },
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
                        }],
                        lang: {
                            noData: "No data to display"
                        },
                        noData: {
                            style: {
                                fontWeight: 'bold',
                                fontSize: '13px',
                                color: '#0086b3'
                            }
                        }
                    });
                }

                console.log('Channel wise traffic graph');
                //Channel wise traffic graph
                var channel_wise_traffic_string = ((resultObj != null) && (resultObj.channel_wise_traffic_string != null))?atob(resultObj.channel_wise_traffic_string):'';
                if(channel_wise_traffic_string != ''){
                    channel_wise_traffic_string = JSON.parse(channel_wise_traffic_string);
                }

                var channel_wise_traffic_graph = (channel_wise_traffic_string.channel_wise_traffic_graph != null)?channel_wise_traffic_string.channel_wise_traffic_graph:'';

                if(channel_wise_traffic_graph != null){
                    var channelData = channel_wise_traffic_graph.data;

                    var data  = null;
                    try {
                        var data = JSON.parse(channelData);
                    } catch (e) {
                        console.log('Invalid json for channel wise data');
                    }
                  
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
                        }],
                        lang: {
                            noData: "No data to display"
                        },
                        noData: {
                            style: {
                                fontWeight: 'bold',
                                fontSize: '13px',
                                color: '#0086b3'
                            }
                        }
                    });
                }

                console.log('===============4=============');
                //Department wise ticket status graph
                var department_ticket_status_string = ((resultObj != null) && (resultObj.department_ticket_status_string != null))?atob(resultObj.department_ticket_status_string):'';
                if(department_ticket_status_string != ''){
                    department_ticket_status_string = JSON.parse(department_ticket_status_string);
                }

                console.log(department_ticket_status_string);
                var department_ticket_status_graph = (department_ticket_status_string.department_ticket_status_graph != null)?department_ticket_status_string.department_ticket_status_graph:'';

                if(department_ticket_status_graph != null){
                    console.log('Department wise ticket status');
                    var totalVal = department_ticket_status_graph.ttl;
                    totalVal = (totalVal != null && totalVal != '')?JSON.parse(totalVal):'';
                    // console.log('totalVal');
                    // console.log(totalVal);
                    var jsonStr = department_ticket_status_graph.jsonStr;
                    jsonStr = (jsonStr != null && jsonStr != '')?JSON.parse(jsonStr):'';
                    // console.log('jsonStr',typeof(jsonStr));
                    // console.log(jsonStr);
                    var jsonDlStr = department_ticket_status_graph.jsonDlStr;
                    jsonDlStr = (jsonDlStr != null && jsonDlStr != '')?JSON.parse(jsonDlStr):'';
                    // console.log('jsonDlStr');
                    // console.log(jsonDlStr);
                    var jsonDtc = department_ticket_status_graph.dtc;
                    jsonDtc = (jsonDtc != null && jsonDtc!='')?JSON.parse(jsonDtc):'';

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
                            allowDecimals: false,
                            title: {
                                text: 'Total numbers of tickets'
                            }

                        },
                        legend: {
                            enabled: false
                        },
                        plotOptions: {
                            series: {
                                maxPointWidth: 50,
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
                        },
                        lang: {
                            noData: "No data to display"
                        },
                        noData: {
                            style: {
                                fontWeight: 'bold',
                                fontSize: '13px',
                                color: '#0086b3'
                            }
                        }
                    });
                }

                console.log('Department wise close percent graph');
                //Department wise close percent graph
                var department_closing_percent_string = ((resultObj != null) && (resultObj.department_closing_percent_string != null))?atob(resultObj.department_closing_percent_string):'';
                if(department_closing_percent_string != ''){
                    department_closing_percent_string = JSON.parse(department_closing_percent_string);
                }

                var department_closing_percent_graph = (department_closing_percent_string.department_closing_percent_graph != null)?department_closing_percent_string.department_closing_percent_graph:'';

                if(department_closing_percent_graph != null){
                    var data_close_key = department_closing_percent_graph.data_close_key;
                    data_close_key = (data_close_key != null && data_close_key != '')?JSON.parse(data_close_key):'';
                    // console.log('data_close_key');
                    // console.log(data_close_key);
                    var data_close_val = department_closing_percent_graph.data_close_val;
                    data_close_val = (data_close_val != null && data_close_val != '')?JSON.parse(data_close_val):'';
                    // console.log('data_close_val');
                    // console.log(data_close_val);

                    //Department-wise Close Percentage graph
                    var chart = Highcharts.chart('department_closing_percent', {
                        title: {
                            text: 'Department-wise Close Percentage-'+range
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
                            // allowDecimals: false,
                            title: {
                                text: 'Ticket Closing Percentage'
                            }
                        },
                        tooltip: {
                            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}%</b> <br/>'
                        },
                        plotOptions: {
                            series: {
                                maxPointWidth: 50
                            },
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
                        }],
                        lang: {
                            noData: "No data to display"
                        },
                        noData: {
                            style: {
                                fontWeight: 'bold',
                                fontSize: '13px',
                                color: '#0086b3'
                            }
                        }
                    });
                }

                //Department wise close percent below 90 graph
                var department_closing_percent_below_ninty_string = ((resultObj != null) && (resultObj.department_closing_percent_below_ninty_string != null))?atob(resultObj.department_closing_percent_below_ninty_string):'';
                if(department_closing_percent_below_ninty_string != ''){
                    department_closing_percent_below_ninty_string = JSON.parse(department_closing_percent_below_ninty_string);
                }

                var department_closing_percent_below_ninty_graph = (department_closing_percent_below_ninty_string.department_closing_percent_below_ninty_graph != null)?department_closing_percent_below_ninty_string.department_closing_percent_below_ninty_graph:'';

                if(department_closing_percent_below_ninty_graph != null){
                 
                    var data_process_key = department_closing_percent_below_ninty_graph.data_process_key;
                    data_process_key = (data_process_key != null && data_process_key != '')?JSON.parse(data_process_key):'';
                    var data_process_val = department_closing_percent_below_ninty_graph.data_process_val;
                    data_process_val = (data_process_val != null && data_process_val != '')?JSON.parse(data_process_val):'';

                    //Department-Wise Close Percentage-Below 90% 
                    var chart = Highcharts.chart('process_ticket_count', {
                        title: {
                            text: 'Department-Wise Close Percentage-Below 90%-'+range
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
                            // allowDecimals: false,
                            title: {
                                text: 'Ticket Closing Percentage'
                            }
                        },
                        plotOptions: {
                            series: {
                                maxPointWidth: 50
                            },
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
                        }],
                        lang: {
                            noData: "No data to display"
                        },
                        noData: {
                            style: {
                                fontWeight: 'bold',
                                fontSize: '13px',
                                color: '#0086b3'
                            }
                        }
                    });
                }

            }else{
                console.log("Blank result");
            }
        },
        error: function(){
            console.log(error);
        },
    });
    //Inprogress/Open tickets graph
    // $(function () {
    $.get('open_issues_data.php?id='+ '1'+'&range='+range+'&client_id='+client_id, function(receivedMainData){
        var maindata  = null;
        try {
            maindata = JSON.parse(receivedMainData);
        } catch (e) {
            console.log('Invalid json for open issues');
        }

        if(maindata != null){
            const openInprogressChartConfig = {
                chart: {
                    type: 'column',
                    events: {

                        drilldown: function (e) {
                            if (!e.seriesOptions && e.target.renderTo.id === 'new_inprogress_graph') {
                                console.log('open drill 1',e);
                                console.log('range',range);

                                let chart = this;
                                chart.showLoading('<img src="images/loader2.gif" alt="Loading....">');
                                setTimeout(function () {
                                    console.log(window.range_to_pass,'window range  ',range);
                                        $.get('open_issues_data.php?id='+ e.point.drilldown+'&range='+window.range_to_pass+'&client_id='+client_id, function(data){
                                            chart.hideLoading();
                                            console.log(data,'=====data');
                                            let open_issues_drilldown_data = {};
                                            if(data != null && data != '' && e.point != null && e.point != ''){   
                                                try {
                                                    open_issues_drilldown_data = JSON.parse(data);
                                                    console.log('=====open_issues_drilldown_data');
                                                    console.log(open_issues_drilldown_data);
                                                } catch (e) {
                                                    console.log('Invalid json for open issues drilldown issue');
                                                }
                                            }
                                            chart.addSeriesAsDrilldown(e.point, open_issues_drilldown_data);
                                        });
                                }, 100);
                            }
                        }
                           
                    }
                },
                title: {
                    text: '(NEW & OPEN-EMAIL)/INPROGRESS Ticket Details-'+range
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -60
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: 'Total number of tickets'
                    }
                },
                tooltip: {
                    // shared: true,
                    headerFormat: '<span style="font-size:10px">{point.key.name}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                    footerFormat: '</table>',
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
                    //  align: 'left',
                        maxPointWidth: 50,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            // align: 'left',
                            format: '{point.y}'
                        }
                    }
                },
                series: [{
                    name: 'NEW & OPEN-EMAIL Tickets',
                    data: maindata["new"] 
                }, {
                    name: 'INPROGRESS Tickets',
                    data : maindata["inprogress"]    
                }],
                lang: {
                    noData: "No data to display"
                },
                noData: {
                    style: {
                        fontWeight: 'bold',
                        fontSize: '13px',
                        color: '#0086b3'
                    }
                }
            };

            if(window.openIssuesChartInitialized){  
                openInprogressChartConfig.chart.events = Object.create({});           
            }
            else{
                window.openIssuesChartInitialized = true;
            }
            Highcharts.chart('new_inprogress_graph',openInprogressChartConfig);
        } 
    });

    $.get('repeated_issue_api.php?id='+ '1'+'&range='+range+'&client_id='+client_id, function(maindata){
        var repeated_issues_data  = null;
        try {
            repeated_issues_data = JSON.parse(maindata);
        } catch (e) {
           console.log('Invalid json for repeated issue');
        }
        
        if(repeated_issues_data != null){

            const repeatedIssuesChartConfig = {
                chart: {
                    type: 'column',
                    events: {
                    drilldown: function (e) {
                        // alert('repeated drill 1');
                        
                        if (!e.seriesOptions && e.target.renderTo.id === 'repeated_issue') {
                            console.log('repeated drill 1',e.target.renderTo.id);
                                let chart = this;
                                chart.showLoading('<img src="images/loader2.gif" alt="Loading....">');
                                setTimeout(function () {
                                    // try {
                                        $.get('repeated_issue_api.php?id='+ e.point.drilldown+'&range='+window.range_to_pass+'&client_id='+client_id, function(data){
                                            chart.hideLoading();
                                            let repeated_issues_drilldown_data = {};
                                            if(data != null && data != '' && e.point != null && e.point != ''){   
                                                try {
                                                    repeated_issues_drilldown_data = JSON.parse(data);
                                                } catch (e) {
                                                    console.log('Invalid json for open issues drilldown issue');
                                                }
                                            }
                                            chart.addSeriesAsDrilldown(e.point, repeated_issues_drilldown_data); 
                                        });
                                    // } catch (e) {
                                    //     console.log('Invalid json for open inprogress issue');
                                    // }
                                }, 100);
                            }
                        }
                    }       
                },
                title: {
                    text: 'Repeated Issues-'+range
                },
                subtitle: {
                    //text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                },
                xAxis: {
                            type: 'category'
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Total number of Issues'
                    }
                },
                series: [{
                    name: 'Repeated Issue',
                    colorByPoint: true,
                    data: repeated_issues_data,
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
                        maxPointWidth: 50,
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
                },
                lang: {
                    noData: "No data to display"
                },
                noData: {
                    style: {
                        fontWeight: 'bold',
                        fontSize: '13px',
                        color: '#0086b3'
                    }
                }
            };

            if(window.repeatedIssuesChartInitialized){  
                repeatedIssuesChartConfig.chart.events = Object.create({});           
            }
            else{
                window.repeatedIssuesChartInitialized = true;
            }

            Highcharts.chart('repeated_issue',repeatedIssuesChartConfig);
          
        }
    });
}
$(function() {
    let session_storage_range = sessionStorage.getItem("range_to_pass");

    var start = moment();
    var end = moment();
   
    switch(session_storage_range) {
        case 'Today':
            start = moment();
            end = moment();
        break;
        case 'Yesterday':
            start = moment().subtract(1, 'days');
            end = moment().subtract(1, 'days');
        break;
        case 'Last 7 Days':
            start = moment().subtract(7, 'days');
            end = moment().subtract(1, 'days');
        break;
        case 'Last 30 Days':
            start = moment().subtract(30, 'days');
            end = moment().subtract(1, 'days');
        break;
    }

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
           'Last 30 Days': [moment().subtract(30, 'days'), moment().subtract(1, 'days')],
        //    'This Month': [moment().startOf('month'), moment().endOf('month')],
        //    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    // if(session_storage_range){

    // }
    $('li').click(function(){
        window.range_to_pass = $(this).attr('data-range-key');
        console.log('case for dashboard',window.range_to_pass);
        if(window.range_to_pass != null){
            sessionStorage.setItem("range_to_pass", window.range_to_pass);
            getData(window.range_to_pass);
        }
    });
});

$(document).ready(function(){
    let session_storage_range = sessionStorage.getItem("range_to_pass");
    if((session_storage_range != null) && (session_storage_range!='')){
        window.range_to_pass = session_storage_range;
        console.log('set in session '+session_storage_range);

        // console.log('===see here===');
  
        // let elements = $(".ranges").find("li"); 

        // for (let elem of elements) {
        //     let elem_text = elem.innerText;
        //     if(elem_text != null){
        //         if(elem_text == window.range_to_pass){
        //             console.log('selected range is ===='+elem_text);
        //             console.log(elem);
        //             elem.classList.add("active");
        //             // elem.addClass("active");
        //             // elem.className = "active";
        //         }else{
        //             elem.classList.remove("active");
        //         }
               
        //     }
        // }
        

    }else{
        window.range_to_pass = 'Today';
        console.log('range not set in session');
    }
   
    window.openIssuesChartInitialized = false;
    window.repeatedIssuesChartInitialized = false;
   getData(window.range_to_pass,'all');
});
</script>    
                 
