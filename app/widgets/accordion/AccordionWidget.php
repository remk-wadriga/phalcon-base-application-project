<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 05-08-2015
 * Time: 14:16 PM
 */

namespace widgets\accordion;

use abstracts\WidgetAbstract;

class AccordionWidget extends WidgetAbstract
{
    const TYPE_MAIN = 0;

    public $modelClass;
    public $methodName;

    protected $_items = [];

    public function init()
    {
        $class = $this->modelClass;
        $method = $this->methodName;

        if(!class_exists($class) || !method_exists($class, $method)){
            return null;
        }

        $this->createItems($class::$method());

        echo $this->render('template.php', [
            'content' => $this->renderItems()
        ]);
    }

    protected function createItems(array $items = null)
    {
        $this->_items = $items;
    }

    protected function renderItems(array $items = null)
    {
        if($items === null){
            $items = $this->_items;
        }
        $string = '';

        foreach($items as $item){
            $params = ['item' => $item];
            if(!empty($item['children'])){
                $params['children'] = $this->renderItems($item['children']);
            }
            $string .= $this->render($params);
        }

        return $string;
    }
}