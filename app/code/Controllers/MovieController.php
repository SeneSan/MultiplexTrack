<?php


namespace Controllers;


use Models\Movie;

class MovieController
{
    public function getMovies() {

        Movie::getMovies();
    }
}