<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 03-08-2015
 * Time: 16:09 PM
 */

namespace controllers;

use abstracts\ControllerAbstract;

class SigninController extends ControllerAbstract
{
    public function indexAction()
    {

    }

    public function loginAction()
    {
        $this->getUser()->login($this->post('email'), $this->post('password'));
        echo '<pre>'; print_r($this->getUser()->getIdentity()); exit('</pre>');
    }
}