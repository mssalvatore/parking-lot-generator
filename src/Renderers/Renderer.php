<?php

namespace ParkingLot\Renderers;

use \ParkingLot\Features\FeatureSet as FeatureSet;
use \ParkingLot\Features\FeatureArea as FeatureArea;

abstract class Renderer
{
    abstract public function renderParkingLot(array $inFeatureAreas);
    abstract public function renderFeatureArea(FeatureArea $inFeatureArea);
    abstract public function renderFeatureSet(FeatureSet $inFeatureSet, $inPaddingLeft = 0, $inPaddingRight = 0);
    abstract public function renderFeatureOwner(FeatureSet $inFeatureSet, $inPaddingLeft = 0, $inPaddingRight = 0);
}

?>
