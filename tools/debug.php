<?php

/**
 * Copyright © 2017 FinalBit Solution. All rights reserved.
 * http://www.finalbit.ch
 * See LICENSE.txt for license details.
 */

if (defined('FB_DEBUG') && FB_DEBUG && is_readable(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
    Symfony\Component\Debug\Debug::enable();
}
