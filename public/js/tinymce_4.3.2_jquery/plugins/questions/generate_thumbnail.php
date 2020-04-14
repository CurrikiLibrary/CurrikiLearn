<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

  exit(0);
}

//Get URL Headers

require_once '../../../functions.php';
$url = 'http://' . str_replace(array('http://', 'https://'), '', urldecode($_REQUEST['url']));
$response = array(
    'status' => '0', //0 if uploading or validation is halted or not successfull otherwise 1
    'error' => '', //Error message why it is failed
    'url' => $url, //S3 bucket Object URL for uplaoded file
    'url_alt1' => $url, //Aleternative resource for file
    'poster' => '', //Poster of resource for file
    'filename' => $url, //Original file name of uploaded file. it will be used only to show user
    'uniquename' => uniqid(), //File will be savedo on /wp-contents/uploads folder as well as on S3 with this name
    'folder' => $vars['resLinkImages'], //Folder name of file on S3 as well as /wp-contents/uploads folder
    'bucket' => $vars['awsBucket'],
    'ext' => 'jpg', //Extenssion of uploaded file
    'type' => 'external',
    'transcoded' => 'T',
    'SDFstatus' => 'Source has an unsupported content-type',
    'html' => '<p>Error: File Not Exists</p>',
    'time' => time());

$headers = get_headers($url, 1);
$width = 854;
$height = 480;
$size = $headers["Content-Length"];
$response['ext'] = strtolower(current(explode(';', end(explode('/', $headers["Content-Type"])))));
$upload_path = $vars['wp_contents'] . $vars['resUploadFolder'] . $response['folder']; // relative path from filemanager folder to upload files folder

if ($response['ext'] == 'html') {
  $response['ext'] = 'jpg';
  $command = "/usr/local/bin/wkhtmltoimage --width {$width} --crop-y 0 --crop-h {$height} --load-error-handling ignore";
  $test = (isset($_REQUEST['test']) ? ' 2>&1' : '');
  system("$command '$response[url]' {$vars[upload_path]}{$response[folder]}{$response[uniquename]}.{$response[ext]} {$test}");
  uploadFileS3($response);
} elseif (($response['ext'] == 'swf' OR $response['ext'] == 'x-shockwave-flash') && $size < $vars['resMaxSWFSize']) {
  $response['ext'] = 'swf';
  $response['type'] = 'swf';
} elseif (in_array($response['ext'], explode(',', $vars['resAllowedImg'])) && $size < $vars['resMaxImageSize']) {
  $response['type'] = 'image';
} elseif (in_array($response['ext'], explode(',', $vars['resAllowedVideo'])) && $size < $vars['resMaxVideoSize']) {
  $response['type'] = 'video';
} elseif (in_array($response['ext'], explode(',', $vars['resAllowedDocs'])) && $size < $vars['resMaxDocSize']) {
  $response['type'] = 'document';
} else {
  $response['ext'] = 'downloadablefile';
  $response['url'] = 'https://currikicdn.s3-us-west-2.amazonaws.com/resourceimgs/561d08936193b.png';
}

echo json_encode(getEmbedHTML($response));

exit;
?>
