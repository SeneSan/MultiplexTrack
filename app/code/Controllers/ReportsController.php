<?php


namespace Controllers;


use Models\Ticket;
use Models\TimeSlot;

class ReportsController extends Controller
{
    public function getTicketsSold() {
        /** @var Ticket $ticketModel */
        $ticketModel = $this->model('Ticket');
        echo json_encode($ticketModel->getMostNrOfTicketsSold());
    }

    public function getTotalAmounts() {
        /** @var Ticket $ticketModel */
        $ticketModel = $this->model('Ticket');
        echo json_encode($ticketModel->getTotalGrossAmount());
    }

    public function getWeeksInTheater() {
        /** @var TimeSlot $ticketModel */
        $ticketModel = $this->model('TimeSlot');
        echo json_encode($ticketModel->getWeeksInTheater());
    }
}