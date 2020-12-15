<?php


namespace Controllers;


use Models\Movie;

class MovieController extends Controller
{
    public function getMovies() {

        /** @var Movie $movieModel */
        $movieModel = $this->model('Movie');
        $data = $movieModel->getMovies();
        $layout = $this->view('Movies/movies-list', [$data]);

        if (!$data) {
            echo OOPS_MESSAGE;
        } else {
            echo $layout;
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

        /** @var Movie $movieModel */
        $movieModel = $this->model('Movie');
        $result = $movieModel->addMovie($movie);

        if ($result) {
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($result);
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function getMovieByTitle($title, $timeSlot) {

        /** @var Movie $movieModel */
        $movieModel = $this->model('Movie');
        $movies = $movieModel->getMovieByTitle($title, $timeSlot);

        if ($movies) {
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($movies);
        } else {
            echo OOPS_MESSAGE;
        }
    }
}