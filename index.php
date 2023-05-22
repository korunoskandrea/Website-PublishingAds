<?php
session_start();
const ROOT = __DIR__ . DIRECTORY_SEPARATOR; // apsolutne poti do direktorije da lahko samo autoload
const APP = ROOT . 'app' . DIRECTORY_SEPARATOR;
const CONTROLLER = APP . 'controller' . DIRECTORY_SEPARATOR;
const ROUTES = APP . 'routes' . DIRECTORY_SEPARATOR;
const DATA = APP . 'data' . DIRECTORY_SEPARATOR;
const CORE = APP . 'core' . DIRECTORY_SEPARATOR;
const MODEL = APP . 'model' . DIRECTORY_SEPARATOR;
const VIEW = APP . 'view' . DIRECTORY_SEPARATOR;
const SERVICE = APP.'service'.DIRECTORY_SEPARATOR;
const _PUBLIC = ROOT . 'app' . DIRECTORY_SEPARATOR;

$modules = [ROOT, APP, CORE, CONTROLLER, MODEL, DATA, SERVICE];
set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $modules));
spl_autoload_register('spl_autoload');

require_once(ROUTES.'web.php'); // "Nalozi mi route"