<?php

/**
 * Simple encryption key generator
 */
class CryptoKeygen
{

    public static function getKey(){
        return hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');
    }

}