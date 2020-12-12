<?php


namespace Controllers;


use Models\Logger;
use Models\Movie;
use Models\Ticket;
use Models\TimeSlot;

class WatchTodayController
{
    public function displayMovieModal($movieId) {

        $movieDetails = Movie::getMovieDetails($movieId);
        $movieTimeSlots = TimeSlot::watchTodayMovie($movieId);

        if (isset($movieDetails) && isset($movieTimeSlots)) {
            $price = Ticket::getMoviePrice($movieDetails['type'], $movieTimeSlots['date']);
        }
        $modal = include __ROOT__ . 'app/frontend/WatchToday/watch-today-modal.phtml';

        if ($modal !== '\n' and strlen($modal) > 1) {
            echo OOPS_MESSAGE;
        }
    }

    public function sellTickets() {
        if (isset($_POST['start_date_time'])) {
            $startDateTime = $_POST['start_date_time'];

            $seats = $_POST['seats'];
            $seatsTrimmed = rtrim($seats, ', ');
            $seatsArray = explode(', ',  $seatsTrimmed);

            $singlePrice = $_POST['single_price'];
            $totalPrice = $_POST['total_price'];
            $userId = $_SESSION['user']->getUserId();
            $timeSlot = TimeSlot::getTimeSlotByDateTime($startDateTime);

            if (isset($timeSlot['id'])) {

                foreach ($seatsArray as $seat) {
                    $response = Ticket::sellTickets($timeSlot['id'], $seat, $singlePrice, $userId);
                }

                if ($response['error'] == false) {

                    $newTicket = $response['new-ticket'];
                    $movie = Movie::getMovieDetails($timeSlot['movie_id']);

                    Ticket::generatePDF($newTicket['id'], $timeSlot['screen_id'], $startDateTime, $movie, $seatsTrimmed, $totalPrice);

                    echo "<div>The following invoice was generated <a href=\"invoices/invoice_{$newTicket['id']}.pdf\">invoice_{$newTicket['id']}.pdf</div>";

                } elseif ($response['error'] === true) {
                    header('Content-type:application/json;charset=utf-8');
                    echo json_encode($response);
                }
            } else {
                echo OOPS_MESSAGE;
            }
        }
    }

    public function getSeats($hour, $screenId) {
        $hour = str_replace('-', ':', $hour);

        $layout = WatchTodayController::getSeatsLayout($screenId);
        $existingSeats = Ticket::getSeats($hour, $screenId);

        if ($existingSeats) {
            $response = [
                'error' => false,
                'existing_seats' => $existingSeats,
                'layout' => $layout
            ];
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($response);
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public static function getSeatsLayout($screenId) {
        ob_start();
        $screenID = $screenId;
        include __ROOT__ . 'app/frontend/WatchToday/select-seats.phtml';
        return ob_get_clean();
    }
}