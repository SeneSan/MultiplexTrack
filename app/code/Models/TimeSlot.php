<?php


namespace Models;


class TimeSlot
{
    private $screenId;
    private $movieId;
    private $startDateTime;

    /**
     * @return mixed
     */
    public function getScreenId()
    {
        return $this->screenId;
    }

    /**
     * @param mixed $screenId
     */
    public function setScreenId($screenId): void
    {
        $this->screenId = $screenId;
    }

    /**
     * @return mixed
     */
    public function getMovieId()
    {
        return $this->movieId;
    }

    /**
     * @param mixed $movieId
     */
    public function setMovieId($movieId): void
    {
        $this->movieId = $movieId;
    }

    /**
     * @return mixed
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * @param mixed $startDateTime
     */
    public function setStartDateTime($startDateTime): void
    {
        $this->startDateTime = $startDateTime;
    }


}