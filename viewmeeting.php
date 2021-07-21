<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/test/OnePage/db.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/test/OnePage/functions.php");
	$data = "";
	if(!isset($_GET['id']) || !is_numeric($_GET['id']))
	{
		//echo "<script>window.location='/QR_Staff/admin/faculty/add_faculty.php';</script>";
		exit(0);
	}
	else if($data = fetchData(array("table" => "meeting", "condition" => "WHERE id  = ".trim($_GET['id']))))
	{
		$data = $data[0];

	}
	else{
		exit(0);
	}
if(is_array($data) || is_object($data)){	
		foreach($data as $d)
		$url=$d;
}
	
?>
<!--  <script type="text/javascript">
                    	
                    	alert(data_url);
                    </script> -->
 <?php 
 $url=substr($url,26,11);
 ?>
<div class="iframe-container" style="overflow: hidden; padding-top: 56.25%; position: relative;">
    	<iframe allow="microphone; camera" style="border: 0; height: 100%; left: 0; position: absolute; top: 0; width: 100%;" src="https://success.zoom.us/wc/join/<?php echo($url);?>" frameborder="0" sandbox="allow-forms allow-scripts allow-same-origin" frame-ancestors ="none"></iframe>
	</div>
					 	<!-- <source  frameborder="0" type=""> -->