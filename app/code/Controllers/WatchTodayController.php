<?php


namespace Controllers;


use Models\Logger;
use Models\Movie;
use Models\Ticket;
use Models\TimeSlot;

class WatchTodayController extends Controller
{
    public function displayMovieModal($movieId) {

        /** @var Movie $movieModel */
        $movieModel = $this->model('Movie');
        $movieDetails = $movieModel->getMovieDetails($movieId);

        /** @var TimeSlot $timeSlotModel */
        $timeSlotModel = $this->model('TimeSlot');
        $movieTimeSlots = $timeSlotModel->watchTodayMovie($movieId);

        if (isset($movieDetails) && isset($movieTimeSlots)) {
            /** @var Ticket $ticketModel */
            $ticketModel = $this->model('Ticket');
            $price = $ticketModel->getMoviePrice($movieDetails['type'], $movieTimeSlots['date']);
        }

        $data = [$movieDetails, $movieTimeSlots, $price];
        $modal = $this->view('WatchToday/watch-today-modal', $data);

        if ($modal !== '\n' and strlen($modal) > 1) {
            echo $modal;
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function sellTickets() {
        if (isset($_POST['start_date_time'])) {
            $movieId = $_POST['movie_id'];
            $screenId = $_POST['screen_id'];
            $startDateTime = $_POST['start_date_time'];

            $seats = $_POST['seats'];
            $seatsTrimmed = rtrim($seats, ', ');
            $seatsArray = explode(', ',  $seatsTrimmed);

            $singlePrice = $_POST['single_price'];
            $totalPrice = $_POST['total_price'];
            $userId = $_SESSION['user']->getUserId();

            /** @var TimeSlot $timeSlotModel */
            $timeSlotModel = $this->model('TimeSlot');
            $timeSlot = $timeSlotModel->getTimeSlotByDateTime($startDateTime, $screenId, $movieId);

            if (isset($timeSlot['id'])) {

                /** @var Ticket $ticketModel */
                $ticketModel = $this->model('Ticket');

                foreach ($seatsArray as $seat) {
                    $response = $ticketModel->sellTickets($timeSlot['id'], $seat, $singlePrice, $userId);
                }

                if ($response['error'] == false) {

                    $newTicket = $response['new-ticket'];

                    /** @var Movie $movieModel */
                    $movieModel = $this->model('Movie');
                    $movie = $movieModel->getMovieDetails($timeSlot['movie_id']);

                    $ticketModel->generatePDF($newTicket['id'], $timeSlot['screen_id'], $startDateTime, $movie, $seatsTrimmed, $totalPrice);

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

        $data = [$screenId];
        $layout = $this->view('WatchToday/select-seats', $data);

        /** @var Ticket $ticketModel */
        $ticketModel = $this->model('Ticket');
        $existingSeats = $ticketModel->getSeats($hour, $screenId);

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

    public function getFilteredMovies($hour, $screenId, $category) {
        if ($hour != 'none') {
            $formatHour = str_replace('-', ':' , $hour);
        } else {
            $formatHour = null;
        }

        if ($screenId != 'none') {
            $formatScreenID = $screenId;
        } else {
            $formatScreenID = null;
        }

        if ($category != 'none') {
            $formatCategory = $category;
        } else {
            $formatCategory = null;
        }

        /** @var Movie $movieModel */
        $movieModel = $this->model('Movie');
        $filteredMovies = $movieModel->getTodayMovies($formatHour, $formatScreenID, $formatCategory);

        if (gettype($filteredMovies) == 'array') {
            $data = [$filteredMovies];
            $response = [
                'error' => false,
                'movies_list_layout' => $this->view('WatchToday/watch-today-movies-list', $data)
            ];

            header('Content-type:application/json;charset=utf-8');
            echo json_encode($response);
        } else {
            header('Content-type:application/json;charset=utf-8');
            echo json_encode([
                'error' => true,
                'message' => $filteredMovies
            ]);
        }
    }
}