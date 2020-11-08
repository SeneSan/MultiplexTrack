<?php


namespace Controllers;


class ScheduleController
{
    public function getView() {
        $view = include __ROOT__ . 'app/frontend/Schedules/schedules.phtml';

        return [
          'message' => 'Success',
          'html' => $view
        ];
    }
}