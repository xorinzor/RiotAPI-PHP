<?php
/**
 * Slightly modified version of the class available at
 * http://www.script-tutorials.com/how-to-use-apc-caching-with-php/ (<- credits to them)
 */
class CacheAPC {

    const ttl = 600; //Default Time To Live

    // get data from memory
    public static function getData($key) {
        $result = false;
        $data = apc_fetch($key, $result);
        return ($result) ? $data : null;
    }

    // save data to memory
    public static function setData($key, $data, $customTtl = 0) {
        //We dont want NULL data in our memory
        if(is_null($data)) return false;

        return apc_store($key, $data, (($customTtl > 0) ? $customTtl : self::ttl));
    }

    // delete data from memory
    public static function delData($key) {
        return (apc_exists($key)) ? apc_delete($sKey) : true;
    }
}
