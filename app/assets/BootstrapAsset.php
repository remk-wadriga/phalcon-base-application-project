<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 04-08-2015
 * Time: 15:25 PM
 */

namespace assets;

use abstracts\AssetAbstract;

class BootstrapAsset extends AssetAbstract
{
    public $cssPath = '/bootstrap/css';
    public $jsPath = '/bootstrap/js';
    public $fontsPath = '/bootstrap/fonts';

    public $css = [
        'bootstrap.min',
        'bootstrap-theme.min',
    ];

    public $js = [
        'bootstrap.min',
    ];

    public $fonts = [
        'glyphicons-halflings-regular.eot',
        'glyphicons-halflings-regular.svg',
        'glyphicons-halflings-regular.ttf',
        'glyphicons-halflings-regular.woff',
        'glyphicons-halflings-regular.woff2',
    ];

    public $depending = [
        'jquery',
    ];
}