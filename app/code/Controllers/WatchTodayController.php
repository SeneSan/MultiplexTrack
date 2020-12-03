<?php


namespace Controllers;


use Models\Movie;
use Models\TimeSlot;

class WatchTodayController
{
    public function displayMovieModal($movieId) {

        $movieDetails = Movie::getMovieDetails($movieId);
        $movieTimeSlots = TimeSlot::watchTodayMovie($movieId);

        $modal = include __ROOT__ . 'app/frontend/WatchToday/watch-today-modal.phtml';

        return $modal;
    }
}