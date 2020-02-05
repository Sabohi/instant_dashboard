<!-- create view n1 as select * from ticket_details;
drop view  n1;-->
<?php
	require_once("/var/www/html/CZCRM/configs/config.php");
	
	class customDB extends DATABASE_MANAGER{
		private $DB, $DB_H, $client_id;
		
		function __construct($client_id=""){   
			$this->client_id = $client_id;
			$db_name=($this->client_id==0)?GDB_NAME:DB_PREFIX.$client_id;
			parent::__construct(DB_HOST, DB_USERNAME, DB_PASSWORD,$db_name);
			$this->DB_H = $this->CONNECT();       
		}
		
		// function createDashboardDB(){
		// 	$query="CREATE DATABASE IF NOT EXISTS ".DASH_DB_NAME.$this->client_id;
		// 	$this->EXECUTE_QUERY($query,$this->DB_H);
		// } 
		
		// function createDashbordDB($client_id){
		// 	if(!empty($client_id)){
		// 		parent::__construct(DB_HOST, DB_USERNAME, DB_PASSWORD,DB_PREFIX.$client_id);
		// 		$this->DB_H = $this->CONNECT();
				
		// 		$this->createDashboardView();
			
		// 	}
		// 	else{
		// 		return "Invalid Client ID!!";
		// 	}
		// }
		
		private function createDashboardView(){
			$query="create view ".GDB_NAME.".enableDashboard as select registration_id from ".GDB_NAME.".clientRegistrationBasic where dashboard_flag=1";
            $this->EXECUTE_QUERY($query,$this->DB_H);
            
            
        }
    }
    
    //Remote server , port socket connectivity required
    //Today - refresh after 1 minute note
    //It is suggested to create db on your end namely ticket_visualization_1.2.3.....
    //Not different tables for different views
    //Different script for today
?>
