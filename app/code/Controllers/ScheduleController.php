<?php


namespace Controllers;


use Models\TimeSlot;

class ScheduleController
{
    public function getView() {
        $view = include __ROOT__ . 'app/frontend/Schedules/schedules.phtml';

        if ($view) {
            return $view;
        } else {
            return OOPS_MESSAGE;
        }
    }

    public function publish() {

        if (isset($_POST['movie_id']) && isset($_POST['time_slot'])) {

            $movieId = $_POST['movie_id'];
            $timeSlot = $_POST['time_slot'];

            $response = TimeSlot::publishMovie($movieId, $timeSlot);
            if ($response) {
                header('Content-type:application/json;charset=utf-8');
                echo json_encode($response);
            } else {
                echo OOPS_MESSAGE;
            }
        }
    }

    public function getCurrentTimeSlots() {
        $timeSlots = TimeSlot::getTimeSlots();
        if ($timeSlots) {
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($timeSlots);
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function removeTimeSlot() {
        if (isset($_POST['remove_date_time'])) {
            $startDateTime = $_POST['remove_date_time'];
            $response = TimeSlot::removeTimeSlot($startDateTime);
            if ($response) {
                header('Content-type:application/json;charset=utf-8');
                echo json_encode($response);
            } else {
                echo OOPS_MESSAGE;
            }
        }
    }
}