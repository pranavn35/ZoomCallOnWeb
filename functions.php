<?php

function generateVerificationCode()
{
	return md5(uniqid(rand(), true));
}

function fetchData($parameters)
{
	global $db;
	$tables = array(
		"meeting"
	);
	
	$table = isset($parameters['table']) ? $parameters['table'] : '';
	$condition = isset($parameters['condition']) ? $parameters['condition'] : '';
	
	if(in_array($table, $tables))
	{
		$sql = "SELECT * FROM $table $condition";
		$result = $db->query($sql);
		if($result->rowCount())
		{
			return $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return false;
	}
	return false;
}
function getData($parameters)
{
	global $db;
	$tables = array(
		"meeting"
	);
	
	$table = isset($parameters['table']) ? $parameters['table'] : '';
	$condition = isset($parameters['condition']) ? $parameters['condition'] : '';
	
	if(in_array($table, $tables))
	{
		$sql = "SELECT name,department_id FROM user AND select name from department JOIN ON user_id.attendance=id.attendance WHERE user_id=id AND id=user_id $table $condition";
		$result = $db->query($sql);
		if($result->rowCount())
		{
			return $result->fetchAll(PDO::FETCH_ASSOC);
		}
		return false;
	}
	return false;
}

function deleteRow($rowId='', $table='')
{
	$allowedTables = array(
		"subject",
		"course"
	);

	if(!empty($rowId) && is_numeric($rowId) && !empty($table) && in_array($table, $allowedTables))
	{	
		global $db;
		
		$sql = "DELETE FROM $table WHERE id=:id LIMIT 1";
		// echo $sql;
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $rowId, PDO::PARAM_INT);

		if($stmt->execute())
		{
			if($stmt->rowCount() == 1)
			{
				return true;
			}
			else
			{
				return $stmt->ErrorInfo();
			}

		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function getFilePath($table = '', $columnName = '', $id = '')
{
	global $db;
	$sql = "SELECT $columnName FROM $table WHERE id=$id LIMIT 1";
	$result = $db->query($sql);

	if($result->rowCount())
	{
		$url = $result->fetchObject();
		$result->closeCursor();

		if($url->$columnName != null && trim($url->$columnName) != "")
		{
			return $url->$columnName;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function getVisitorNumber()
{
	global $db;
	$sql = "SELECT count FROM visitor_number LIMIT 1";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$hitcountervar = 0;
	if($stmt->rowCount() === 1)
	{
		$name = $stmt->fetch();
		$hitcountervar = intval($name['count']) + 1;
		
		if(!isset($_SESSION['hitcounter']))
		{
			
			$sql = "UPDATE visitor_number SET count='".$hitcountervar."',visiting_timestamp=now()";

			$stmt = $db->prepare($sql);
			if($stmt->execute())
			{
				$_SESSION['hitcounter'] = $hitcountervar;	
			}
		}
		else
		{
			$_SESSION['hitcounter'] = $hitcountervar;
		}
		return $hitcountervar;
	}
}

function sendMail($recipient, $subject = "", $body="")
{
	include($_SERVER['DOCUMENT_ROOT'] . '/Resi/assets/lib/PHPMailer/class.phpmailer.php');
	include($_SERVER['DOCUMENT_ROOT'] . '/Resi/assets/lib/PHPMailer/class.smtp.php'); 

	$mail             = new PHPMailer();

	$mail->IsSMTP();
	$mail->SMTPAuth   = true;                  
	$mail->SMTPSecure = "ssl";
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = 465;

	$mail->Username   = "";
	$mail->Password   = "";

	$mail->From       = "";
	$mail->FromName   = "Question Paper Portal";
	$mail->Subject    = $subject;
	$mail->WordWrap   = 50;

	$mail->Body 	  = $body;
	$mail->isHTML(true);
	
	$mail->AddReplyTo("","Webmaster");

	foreach ($recipient as $receiver) 
	{
		$mail->AddAddress($receiver);
	}

	if(!$mail->Send()) 
	{
		return false;
	} 
	else 
	{
		return true;
	}
}

function getCount($table = '')
{
	global $db;
	$sql = "SELECT count(*) FROM ".$table." LIMIT 1";
	$result = $db->prepare($sql); 
	$result->execute(); 
	$count = $result->fetchColumn(); 
	return $count;
	
}
function getCounts($parameters)
{
	global $db;
	
	$table = isset($parameters['table']) ? $parameters['table'] : '';
	$condition = isset($parameters['condition']) ? $parameters['condition'] : '';
	$sql = "SELECT count(*) FROM $table $condition";
	$result = $db->prepare($sql); 
	$result->execute(); 
	$count = $result->fetchColumn(); 
	return $count;
	
}
function deletePriority($parameters)
{
	global $db;
	$tables = array(
		'board_member',
		'board_member_type'
	);
	
	$table = isset($parameters['table']) ? $parameters['table'] : "";
	$id = isset($parameters['id']) ? $parameters['id'] : "";
	$condition = isset($parameters['condition']) && $parameters['condition'] ? $parameters['condition']." AND " : "WHERE ";
	
	$priority=fetchData(array('table' => $table,'condition' => 'WHERE Id='.$id))[0]['priority'];
	$sql = "update ".$table." set priority=priority-1 ".$condition."priority>$priority ";	
	$stmt = $db->prepare($sql);
	if($stmt->execute())
	{
		return True;
	}
	return False;
}

?>