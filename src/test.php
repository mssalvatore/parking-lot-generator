<?php

namespace ParkingLot;

use \DateTime as DateTime;


error_reporting(E_ALL);

require_once(__DIR__ . "/../vendor/autoload.php");


$inputParser = new InputParser(__DIR__ . "/../config/FeatureSets.csv", __DIR__ . "/../config/features.csv");

$featureAreas = $inputParser->getFeatureAreas();

$renderer = new Renderers\HtmlRenderer();
$renderedFa = $renderer->renderParkingLot($featureAreas);

echo $renderedFa;
echo "\n";


/*
$features = array();
$features[] = new Features\Feature("Feature 1", new DateTime("Nov 2016"));
$features[] = new Features\Feature("Feature 2", new DateTime("Dec 2016"), true);
$features[] = new Features\Feature("Feature 3", new DateTime("Dec 2016"));
$fs = new Features\FeatureSet("Authenticating Users", $features, "MSS");

$features = array();
$features[] = new Features\Feature("Feature 2", new DateTime("Jan 2017"), false, true);
$features[] = new Features\Feature("Feature 3", new DateTime("Mar 2017"));
$features[] = new Features\Feature("Feature 1", new DateTime("Dec 2016"));
$features[] = new Features\Feature("Feature 3", new DateTime("Jun 2017"));
$fs1 = new Features\FeatureSet("A TestFeature Set2", $features, "MSP");

$features = array();
$features[] = new Features\Feature("Feature 2", new DateTime("Jan 2017"));
$features[] = new Features\Feature("Feature 3", new DateTime("March 2017"));
$features[] = new Features\Feature("Feature 3", new DateTime("March 2017"));
$features[] = new Features\Feature("Feature 1", new DateTime("Dec 2016"));
$fs2 = new Features\FeatureSet("Mikes Features", $features, "ACS");

$features = array();
$features[] = new Features\Feature("Feature 1", new DateTime("Dec 2016"), true);
$features[] = new Features\Feature("Feature 2", new DateTime("Jan 2017"), true);
$fs3 = new Features\FeatureSet("Mikes Features", $features, "MSP");

$fa = new Features\FeatureArea("TestArea2", array($fs, $fs1, $fs2, $fs3));

$renderer = new Renderers\HtmlRenderer();
$renderedFa = $renderer->renderParkingLot(array($fa));
echo $renderedFa;
echo "\n";
*/

?>
