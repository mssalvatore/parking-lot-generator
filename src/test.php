<?php

namespace ParkingLot;

error_reporting(E_ALL);

require_once(__DIR__ . "/../vendor/autoload.php");

$features = array();
$features[] = new Features\Feature("Feature 1", "Dec 2016", false);
$features[] = new Features\Feature("Feature 2", "Jan 2017", true);
$features[] = new Features\Feature("Feature 3", "March 2017", false);

$fs = new Features\FeatureSet("TestFeatureSet", $features, "MSS");

$fa = new Features\FeatureArea("TestArea", array($fs));

$renderer = new Renderers\HtmlRenderer();
$renderedFa = $renderer->renderParkingLot(array($fa));
echo $renderedFa;
echo "\n";

?>
