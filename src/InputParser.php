<?php

namespace ParkingLot;

use \DateTime as DateTime;

use ParkingLot\Exceptions\InvalidCsvInputException as InvalidCsvInputException;

use ParkingLot\Features\Feature as Feature;
use ParkingLot\Features\FeatureSet as FeatureSet;
use ParkingLot\Features\FeatureArea as FeatureArea;

class InputParser
{
    protected $mFeatureAreas;
    protected $mOwners;
    
    public function __construct($inFeatureAreasJsonPath)
    {
        $this->mFeatureAreas = $this->decodeFeatureAreasJson($inFeatureAreasJsonPath);
    }

    protected function decodeFeatureAreasJson($inFeatureAreasJsonPath)
    {
        $featureAreasJson = file_get_contents($inFeatureAreasJsonPath);

        $featureAreasDecoded = json_decode($featureAreasJson, true);

        if ($featureAreasJson === false) {
            throw new \Exception("Failed to read file: $inFeatureAreasJsonPath");
        }

        return $this->buildFeatureAreasFromDecodedJson($featureAreasDecoded);
    }

    protected function buildFeatureAreasFromDecodedJson($inFeatureAreasDecoded)
    {
        $featureAreas = array();
        foreach ($inFeatureAreasDecoded['featureAreas'] as $featureArea) {
            $featureAreaName = $featureArea['name'];
            $featureSets = $this->buildFeatureSetsFromDecodedJson($featureArea['featureSets']);

            $featureAreas[] = new FeatureArea($featureAreaName, $featureSets);
        }

        return $featureAreas;
    }

    protected function buildFeatureSetsFromDecodedJson($inFeatureSets)
    {
        $featureSets = array();
        foreach ($inFeatureSets as $featureSet) {
            $featureSetName = $featureSet['name'];
            $featureSetOwner = $featureSet['owner'];
            $features = $this->buildFeaturesFromDecodedJson($featureSet['features']);

            $featureSets[] = new FeatureSet($featureSetName, $features, $featureSetOwner);
        }

        return $featureSets;
    }

    protected function buildFeaturesFromDecodedJson($inFeatures)
    {
        $features = array();
        foreach ($inFeatures as $feature) {
            $featureName = $feature['name'];
            $dueDate = new DateTime($feature['dueDate']);
            $status = $feature['status'];
            $isCompleted = $status == "Completed";
            $inProgress = $status == "In Progress";

            $features[] = new Feature($featureName, $dueDate, $isCompleted, $inProgress);
        }

        return $features;
    }


public function getFeatureAreas()
{
    return $this->mFeatureAreas;
}

protected function validateFeatureRow(array $inRow)
{
    if (empty($inRow['Feature Area'])) {
        throw new InvalidCsvInputException("Row may not contain empty Feature Area");
    }
    if (empty($inRow['Feature Set'])) {
        throw new InvalidCsvInputException("Row may not contain empty Feature Set");
    }
    if (empty($inRow['Feature'])) {
        throw new InvalidCsvInputException("Row may not contain empty Feature");
    }
    if (empty($inRow['Due Date'])) {
        throw new InvalidCsvInputException("Row may not contain empty Due Date");
    }
}
}
