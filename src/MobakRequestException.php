<?php

namespace Mobak;

class MobakRequestException extends MobakBaseException {
    public function __construct($result, \SimpleXMLElement $decodeResult,$httpCode = 0) {

    }
}