<?php
    require_once("/var/www/html/CZCRM/dashboard_requirements.php");
?>
<html lang="en">
<style>
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
        <div id="column_inprogress" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</body>
<script>
$(function () {
$.get('dashboard_data.php?id='+ '1', function(mainData){
// console.log(mainData);
	var maindata=JSON.parse(mainData);
    console.log(maindata);
    // {open: [{name: "Admin_Dept", y: 50, drilldown: "OPEN:Admin_Dept"}, {name: "CRM", y: 47, drilldown: "OPEN:CRM"}, {name: "CS", y: 17, drilldown: "OPEN:CS"}, {name: "QA", y: 7, drilldown: "OPEN:QA"}]}

// var maindata = {"open":[{"name":"Adil_Pre_Sale","y":1,"drilldown":"OPEN:Adil_Pre_Sale"},{"name":"Cloud_Sales","y":1,"drilldown":"OPEN:Cloud_Sales"},{"name":"CRM_Development_Team_2","y":3,"drilldown":"OPEN:CRM_Development_Team_2"},{"name":"Engineers","y":15,"drilldown":"OPEN:Engineers"},{"name":"Pre_Sales","y":1,"drilldown":"OPEN:Pre_Sales"},{"name":"Project_Management","y":1,"drilldown":"OPEN:Project_Management"},{"name":"Reports_UI","y":1,"drilldown":"OPEN:Reports_UI"}],"inprogress":[{"name":"Adil_Pre_Sale","y":6,"drilldown":"INPROGRESS:Adil_Pre_Sale"},{"name":"Chat","y":3,"drilldown":"INPROGRESS:Chat"},{"name":"Cloud_Development","y":1,"drilldown":"INPROGRESS:Cloud_Development"},{"name":"CRM_Development_Team_1","y":6,"drilldown":"INPROGRESS:CRM_Development_Team_1"},{"name":"CRM_Development_Team_2","y":6,"drilldown":"INPROGRESS:CRM_Development_Team_2"},{"name":"Direct_Sales","y":21,"drilldown":"INPROGRESS:Direct_Sales"},{"name":"Engineers","y":48,"drilldown":"INPROGRESS:Engineers"},{"name":"Finance_and_Ops","y":3,"drilldown":"INPROGRESS:Finance_and_Ops"},{"name":"Helpdesk","y":4,"drilldown":"INPROGRESS:Helpdesk"},{"name":"IT","y":4,"drilldown":"INPROGRESS:IT"},{"name":"IVR_and_Dialer","y":9,"drilldown":"INPROGRESS:IVR_and_Dialer"},{"name":"Java_Android_Team","y":1,"drilldown":"INPROGRESS:Java_Android_Team"},{"name":"Management","y":1,"drilldown":"INPROGRESS:Management"},{"name":"Pre_Sales","y":13,"drilldown":"INPROGRESS:Pre_Sales"},{"name":"Project_Management","y":1,"drilldown":"INPROGRESS:Project_Management"},{"name":"QA","y":3,"drilldown":"INPROGRESS:QA"},{"name":"QA_LMS","y":1,"drilldown":"INPROGRESS:QA_LMS"},{"name":"Reports_UI","y":17,"drilldown":"INPROGRESS:Reports_UI"},{"name":"TE","y":4,"drilldown":"INPROGRESS:TE"}]};
        $("#column_inprogress").highcharts({
            chart: {
                type: 'column',
                events: {
	        	drilldown: function (e) {
                        	if (!e.seriesOptions) {
                                    var chart = this;
                                    chart.showLoading('<img src="img/loader2.gif" alt="Loading....">');
                                    //alert( e.point.name);
                                    setTimeout(function () {
                                        $.get('dashboard_data.php?id='+ e.point.drilldown, function(data){
                                        	console.log(data);
                                        	chart.hideLoading();
                                        	chart.addSeriesAsDrilldown(e.point, JSON.parse(data));
                                        });
                                    }, 100);
                                }
                                //height=Math.max.apply(Math, data);
                                //if(height > 1000){
            			//	height = 1000;
            			//}
            		}
            	}
	    },
            title: {
                text: 'Open/Inprogress Ticket Details-Since 2018'
            },
            subtitle: {
                //text: 'Source: WorldClimate.com'
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
           // plotOptions: {
             //   column: {
               //     pointPadding: 0.2,
                 //   borderWidth: 0
               // }
           // },


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
                //data: [{name:"Killing",y:49.9},{name:"Hello",y:71}]
                data: maindata["open"] 
    
            }, {
                name: 'InProgress Tickets',
                //data: [{name:"Hello", y:49.9, drilldown:'ny 1'}, {name:"Hi",y:71.5}]
		data : maindata["inprogress"]
    
            }],
         /*   drilldown:{
                series: [ 
                {name:'nyc1',
                    id: 'ny 1',
                    data: [{y:39.9, name:'name1'}, {y:31.5, name:'name2'}]
        
                }, {name:'nyc2',
                    id: 'ny 1',
                    data: [{y:39.9, name:'name1'}, {y:31.5, name:'name2'}]
        
                }]
              }*/
        });
    });
});    

</script>
