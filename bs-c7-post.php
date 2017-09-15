<?php
/*
Plugin Name: Contact Form 7 Post
Plugin URI: http://blueside.nl/
Description: When submitting a Contact Form 7, POST it to a specified URL
Author: Marlon Peeters
Author URI: http://blueside.nl/
Text Domain: bs-c7-post
Version: 0.1
*/

//TODO: Define constants
define('URL_TO_WEBSERVICE', 'url-to-web-service');
define('FILE_CONTENT', 'FileContent');

add_action('wpcf7_before_send_mail', 'wpcf7_to_web_service');

function wpcf7_to_web_service ($WPCF7_ContactForm)
{
    $submission = WPCF7_Submission::get_instance();
	$posted_data = $submission->get_posted_data();	   
    $uploadedFiles = $submission->uploaded_files();

    if(isset($posted_data[URL_TO_WEBSERVICE]))
    {
        $url = $posted_data[URL_TO_WEBSERVICE];

        $payload = [];
        foreach ($posted_data as $key => $value)
        {
            //NOTE: Skip keys starting with an underscore or if it's the url to the webservice
            if($key[0] !== "_" && $key !== URL_TO_WEBSERVICE)
            {
                $payload[$key] = $value;
            }
        }

        if(isset($uploadedFiles))
        {
            foreach ($uploadedFiles as $filePath)
            {
                $payload[FILE_CONTENT] = base64_encode(file_get_contents($filePath));
            }
    
        }

        $response = wp_remote_post(
            $url,
            array(
                'method' => 'POST',
                'timeout' => 45,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'Content-Type' => 'application/json'
                                   ),
                'body' => json_encode($payload),
                  ));
    }
}
