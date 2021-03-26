<?php 
//ini_set('display_errors','On');
//error_reporting(E_ALL);
$whoisuser = $_REQUEST['whoisuser'];
include $whoisuser. '.php';
$search = $_REQUEST['term'];
$object = new Invoice;
/*
[{"id":"Caprimulgus europaeus","label":"European Nightjar","value":"European Nightjar"},{"id":"Garrulus glandarius","label":"Eurasian Jay","value":"Eurasian Jay"},{"id":"Corvus monedula","label":"Western Jackdaw","value":"Western Jackdaw"},{"id":"Lymnocryptes minimus","label":"Jack Snipe","value":"Jack Snipe"},{"id":"Stercorarius parasiticus","label":"Parasitic Jaeger","value":"Parasitic Jaeger"},{"id":"Stercorarius longicaudus","label":"Long-tailed Jaeger","value":"Long-tailed Jaeger"},{"id":"Stercorarius pomarinus","label":"Pomarine Jaeger","value":"Pomarine Jaeger"},{"id":"Caprimulgus ruficollis","label":"Red-Necked Nightjar","value":"Red-Necked Nightjar"},{"id":"Corvus dauuricus","label":"Daurian Jackdaw","value":"Daurian Jackdaw"}]
*/

$data = $object->searchCompany($search);
header('Content-Type: application/json');
echo json_encode($data);