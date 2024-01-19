<?php

namespace ParkingLot\Features;

use ParkingLot\Exceptions\InvalidFeatureSetException as InvalidFeatureSetException;

class FeatureSet
{
    protected $mName;
    protected $mFeatures;
    protected $mPercentCompleted;
    protected $mDueDate;
    protected $mRawDueDate;
    protected $mOwner;
    protected $mIsInProgress;

    public function __construct($name, array $features, $owner)
    {
        if (empty($name))
        {
            throw new InvalidFeatureSetException("A Feature Set may not have an empty (blank) name");
        }

        if (empty($features))
        {
            throw new InvalidFeatureSetException("A Feature Set must have at least one Feature");
        }

        $this->mName = $name;
        $this->mFeatures = $features;
        $this->mOwner = $owner;

        # Add some type checking
        $this->mPercentCompleted = $this->calculatePercentCompleted();
        $this->mRawDueDate = $this->computeDueDate();
        $this->mDueDate = $this->mRawDueDate->format('M') . " " . $this->mRawDueDate->format('Y');
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

    protected function computeDueDate()
    {
        $copiedFeatures = $this->mFeatures;
        usort($copiedFeatures, function($left, $right) {
            if ($left->getDueDate() == $right->getDueDate()) {
                return 0;
            }

            return $left->getDueDate() > $right->getDueDate() ? -1 : 1;
        });

        return $copiedFeatures[0]->getDueDate();
    }

    public function getName()
    {
        return $this->mName;
    }

    public function getRawDueDate()
    {
        return $this->mRawDueDate;
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

    public function getFeatures()
    {
        return $this->mFeatures;
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
