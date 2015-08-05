<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 05-08-2015
 * Time: 14:13 PM
 */

namespace abstracts;


abstract class WidgetAbstract
{
    public $basePath = APP_PATH . '/app/widgets';
    public $templatesView = '/views';
    public $defaultView = 'index.php';

    public function __construct($params = [])
    {
        foreach($params as $name => $value){
            if(property_exists($this, $name)){
                $this->$name = $value;
            }
        }
    }

    public static function run($params = [])
    {
        $class = get_called_class();
        $widget = new $class($params);
        call_user_func([$widget, 'init']);
    }

    abstract function init();

    /**
     * render
     * @param string $view
     * @param array $prams
     * @return string
     */
    public function render($view = null, $prams = [])
    {
        if(is_array($view)){
            $prams = $view;
            $view = null;
        }

        $view = $this->getViewFile($view);
        if(!file_exists($view)){
            return null;
        }

        ob_start();
            foreach($prams as $name => $value){
                $$name = $value;
            }
            include($view);

            $viewString = ob_get_contents();
        ob_end_clean();

        return $viewString;
    }

    public function getViewFile($view)
    {
        if($view === null){
            $view = $this->defaultView;
        }

        $className = get_class($this);
        $classParts = explode('\\', $className);

        array_shift($classParts);
        array_pop($classParts);
        $view = $this->basePath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $classParts) . $this->templatesView . DIRECTORY_SEPARATOR . $view;
        return str_replace(['/', '\\', '//', '\\\\'], DIRECTORY_SEPARATOR, $view);
    }
}