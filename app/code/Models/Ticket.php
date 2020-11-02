<?php


namespace Models;


class Ticket
{
    private $movieName;
    private $screeNumber;
    private $seatNumber;
    private $startHour;
    private $price;

    /**
     * @return mixed
     */
    public function getMovieName()
    {
        return $this->movieName;
    }

    /**
     * @param mixed $movieName
     */
    public function setMovieName($movieName)
    {
        $this->movieName = $movieName;
    }

    /**
     * @return mixed
     */
    public function getScreeNumber()
    {
        return $this->screeNumber;
    }

    /**
     * @param mixed $screeNumber
     */
    public function setScreeNumber($screeNumber)
    {
        $this->screeNumber = $screeNumber;
    }

    /**
     * @return mixed
     */
    public function getSeatNumber()
    {
        return $this->seatNumber;
    }

    /**
     * @param mixed $seatNumber
     */
    public function setSeatNumber($seatNumber)
    {
        $this->seatNumber = $seatNumber;
    }

    /**
     * @return mixed
     */
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * @param mixed $startHour
     */
    public function setStartHour($startHour)
    {
        $this->startHour = $startHour;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }
}