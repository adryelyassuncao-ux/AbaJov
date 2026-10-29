<?php

spl_autoload_register(static function (string $class): void {
    $baseDir = __DIR__ . '/../';

    $names = array_unique([
        $class,
        strtolower($class),
        ucfirst(strtolower($class)),
        $class . 's',
        strtolower($class) . 's',
        ucfirst(strtolower($class)) . 's',
        rtrim($class, 's'),
        strtolower(rtrim($class, 's')),
        ucfirst(strtolower(rtrim($class, 's'))),
    ]);

    $paths = [
        'models/',
        'controlers/',
        '',
    ];

    foreach ($paths as $path) {
        foreach ($names as $name) {
            $file = $baseDir . $path . $name . '.php';
            if (is_file($file)) {
                require_once $file;
                return;
            }
        }
    }
});
