
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

$tName = 'department_ticket_count_2015';

$f = array ('key_name','json_data');
$w = '';
$tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);
$row_count = $DB->GET_ROWS_COUNT($tName);

if ($row_count > 0) {
    $row_data=array();
    while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
        $row_data[trim($row["key_name"])]=$row["json_data"];
    }
  
    $tc=json_decode($row_data["ticket_status_count"],true);
    $dtc=$row_data["dept_total_ticket_count"];  
   
    $ttl=array_sum(json_decode($dtc,true));
 
    $tcid=json_decode($row_data["department_wise_ticket_count"],true);

    $a=0;	
    $series=array();
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
} else {
    echo "No results";
}


$jsonStr=json_encode($series);
$jsonDlStr=json_encode($dlseries);

?>
<html lang="en">
<style>
.highcharts-container
{
height:400px!important;
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
.ghcharts-contextbutton
{
display:none;
}
.highcharts-contextbutton
{
display:none;
}
.department-graphTable {
    width: 200px;
    height: auto;
    position: absolute;
    z-index: 999;
    background-color: #fff;
    right:16%;
}
.department-graphTable th, td
{
        padding:3px!important;
}
</style>
<body>
<div class="container-fluid" style="padding:0px;">
	<div class="department-graphTable">
		<table class="table table-bordered" style="font-size:12px;margin-bottom:0;">
			<thead style="background:#ddd">
				<tr>
					<th colspan="6" style="text-align:center;">Total Tickets</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="color:#FFF;background-color:blue;border:1px solid blue;text-align:center;" id="valueDept"><?php echo $ttl ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="company_count" style="min-width: 310px; height:400px; margin: 0 auto"></div>
	  
    </div>
 
</body>
<script>
var totalVal="<?php echo $ttl;?>"
var jsonStr=<?php echo  $jsonStr;?>;
var jsonDlStr=<?php echo  $jsonDlStr;?>;
var jsonDtc=<?php echo  $dtc;?>; 

Highcharts.chart("company_count", {
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
        text: 'Department-Wise Ticket Status Since 2015'
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
</script>
