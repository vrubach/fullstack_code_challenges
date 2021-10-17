<?php
/**
 * Created by PhpStorm.
 * User: vrubach
 * Date: 17.10.21
 * Time: 13:23
 */

class CacheManager
{
    private $cache;

    public function setCache(string $cachingSystem)
    {

        switch ($cachingSystem){

            case "redis":
                $this->cache=new \Redis();
                break;
            case "memcache":
                $this->cache=new \Memcache();
                break;
            default:
                throw new \Exception("Cache Manager Not Found");

        }

    }

    public function connect(string $host, string $port){

        $this->cache->connect($host,$port);

    }

    public function set(string $key, string $value, string $is_compressed=false, string $ttl=0){

        if($this->cache instanceof \Memcache)
            $this->cache->set($key,$value,$is_compressed,$ttl);
        else if($this->cache instanceof \Redis)
            $this->cache->set($key,$value,$ttl);
    }

    public function get(string $key){

        return $this->cache->get($key);
    }

    public function lpush(string $key, string $value){

        if($this->cache instanceof \Memcache)
            throw new \Exception("method not supported");
        else if($this->cache instanceof \Redis)
            $this->cache->lPush($key,$value);

    }


}

$cm=new CacheManager();

$cm->setCache('redis');
$cm->connect('somehost','121');
$cm->set('one','1');
$cm->lpush('two','2');
echo $cm->get('one');

$cm->setCache('memcache');
$cm->connect('somehost','121');
$cm->set('one','1');
$cm->lpush('two','2'); // generates exception
echo $cm->get('one');


