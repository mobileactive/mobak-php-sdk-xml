<?php

namespace Mobak;
/**
 * Class Mobak
 * @package Mobak
 * @author Miсhael Grigorieff <grigorief@gmail.com>
 */
class Mobak
{
    protected $user;

    public function __construct(array $configuration)
    {
        $this->user = new MobakUser($configuration);
    }


    protected function getUser()
    {
        return $this->user;
    }



}