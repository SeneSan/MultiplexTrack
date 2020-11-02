<?php


namespace Models;


class Screen
{
    private $screenNumber;
    private $numberOfSeats;

    /**
     * @return mixed
     */
    public function getScreenNumber()
    {
        return $this->screenNumber;
    }

    /**
     * @param mixed $screenNumber
     */
    public function setScreenNumber($screenNumber)
    {
        $this->screenNumber = $screenNumber;
    }

    /**
     * @return mixed
     */
    public function getNumberOfSeats()
    {
        return $this->numberOfSeats;
    }

    /**
     * @param mixed $numberOfSeats
     */
    public function setNumberOfSeats($numberOfSeats)
    {
        $this->numberOfSeats = $numberOfSeats;
    }
}