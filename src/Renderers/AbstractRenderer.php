<?php

namespace ParkingLot\Renderers;

use \ParkingLot\Features\FeatureSet as FeatureSet;
use \ParkingLot\Features\FeatureArea as FeatureArea;

abstract class AbstractRenderer
{
    abstract public function renderParkingLot($projectName, array $featureAreas);
    abstract public function renderFeatureArea(FeatureArea $featureArea);
    abstract public function renderFeatureSet(FeatureSet $featureSet, $paddingLeft = 0, $paddingRight = 0);
    abstract public function renderFeatureOwner(FeatureSet $featureSet, $paddingLeft = 0, $paddingRight = 0);
}

?>
