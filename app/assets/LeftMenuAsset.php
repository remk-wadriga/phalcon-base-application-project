<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 05-08-2015
 * Time: 12:49 PM
 */

namespace assets;

use abstracts\AssetAbstract;

class LeftMenuAsset extends AssetAbstract
{
    public $cssPath = '/left-menu';
    public $jsPath = '/left-menu';

    public $css = [
        'left-menu'
    ];

    public $js = [
        'left-menu'
    ];

    public $depending = [
        'metisMenu',
    ];
}