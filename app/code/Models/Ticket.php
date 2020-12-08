<?php


namespace Models;


use Dompdf\Dompdf;

class Ticket
{
    public static function getMoviePrice($movieType, $startDateTime) {
        $price = 0.00;

        switch ($movieType) {
            case '2D':
                $price = 3.00;
                break;
            case '3D':
                $price = 6.00;
                break;
            default:
                break;
        }

        $currentDay = date('l', strtotime($startDateTime));

        if ($currentDay === 'Friday') {
            return number_format($price / 2, 2);
        }
        return number_format($price, 2);
    }

    public static function isFridayDiscount($startDateTime) {
        $currentDay = date('l', strtotime($startDateTime));

        if ($currentDay === 'Friday') {
            return 'Yes';
        }
        return 'No';
    }

    public static function sellTickets($timeSlotId, $nrSeats, $totalPrice, $userId) {
        $pdo = Database::getConnection();

        $sql = "INSERT INTO tickets (time_slot_id, nr_seats, total_price, user_id) VALUE (? , ? , ?, ?)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$timeSlotId, $nrSeats, $totalPrice, $userId]);

            if ($stmt) {
                $lastTicketId = $pdo->lastInsertId();

                return [
                    'error' => false,
                    'new-ticket' => self::getTicketById($lastTicketId)['ticket']
                ];
            }

            return [
                'error' => true,
                'message' => 'Oops! Purchase was not processed.'
            ];
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    public static function getTicketById($ticketId) {
        $pdo = Database::getConnection();

        $sql = "SELECT * FROM tickets WHERE id = ?";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ticketId]);

            $result = $stmt->fetch();

            if ($result) {
                return [
                    'error' => false,
                    'ticket' => $result
                ];
            }
            return [
                'error' => true,
                'message' => "No ticket with id {$ticketId} was found."
            ];

        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    public static function generatePDF($ticketId, $screenID, $startDateTime, $movie, $nrSeats, $totalPrice) {
        $dompdf = new Dompdf();

        $startsAt = date('H:i l d-m-Y', strtotime($startDateTime));
        $movieTitle = $movie['title'];
        $movieType = $movie['type'];
        $fridayDiscount = self::isFridayDiscount($startDateTime);

        $invoiceTemplate = file_get_contents(__ROOT__ . 'app/frontend/Invoices/invoice-template.phtml');
        $invoice = sprintf($invoiceTemplate, $ticketId, $screenID, $startsAt, $movieTitle, $movieType, $nrSeats, $fridayDiscount, $totalPrice);

        $dompdf->loadHtml($invoice);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        file_put_contents(__ROOT__ . "invoices/invoice_{$ticketId}.pdf", $dompdf->output());
    }
}