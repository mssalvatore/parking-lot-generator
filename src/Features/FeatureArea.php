<?php

namespace ParkingLot\Features;

use ParkingLot\Exceptions\InvalidFeatureAreaException as InvalidFeatureAreaException;

class FeatureArea
{
    protected $mName;
    protected $mFeatureSets;

    public function __construct($inName, array $inFeatureSets)
    {
        if (empty($inName))
        {
            throw new InvalidFeatureAreaException("A Feature Area may not have an empty (blank) name");
        }

        if (empty($inFeatureSets))
        {
            throw new InvalidFeatureAreaException("A Feature Area must have at least one Feature Set");
        }

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
