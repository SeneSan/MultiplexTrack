<?php


namespace Models;


class TimeSlot
{
    CONST LOG_FILE = 'timeslot';

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
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
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
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
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
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
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
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }

    public static function watchTodayMovie($movieId) {
        $pdo = Database::getConnection();

        $sql = "SELECT t.screen_id, t.start_date_time FROM time_slots as t INNER JOIN movies as m ON m.id = t.movie_id WHERE ";
        $sql .= "t.movie_id = ? AND t.start_date_time LIKE concat(curdate(), '%')";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$movieId]);

            $response = [];
            while ($result = $stmt->fetch()) {

                if (!isset($response['date'])){
                    $response['date'] = date('Y-m-d', strtotime($result['start_date_time']));
                }

                switch ($result['screen_id']){
                    case 1:
                        $response['screens']['screen_1'][] = date('H:i', strtotime($result['start_date_time']));
                        break;
                    case 2:
                        $response['screens']['screen_2'][] = date('H:i', strtotime($result['start_date_time']));
                        break;
                    case 3:
                        $response['screens']['screen_3'][] = date('H:i', strtotime($result['start_date_time']));
                        break;
                    case 4:
                        $response['screens']['screen_4'][] = date('H:i', strtotime($result['start_date_time']));
                        break;
                    default:
                        break;
                }
            }
            return $response;

        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }

    public static function getTimeSlotByDateTime($startDateTime, $screenId, $movieId) {
        $pdo = Database::getConnection();

        $sql = "SELECT * FROM time_slots WHERE start_date_time = ? and screen_id = ? and movie_id = ?";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$startDateTime, $screenId, $movieId]);

            $result = $stmt->fetch();

            if ($result) {
                return $result;
            }
            return 'No time slot was found';

        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }

    public static function getTodayCategories() {
        $pdo = Database::getConnection();

        $sql = "SELECT m.categories FROM time_slots as ts INNER JOIN movies as m ON ts.movie_id = m.id WHERE ts.start_date_time LIKE concat(curdate(), '%')";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll();

            if ($results) {
                $finalCategories = [];
                foreach ($results as $categories) {
                    $arr = explode(', ', $categories['categories']);
                    $finalCategories = array_merge($arr, $finalCategories);
                }

                return array_unique($finalCategories);
            }
            return 'No categories were found';

        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }
}