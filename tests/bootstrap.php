<?php

require __DIR__ . '/../vendor/autoload.php';

// don't let Kirby register its error handlers in tests
Kirby\Cms\App::$enableWhoops = false;
