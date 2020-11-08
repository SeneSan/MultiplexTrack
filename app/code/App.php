<?php


class App
{
    const __ROOT__ = '/Users/macbook/PhpstormProjects/MultiplexTrack/';

    private static $head;
    private static $header;
    private static $body;
    private static $footer;
    private static $html = '';

    private static $userType;

    public static function run(){

        self::addHead();
        self::addHeader();
        self::addBody();
        self::addFooter();

        echo self::$html;
    }

    private static function addHead() {
        self::$head = self::getContent(App::__ROOT__ . 'app/frontend/head.phtml');
        self::$html .= self::$head;
    }

    private static function addHeader() {
        self::$header = self::getContent(App::__ROOT__ . 'app/frontend/header.phtml');
        self::$html .= self::$header;
    }

    private static function addBody() {
        self::$body = self::getContent(App::__ROOT__ . 'app/frontend/body.phtml');
        self::$html .= self::$body;
    }

    private static function addFooter() {
        self::$footer = self::getContent(App::__ROOT__ . 'app/frontend/footer.phtml');
        self::$html .= self::$footer;
    }

    private static function getContent($path) {
        ob_start();
        include $path;
        return ob_get_clean();
    }
}