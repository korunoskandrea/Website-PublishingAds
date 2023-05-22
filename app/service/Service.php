<?php
abstract class Service {

    protected function __construct() {}

    protected function __clone() {}

    abstract static function get();
}