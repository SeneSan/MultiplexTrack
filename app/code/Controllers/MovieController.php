<?php


namespace Controllers;


use Models\Movie;

class MovieController
{
    public function getMovies() {

        $results = Movie::getMovies();
        include __ROOT__ . 'app/frontend/Movies/movies-list.phtml';

        if (!$results) {
            echo OOPS_MESSAGE;
        }
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
        if ($result) {
            echo json_encode($result);
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function getMovieByTitle($title, $timeSlot) {

        $movies = Movie::getMovieByTitle($title, $timeSlot);
        header('Content-type:application/json;charset=utf-8');

        if ($movies) {
            echo json_encode($movies);
        } else {
            echo OOPS_MESSAGE;
        }
    }
}