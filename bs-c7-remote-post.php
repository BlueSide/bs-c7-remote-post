<?php
/*
Plugin Name: Contact Form 7 Remote Post
Plugin URI: http://www.blueside.nl/
Description: When submitting a Contact Form 7, POST it to a specified web service
Author: Marlon Peeters, Blue Side
Author URI: http://www.blueside.nl/
Text Domain: bs-c7-post
Version: 1.0
License: MIT License

MIT License

Copyright (c) 2017 Blue Side v.o.f.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

// URL to send the HTTP request to
define('URL_TO_WEBSERVICE', 'url-to-web-service');

// Comma separated 
define('FIELDS_TO_SEND', 'fields-to-send');

// The JSON parameter to store the contents of the uploaded file
define('FILE_CONTENT', 'FileContent');

add_action('wpcf7_before_send_mail', 'wpcf7_to_web_service');

function wpcf7_to_web_service ($WPCF7_ContactForm)
{
    // Get Contact Form 7 data
    $submission = WPCF7_Submission::get_instance();
	$posted_data = $submission->get_posted_data();	   
    $uploaded_files = $submission->uploaded_files();

    // Check if the hidden URL field is given
    if(isset($posted_data[URL_TO_WEBSERVICE]))
    {
        $url = $posted_data[URL_TO_WEBSERVICE];

        $fields_to_send = explode(",", $posted_data[FIELDS_TO_SEND]);

        // Trim extra whitespaces
        for($i = 0; $i < count($fields_to_send); ++$i)
        {
            $fields_to_send[$i] = trim($fields_to_send[$i]);
        }
        
        $payload = [];
        foreach ($fields_to_send as $key)
        {
            $payload[$key] = $posted_data[$key];
        }

        if(isset($uploaded_files))
        {
            foreach ($uploaded_files as $filePath)
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
