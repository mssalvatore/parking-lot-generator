<?php

namespace ParkingLot;

use \DateTime as DateTime;

use ParkingLot\Features\Feature as Feature;
use ParkingLot\Features\FeatureSet as FeatureSet;
use ParkingLot\Features\FeatureArea as FeatureArea;

class InputParser
{
    protected $mData;
    protected $mOwners;
    
    public function __construct($inFeatureSetCsv, $inFeatureCsv)
    {
        $this->mOwners = $this->getFeatureSetOwnersFromCsv($inFeatureSetCsv);
        $this->mData = $this->readCsv($inFeatureCsv);
    }

    /// Reads a csv file into an array indexed by row, column name
    ///
    /// @param  inFileName      The file name/path of the csv file to be read
    /// @param  inCustomHeaders An array containing a set of headers (column names) for the data
    /// @param  inCsvHasHeaders True (default) if the csv file already contains headers, false if the
    ///                         csv file is missing its headers row
    /// 
    /// @return A multi-dimensonal array. array[row #][column name] = column data
    protected function readCsv($inFileName)  
    {
        $inputCsv = fopen("$inFileName", 'r');
        if (!$inputCsv) {
            throw new \Exception("Couldn't open file: '$inFileName'");
        }

        $headers = fgetcsv($inputCsv);

        $csv = array();
        while (($data = fgetcsv($inputCsv)) !== FALSE) {
            $csv[] = array_combine($headers, $data);
        }

        return $csv;
    }

    public function getFeatureAreas()
    {
        $featureAssociations =  $this->getFeatureAssociations($this->mData);
        return $this->getFeatureAreasFromAssociations($featureAssociations);
    }

    protected function getFeatureAssociations()
    {
        $featureAssociations = array();

        foreach ($this->mData as $row) {
            $isCompleted = false;
            $inProgress = false;
            $featureArea = $row['Feature Area'];
            $featureSet = $row['Feature Set'];
            $feature = $row['Feature'];
            $dueDate = new DateTime($row['Due Date']);
            $status = $row['Status'];

            if ($status == "Completed") {
                $isCompleted = true;
            }
            else if ($status == "In Progress") {
                $inProgress = true;
            }

            if (!array_key_exists($featureArea, $featureAssociations)) {
                $featureAssociations[$featureArea] = array();
            }
            if (!array_key_exists($featureSet, $featureAssociations[$featureArea])) {
                $featureAssociations[$featureArea][$featureSet] = array();
            }

            $featureAssociations[$featureArea][$featureSet][] = new Feature($feature, $dueDate, $isCompleted, $inProgress);
        }

        return $featureAssociations;
    }

    protected function getFeatureAreasFromAssociations(array $featureAssociations) 
    {
        $featureAreas = array();
        foreach ($featureAssociations as $featureAreaName =>  $featureArea) {
            $featureSets = array();
            foreach ($featureArea as $featureSetName => $featureSet) {
                if (array_key_exists($featureAreaName, $this->mOwners) && 
                    array_key_exists($featureSetName, $this->mOwners[$featureAreaName])) {
                    $owner = $this->mOwners[$featureAreaName][$featureSetName];
                }
                else {
                    $owner = "";
                }
                $featureSets[] = new FeatureSet($featureSetName, $featureSet, $owner);
            }

            $featureAreas[] = new FeatureArea($featureAreaName, $featureSets);
        }

        return $featureAreas;
    }

    protected function getFeatureSetOwnersFromCsv($inCsvFilePath)
    {
        $csvData = $this->readCsv($inCsvFilePath);
        $owners = array();
        foreach ($csvData as $row) {
            $featureArea = $row['Feature Area'];
            $featureSet = $row['Feature Set'];
            $owner = $row['Owner'];

            if (!array_key_exists($featureArea, $owners)) {
                $owners[$featureArea] = array();
            }
            $owners[$featureArea][$featureSet] = $row['Owner'];
        }

        return $owners;
    }
}
