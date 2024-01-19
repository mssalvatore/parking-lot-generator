<?php

namespace ParkingLot\Renderers;

use \DateTime as DateTime;

use \ParkingLot\Features\FeatureSet as FeatureSet;
use \ParkingLot\Features\FeatureArea as FeatureArea;
use \ParkingLot\Templates as Templates;

function sanitize_xss($string) {
    return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
}

class HtmlRenderer extends AbstractRenderer
{
    const FS_WIDTH = 125;
    protected $mBinSize;

    public function __construct()
    {
        $this->mBinSize = 11;
    }

    public function renderParkingLot($projectName, array $featureAreas)
    {
        usort($featureAreas, array($this, "compareFeatureAreas"));

        $this->resizeBins($featureAreas);

        $rows = $this->packRows($featureAreas);
        $areaRows = "";
        foreach ($rows as $row) {
            $areaRows .= $this->renderAreaRow($row);
        }

        $html = str_replace("%%PROJECT_NAME%%", $projectName, Templates::FRAME);
        $html = str_replace("%%TIME%%", date('H:i \o\n m/d/Y'), $html);

        return str_replace("%%FEATURE_AREA_ROWS%%", $areaRows, $html);
    }

    protected function compareFeatureAreas(FeatureArea $left, FeatureArea $right) {
        $leftSets = $left->getNumberOfFeatureSets();
        $rightSets = $right->getNumberOfFeatureSets();

        if ($leftSets == $rightSets) {
            return 0;
        }

        return $leftSets > $rightSets ? -1 : 1;
    }

    protected function resizeBins(array $featureAreas)
    {
        if (count($featureAreas[0]->getFeatureSets()) > $this->mBinSize) {
            $this->mBinSize = count($featureAreas[0]->getFeatureSets());
        }
    }

    protected function renderAreaRow(array $featureAreas)
    {
        $renderedFeatureAreas = "\n";
        foreach ($featureAreas as $featureArea) {
            $renderedFeatureAreas .= $this->renderFeatureArea($featureArea);
        }

        return str_replace("%%FEATURE_AREAS%%", $renderedFeatureAreas, Templates::FEATURE_AREA_ROW_TEMPLATE);
    }

    public function renderFeatureArea(FeatureArea $featureArea)
    {
        $featureOwners = "\n";
        $featureSets = "\n";
        foreach ($featureArea->getFeatureSets() as $featureSet) {
            $featureSets .= $this->renderFeatureSet($featureSet);
            $featureOwners .= $this->renderFeatureOwner($featureSet);
        }

        $renderedFeatureArea = str_replace("%%WIDTH%%", self::FS_WIDTH * count($featureArea->getFeatureSets()), Templates::FEATURE_AREA_TEMPLATE);
        $renderedFeatureArea = str_replace("%%TITLE%%", sanitize_xss($featureArea->getName()), $renderedFeatureArea);
        $renderedFeatureArea = str_replace("%%FEATURE_SET_OWNERS%%", $featureOwners, $renderedFeatureArea);
        $renderedFeatureArea = str_replace("%%FEATURE_SETS%%", $featureSets, $renderedFeatureArea);

        return $renderedFeatureArea;
    }

    public function renderFeatureSet(FeatureSet $featureSet, $paddingLeft = 0, $paddingRight = 0)
    {
        $fs = str_replace("%%PADDING_LEFT%%", $paddingLeft, Templates::FEATURE_SET_TEMPLATE);
        $fs = str_replace("%%PADDING_RIGHT%%", $paddingRight, $fs);
        $fs = str_replace("%%TITLE%%", sanitize_xss($featureSet->getName()), $fs);
        $fs = str_replace("%%NUM_FEATURES%%", sanitize_xss($featureSet->getNumberOfFeatures()), $fs);
        $fs = str_replace("%%PERCENT_COMPLETE%%", sanitize_xss($featureSet->getPercentCompleted()), $fs);
        $fs = str_replace("%%DUE_DATE%%", sanitize_xss($featureSet->getDueDate()), $fs);

        $backgroundStyle = $this->determineFeatureSetBackgroundStyle($featureSet);
        $fs = str_replace("%%BG_STYLE%%", $backgroundStyle, $fs);

        return $fs;
    }

    protected function determineFeatureSetBackgroundStyle($featureSet)
    {
        $now = new DateTime();

        if ($featureSet->isCompleted()) {
            return "completed";
        }

        if ($now > $featureSet->getRawDueDate()) {
            return "warning";
        }

        if ($featureSet->isInProgress()) {
            return "in_progress";
        }

        return "not_started";
    }

    public function renderFeatureOwner(FeatureSet $featureSet, $paddingLeft = 0, $paddingRight = 0)
    {
        $owner = str_replace("%%PADDING_LEFT%%", $paddingLeft, Templates::FEATURE_SET_OWNER_TEMPLATE);
        $owner = str_replace("%%PADDING_RIGHT%%", $paddingRight, $owner);
        $owner = str_replace("%%OWNER%%", sanitize_xss($featureSet->getOwner()), $owner);

        return $owner;
    }

    protected function packRows($featureAreas)
    {
        $bins = array();

        foreach($featureAreas as $area)
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

    protected function sumBin(array $bin)
    {
        $sum = 0;
        foreach ($bin as $featureArea)
        {
            $sum += $featureArea->getNumberOfFeatureSets();
        }

        return $sum;
    }
}

?>
