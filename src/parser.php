<?php

namespace Abraham\TwitterOAuth;

include "TwitterOAuth.php";


$consumer="rjdbtjQGfI8mSbGgcj4utIyS0";
$consumersecret="02wEB6XPDnuVZNd6VW3Mi1p1gvT0qaNXSFPTHCs2KpmOvzoCJj";

$access_token="3093406657-CECXniHuiEmqX1Vh3wbNVJjPK1ur8iVgl79CBbp";
$access_token_secret="8klf7c9tPQ9vYYsZr4yoQ5lVnnwlQNZhWXln3KbZwXiGm";


$connection = new TwitterOAuth($consumer, $consumersecret, $access_token, $access_token_secret);
$connection->setProxy(array(
    'CURLOPT_PROXY' => '192.168.68.72',
    'CURLOPT_PROXYUSERPWD' => '',
    'CURLOPT_PROXYPORT' => 809,
));
//$tweets=$twitter->get('search/tweets', array('q' => 'hghgj','count'=>'4'));


//print_r($tweets);
$table=array();
if(isset($_POST['keyword'])){
    $latlong=$_POST['latitude'].','.$_POST['longitude'].',5mi';
    $tweets=$connection->get('search/tweets', array('q' => $_POST['keyword'],'count'=>'40','geocode'=>$latlong,'result_type'=>'mixed'));
    $statuses=$tweets->statuses;



        foreach($statuses as $tweet ){
            /*
            echo '<img src="'.$tweet->user->profile_image_url.'" /> '.'Time: '.$tweet->created_at.'&nbsp;&nbsp;&nbsp;&nbsp;'.
                'Name: '.$tweet->user->name.'&nbsp;&nbsp;&nbsp;&nbsp;'.'Location: '.$tweet->coordinates->coordinates[0].
                ','.$tweet->coordinates->coordinates[1].'&nbsp;&nbsp;&nbsp;&nbsp;'.'Text: '. $tweet->text.'<br>';
            */

            $table[]=array($tweet->user->profile_image_url,$tweet->created_at,$tweet->user->name,$tweet->coordinates->coordinates[1],
                $tweet->coordinates->coordinates[0],$tweet->text);

        }
    }
//echo 'Returned array: '.'<br><br><br><pre>'.print_r($table,1).'</pre>';
echo json_encode($table);



/* Fields to parse from the object/associative array

created_at (String)
"created_at": "Mon Sep 24 03:35:21 +0000 2012"


coordinates->coordinates(collection of float)
"coordinates":
    {
        "coordinates":
    [
        -75.14310264,
        40.05701649
    ],
    "type":"Point"
}


text(String)
"text":"Tweet Button, Follow Button, and Web Intents javascript now support SSL http:\/\/t.co\/9fbA0oYy ^TS"

user->name(String)

*/
