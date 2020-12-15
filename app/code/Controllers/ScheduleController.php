<?php


namespace Controllers;


use Cassandra\Time;
use Models\TimeSlot;

class ScheduleController extends Controller
{
    public function getView() {
        $scheduleView = $this->view('Schedules/schedules');

        if ($scheduleView) {
            echo $scheduleView;
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function publish() {

        if (isset($_POST['movie_id']) && isset($_POST['time_slot'])) {

            $movieId = $_POST['movie_id'];
            $timeSlot = $_POST['time_slot'];

            /** @var TimeSlot $timeSlotModel */
            $timeSlotModel = $this->model('TimeSlot');
            $response = $timeSlotModel->publishMovie($movieId, $timeSlot);

            if ($response) {
                header('Content-type:application/json;charset=utf-8');
                echo json_encode($response);
            } else {
                echo OOPS_MESSAGE;
            }
        }
    }

    public function getCurrentTimeSlots() {
        /** @var TimeSlot $timeSlotModel */
        $timeSlotModel = $this->model('TimeSlot');
        $timeSlots = $timeSlotModel->getTimeSlots();
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

            /** @var TimeSlot $timeSlotModel */
            $timeSlotModel = $this->model('TimeSlot');
            $response = $timeSlotModel->removeTimeSlot($startDateTime);

            if ($response) {
                header('Content-type:application/json;charset=utf-8');
                echo json_encode($response);
            } else {
                echo OOPS_MESSAGE;
            }
        }
    }
}