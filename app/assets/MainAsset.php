<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 04-08-2015
 * Time: 14:54 PM
 */

namespace assets;

use abstracts\AssetAbstract;

class MainAsset extends AssetAbstract
{
    public function init()
    {
        parent::init();
        $this->priority = self::PRIORITY_MAIN;
    }

    public $js = [
        'main',
    ];

    public $css = [
        'main',
    ];

    public $depending = [
        'bootstrap',
    ];
}