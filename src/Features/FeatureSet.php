<?php

namespace ParkingLot\Features;

class FeatureSet
{
    protected $mName;
    protected $mFeatures;
    protected $mPercentCompleted;
    protected $mDueDate;
    protected $mOwner;
    protected $mIsInProgress;

    public function __construct($inName, array $inFeatures, $inOwner)
    {
        $this->mName = $inName;
        $this->mFeatures = $inFeatures;
        $this->mOwner = $inOwner;

        $this->mPercentCompleted = $this->calculatePercentCompleted();
        $this->mDueDate = $this->calculateDueDate();
        $this->mIsInProgress = $this->determineIfInProgress();
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

    protected function determineIfInProgress()
    {
        foreach ($this->mFeatures as $feature) {
            if ($feature->isInProgress()) {
                return true;
            }
        }

        return $this->getPercentCompleted() > 0 && $this->getPercentCompleted() < 100;
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

    public function isCompleted()
    {
        return $this->getPercentCompleted() == 100;
    }

    public function isInProgress()
    {
        return $this->mIsInProgress;
    }
}
?>
