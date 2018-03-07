<?php
	
	// Cron.php - Takes screenshots and follows up with sites
	// Schedule this file via a php cron job
	// Author: Ryder Damen - ryderdamen.com/projects/site-monitor

	// Handling dependency warnings
	if (PHP_MAJOR_VERSION >= 7) {
	    set_error_handler(function ($errno, $errstr) {
	       return strpos($errstr, 'Declaration of') === 0;
	    }, E_WARNING);
	}
			    
	// Initialization
	include( __DIR__ . '/config.php' );
	require '../vendor/autoload.php';
	use JonnyW\PhantomJs\Client;
	$client = Client::getInstance();
	$client->getEngine()->setPath('/usr/local/bin/phantomjs');
	$client->isLazy();
	
	// Set dimensions for capture    
	$width  = 1800;
	$height = 1200;
	$top    = 0;
	$left   = 0;
	
	$responses = array();
	
	// Loop through the sites array
	foreach ($sites as $url ) {
		
		$parsed_URL = parse_url( $url );
		$host = $parsed_URL['host'];
		$path = isset($parsed_URL['path']) ? str_ireplace('/', '-', $parsed_URL['path']) : "";
				
	    // Request Screenshot from the site, and save it to the directory
	    $request = $client->getMessageFactory()->createCaptureRequest($url, 'GET');
	    $request->setOutputFile( __DIR__ . '/../screenshots/' . $host . $path . '.png');
	    $request->setViewportSize($width, $height);
	    $request->setCaptureDimensions($width, $height, $top, $left);
	    $response = $client->getMessageFactory()->createResponse();
	    $client->send($request, $response);

	    $responses[$url] = array(
		    'status' => $response->status,
		    'date' => $response->headers['Date']
	    );
	}
	
	$json = fopen("responses.json", "w") or die("400: Error");
	fwrite($json, json_encode($responses, JSON_PRETTY_PRINT));
	fclose($json);
	
	echo "200: OK" . PHP_EOL;
    
?>