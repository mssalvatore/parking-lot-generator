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


    public function __construct($name, DateTime $dueDate, $isCompleted = false, $isInProgress = false)
    {
        if (empty($name))
        {
            throw new InvalidFeatureException("A Feature may not have an empty (blank) name");
        }

        $this->mName = $name;
        $this->mDueDate = $dueDate;
        $this->mIsCompleted = $isCompleted;
        $this->mIsInProgress = $isInProgress;
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
