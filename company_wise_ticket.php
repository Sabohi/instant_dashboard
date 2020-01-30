<?php
require_once ("/var/www/html/CZCRM/modules/SESSION/session_config.php");
require_once ("/var/www/html/CZCRM/modules/SESSION/session.php");
$SESS = new SESSION (true);
require_once ("/var/www/html/CZCRM/configs/config.php");
require_once (_MODULE_PATH . "DATABASE/database_config.php");
require_once (_MODULE_PATH . "DATABASE/DatabaseManageri.php");
require_once("/var/www/html/CZCRM/dashboard_requirements.php");

$DB = new DATABASE_MANAGER (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
$DB_H = $DB->CONNECT ();
// print DB_NAME;
$tName = 'top_companies';   ///TABLE DOESNT EXISTS

$f = array ('*');
$w = '';
$tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);
// print $DB->getLastQuery();
$row_count = $DB->GET_ROWS_COUNT($tName);

if ($row_count > 0) {
        $row_data=array();
        while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
            $row_data[trim($row["key_name"])]=$row["json_data"];
        }

        $tc=json_decode($row_data["company_name_with_status"],true);
        $series=array("name"=>"Top 10 Companies","colorByPoint"=>true);
        $tcid=json_decode($row_data["top_10_companies"],true);
        $a=0;
        foreach($tcid as $tk=>$tv){
            if($tc[$tk]){
                $series["data"][$a]=array("name"=>$tk,"y"=>$tv,"drilldown"=>$tk);
            }
            else
            {
                $series["data"][$a]=array("name"=>$tk,"y"=>$tv);
            }
            $a++;
        }
//      if(trim($row["key_name"])=="top_10_companies"){
//      }
//      else if(trim($row["key_name"])=="company_name_with_status"){
        //      $tc=json_decode($row["json_data"],true);
                $dlseries=array();
                $a=0;
                foreach($tc as $tk=>$tv){
                        $dlseries[$a]["name"]=$tk;
                        $dlseries[$a]["id"]=$tk;
                        foreach($tv as $tvk=>$tvv){
                                $dlseries[$a]["data"][]=array($tvk,$tvv);
                        }
                        $a++;
                }
//      }

} else {
    echo "0 results";
}
$jsonStr=json_encode($series);
$jsonDlStr=json_encode($dlseries);

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
		<div id="company_count" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
	  
    </div>
 
</body>
<script>
var jsonStr=<?php echo  $jsonStr;?>;   //Require this data
var jsonDlStr=<?php echo  $jsonDlStr;?>;  //Require this data
// var jsonStr={"name":"Top 10 Companies","colorByPoint":true,"data":[{"name":"Thomas_Cook","y":22,"drilldown":"Thomas_Cook"},{"name":"TVT","y":10,"drilldown":"TVT"},{"name":"Faircent","y":6,"drilldown":"Faircent"},{"name":"Magus","y":3,"drilldown":"Magus"},{"name":"OYO_Rooms","y":3,"drilldown":"OYO_Rooms"},{"name":"Lenskart","y":3,"drilldown":"Lenskart"},{"name":"Usha_International","y":2,"drilldown":"Usha_International"},{"name":"31Parallels","y":2,"drilldown":"31Parallels"},{"name":"Snapdeal","y":2,"drilldown":"Snapdeal"},{"name":"Shuttl","y":2,"drilldown":"Shuttl"}]};
// var jsonDlStr=[{"name":"31Parallels","id":"31Parallels","data":[["CLOSED",2]]},{"name":"Faircent","id":"Faircent","data":[["CLOSED",5],["REOPEN",1]]},{"name":"Lenskart","id":"Lenskart","data":[["CLOSED",1],["FIX-GIVEN-UNDER-OBSERVATION",2]]},{"name":"Magus","id":"Magus","data":[["CLOSED",1],["FIX-GIVEN-UNDER-OBSERVATION",2]]},{"name":"OYO_Rooms","id":"OYO_Rooms","data":[["CLOSED",1],["RESOLVED",2]]},{"name":"Shuttl","id":"Shuttl","data":[["CLOSED",2]]},{"name":"Snapdeal","id":"Snapdeal","data":[["CLOSED",2]]},{"name":"Thomas_Cook","id":"Thomas_Cook","data":[["CLOSED",14],["FIX-GIVEN-UNDER-OBSERVATION",1],["INPROGRESS",7]]},{"name":"TVT","id":"TVT","data":[["CLOSED",3],["FIX-GIVEN-UNDER-OBSERVATION",4],["INPROGRESS",1],["JRF_Inprogress",1],["Waiting_For_Client_Revert",1]]},{"name":"Usha_International","id":"Usha_International","data":[["CLOSED",1],["INPROGRESS",1]]}];

Highcharts.chart("company_count", {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Top 10 Companies Ticket Details-Past 24 Hours'
    },
    subtitle: {
       // text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Numbers Of Tickets'
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
    series: [jsonStr],
    drilldown: {
        series: jsonDlStr
    }
});
</script>
    
