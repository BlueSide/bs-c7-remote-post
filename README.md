# Contact Form 7 Remote Post

A plugin that let's you send Contact Form 7 data to a web service via HTTP POST.

## Dependencies
#### Contact Form 7
The plugin we get the data from
<br />
https://wordpress.org/plugins/contact-form-7/

#### Contact Form 7 - Dynamic Text Extension
Used for the `dynamichidden` fields
<br />
https://nl.wordpress.org/plugins/contact-form-7-dynamic-text-extension/
<br />
<br />
## Installation
First add the `bs-c7-remote-post.php` file to:
```
[path to WordPress installation]/wp-content/plugins/bs-cf7-remote-post
```
Then go to `[WordPress URL]/wp-admin/plugins.php` and activate the `Contact Form 7 Remote Post` plugin
<br />
<br />
## Usage
You should add the following `dynamichidden` fields to your Contact Form:
```
[dynamichidden fields-to-send hiddendefault "[comma seperated list of fields]"]
```
and
```
[dynamichidden url-to-web-service hiddendefault "[url to the webservice]"]
```