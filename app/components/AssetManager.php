<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 04-08-2015
 * Time: 14:50 PM
 */

namespace components;

use abstracts\ServiceAbstract;
use abstracts\AssetAbstract;

class AssetManager extends ServiceAbstract
{
    const MIMETYPE_STYLESHEET = 'stylesheet';
    const MIMETYPE_JAVASCRIPT = 'text/javascript';
    const MIMETYPE_FONOBJECT = 'application/vnd.ms-fontobject';
    const MIMETYPE_SVG_XML = 'image/svg+xml';
    const MIMETYPE_TTF = 'application/x-font-ttf';
    const MIMETYPE_WOOF = 'application/x-font-woff';
    const MIMETYPE_OCTET_STREAM = 'application/octet-stream';

    /**
     * @var AssetAbstract[]
     */
    public $collections = [];
    public $basePath;
    public $cssPath;
    public $jsPath;
    public $fontsPath;
    public $assetsNamespace;
    public $defaultMimeType = 'stylesheet';

    protected $cssPattern = '<link rel="%s" type="text/css" href="%s">';
    protected $jsPattern = '<script type="%s" src="%s"></script>';
    protected $fontPattern = '<link rel="%s" type="text/css" href="%s">';

    private $_displayedScc = [];
    private $_displayedJs = [];
    private $_displayedFonts = [];

    private static $_mimeTypes = [
        self::MIMETYPE_STYLESHEET => '.css',
        self::MIMETYPE_JAVASCRIPT => '.js',
        self::MIMETYPE_FONOBJECT => '.eot',
        self::MIMETYPE_SVG_XML => '.svg',
        self::MIMETYPE_TTF => '.ttf',
        self::MIMETYPE_WOOF => '.woff',
        self::MIMETYPE_OCTET_STREAM => '.woff2',
    ];

    public function init()
    {
        $this->addCollections($this->collections);
    }

    public function displayScc($collection = null)
    {
        $cssArray = [];
        if($collection !== null){
            $collection = $this->getCollection($collection);
            if($collection !== null){
                $cssArray = $collection->css;
            }
            foreach($this->collections as $collection){
                if($collection->priority = AssetAbstract::PRIORITY_MAIN){
                    $cssArray = array_merge($cssArray, $collection->css);
                }
            }
        }else{
            foreach($this->collections as $collection){
                $cssArray = array_merge($cssArray, $collection->css);
            }
        }

        foreach($cssArray as $css){
            if(!in_array($css, $this->_displayedScc)){
                $this->_displayedScc[] = $css;
                echo sprintf($this->cssPattern, $this->getMimeType($css), $css);
            }
        }
    }

    public function displayJs($collection = null)
    {
        $jsArray = [];
        if($collection !== null){
            $collection = $this->getCollection($collection);
            if($collection !== null){
                $jsArray = $collection->js;
            }
            foreach($this->collections as $collection){
                if($collection->priority = AssetAbstract::PRIORITY_MAIN){
                    $jsArray = array_merge($jsArray, $collection->js);
                }
            }
        }else{
            foreach($this->collections as $collection){
                $jsArray = array_merge($jsArray, $collection->js);
            }
        }

        foreach($jsArray as $js){
            if(!in_array($js, $this->_displayedJs)){
                $this->_displayedJs[] = $js;
                echo sprintf($this->jsPattern, $this->getMimeType($js), $js);
            }
        }
    }

    public function displayFonts($collection = null)
    {
        $fontsArray = [];
        if($collection !== null){
            $collection = $this->getCollection($collection);
            if($collection !== null){
                $fontsArray = $collection->fonts;
            }
            foreach($this->collections as $collection){
                if($collection->priority = AssetAbstract::PRIORITY_MAIN){
                    $fontsArray = array_merge($fontsArray, $collection->fonts);
                }
            }
        }else{
            foreach($this->collections as $collection){
                $fontsArray = array_merge($fontsArray, $collection->fonts);
            }
        }

        foreach($fontsArray as $font){
            if(!in_array($font, $this->_displayedFonts)){
                $this->_displayedFonts[] = $font;
                echo sprintf($this->fontPattern, $this->getMimeType($font), $font);
            }
        }
    }

    /**
     * getCollection
     * @param $collection
     * @return AssetAbstract|null
     */
    public function getCollection($collection)
    {
        return isset($this->collections[$collection]) ? $this->collections[$collection] : null;
    }

    /**
     * addCollections
     * @param array $collections
     * @param AssetAbstract $dependent
     */
    protected function addCollections(array $collections, $dependent = null)
    {
        foreach($collections as $index => $collection){
            $params = [];
            if(is_array($collection)){
                $params = $collection;
                $collection = $index;
            }
            $key = $collection;

            if(!isset($params['priority'])){
                $params['priority'] = AssetAbstract::PRIORITY_PAGE;
            }
            if(!isset($params['id'])){
                $params['id'] = $key;
            }

            $collectionClass = $this->assetsNamespace . '\\' . ucfirst($collection) . 'Asset';
            if(!class_exists($collectionClass)){
                continue;
            }

            $newCollection = new $collectionClass($params);

            $newCollection->css = $this->getStyles($newCollection);
            $newCollection->js = $this->getScripts($newCollection);
            $newCollection->fonts = $this->getFonts($newCollection);

            if(isset($newCollection->depending)){
                $depending = [];
                foreach($newCollection->depending as $dependingName){
                    if(isset($this->collections[$dependingName])){
                        $depending[$dependingName] = $this->collections[$dependingName];
                    }else{
                        $depending[] = $dependingName;
                    }
                }

                $this->addCollections(array_reverse($depending), $newCollection);
            }

            if($dependent !== null){
                $dependent->css = array_merge($newCollection->css, $dependent->css);
                $dependent->js = array_merge($newCollection->js, $dependent->js);
                $dependent->fonts = array_merge($newCollection->fonts, $dependent->fonts);
            }

            $this->collections[$key] = $newCollection;
        }
    }

    private function getStyles(AssetAbstract $collection)
    {
        $styles = [];
        $path = isset($collection->basePath) ? $collection->basePath : $this->basePath;

        if(!empty($collection->css)){
            $cssPath = isset($collection->cssPath) ? $collection->cssPath : $this->cssPath;
            $cssPath = str_replace(['//', '\\\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR], '/', $path . $cssPath . '/');
            foreach($collection->css as $css){
                $styles[] = $cssPath . $css . '.css';
            }
        }

        return $styles;
    }

    private function getScripts(AssetAbstract $collection)
    {
        $scripts = [];
        $path = isset($collection->basePath) ? $collection->basePath : $this->basePath;

        if(!empty($collection->js)){
            $jsPath = isset($collection->jsPath) ? $collection->jsPath : $this->jsPath;
            $jsPath = str_replace(['//', '\\\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR], '/', $path . $jsPath . '/');
            foreach($collection->js as $js){
                $scripts[] = $jsPath . $js . '.js';
            }
        }

        return $scripts;
    }

    private function getFonts(AssetAbstract $collection)
    {
        $fonts = [];
        $path = isset($collection->basePath) ? $collection->basePath : $this->basePath;

        if(!empty($collection->fonts)){
            $fontsPath = isset($collection->fontsPath) ? $collection->fontsPath : $this->fontsPath;
            $fontsPath = str_replace(['//', '\\\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR], '/', $path . $fontsPath . '/');
            foreach($collection->fonts as $font){
                $fonts[] = $fontsPath . $font;
            }
        }

        return $fonts;
    }

    private function getMimeType($link)
    {
        $mime = null;

        foreach(self::$_mimeTypes as $mimeType => $prefixes){
            if(!is_array($prefixes)){
                $prefixes = [$prefixes];
            }

            foreach($prefixes as $prefix){
                if(strpos($link, $prefix)){
                    $mime = $mimeType;
                    break;
                }
            }

            if($mime !== null){
                break;
            }
        }

        return $mime !== null ? $mime : $this->defaultMimeType;
    }
}