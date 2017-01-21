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
    
    public function __construct($inFeatureSetCsv, $inFeatureCsv)
    {
        $this->mOwners = $this->getFeatureSetOwnersFromCsv($inFeatureSetCsv); 

        $csvData = $this->readCsv($inFeatureCsv);
        $this->validateFeaturesCsv($csvData);
        $featureAssociations =  $this->getFeatureAssociations($csvData);
        $this->mFeatureAreas = $this->getFeatureAreasFromAssociations($featureAssociations);
    }

    protected function getFeatureSetOwnersFromCsv($inCsvFilePath)
    {
        $csvData = $this->readCsv($inCsvFilePath);
        $this->validateFeatureSetCsv($csvData);
        $owners = array();
        foreach ($csvData as $row) {
            $this->validateFeatureSetRow($row);
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

    protected function validateFeatureSetCsv($inCsvData)
    {
        $this->throwIfCsvEmpty($inCsvData);
        $this->throwIfFeatureSetCsvHasIncorrectHeaders($inCsvData);
    }

    protected function validateFeatureSetRow(array $inRow)
    {
        if (empty($inRow['Feature Area'])) {
            throw new InvalidCsvInputException("Row may not contain empty Feature Area");
        }
        if (empty($inRow['Feature Set'])) {
            throw new InvalidCsvInputException("Row may not contain empty Feature Set");
        }
    }

    protected function throwIfFeatureSetCsvHasIncorrectHeaders(array $inCsvData) 
    {
        if (! array_key_exists("Feature Area", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature Set CSV input missing field: 'Feature Area'");
        }
        if (! array_key_exists("Feature Set", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature Set CSV input missing field: 'Feature Set'");
        }
        if (! array_key_exists("Owner", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature CSV input missing field: 'Owner'");
        }
    }

    protected function validateFeaturesCsv($inCsvData)
    {
        $this->throwIfCsvEmpty($inCsvData);
        $this->throwIfFeaturesCsvHasIncorrectHeaders($inCsvData);
    }

    protected function throwIfCsvEmpty($inCsvData)
    {
        if (empty($inCsvData)) {
            throw new InvalidCsvInputException("Received empty CSV\n");
        }
    }

    protected function throwIfFeaturesCsvHasIncorrectHeaders(array $inCsvData) 
    {
        if (! array_key_exists("Feature Area", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature CSV input missing field: 'Feature Area'");
        }
        if (! array_key_exists("Feature Set", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature CSV input missing field: 'Feature Set'");
        }
        if (! array_key_exists("Feature", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature CSV input missing field: 'Feature'");
        }
        if (! array_key_exists("Due Date", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature CSV input missing field: 'Due Date'");
        }
        if (! array_key_exists("Status", $inCsvData[0])) {
            throw new InvalidCsvInputException("Feature CSV input missing field: 'Status'");
        }
    }

    public function getFeatureAreas()
    {
        return $this->mFeatureAreas;
    }

    protected function getFeatureAssociations(array $inCsvData)
    {
        $featureAssociations = array();

        foreach ($inCsvData as $row) {
            $this->validateFeatureRow($row);
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
}
