<?php

spl_autoload_register(function ($class) {
include '../lib/imagehash/' . $class . '.php';
});

