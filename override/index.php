<?php

/**
 * Copyright © 2017 FinalBit Solution. All rights reserved.
 * http://www.finalbit.ch
 * See LICENSE.txt for license details.
 */


header('Expires: Fri, 31 Dec 1999 23:59:59 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

header('Location: ../');

return;
