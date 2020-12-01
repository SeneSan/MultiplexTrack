<?php


namespace Controllers;


use Models\TimeSlot;

class ScheduleController
{
    public function getView() {
        $view = include __ROOT__ . 'app/frontend/Schedules/schedules.phtml';

        return [
          'message' => 'Success',
          'html' => $view
        ];
    }

    public function publish() {

        if (isset($_POST['movie_id']) && isset($_POST['time_slot'])) {

            $movieId = $_POST['movie_id'];
            $timeSlot = $_POST['time_slot'];

            $response = TimeSlot::publishMovie($movieId, $timeSlot);
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($response);
        }
    }

    public function getCurrentTimeSlots() {
        header('Content-type:application/json;charset=utf-8');
        echo json_encode(TimeSlot::getTimeSlots());
    }

    public function removeTimeSlot() {
        if (isset($_POST['remove_date_time'])) {
            $startDateTime = $_POST['remove_date_time'];
            header('Content-type:application/json;charset=utf-8');
            echo json_encode(TimeSlot::removeTimeSlot($startDateTime));
        }
    }
}