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

    public $css = [
        'bootstrap',
        'bootstrap-responsive',
    ];

    public $js = [
        'bootstrap.min',
    ];

    public $depending = [
        'jquery',
    ];
}