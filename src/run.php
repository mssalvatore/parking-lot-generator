<?php

namespace ParkingLot;

require_once(__DIR__ . "/../vendor/autoload.php");

function printUsage($inErrorMessage = NULL)
{
    if (!is_null($inErrorMessage)) {
        echo "ERROR: $inErrorMessage\n\n";
    }

    echo "\nDescription:\n";
    echo "  Generates a Parking Lot diagram from an input CSV.\n";
    echo "\n";
    echo "Usage:\n";
    echo "  php ParkingLotGenerator.phar -h | --help\n";
    echo "  php ParkingLotGenerator.phar --version\n";
    echo "  php ParkingLotGenerator.phar --input <file path> [--output <file_path>]\n";
    echo "\n";
    echo "Options:\n";
    echo "  -h --help                           Show Help\n";
    echo "  --version                           Show the version and git commit of this build\n";
    echo "  --project-name                      The name of the project\n";
    echo "  --input                             The input json file\n";
    echo "  --output                            Optional output file (default: './parking_lot.html')\n";
    echo "\n";
    echo "Output: Outputs an html file that contains a ParkingLot Diagram\n";
    echo "\n";
} 

function printVersion()
{
    $version = file_get_contents(__DIR__ . "/../version.txt");
    $version = str_replace("\n", "\n\t", $version);
    echo "\nVersion:\n\t$version\n";
}

$options = getopt("h", array("help", "version", "project-name:", "input:", "output:"));

if (array_key_exists("version", $options)) {
    printVersion();
    exit();
}

if (array_key_exists("h", $options) || array_key_exists("help", $options) || !array_key_exists("input", $options) || !array_key_exists("project-name", $options)) {
    printUsage();
    exit();
}

$projectName = $options["project-name"];
$inputFile = $options["input"];
$outputFile = "./parking_lot.html";

if (array_key_exists("output", $options)) {
    $outputFile = $options["output"];
}

try {
    $featureAreas = InputParser::getFeatureAreas($inputFile);
} catch (\Exception $ex) {
    echo "An exception was thrown while attempting to parse input: {$ex->getMessage()}\n";
    exit(1);
}


$renderer = new Renderers\HtmlRenderer();
$renderedHtml = $renderer->renderParkingLot($projectName, $featureAreas);

$success = file_put_contents($outputFile, $renderedHtml);

if ($success === false) {
    echo "Unable to write to file '$outputFile'\n";
    exit(1);
}

?>
