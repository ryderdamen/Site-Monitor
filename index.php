<?php
	
	// index.php - Displays site information
	// Author: Ryder Damen - ryderdamen.com/projects/site-monitor
	
	include(__DIR__ . '/app/config.php');
	include(__DIR__ . '/inc/functions.php');
	if ($indexAccessKey != $_GET['key']) die;	
	$responses = json_decode( file_get_contents(__DIR__ . '/app/responses.json'), true);
	$statusCodes = json_decode( file_get_contents(__DIR__ . '/inc/statusCodes.json'), true);
		
?>
<!DOCTYPE html>
<html>
	<head>
    	<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Monitor</title>
		<link rel="stylesheet" href="inc/main.css" type="text/css">
		<link href="inc/lightbox/css/lightbox.min.css" rel="stylesheet">
		<script src="inc/lightbox/js/lightbox-plus-jquery.min.js"></script>
		<script>
		    lightbox.option({
		      'fadeDuration': 100
		    })
		</script>
  	</head>
  	<body>
	  	<header>
	  		<h1 class="title">Site Monitor 🚦</h1>
  		</header>
  		<?php
	  		foreach ($sites as $url) { // Loop through sites and print the image and name
		  		$status = $responses[$url]['status'];
		  		$date = $responses[$url]['date'];
				$parsed_URL = parse_url( $url );
				$host = $parsed_URL['host'];
				$path_image = isset($parsed_URL['path']) ? str_ireplace('/', '-', $parsed_URL['path']) : "";
				$path = isset($parsed_URL['path']) ? $parsed_URL['path'] : "";
				$imageUrl =  'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] .'/screenshots/' . $host . $path_image . '.png';
		?>
		<div class="site-container">
			<div class="site-info">
				<a href="<?php echo $url; ?>" target="_blank">
					<div class="title <?php echo ($status == 200) ? "status-ok" : "status-error"; ?>">
						<span class="title-inner"><?php 
							if ($status == 200) echo $host . $path;
							else echo $host . $path . ' ( ' . $status . ' )';
							 ?>
						</span>
					</div>
				</a>
				<div class="status">
					<ul>
						<li>Last Updated: <?php echo time_elapsed_string($date)?></li>
						<li>Status: <?php echo $status?></li>
						<?php 
							if ($status !== 200 ) {
								$message = "";
								foreach ($statusCodes as $code ) {
									if ($code['code'] == $status) {
										$message = $code['phrase'];
									}
								}
								echo '<li>' . $message . '</li>';
							}
						?>
					</ul>
				</div>
				<div class="more"></div>
			</div>
			<a href="<?php echo $imageUrl; ?>" data-lightbox="sites" data-title="<?php echo $host . $path; ?>" >
				<div class="site-screen" style="background-image: url(<?php echo $imageUrl; ?>);"></div>
			</a>
		</div>
		<?php
	  		}
  		?>
  	</body>
</html>
	
	