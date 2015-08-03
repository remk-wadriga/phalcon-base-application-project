<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 31-07-2015
 * Time: 13:57 PM
 */

namespace abstracts;

use Phalcon\Mvc\Model;

abstract class ModelAbstract extends Model
{
    /**
     * getTimeService
     * @return \components\TimeService
     */
    protected function timeService()
    {
        return $this->getService('timeService');
    }

    /**
     * getService
     * @param string $key
     * @return mixed|object
     */
    protected function getService($key)
    {
        return $this->getDI()->get($key);
    }
}