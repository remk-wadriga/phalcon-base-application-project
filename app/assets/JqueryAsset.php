<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 04-08-2015
 * Time: 15:28 PM
 */

namespace assets;

use abstracts\AssetAbstract;

class JqueryAsset extends AssetAbstract
{
    public $cssPath = '/jquery/css';
    public $jsPath = '/jquery/js';

    public $js = [
        'jquery',
    ];
}