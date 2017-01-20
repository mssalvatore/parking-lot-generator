<?php

namespace ParkingLot;

error_reporting(E_ALL);

require_once(__DIR__ . "/../vendor/autoload.php");

$features = array();
$features[] = new Features\Feature("Feature 1", "Dec 2016", false);
$features[] = new Features\Feature("Feature 2", "Jan 2017", true);
$features[] = new Features\Feature("Feature 3", "March 2017", false);

$fs = new Features\FeatureSet("TestFeatureSet", $features, "MSS");

$features[] = new Features\Feature("Feature 4", "March 2017", true);
$fs1 = new Features\FeatureSet("A TestFeature Set2", $features, "MSP");

$fa = new Features\FeatureArea("TestArea2", array($fs, $fs1));
$fa2 = new Features\FeatureArea("TestArea1", array($fs, $fs1));

$renderer = new Renderers\HtmlRenderer();
$renderedFa = $renderer->renderParkingLot(array($fa, $fa2));
echo $renderedFa;
echo "\n";

?>
