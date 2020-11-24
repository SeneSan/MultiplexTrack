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

        if ($_POST['schedules']) {
            $schedules = $_POST['schedules'];
//            var_dump(json_decode($schedules, true));
            $result = TimeSlot::publishSchedules(json_decode($schedules, true));

            header('Content-type: application/json');

            echo json_encode($result);
        }

    }
}