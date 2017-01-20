<?php

namespace ParkingLot\Features;

class FeatureArea
{
    protected $mName;
    protected $mFeatureSets;

    public function __construct($inName, array $inFeatureSets)
    {
        $this->mName = $inName;
        $this->mFeatureSets = $inFeatureSets;
    }

    public function getName()
    {
        return $this->mName;
    }

    public function getFeatureSets()
    {
        return $this->mFeatureSets;
    }

    public function getNumberOfFeatureSets()
    {
        return count($this->mFeatureSets);
    }
}
?>
