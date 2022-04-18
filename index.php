<?php

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

	function CheckStr($Find1, $Find2){
		$flag;
		
		$flag = 0;
			
		$post_data = trim(strip_tags_content($_POST["txt-search"]));
		
		if(strlen($post_data) > 64){
			$post_data=substr($post_data,0,64);
		}
		
		if(strlen($post_data) > 0){
			
			$Keywords = explode(' ' , $post_data);
			
			foreach($Keywords as $key){
				if(strpos(strtolower($Find1),strtolower($key)) !== FALSE){
					$flag++;
				}
				if(strpos(strtolower($Find2),strtolower($key)) !== FALSE){
					$flag++;
				}
			}
	}
		
		unset($Keywords);
		return $flag;
	}

function GetData(){
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
		$db_file = 'data/db.txt';

		if(!file_exists($db_file)){
			die("There was a problem processing your request.");
		}
		
		$counter;
		$Lines = File($db_file);
		$counter = 0;
		
		foreach($Lines as $line){
			$DataLines = explode('|',$line);
			
			$u_title = $DataLines[0];
			$u_info = $DataLines[1];
			
			$check = CheckStr($u_title,$u_info);
			
			if($check == 1 || $check == 2){
				$counter++;
				$buffer = '<div class="search-results">' .
						'<h2><a href="' . $DataLines[2] . '" target="_blank">' . $u_title . '</a></h2>
						<span>' . $DataLines[2] . '</span>
						<p>'. $u_info . '</p>
					</div>';
					
					echo($buffer);
			}
		}
	}
	
	if(isset($counter)) {
		$GLOBALS["count"] = $counter;
		
		if($counter > 5){
			echo('<a class="top-page" href="#top">Back to top of page</a>');
		}
		
	}else{
		$GLOBALS["count"] = '';
	}
	
	unset($DataLines);
	unset($Lines);
	$buffer = '';
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
		<a name="top"></a>
		<div class="wrapper">
			<div class="container">
				<div class="add-search">
					<span><a href="additem.php">New Entry</a></span>
				</div>
				<div class="logo">
					<img class="img-fuild" src="assets/images/logo.png" title="FindIT Search Engine">
				</div>
				
				<form method="post" action="">
					<input class="frm-input" type="search" name="txt-search" placeholder="Enter search term e.g. java" required="">
					<input type="submit" class="frm-btn" name="cmd-submit" value="Search">
				</form>
				<div class="container">
					<div id="result-count" class="results-count" style="display: none;">
						
					</div>
					
					<?php
						GetData();
					?>
					
					<div id="icount" style="display: none;">
						<?php
							echo($GLOBALS["count"]);
						?>
					</div>
				</div>
			</div>
		</div>
		
<script>
	if ( window.history.replaceState ) {
  		window.history.replaceState( null, null, window.location.href );
	}
</script>

<script>
	var sCount = document.getElementById('icount').textContent.trim();
	
	if(sCount !== ''){
		var countdiv = document.getElementById('result-count');
		countdiv.style.display = "block";
		countdiv.innerHTML = '<span>' + sCount + ' matches (s) found</span>';
	}
</script>
		
		
	</body>
</html>