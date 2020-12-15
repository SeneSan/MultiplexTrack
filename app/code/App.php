<?php


class App
{
    private static $head;
    private static $header;
    private static $body;
    private static $footer;
    private static $html = '';

    public static function getMainPageStructure(){

        self::addHead();
        self::addHeader();
        self::addBody();
        self::addFooter();

        echo self::$html;
    }

    private static function addHead() {
        self::$head = self::getContent(__ROOT__ . 'app/frontend/head.phtml');
        self::$html .= self::$head;
    }

    private static function addHeader() {
        self::$header = self::getContent(__ROOT__ . 'app/frontend/header.phtml');
        self::$html .= self::$header;
    }

    private static function addBody() {
        self::$body = self::getContent(__ROOT__ . 'app/frontend/body.phtml');
        self::$html .= self::$body;
    }

    private static function addFooter() {
        self::$footer = self::getContent(__ROOT__ . 'app/frontend/footer.phtml');
        self::$html .= self::$footer;
    }

    private static function getContent($path) {
        ob_start();
        include $path;
        return ob_get_clean();
    }
}