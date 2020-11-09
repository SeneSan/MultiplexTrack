<?php


namespace Models;


class Movie
{
    private string $title;
    private int $year;
    private string $type;
    private int $duration;
    private string $categories;
    private string $poster;
    private string $description;

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
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
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

    public static function addMovie(Movie $movie) {
        $pdo = Database::getConnection();

        $sql = "INSERT INTO movies (title, year, type, duration, categories, poster, description) ";
        $sql .= "VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $query = $pdo->prepare($sql);
            $query->execute([$movie->title, $movie->year, $movie->type, $movie->duration, $movie->categories, $movie->poster, $movie->description]);
            if ($query) {
                return [
                    'error' => false,
                    'message' => 'Movie was added successfully!'
                ];
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function uploadPoster() {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["poster"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["poster"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                $result = [
                    'error' => 'true',
                    'message' => 'File is not an image!'
                ];
                echo json_encode($result);
                exit();
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
            $result = [
                'error' => 'true',
                'message' => 'File with the same name already exists!'
            ];
            echo json_encode($result);
            exit();
        }

        // Check file size
        if ($_FILES["poster"]["size"] > 2000000) {
            $uploadOk = 0;
            $result = [
                'error' => 'true',
                'message' => 'Sorry, file is too large!'
            ];
            echo json_encode($result);
            exit();
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $uploadOk = 0;
            $result = [
                'error' => 'true',
                'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'
            ];
            echo json_encode($result);
            exit();
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $result = [
                'error' => 'true',
                'message' => 'Sorry, your file was not uploaded for some reason.'
            ];
            echo json_encode($result);
            exit();
        // if everything is ok, try to upload file
        } else {
            move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file);
        }
    }
}