<?php
class ConnectionMgr
{
	var $con;
	var $cur;
	var $tCount;
	var $arrQry;
	var $errNo;
	var $dbName;
//==========================================================
	function ConnectionMgr()
	{
		unset($this->conLink);
		unset($this->dbName);
		$this->dbName = DB_NAME;
	}
//=====================Creating connection ==================	
	function createConnection()
	{
	 	global $isAjaxRequest;
		$this->conLink = @mysqli_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD,$this->dbName); 
		if($this->conLink === false)
		{
			if(!$isAjaxRequest) 
				redirectPage((isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'],'postfiles/') !== FALSE ? '../' : '' ).'index.php?st=logout',ERROR_DBCONNECT); 
			else 
			{
				$_SESSION['reload_msg'] = ERROR_DBCONNECT;
				$_SESSION['reload_msgType'] = 'e';
				echo json_encode(array('dberror'=>'Y','msg'=>$_SESSION['reload_msg'],'msgType'=>$_SESSION['reload_msgType']));
				exit;
			}
		}
		
		if(!$this->conLink) 
			return false;
		
		return $this->conLink;
	}
	function closeConnection($obj)
	{
		return mysqli_close($obj->conLink);
	}
//===========Executing the select query==================	
	
	function logSQLError($functionName, $link,$query, $file, $line)
	{
		if(DEBUG == 'TRUE')
		{
			global $_POST;
			//pr( $_POST);
			if((isset($_POST['_search'])) || (isset($_POST['ajx_request_type']) && $_POST['ajx_request_type']== 'ajax'))
			{
				echo json_encode(array('dberror'=>'Y', 'dberrorMsg'=>("<div style='float:left; width:90%; height:100px; overflow:auto; background-color:#FFBABA; border:3px solid #d8000c; text-align:left;'>You have an error in File : ". $file." at <strong>Line Number :</strong> ". $line."<br /><strong>Function Name : </strong>".$functionName." <strong>Query :</strong> ".$query."<br /><strong>Error String : </strong>".mysqli_error($link)."</div>")));
			}
			else
			{
				echo "<div style='width:98%; float:left; border:1px solid #F00; background-color:#F5D3D3; padding:1%; margin-bottom:5px;'><div style='background-color:#f00; color:#FFF; '><b>You have an error in File : </b> ". $file." at <b>Line Number :</b> ". $line."</div><br /><b>Function Name : </b>".$functionName." <b>Query :</b> ".$query."<br /><b>Error String : </b>".mysqli_error($link)."</div>";
			}
			exit;
		}
		else
		{
		// write error in log file
		}
	}
	
//===========================Executing the insert update delete query===============	
	
	
	function setSecurity($fields,$table,$where,$sessionName)
	{
		$query = "Select $fields from $table where $where";
		//echo $query;		
		$errNoRecom=$this->DML_executeQry($query);								
		$errNo = mysqli_fetch_object($errNoRecom);
		if($errNo > 0)
		{
			session_start();
			$_SESSION['username']=$sessionName;
			return $errNo;
		}
		else
			return $errNo;
		
	}	

	function errorHandeler($errNo)
	{
		$msgQuery = $this->DML_executeQry("SELECT fErrorDetail FROM ".TBL_MYSQL_CODE." WHERE fErrorNumber = '$errNo'");
		$rec = mysqli_fetch_object($msgQuery);
		$msg = $rec->fErrorDetail;
		return $msg;
	}

 //=========================CMS Section==============================================
	function curPageName()
	 {
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	 }	

	function notUnique($sql)
	{
		$result = $this->DML_executeQry($sql);
		return mysqli_num_rows($result);
	}

	function getSingleRow($sql, $file = __FILE__, $line = __LINE__)
	{
		$this->errNo = true;
		if(!isset($this->conLink))
			$this->errNo = $this->createConnection();
			
		if($this->errNo)
		{
			$result = mysqli_query($this->conLink,$sql);
			
			if (!$result) 
			{
				$this->logSQLError('getSingleRow', $this->conLink,$sql, $file, $line);		
				return -3;
			}
			else
			{
				$returnData = array();
				$totRows = mysqli_num_rows($result);
				
				if($totRows)
				{
					$row = mysqli_fetch_object($result);
					foreach($row as $key=>$val)
					{
						$returnData[$key] = $val;
					}
				}
				
				mysqli_free_result($result);
				return $returnData;
			}
		}
		else
		{
			return $this->errNo;
		}
		return false;
	}

	function getSingleCell($sql, $file = __FILE__, $line = __LINE__)
	{
		$this->errNo = true;
		if(!isset($this->conLink))
			$this->errNo = $this->createConnection();
			
		if($this->errNo)
		{
			$result = mysqli_query($this->conLink,$sql);
			
			if (!$result) 
			{
				$this->logSQLError('getSingleCell', $this->conLink,$sql, $file, $line);		
				return -3;
			}
			else
			{
				$returnData = array();
				$totRows = mysqli_num_rows($result);
				
				if($totRows)
				{
					$row = mysqli_fetch_array($result);
					
					$returnData = $row[0];
				
					mysqli_free_result($result);
					return $returnData;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return $this->errNo;
		}
		return false;
	}
	
	function getMultiRow($sql, $file = __FILE__, $line = __LINE__)
	{
		$this->errNo = true;
		if(!isset($this->conLink))
			$this->errNo = $this->createConnection();
			
		if($this->errNo)
		{
			$result = mysqli_multi_query($this->conLink,$sql);
			
			if (!$result) 
			{
				$this->logSQLError('getMultiRow', $this->conLink,$sql, $file, $line);		
				return -3;
			}
			else
			{
				$returnData = array();
				do {
					
					if ($result = mysqli_store_result($this->conLink)) 
					{
						while ($row = mysqli_fetch_assoc($result)) 
						{
							$returnData[] = $row;
						}
						mysqli_free_result($result);
					}
				} while (mysqli_more_results($this->conLink) && mysqli_next_result($this->conLink));
				
				return $returnData;
			}
		}
		else
		{
			return $this->errNo;
		}
		return false;
	}
		
	function updateRecords($sql, $file = __FILE__, $line = __LINE__)
	{
		$this->errNo = true;
		if(!isset($this->conLink))
			$this->errNo = $this->createConnection();
			
		if($this->errNo)
		{
			if( ( $result = mysqli_query ($this->conLink,$sql) ) == FALSE )
			{
				$this->logSQLError('updateRecords', $this->conLink,$sql, $file, $line);
				return -3;
			} 
			else
			{	
				$result = mysqli_store_result($this->conLink);
				//mysqli_free_result($result);
				return true;
			}
		}
		else
		{	
			return false;
		}
	}
		
	function multi_updateinsertRecords($sql, $file = __FILE__, $line = __LINE__)
	{
		$this->errNo = true;
		if(!isset($this->conLink))
			$this->errNo = $this->createConnection();
			
		if($this->errNo)
		{
		
			if( ! mysqli_multi_query ($this->conLink,$sql) )
			{
				$this->logSQLError('multi_updateinsertRecords', $this->conLink,$sql, $file, $line);
				return -3;
			} 
			else
			{	
				do
				{
					$res = mysqli_store_result($this->conLink);
					
					if($res !== FALSE)
						mysqli_free_result($res);
						
				}while(mysqli_more_results($this->conLink) && mysqli_next_result($this->conLink));
				return true;
			}
		}
		else
		{	
			return false;
		}
	}
		
	function deleteRecords($sql, $file = __FILE__, $line = __LINE__)
	{
		$this->errNo = true;
		if(!isset($this->conLink))
			$this->errNo = $this->createConnection();
			
		if($this->errNo)
		{
			if( ( $result = mysqli_query ($this->conLink,$sql) ) == FALSE )
			{
				$this->logSQLError('deleteRecords', $this->conLink,$sql, $file, $line);
				return -3;
			} 
			else
			{	
				$result = mysqli_store_result($this->conLink);
				if(!is_bool($result))
					mysqli_free_result($result);
				return true;
			}
		}
		else
		{	
			return false;
		}
	}
		
	function insertRecords($sql, $file = __FILE__, $line = __LINE__)
	{
		$this->errNo = true;
		if(!isset($this->conLink))
			$this->errNo = $this->createConnection();
			
		if($this->errNo)
		{
			if( ( $result = mysqli_query ($this->conLink,$sql) ) == FALSE )
			{
				$this->logSQLError('insertRecords', $this->conLink,$sql, $file, $line);
				return -3;
			} 
			else
			{	
				$result = mysqli_store_result($this->conLink);
				if($result !== FALSE)
					mysqli_free_result($result);
				return true;
			}
		}
		else
		{	
			return false;
		}
	}
}

$con = new ConnectionMgr();
if(!isset($_GET['st']) || $_GET['st'] != 'logout')
	$con->createConnection();

$page = $con->curPageName();
?>