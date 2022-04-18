<?php
	
	SetData();
	
	function SetData(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
			$db_file = 'data/db.txt';
			
			if(!file_exists($db_file)){
				die("Something went wrong while processing your request.");
			}else{
				
				$u_title = strip_tags_content($_POST['url-title']);
				$u_desc = strip_tags_content($_POST['url-desc']);
				$u_url = strip_tags_content($_POST['url-url']);
				
				//Check limits
				if(strlen($u_title) > 128){
					$u_title = substr($u_title,0,128);
				}
				
				if(strlen($u_desc) > 512){
					$u_desc = substr($u_desc,0,512);
				}
				
				if(strlen($u_url) > 256){
					$u_url = substr(256,0,512);
				}
				
				//Don't allow the user to use the seperator char
				$u_title = str_replace("|"," ",$u_title);
				$u_desc = str_replace("|"," ",$u_desc);
				$u_url = str_replace("|"," ",$u_url);
				
				$final = $u_title . "|" . $u_desc . "|" . $u_url . "\r\n";
				
				try{
					//Append to flat file db	
					$fp = fopen($db_file,'a');
					fwrite($fp,$final);
					fclose($fp);
					header("Location: index.php");
				}catch(Exception $ex){
					echo($ex->getMessage);
				}
			}
		}
}

function strip_tags_content($string) { 
    // ----- remove HTML TAGs ----- 
    $string = preg_replace ('/<[^>]*>/', ' ', $string); 
    // ----- remove control characters ----- 
    $string = str_replace("\r", '', $string);
    $string = str_replace("\n", ' ', $string);
    $string = str_replace("\t", ' ', $string);
    // ----- remove multiple spaces ----- 
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
    return $string; 
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">
		<title></title>
	</head>
	<body>
		<div class="wrapper">
			<div class="container">
				<div class="add-search">
					<span><a href="index.php">Return to homepage</a></span>
				</div>
				<h1 class="header">Add item to search engine</h1>
				
				<form method="post" action="">
					<input class="frm-input m1" type="text" name="url-title" placeholder="Enter url title" required="" maxlength="128">
					<input class="frm-input m1" type="text" name="url-desc" placeholder="short url description" required="" maxlength="512">
					<input class="frm-input m1" type="url" name="url-url" placeholder="Url address" required="" maxlength="256">
					</br>
					<input type="submit" class="btn" value="Submit Url">
					<input type="reset" class="btn btn-orange" value="Reset">
				</form>
			</div>
		</div>
		
<script>
	if ( window.history.replaceState ) {
  		window.history.replaceState( null, null, window.location.href );
	}
</script>
		
		
	</body>
</html>