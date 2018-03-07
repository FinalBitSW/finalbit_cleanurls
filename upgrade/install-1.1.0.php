<?php

/**
 * Copyright © 2017 FinalBit Solution. All rights reserved.
 * http://www.finalbit.ch
 * See LICENSE.txt for license details.
 */


if (!defined('_PS_VERSION_')) {
    return;
}

function upgrade_module_1_1_0($module)
{
    $old_module = 'zzcleanurls';

    if (Module::isInstalled($old_module)) {
        Module::disableByName($this->name);

        die(Tools::displayError('You must first un-install module "ZiZuu Clean URLs"'));
    }

    Db::getInstance()->delete('module', "`name` = '$old_module'", 1);
    Db::getInstance()->delete('module_preference', "`module` = '$old_module'");
    Db::getInstance()->delete('configuration', "`name` LIKE '$old_module%'");
    Db::getInstance()->delete('quick_access', "`link` LIKE '%module_name=$old_module%'");

    return true;
}
