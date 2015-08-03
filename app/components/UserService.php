<?php
/**
 * Created by Rem.
 * Author: Dmitry Kushneriv
 * Email: remkwadriga@yandex.ua
 * Date: 03-08-2015
 * Time: 16:50 PM
 */

namespace components;

use abstracts\ServiceAbstract;
use interfaces\UserIdentityInterface;
use Phalcon\Exception;
use Phalcon\Session\Adapter\Files AS SessionAdapter;

class UserService extends ServiceAbstract
{
    /**
     * @var \interfaces\UserIdentityInterface
     */
    private $_identity;

    /**
     * @var integer
     */
    private $_id;

    /**
     * @var SessionAdapter
     */
    public $_session;
    public $identityClass;
    public $sessionKey = 'USER_IDENTITY';

    public function login($userName, $password)
    {
        if(empty($userName) || empty($password)){
            throw new Exception('Useraname and password required');
        }

        $this->logout();
        $identityClass = $this->getIdentityClass();
        $identityObject = $identityClass::getByUserName($userName);
        if(!empty($identityObject) && $identityObject->validatePassword($password)){
            $this->getSession()->set($this->sessionKey, $identityObject->getID());
            $this->_identity = $identityObject;
            return true;
        }else{
            return false;
        }
    }

    public function logout()
    {
        $this->getSession()->remove($this->sessionKey);
    }

    public function setSession(SessionAdapter $adapter)
    {
        $this->_session = $adapter;
    }

    /**
     * getSession
     * @return SessionAdapter
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * getIdentity
     * @return UserIdentityInterface
     * @throws Exception
     */
    public function getIdentity()
    {
        if($this->_identity !== null){
            return $this->_identity;
        }

        $id = $this->getID();
        if($id > 0){
            $identityClass = $this->getIdentityClass();
            $identityObject = $identityClass::getByID($this->getID());
        }else{
            throw new Exception('User is not logged');
        }

        return $this->_identity = $identityObject;
    }

    /**
     * getID
     * @return int
     */
    public function getID()
    {
        if($this->_id !== null){
            return $this->_id;
        }

        return $this->_id = $this->getSession()->get($this->sessionKey, 0);
    }

    /**
     * getIdentityClass
     * @return UserIdentityInterface
     * @throws Exception
     */
    protected function getIdentityClass()
    {
        $identityClass = $this->identityClass;
        if(!class_exists($identityClass)){
            throw new Exception('Incorrect identity class');
        }

        return $identityClass;
    }
}