<?php


namespace Controllers;


use Models\Ticket;
use Models\TimeSlot;

class ReportsController
{
    public function getTicketsSold() {
        echo json_encode(Ticket::getMostNrOfTicketsSold());
    }

    public function getTotalAmounts() {
        echo json_encode(Ticket::getTotalGrossAmount());
    }

    public function getWeeksInTheater() {
        echo json_encode(TimeSlot::getWeeksInTheater());
    }
}