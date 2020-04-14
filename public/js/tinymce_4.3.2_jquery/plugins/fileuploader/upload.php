<?php

//echo '{"status":"1","error":"","url":"https:\/\/currikicdn.s3-us-west-2.amazonaws.com\/videos\/55537700b227b-360p.mp4","url_alt1":"https:\/\/currikicdn.s3-us-west-2.amazonaws.com\/videos\/55537700b227b-360p.webm","poster":"https:\/\/currikicdn.s3-us-west-2.amazonaws.com\/posters\/55537700b227b-00001.png","filename":"Blaze_test1_WMVWMV9MP_CBR_320x240_AR4to3_15fps_512kbps_WMA92L2_32kbps_44100Hz_Mono","uniquename":"55537700b227b","folder":"videos\/","ext":"wmv","mime":"video\/x-ms-asf","time":1431533312,"url_alt2":"https:\/\/currikicdn.s3-us-west-2.amazonaws.com\/videos\/55537700b227b-MB1\/video.m3u8"}';
//exit;

include_once($_SERVER['DOCUMENT_ROOT'] . dirname(dirname(dirname(dirname(dirname(dirname(dirname($_SERVER['REQUEST_URI']))))))) . '/libs/functions.php');

$response = array();
validateUploadFile('file', $_REQUEST['type'], $response);
if ($response['status']) {
    uploadFileS3($response);
}

echo json_encode(getEmbedHTML($response));
exit;
