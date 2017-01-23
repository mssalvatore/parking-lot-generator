<?php

namespace ParkingLot;

use \DateTime as DateTime;

use ParkingLot\Exceptions\InvalidFeatureException as InvalidFeatureException;

use ParkingLot\Features\Feature as Feature;
use ParkingLot\Features\FeatureSet as FeatureSet;
use ParkingLot\Features\FeatureArea as FeatureArea;

class InputParser
{
    protected static function decodeFeatureAreasJson($inFeatureAreasJsonPath)
    {
        $featureAreasJson = file_get_contents($inFeatureAreasJsonPath);

        $featureAreasDecoded = json_decode($featureAreasJson, true);

        if ($featureAreasJson === false) {
            throw new \Exception("Failed to read file: $inFeatureAreasJsonPath");
        }

        return static::buildFeatureAreasFromDecodedJson($featureAreasDecoded);
    }

    protected static function buildFeatureAreasFromDecodedJson($inFeatureAreasDecoded)
    {
        $featureAreas = array();
        foreach ($inFeatureAreasDecoded['featureAreas'] as $featureArea) {
            $featureAreaName = $featureArea['name'];
            $featureSets = static::buildFeatureSetsFromDecodedJson($featureArea['featureSets']);

            $featureAreas[] = new FeatureArea($featureAreaName, $featureSets);
        }

        return $featureAreas;
    }

    protected static function buildFeatureSetsFromDecodedJson($inFeatureSets)
    {
        $featureSets = array();
        foreach ($inFeatureSets as $featureSet) {
            $featureSetName = $featureSet['name'];
            $featureSetOwner = $featureSet['owner'];
            $features = static::buildFeaturesFromDecodedJson($featureSet['features']);

            $featureSets[] = new FeatureSet($featureSetName, $features, $featureSetOwner);
        }

        return $featureSets;
    }

    protected static function buildFeaturesFromDecodedJson($inFeatures)
    {
        $features = array();
        foreach ($inFeatures as $feature) {

            if (empty($feature['dueDate'])) {
                throw new InvalidFeatureException("Due date my not be empty");
            }
            $featureName = $feature['name'];
            $dueDate = new DateTime($feature['dueDate']);
            $status = $feature['status'];
            $isCompleted = $status == "Completed";
            $inProgress = $status == "In Progress";

            $features[] = new Feature($featureName, $dueDate, $isCompleted, $inProgress);
        }

        return $features;
    }

    public static function getFeatureAreas($inFeatureAreasJsonPath)
    {
        return static::decodeFeatureAreasJson($inFeatureAreasJsonPath);
    }
}
