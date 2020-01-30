<?php
class redisHandler{
	
	private $REDIS_SERVER1, $REDIS_PORT1, $REDIS_PASS1, $REDIS_SERVER2, $REDIS_PORT2, $REDIS_PASS2,$redis;	
	function __construct ($h = NULL, $u = NULL, $p = NULL,$d=NULL) {
		
		$filecontent =file_get_contents("/var/www/html/CZCRM/configs/config.txt");
		$fileArr = json_decode($filecontent,true);
		//print_r($fileArr);

		//print_r($fileArr);
		$this->REDIS_SERVER1	=	$fileArr["REDIS_SERVER1"];
			$this->REDIS_PORT1      =	$fileArr["REDIS_PORT1"];
			$this->REDIS_PASS1      =	$fileArr["REDIS_PASS1"];
			$this->REDIS_SERVER2    =	$fileArr["REDIS_SERVER2"];
			$this->REDIS_PORT2      =	$fileArr["REDIS_PORT2"];
			$this->REDIS_PASS2      =	$fileArr["REDIS_PASS2"];
			$this->connection($this->REDIS_SERVER1, $this->REDIS_PORT1);
			return $this->connection;
	}
	public function checkConnction()
	{
		return $this->connection;
	}
	public function __destruct()
	{
		if($this->connection){
			$this->redis->close();
		}
		else{
			print "Connection Issue";
		}
	}
	private function connection(){
			try{
			 if($this->connection = fsockopen($this->REDIS_SERVER1,$this->REDIS_PORT1, $errorNo, $errorStr )){
				if( $errorNo ){
				
				  throw new RedisException("Socket cannot be opened");
				}else{
					$this->redis = new Redis();
					$this->redis->connect($this->REDIS_SERVER1,$this->REDIS_PORT1);
					if($this->REDIS_PASS1)
					{
						$this->redis->auth($this->REDIS_PASS1);
					}
				}
			 }
			 else if($this->connection = fsockopen( $this->REDIS_SERVER2,$this->REDIS_PORT2, $errorNo1, $errorStr)){
				if( $errorNo1 ){
				  throw new RedisException("Socket cannot be opened");
				}else{
					$this->redis = new Redis();
					$this->redis->connect($this->REDIS_SERVER2, $this->REDIS_PORT2);
					if($this->REDIS_PASS2)
					{
						$this->redis->auth($this->REDIS_PASS2);
					}
				}
			 }
			}catch( Exception $e ){

				  echo $e -> getMessage( );
			}
			//print $this->connection;
			 return $this->connection;
	}

	//parameters required - 1). Name of hash, 2). Name of key in hash, 3). Value at key in hash
	public function setHash($hashName,$key,$value)
	{
		 if($this->connection){
				  $this->redis->hset($hashName, $key, $value); 
		 }
		 else{
			 print "Connection Issue";
		 }		
	}

	public function lpushRedis($key,$value)
	{
		 if($this->connection){
				  $this->redis->lpush($key, $value); 
		 }
		 else{
			 print "Connection Issue";
		 }		
	}
	public function publishRedis($key,$value)
	{
		 if($this->connection){
			$this->redis->publish($key, $value); // send message to channel 1.
		 }
		 else{
			 print "Connection Issue";
		 }		
	}
	public function rPushRedis($key,$value)
	{
		 if($this->connection){
			$this->redis->rpush($key, $value);
		 }
		 else{
			 print "Connection Issue";
		 }	 
	}
	public function lpopRedis($key)
	{
		 if($this->connection){
			$this->redis->lpop($key);
		 }else{
			 print "Connection Issue";
		 }		 
	}
	public function rpopRedis($key)
	{
		 if($this->connection){
			return $this->redis->rpop($key);
		 }else{
			 print "Connection Issue";
		 }		
	}
	public function delRedis($key)
	{
		if($this->connection){
			 $this->redis->del($key); 
		 }else{
			 print "Connection Issue";
		 }	
	}
	public function existRedis($key)
	{
		if($this->connection){
			 $this->redis->exists($key);
		 }else{
			 print "Connection Issue";
		 }
		
	}
	public function setRedis($key,$value,$exp='')
	{
		if($this->connection){
			 if(!empty($exp))
			 {
				 $this->redis->setex($key, $exp, $value );
			 }else
			 {
				  $this->redis->set($key, $value);
			 }
		}else{
			 print "Connection Issue";
		}

	}
	public function getRedis($key)
	{
		if($this->connection){
		 return $this->redis->get($key);
		}else{
			 print "Connection Issue";
		}

	}
	
	public function getKeysRedis()
	{
		if($this->connection){
		   $arList = $this->redis->keys("*"); 
		   return print_r($arList);  
		}else{
			 print "Connection Issue";
		}
	}
	public function lrangeRedis($key,$start='0',$end='-1')
	{
		if($this->connection){
		   $arList = $this->redis->lrange($key, $start ,$start); 
		   return  print_r($arList); 
		 }else{
			 print "Connection Issue";
		}
	}
	public function publishToChannel($channel_name,$data)
	{
		if($this->connection){
			$this->redis->publish($channel_name, $data); 
		}
		else{
			print "Connection Issue";
		}
	}

	public function getAllHash($hashName)
	{
		if($this->connection){
			$this->redis->hgetall($hashName); 
		}
		else{
			 print "Connection Issue";
		}		
	}
}
?>
