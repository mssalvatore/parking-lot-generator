<?php

namespace ParkingLot;

error_reporting(E_ALL);

require_once(__DIR__ . "/../vendor/autoload.php");

$features = array();
$features[] = new Features\Feature("Feature 1", "Dec 2016");
$features[] = new Features\Feature("Feature 2", "Jan 2017", true);
$features[] = new Features\Feature("Feature 3", "March 2017");
$fs = new Features\FeatureSet("TestFeatureSet", $features, "MSS");

$features = array();
$features[] = new Features\Feature("Feature 1", "Dec 2016");
$features[] = new Features\Feature("Feature 2", "Jan 2017", false, true);
$features[] = new Features\Feature("Feature 3", "March 2017");
$features[] = new Features\Feature("Feature 3", "March 2017");
$fs1 = new Features\FeatureSet("A TestFeature Set2", $features, "MSP");

$features = array();
$features[] = new Features\Feature("Feature 1", "Dec 2016");
$features[] = new Features\Feature("Feature 2", "Jan 2017");
$features[] = new Features\Feature("Feature 3", "March 2017");
$features[] = new Features\Feature("Feature 3", "March 2017");
$fs2 = new Features\FeatureSet("Mikes Features", $features, "ACS");

$features = array();
$features[] = new Features\Feature("Feature 1", "Dec 2016", true);
$features[] = new Features\Feature("Feature 2", "Jan 2017", true);
$fs3 = new Features\FeatureSet("Mikes Features", $features, "MSP");

$fa = new Features\FeatureArea("TestArea2", array($fs, $fs1, $fs2, $fs3));

$renderer = new Renderers\HtmlRenderer();
$renderedFa = $renderer->renderParkingLot(array($fa));
echo $renderedFa;
echo "\n";

?>
