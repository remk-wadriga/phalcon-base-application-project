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
    /**
     * @var AssetAbstract[]
     */
    public $collections = [];
    public $basePath;
    public $cssPath;
    public $jsPath;
    public $assetsNamespace;

    protected $cssPattern = '<link rel="stylesheet" type="text/css" href="%s">';
    protected $jsPattern = '<script type="text/javascript" src="%s"></script>';

    private $_displayedScc = [];
    private $_displayedJs = [];

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
                echo sprintf($this->cssPattern, $css);
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
                echo sprintf($this->jsPattern, $js);
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

            if(isset($newCollection->depending)){
                $depending = [];
                foreach($newCollection->depending as $dependingName){
                    if(isset($this->collections[$dependingName])){
                        $depending[$dependingName] = $this->collections[$dependingName];
                    }else{
                        $depending[] = $dependingName;
                    }
                }

                $this->addCollections($depending, $newCollection);
            }

            if($dependent !== null){
                $dependent->css = array_merge($newCollection->css, $dependent->css);
                $dependent->js = array_merge($newCollection->js, $dependent->js);
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
}