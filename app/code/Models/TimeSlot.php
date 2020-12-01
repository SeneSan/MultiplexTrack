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

    public static function deconstructDateTime($dateTime) {
        $day = date('N', strtotime($dateTime));
        $hour = ltrim(date('H:i', strtotime($dateTime)), '0');
        return 'day-' . $day . '-hour-' . $hour;
    }

    public static function publishMovie($movieId, $timeSlot) {
        $screenId = substr($timeSlot, 7, 1);
        $day = substr($timeSlot, 13, 1);
        $time = substr($timeSlot, 20);
        $dateTime = self::constructDateTime($day, $time);

        $pdo = Database::getConnection();
        $sql = 'INSERT INTO time_slots (screen_id, movie_id, start_date_time) VALUE (?, ? ,?)';

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$screenId, $movieId, $dateTime]);

            if ($stmt) {

                return [
                    'error' => false,
                    'message' => 'Schedules was published successfully!',
                    'start_date_time' => TimeSlot::getTimeSlot($dateTime)
                ];
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getTimeSlots() {
        $pdo = Database::getConnection();

        $sql = "select m.id, m.title, m.duration, t.screen_id, t.start_date_time from movies as m inner join time_slots as t on m.id = t.movie_id where t.start_date_time between ? and ?;";
        $start = TimeSlot::constructDateTime('1', '9:00');
        $end = TimeSlot::constructDateTime('7', '18:00');

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$start, $end]);

            $results = $stmt->fetchAll();

            if (count($results) !== 0) {
                $response = [];
                foreach ($results as $result) {
                    $response[] = [
                        'movie_id' => $result['id'],
                        'movie_title' => $result['title'],
                        'movie_duration' => $result['duration'],
                        'start_date_time' => $result['start_date_time'],
                        'time_slot' => 'screen-' . $result['screen_id'] . '-' . TimeSlot::deconstructDateTime($result['start_date_time'])
                    ];
                }

                return [
                    'error' => false,
                    'timeSlots' => $response
                ];

            } else {
                return [
                  'error' => false,
                  'message' => 'No time slots are available.'
                ];
            }

        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getTimeSlot($startDateTime) {
        $pdo = Database::getConnection();

        $sql = "SELECT * FROM time_slots WHERE start_date_time = ?";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$startDateTime]);
            $result = $stmt->fetch();

            if ($result) {
                return [
                    'error' => false,
                    'start_date_time' => $result['start_date_time']
                ];
            }

        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function removeTimeSlot($startDateTime) {
        $pdo = Database::getConnection();

        $sql = "DELETE FROM time_slots WHERE start_date_time = ?";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$startDateTime]);

            if ($stmt) {
                return [
                    'error' => false,
                    'message' => 'Time slot has removed successfully!'
                ];
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}