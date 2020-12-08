<?php


namespace Controllers;


use Models\Movie;
use Models\Ticket;
use Models\TimeSlot;

class WatchTodayController
{
    public function displayMovieModal($movieId) {

        $movieDetails = Movie::getMovieDetails($movieId);
        $movieTimeSlots = TimeSlot::watchTodayMovie($movieId);

        $price = Ticket::getMoviePrice($movieDetails['type'], $movieTimeSlots['date']);

        $modal = include __ROOT__ . 'app/frontend/WatchToday/watch-today-modal.phtml';

        return $modal;
    }

    public function sellTickets() {
        $startDateTime = $_POST['start_date_time'];
        $nrSeats = $_POST['nr_seats'];
        $totalPrice = $_POST['total_price'];
        $userId = $_SESSION['user']->getUserId();
        $timeSlot = TimeSlot::getTimeSlotByDateTime($startDateTime);

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
    }
}