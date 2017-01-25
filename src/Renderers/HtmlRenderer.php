<?php

namespace ParkingLot\Renderers;

use \DateTime as DateTime;

use \ParkingLot\Features\FeatureSet as FeatureSet;
use \ParkingLot\Features\FeatureArea as FeatureArea;
use \ParkingLot\Templates as Templates;

class HtmlRenderer extends Renderer
{
    const FS_WIDTH = 125;
    protected $mBinSize;

    public function __construct()
    {
        $this->mBinSize = 11;
    }

    public function renderParkingLot($inProjectName, array $inFeatureAreas)
    {
        usort($inFeatureAreas, array($this, "compareFeatureAreas"));

        $this->resizeBins($inFeatureAreas);

        $rows = $this->packRows($inFeatureAreas);
        $areaRows = "";
        foreach ($rows as $row) {
            $areaRows .= $this->renderAreaRow($row);
        }

        $html = str_replace("%%PROJECT_NAME%%", $inProjectName, Templates::FRAME);
        $html = str_replace("%%TIME%%", date('H:i \o\n m/d/Y'), $html);

        return str_replace("%%FEATURE_AREA_ROWS%%", $areaRows, $html);
    }

    protected function compareFeatureAreas(FeatureArea $inLeft, FeatureArea $inRight) {
        $leftSets = $inLeft->getNumberOfFeatureSets();
        $rightSets = $inRight->getNumberOfFeatureSets();

        if ($leftSets == $rightSets) {
            return 0;
        }

        return $leftSets > $rightSets ? -1 : 1;
    }

    protected function resizeBins(array $inFeatureAreas)
    {
        if (count($inFeatureAreas[0]->getFeatureSets()) > $this->mBinSize) {
            $this->mBinSize = count($inFeatureAreas[0]->getFeatureSets());
        }
    }

    protected function renderAreaRow(array $inFeatureAreas)
    {
        $featureAreas = "\n";
        foreach ($inFeatureAreas as $featureArea) {
            $featureAreas .= $this->renderFeatureArea($featureArea);
        }

        return str_replace("%%FEATURE_AREAS%%", $featureAreas, Templates::FEATURE_AREA_ROW_TEMPLATE);
    }

    public function renderFeatureArea(FeatureArea $inFeatureArea)
    {
        $featureOwners = "\n";
        $featureSets = "\n";
        foreach ($inFeatureArea->getFeatureSets() as $featureSet) {
            $featureSets .= $this->renderFeatureSet($featureSet);
            $featureOwners .= $this->renderFeatureOwner($featureSet);
        }

        $featureArea = str_replace("%%WIDTH%%", self::FS_WIDTH * count($inFeatureArea->getFeatureSets()), Templates::FEATURE_AREA_TEMPLATE);
        $featureArea = str_replace("%%TITLE%%", $inFeatureArea->getName(), $featureArea);
        $featureArea = str_replace("%%FEATURE_SET_OWNERS%%", $featureOwners, $featureArea);
        $featureArea = str_replace("%%FEATURE_SETS%%", $featureSets, $featureArea);

        return $featureArea;
    }

    public function renderFeatureSet(FeatureSet $inFeatureSet, $inPaddingLeft = 0, $inPaddingRight = 0)
    {
        $fs = str_replace("%%PADDING_LEFT%%", $inPaddingLeft, Templates::FEATURE_SET_TEMPLATE);
        $fs = str_replace("%%PADDING_RIGHT%%", $inPaddingRight, $fs);
        $fs = str_replace("%%TITLE%%", $inFeatureSet->getName(), $fs);
        $fs = str_replace("%%NUM_FEATURES%%", $inFeatureSet->getNumberOfFeatures(), $fs);
        $fs = str_replace("%%PERCENT_COMPLETE%%", $inFeatureSet->getPercentCompleted(), $fs);
        $fs = str_replace("%%DUE_DATE%%", $inFeatureSet->getDueDate(), $fs);

        $backgroundStyle = $this->determineFeatureSetBackgroundStyle($inFeatureSet);
        $fs = str_replace("%%BG_STYLE%%", $backgroundStyle, $fs);

        return $fs;
    }

    protected function determineFeatureSetBackgroundStyle($inFeatureSet)
    {
        $now = new DateTime();

        if ($inFeatureSet->isCompleted()) {
            return "completed";
        }

        if ($now > $inFeatureSet->getRawDueDate()) {
            return "warning";
        }

        if ($inFeatureSet->isInProgress()) {
            return "in_progress";
        }

        return "not_started";
    }

    public function renderFeatureOwner(FeatureSet $inFeatureSet, $inPaddingLeft = 0, $inPaddingRight = 0)
    {
        $owner = str_replace("%%PADDING_LEFT%%", $inPaddingLeft, Templates::FEATURE_SET_OWNER_TEMPLATE);
        $owner = str_replace("%%PADDING_RIGHT%%", $inPaddingRight, $owner);
        $owner = str_replace("%%OWNER%%", $inFeatureSet->getOwner(), $owner);

        return $owner;
    }

    protected function packRows($inFeatureAreas)
    {
        $bins = array();

        foreach($inFeatureAreas as $area)
        {
            $placed = false;
            foreach ($bins as &$bin)
            {
                if ($this->sumBin($bin) + $area->getNumberOfFeatureSets() <= $this->mBinSize) {
                    $bin[] = $area;
                    $placed = true;
                    break;
                }
            }
            if (!$placed) {
                $bins[] = array($area);
            }
        }

        return $bins;
    }

    protected function sumBin(array $inBin)
    {
        $sum = 0;
        foreach ($inBin as $featureArea)
        {
            $sum += $featureArea->getNumberOfFeatureSets();
        }

        return $sum;
    }
}

?>
