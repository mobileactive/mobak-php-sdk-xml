<?php

namespace mobak;

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

    /**
     * @param array $attributes
     *
     * @return MobakObject
     */
    public function send(array $attributes)
    {
        $attributes['sender'] = !isset($attributes['sender']) ? 'Info' : trim($attributes['sender']);
        $attributes['uid'] = !isset($attributes['uid']) ? sha1((new \DateTime())->format("YmdHis")) : trim($attributes['uid']);

        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        $xml .= "<request uid=\"{uid}\" sender=\"{sender}\">";
        $xml .= "<security><login value=\"{login}\"/><sign value=\"{signature}\" /></security>";
        $xml .= "<message><text>{message}</text><abonent phone=\"{phone}\"/></message></request>";

        $xml = preg_replace("/\{uid\}/i", $attributes["uid"], $xml);
        $xml = preg_replace("/\{login\}/i", $this->getUser()->getLogin(), $xml);
        $xml = preg_replace("/\{signature\}/i", strtolower(md5($attributes['uid'] . $this->getUser()->getPassword())), $xml);
        $xml = preg_replace("/\{sender\}/i", $attributes["sender"], $xml);
        $xml = preg_replace("/\{message\}/i", $attributes["message"], $xml);
        $xml = preg_replace("/\{phone\}/i", $attributes["phone"], $xml);

        $request = new MobakRequest($this->getUser(), 'POST', 'messages/xml', [
            'xml' => $xml
        ]);

        return $request->execute()->getMobakObject();
    }

}