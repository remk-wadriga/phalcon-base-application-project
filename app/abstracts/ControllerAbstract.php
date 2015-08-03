<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 03-08-2015
 * Time: 12:27 PM
 */

namespace abstracts;

use Phalcon\Mvc\Controller;

abstract class ControllerAbstract extends Controller
{
    public function render($view = null, $params = [])
    {
        if(is_array($view)){
            $params = $view;
            $view = null;
        }

        foreach($params as $name => $value){
            $this->view->setVar($name, $value);
        }
    }
}