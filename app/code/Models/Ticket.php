<?php


namespace Models;


use Dompdf\Dompdf;

class Ticket
{
    CONST LOG_FILE = 'ticket';

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

    public static function sellTickets($timeSlotId, $seat, $price, $userId) {
        $pdo = Database::getConnection();
        $formatPrice = number_format((int) $price, 2);

        $sql = "INSERT INTO tickets (time_slot_id, seat_nr, price, user_id) VALUE (? , ? , ?, ?)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$timeSlotId, $seat, $formatPrice, $userId]);

            if ($stmt) {
                $lastTicketId = $pdo->lastInsertId();

                return [
                    'error' => false,
                    'new-ticket' => self::getTicketById($lastTicketId)
                ];
            }

            return [
                'error' => true,
                'message' => 'Oops! Purchase was not processed.'
            ];
        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
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
                return $result;
            }
            return [
                'error' => true,
                'message' => "No ticket with id {$ticketId} was found."
            ];

        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }

    public static function generatePDF($ticketId, $screenID, $startDateTime, $movie, $seats, $totalPrice) {
        $dompdf = new Dompdf();

        $startsAt = date('H:i l d-m-Y', strtotime($startDateTime));
        $movieTitle = $movie['title'];
        $movieType = $movie['type'];
        $fridayDiscount = self::isFridayDiscount($startDateTime);

        $invoiceTemplate = file_get_contents(__ROOT__ . 'app/frontend/Invoices/invoice-template.phtml');
        $invoice = sprintf($invoiceTemplate, $ticketId, $screenID, $startsAt, $movieTitle, $movieType, $seats, $fridayDiscount, $totalPrice);

        $dompdf->loadHtml($invoice);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        file_put_contents(__ROOT__ . "invoices/invoice_{$ticketId}.pdf", $dompdf->output());
    }

    public static function getSeats($hour, $screenId) {
        $pdo = Database::getConnection();

        $sql = "SELECT t.seat_nr FROM tickets as t INNER JOIN time_slots as ts ON t.time_slot_id = ts.id WHERE ts.start_date_time = concat(curdate(), ?) and ts.screen_id = ?;";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([" $hour:00", $screenId]);

            $results = $stmt->fetchAll();

            if ($results) {
                return $results;
            }
            return 'No seats were found.';
        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }

    public static function getMostNrOfTicketsSold() {
        $pdo = Database::getConnection();

        $sql = 'select count(t.id) as nr_of_tickets_sold, m.title as movie_title from tickets as t inner join time_slots as ts on t.time_slot_id = ts.id inner join movies as m on ts.movie_id = m.id group by m.title, ts.movie_id order by nr_of_tickets_sold LIMIT 10;';

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll();

            if ($results) {
                $finalResult = [];
                foreach ($results as $result) {
                    $finalResult[] = [(int) $result['nr_of_tickets_sold'], $result['movie_title']];
                }
                return $finalResult;

            } else {
                return 'Something went wrong!';
            }

        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }

    public static function getTotalGrossAmount() {
        $pdo = Database::getConnection();

        $sql = 'select sum(t.price) as total_amount, m.title as movie_title from tickets as t
                    inner join time_slots as ts on t.time_slot_id = ts.id
                    inner join movies as m on ts.movie_id = m.id
                    group by m.title, ts.movie_id order by total_amount LIMIT 10;';

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll();

            if ($results) {
                $finalResult = [];
                foreach ($results as $result) {
                    $finalResult[] = [(int) $result['total_amount'], $result['movie_title']];
                }
                return $finalResult;
            } else {
                return 'Something went wrong!';
            }

        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }
    }
}