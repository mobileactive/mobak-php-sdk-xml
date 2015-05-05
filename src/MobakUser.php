<?php

namespace mobak;
/**
 * Class MobakUser
 * @package Mobak
 */
class MobakUser
{
    private $_login = null;
    private $_password = null;

    public function __construct(array $configuration)
    {
        if (!isset($configuration['login'])) {
            throw new MobakConfigurationException("Property login does not exist.");
        }

        if (!isset($configuration['password'])) {
            throw new MobakConfigurationException("Property password does not exist");
        }

        $configuration['login'] = trim($configuration['login']);
        $configuration['password'] = trim($configuration['password']);

        if ($configuration['login'] === '') {
            throw new MobakConfigurationException("Property login cant be empty");
        }

        if ($configuration['password'] === '') {
            throw new MobakConfigurationException("Property password cant be empty");
        }

        $this->_login = $configuration['login'];
        $this->_password = $configuration['password'];
    }

    public function getLogin()
    {
        return $this->_login;
    }

    public function getPassword()
    {
        return $this->_password;
    }

}