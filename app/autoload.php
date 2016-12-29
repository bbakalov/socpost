<?php
spl_autoload_register(function ($classname) {
    if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
        include_once __DIR__ . '/../app/controller/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
        include_once __DIR__ . '/../app/Model/' . $classname . '.php';
        return true;
    }
});