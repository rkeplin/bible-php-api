<?php

$query = array(
    "from" => 0,
    "size" => 10,
    "query" => array(
        "match" => array(
            "verse" => "water"
        )
    ),
    "highlight" => array(
        "pre_tags" => array('<span class="highlight">'),
        "post_tags" => array("</span>"),
        "fields" => array(
            "verse" => array('type' => 'plain')
        )
    )
);

$query = json_encode($query);

$ch = curl_init('http://bible-es:9200/nlt/_search');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($query))
);

$result = curl_exec($ch);
$result = json_decode($result);

var_dump($result->hits->total->value);
var_dump($result->hits->hits);
