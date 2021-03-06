<?php

namespace ParkingLot\Features;

use \DateTime as DateTime;

use ParkingLot\Exceptions\InvalidFeatureException as InvalidFeatureException;

class Feature
{
    protected $mName;
    protected $mDueDate;
    protected $mIsCompleted;
    protected $mIsInProgress;


    public function __construct($inName, DateTime $inDueDate, $inIsCompleted = false, $inIsInProgress = false)
    {
        if (empty($inName))
        {
            throw new InvalidFeatureException("A Feature may not have an empty (blank) name");
        }

        $this->mName = $inName;
        $this->mDueDate = $inDueDate;
        $this->mIsCompleted = $inIsCompleted;
        $this->mIsInProgress = $inIsInProgress;
    }

    public function getName()
    {
        return $this->mName;
    }

    public function getDueDate()
    {
        return $this->mDueDate;
    }

    public function isCompleted()
    {
        return $this->mIsCompleted;
    }

    public function isInProgress()
    {
        return $this->mIsInProgress;
    }
}
?>
