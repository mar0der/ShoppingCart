<?php

namespace DH\Mvc\Session;

class DBSession extends \DH\Mvc\DB\SimpleDB implements \DH\Mvc\Session\ISession
{
    private $sessionName;
    private $tableName;
    private $lifetime;
    private $path;
    private $domain;
    private $secure;
    private $sessionId = null;
    private $sessionData = array();

    public function __construct($dbConnection, $name, $tableName = 'sessions', $lifetime = 3600, $path = null, $domain = null, $secure = false)
    {
        parent::__construct($dbConnection);
        $this->tableName = $tableName;
        $this->sessionName = $name;
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->sessionId = $_COOKIE[$name];

        if (rand(0, 50) == 1) {
            $this->_gc();
        }
        if (strlen($this->sessionId) < 32) {
            $this->_startNewSession();
        } else if (!$this->_validSession()) {
            $this->_startNewSession();
        }
    }

    private function _startNewSession()
    {
        $this->sessionId = md5(uniqid('DH', true));
        $this->prepare('INSERT INTO ' . $this->tableName . ' (sessid, valid_until) VALUES (?, ?)')
            ->execute(array($this->sessionId, (time() + $this->lifetime)));
        setcookie($this->sessionName, $this->sessionId, (time() + $this->lifetime), $this->path, $this->domain, $this->secure, true);
    }

    private function _validSession()
    {
        //fixed
        if ($this->sessionId) {
            $sessions = $this->prepare('SELECT * FROM ' . $this->tableName . ' WHERE sessid = ? AND valid_until >= ?')
                ->execute(array($this->sessionId, time()))
                ->fetchAllAssoc();

            if (is_array($sessions) && count($sessions) == 1 && $sessions[0]) {
                $this->sessionData = unserialize($sessions[0]['sess_data']);

                return true;
            }
        }

        return false;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function saveSession()
    {
        if ($this->sessionId) {
            $this->prepare('UPDATE ' . $this->tableName . ' SET sess_data = ?, valid_until = ? WHERE sessid = ?')
                ->execute(
                    array(
                        serialize($this->sessionData),
                        (time() + $this->lifetime),
                        $this->sessionId
                    )
                );

            setcookie($this->sessionName, $this->sessionId, (time() + $this->lifetime), $this->path, $this->domain, $this->secure, true);
        }
    }

    public function destroySession()
    {
        if ($this->sessionId) {
            $this->prepare('DELETE FROM ' . $this->tableName . ' WHERE sessid = ? ')
                ->execute(array($this->sessionId));
        }
    }

    public function __get($name)
    {
        return $this->sessionData[$name];
    }

    public function __set($name, $value)
    {
        $this->sessionData[$name] = $value;
    }

    private function _gc()
    {
        $this->prepare('DELETE FROM ' . $this->tableName . ' WHERE valid_until < ?')
            ->execute(array(time()));
    }

}