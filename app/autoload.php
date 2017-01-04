<?php
spl_autoload_register(function ($classname) {
    // project-specific namespace prefix
    $prefix = 'Bdn\\Socpost\\';

    // base directory for the namespace prefix
    $baseDir = __DIR__;

    // does the class use the namespace prefix?
    $len = strlen($prefix);

    if (strncmp($prefix, $classname, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }
    // get the relative class name
    $relativeClass = substr($classname, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $baseDir . DIRECTORY_SEPARATOR . str_replace('\\', '/', $relativeClass) . '.php';
   
    // if the file exists, require it

    if (file_exists($file)) {
        require $file;
    }
});