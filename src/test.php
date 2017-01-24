<?php

namespace ParkingLot;

use \DateTime as DateTime;


error_reporting(E_ALL);

require_once(__DIR__ . "/../vendor/autoload.php");

try {
    $featureAreas = InputParser::getFeatureAreas(__DIR__ . "/../config/jira.json");
} catch (\Exception $ex) {
    echo "An exception was thrown while attempting to parse input: {$ex->getMessage()}\n";
    exit(1);
}


$renderer = new Renderers\HtmlRenderer();
$renderedFa = $renderer->renderParkingLot($featureAreas);

echo $renderedFa;
echo "\n";

?>
