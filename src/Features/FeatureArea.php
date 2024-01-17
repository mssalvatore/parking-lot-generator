<?php

namespace ParkingLot\Features;

use ParkingLot\Exceptions\InvalidFeatureAreaException as InvalidFeatureAreaException;

class FeatureArea
{
    protected $mName;
    protected $mFeatureSets;

    public function __construct($name, array $featureSets)
    {
        if (empty($name))
        {
            throw new InvalidFeatureAreaException("A Feature Area may not have an empty (blank) name");
        }

        if (empty($featureSets))
        {
            throw new InvalidFeatureAreaException("A Feature Area must have at least one Feature Set");
        }

        $this->mName = $name;
        $this->mFeatureSets = $featureSets;
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
