<?php

namespace ParkingLot\Features;

class Feature
{
    protected $mName;
    protected $mDueDate;
    protected $mIsCompleted;

    public function __construct($inName, $inDueDate, $inIsCompleted)
    {
        $this->mName = $inName;
        $this->mDueDate = $inDueDate;
        $this->mIsCompleted = $inIsCompleted;
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
}
?>
