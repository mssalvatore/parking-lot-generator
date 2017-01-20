<?php

namespace ParkingLot\Features;

class FeatureSet
{
    protected $mName;
    protected $mFeatures;
    protected $mPercentCompleted;
    protected $mDueDate;
    protected $mOwner;

    public function __construct($inName, array $inFeatures, $inOwner)
    {
        $this->mName = $inName;
        $this->mFeatures = $inFeatures;
        $this->mOwner = $inOwner;

        $this->mPercentCompleted = $this->calculatePercentCompleted();
        $this->mDueDate = $this->calculateDueDate();
    }

    protected function calculatePercentCompleted()
    {
        $total = count($this->mFeatures);
        $completed = 0.0;
        foreach($this->mFeatures as $feature) {
            if ($feature->isCompleted()) {
                $completed++;
            }
        }

        return floor($completed/$total * 100);
    }

    protected function calculateDueDate()
    {
        return "Dec 2016";
    }

    public function getName()
    {
        return $this->mName;
    }

    public function getDueDate()
    {
        return $this->mDueDate;
    }

    public function getPercentCompleted()
    {
        return $this->mPercentCompleted;
    }

    public function getOwner()
    {
        return $this->mOwner;
    }

    public function getNumberOfFeatures()
    {
        return count($this->mFeatures);
    }
}
?>
