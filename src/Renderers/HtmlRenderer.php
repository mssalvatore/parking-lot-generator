<?php

namespace ParkingLot\Renderers;

use \ParkingLot\Features\FeatureSet as FeatureSet;
use \ParkingLot\Features\FeatureArea as FeatureArea;
use \ParkingLot\Templates as Templates;

class HtmlRenderer extends Renderer
{
    const FS_WIDTH = 94;


    public function renderParkingLot(array $inFeatureAreas)
    {
        usort($inFeatureAreas, array($this, "compareFeatureAreas"));
        $row = $this->renderAreaRow($inFeatureAreas);

        return str_replace("%%FEATURE_AREA_ROWS%%", $this->renderAreaRow($inFeatureAreas), Templates::FRAME);
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

        if ($inFeatureSet->isInProgress()) {
            $fs = str_replace("%%BG_STYLE%%", "in_progress", $fs);
        }
        else if ($inFeatureSet->isCompleted()) {
            $fs = str_replace("%%BG_STYLE%%", "completed", $fs);
        }
        else {
            $fs = str_replace("%%BG_STYLE%%", "not_started", $fs);
        }

        return $fs;
    }

    public function renderFeatureOwner(FeatureSet $inFeatureSet, $inPaddingLeft = 0, $inPaddingRight = 0)
    {
        $owner = str_replace("%%PADDING_LEFT%%", $inPaddingLeft, Templates::FEATURE_SET_OWNER_TEMPLATE);
        $owner = str_replace("%%PADDING_RIGHT%%", $inPaddingRight, $owner);
        $owner = str_replace("%%OWNER%%", $inFeatureSet->getOwner(), $owner);

        return $owner;
    }

    protected function compareFeatureAreas(FeatureArea $inLeft, FeatureArea $inRight) {
        return strcmp($inLeft->getName(), $inRight->getName());
    }
}

?>
