<?php


namespace Controllers;


use Models\Database;
use Models\Movie;

class MovieController
{
    public function getMovies() {

        Movie::getMovies();
    }

    public function addMovie() {

        $movie = new Movie();
        $movie->setTitle($_POST['title']);
        $movie->setYear($_POST['year']);
        $movie->setType($_POST['type']);
        $movie->setDuration($_POST['duration']);
        $movie->setCategories($_POST['categories']);
        $movie->setPoster($_FILES['poster']['name']);
        $movie->setDescription($_POST['description']);

        header('Content-type:application/json;charset=utf-8');
        $result = Movie::addMovie($movie);
        Movie::uploadPoster();

        echo json_encode($result);
    }
}