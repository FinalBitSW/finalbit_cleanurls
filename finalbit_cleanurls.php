<?php

/**
 * Copyright © 2017 FinalBit Solution. All rights reserved.
 * http://www.finalbit.ch
 * See LICENSE.txt for license details.
 */

if (!defined('_PS_VERSION_')) {
    return;
}

// Set true to enable debugging
define('FB_DEBUG', false);

if (version_compare(phpversion(), '5.3.0', '>=')) { // Namespaces support is required
    include_once __DIR__.'/tools/debug.php';
}

class finalbit_CleanUrls extends Module
{
    public function __construct()
    {
        $this->name = 'finalbit_cleanurls';
        $this->tab = 'seo';
        $this->version = '1.0.3';
        $this->author = 'FinalBit';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99');
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('FinalBit Clean URLs for PS');
        $this->description = $this->l('This override-module allows you to remove URL ID\'s.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall "FinalBit Clean URLs" module?');
    }

    public function getContent()
    {
        $output = '<p class="info">'
            .$this->l('On some versions you could have to disable Cache, save, open your shop home page, than go back and enable it:').'<br><br>'
            .sprintf('%s -> %s -> %s', $this->l('Advanced Parameters'), $this->l('Performance'), $this->l('Clear Smarty cache')).'<br>'
            .sprintf('%s -> %s -> %s -> %s', $this->l('Preferences'), $this->l('SEO and URLs'), $this->l('Set user-friendly URL off'), $this->l('Save')).'<br>'
            .sprintf('%s -> %s -> %s -> %s', $this->l('Preferences'), $this->l('SEO and URLs'), $this->l('Set user-friendly URL on'), $this->l('Save')).'<br>'
            .'</p>';

        $sql = 'SELECT * FROM `'._DB_PREFIX_.'product_lang`
            WHERE `link_rewrite`
            IN (SELECT `link_rewrite` FROM `'._DB_PREFIX_.'product_lang`
            GROUP BY `link_rewrite`, `id_lang`
            HAVING count(`link_rewrite`) > 1)';
        if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
            $sql .= ' AND `id_shop` = '.(int) Shop::getContextShopID();
        }

        if ($res = Db::getInstance()->ExecuteS($sql)) {
            $err = $this->l('You need to fix duplicate URL entries:').'<br>';
            foreach ($res as $row) {
                $lang = $this->context->language->getLanguage($row['id_lang']);
                $err .= $row['name'].' ('.$row['id_product'].') - '.$row['link_rewrite'].'<br>';

                $shop = $this->context->shop->getShop($lang['id_shop']);
                $err .= $this->l('Language: ').$lang['name'].'<br>'.$this->l('Shop: ').$shop['name'].'<br><br>';
            }
            $output .= $this->displayWarning($err);
        } else {
            $output .= $this->displayConfirmation($this->l('Nice. You have no duplicate URL entry.'));
        }

        return '<div class="panel">'.$output.'</div>';
    }

    public function install()
    {
        // add link_rewrite as index to improve search
        foreach (array('category_lang', 'cms_category_lang', 'cms_lang', 'product_lang') as $tab) {
            if (!Db::getInstance()->ExecuteS('SHOW INDEX FROM `'._DB_PREFIX_.$tab.'` WHERE Key_name = \'link_rewrite\'')) {
                Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$tab.'` ADD INDEX ( `link_rewrite` )');
            }
        }

        if (!parent::install()) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }
}
