#!/usr/bin/php

<?php
$location = ":/var/lib/mysql/mysql.sock";
$user = "user";
$pass = "pass";
$db = "asteriskcdrdb";
$table = "cdr";

$file_way_src = '/var/spool/asterisk/monitor/';
$file_way_dst = '/root/beerdy/day_ago_mp3/';

mysql_connect($location, $user, $pass) or die('conection error');
mysql_select_db($db) or die('db error');

mysql_query('SET character_set_client = utf8');
mysql_query('SET character_set_connection = utf8');
mysql_query('SET character_set_results = utf8');
mysql_query('SET character set utf8');
mysql_query('SET names utf8;');

$date = date_create();
$timestamp = date_timestamp_get($date);

$timestamp = strtotime('-1 day', $timestamp);
$day_ago_dot = date('d.m.Y', $timestamp);
$day_ago_slash = date('Y/m/d', $timestamp);


$from = $day_ago_dot.' 07:55';
$to   = $day_ago_dot.' 20:05';

$result = mysql_query('SELECT recordingfile FROM cdr  WHERE `recordingfile` is not null  AND `duration` BETWEEN "0" AND "3540" AND `calldate` BETWEEN STR_TO_DATE("'.$from.'", "%d.%m.%Y %H:%i") AND STR_TO_DATE("'.$to.'", "%d.%m.%Y %H:%i") AND ( `dst` IN(3144,3187,3189) AND `src` NOT IN(3100,3101,3102,3103,3104,3105,3106,3107,3108,3109,3110,3111,3112,3113,3114,3115,3116,3117,3118,3119,3120,3121,3122,3123,3124,3125,3126,3127,3128,3129,3130,3131,3132,3133,3134,3135,3136,3137,3138,3139,3140,3141,3142,3143,3144,3145,3146,3147,3148,3149,3150,3151,3152,3153,3154,3155,3156,3157,3158,3159,3160,3161,3162,3163,3164,3165,3166,3167,3168,3169,3170,3171,3172,3173,3174,3175,3176,3177,3178,3179,3180,3181,3182,3183,3184,3185,3186,3187,3188,3189,3190,3191,3192,3193,3194,3195,3196,3197,3198,3199) AND channel LIKE "%from-queue%"  )  ORDER BY `calldate` DESC LIMIT 0, 2000');
$i = 0;

$files = glob($file_way_dst.'*');
foreach($files as $file){
  if(is_file($file))
    unlink($file);
}

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
//	echo "\t\n";
	foreach ($line as $col_value) {
		$i++;
		$file_src = $file_way_src.$day_ago_slash.'/'.$col_value;
		$file_dst = $file_way_dst.$col_value;
		copy($file_src,$file_dst);
//		echo "\t\t$col_value\n";
	}
//	echo "\t\n";
}

echo 'Count files: '.$i;

unset($user);
unset($location);
unset($pass);
unset($db);

//date_default_timezone_set("Etc/GMT+4");
?>