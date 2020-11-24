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

    public static function constructDateTime($day, $time) {
        $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
        return date('Y-m-d H:i:s', strtotime("{$days[$day]} next week {$time}"));
    }

    public static function publishSchedules($schedules) {
        $pdo = Database::getConnection();

        $query = "INSERT INTO time_slots (screen_id, movie_id, start_date_time) VALUES ";

        $data = [];
        foreach ($schedules as $schedule) {
            $dateTime = self::constructDateTime($schedule['day'], $schedule['hour']);
            $data[] = array('screen_id' => $schedule['screen'], 'movie_id' => $schedule['movie_id'], 'start_date_time' => $dateTime);
        }

        $insert_values = array();
        foreach($data as $d){
            $question_marks[] = '('  . self::placeholders('?', sizeof($d)) . ')';
            $insert_values = array_merge($insert_values, array_values($d));
        }

        $sql = $query . implode(',', $question_marks);
        $stmt = $pdo->prepare ($sql);

        try {
            $stmt->execute($insert_values);

            return [
                'error' => false,
                'message' => 'Movies were scheduled successfully!'
            ];

        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    private static function placeholders($text, $count=0, $separator=","){
        $result = array();
        if($count > 0){
            for($x=0; $x<$count; $x++){
                $result[] = $text;
            }
        }

        return implode($separator, $result);
    }
}