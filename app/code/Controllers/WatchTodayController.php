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
            $nrSeats = $_POST['nr_seats'];
            $totalPrice = $_POST['total_price'];
            $userId = $_SESSION['user']->getUserId();
            $timeSlot = TimeSlot::getTimeSlotByDateTime($startDateTime);

            if (isset($timeSlot['id'])) {

                $response = Ticket::sellTickets($timeSlot['id'], $nrSeats, $totalPrice, $userId);

                if ($response['error'] == false) {

                    $newTicket = $response['new-ticket'];
                    $movie = Movie::getMovieDetails($timeSlot['movie_id']);

                    Ticket::generatePDF($newTicket['id'], $timeSlot['screen_id'], $startDateTime, $movie, $nrSeats, $totalPrice);

                    echo "<div>The following invoice was generated <a href=\"invoices/invoice_{$newTicket['id']}.pdf\">invoice_{$newTicket['id']}.pdf</div>";

                } else {
                    header('Content-type:application/json;charset=utf-8');
                    echo json_encode($response);
                }
            } else {
                echo OOPS_MESSAGE;
            }
        }
    }
}