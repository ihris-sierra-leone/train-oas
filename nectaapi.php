<?php
$service_url = 'http://api.necta.go.tz/api/public/results/P0104-0068/1/2016/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJpc3N1cmVyIiwiYXVkIjoiMSIsImlhdCI6MTQ5ODE0Mjc5OSwibmJmIjoxNDk4MTQyNzk5fQ.ZKKyUrDqo3z4F3KwSQJYfbP8Dbbd92o50h2qb-vVQfE';
$curl = curl_init($service_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    echo $info;
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}else{
header('Access-Control-Allow-Origin: *');
$response = file_get_contents($service_url);
echo $response;
}
