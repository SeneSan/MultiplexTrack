<?php


namespace Models;


class Movie
{
    private $title;
    private $year;
    private $type;
    private $timeDuration;
    private $category;
    private $poster;
    private $description;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTimeDuration()
    {
        return $this->timeDuration;
    }

    /**
     * @param mixed $timeDuration
     */
    public function setTimeDuration($timeDuration)
    {
        $this->timeDuration = $timeDuration;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param mixed $poster
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public static function getMovies() {

        $pdo = Database::getConnection();
        $sql = "SELECT * FROM movies";

        try {
            $query = $pdo->prepare($sql);
            $query->execute();
            $results = $query->fetchAll();

            $view = include __ROOT__ . 'app/frontend/Movies/movies-list.phtml';

            return [
                'message' => 'Success',
                'html' => $view
            ];



        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

    }
}