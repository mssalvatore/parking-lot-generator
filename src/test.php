<?php

namespace ParkingLot;

use \DateTime as DateTime;


error_reporting(E_ALL);

require_once(__DIR__ . "/../vendor/autoload.php");

function featureAreasToJson($inFeatureAreas)
{
    echo "{\n";
    echo "    \"featureAreas\": [\n";

    foreach ($inFeatureAreas as $featureArea)
    {
        echo "        {\n";
        echo "            \"name\": \"{$featureArea->getName()}\",\n";
        echo "            \"featureSets\": [\n";
        foreach ($featureArea->getFeatureSets() as $featureSet) {
            echo "                {\n";
            echo "                    \"name\": \"{$featureSet->getName()}\",\n";
            echo "                    \"owner\": \"MSS\",\n";
            echo "                    \"features\": [\n";
            foreach ($featureSet->getFeatures() as $feature) {
                $dueDate = $feature->getDueDate()->format('m/d/Y');
                echo "                        {\n";
                echo "                            \"name\": \"{$feature->getName()}\",\n";
                echo "                            \"dueDate\": \"$dueDate\",\n";
                echo "                            \"status\": \"\"\n";
                echo "                        },\n";
            }
            echo "                    ]\n";
            echo "                },\n";
        }
        echo "            ]\n";
        echo "        },\n";
    }
    echo    "    ]\n";
    echo "}";
}

try {
    $featureAreas = InputParser::getFeatureAreas(__DIR__ . "/../config/features.json");
} catch (\Exception $ex) {
    echo "An exception was thrown while attempting to parse input: {$ex->getMessage()}\n";
    exit(1);
}


$renderer = new Renderers\HtmlRenderer();
$renderedFa = $renderer->renderParkingLot($featureAreas);

echo $renderedFa;
echo "\n";

?>
