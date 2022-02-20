<?php
namespace Rails\Cache\Store;

class APCStore extends AbstractStore
{
    private $pfx;


    public function __construct(array $config)
    {
        $this->pfx = ($config[0] ?? 'App') . '/';

        if (!(function_exists('apcu_enabled') && apcu_enabled()))
            throw new \Exception("APCu not installed or not enabled.");
    }


    public function read($key, array $params = [])
    {
        $val = apcu_fetch($this->pfx . $key, $result);
        return $result ? $val : null;
    }


    public function write($key, $val, array $params)
    {
        $ttl = 0;
        if ($val === null) { return true; }

        if (isset($params['expires_in']))
            $ttl = strtotime('+' . $params['expires_in']) - time();

        return apcu_store($this->pfx . $key, $val, $ttl);
    }


    public function delete($key, array $params = [])
    {
        return apcu_delete($this->pfx . $key);
    }


    public function exists($key, array $params = [])
    {
        return apcu_exists($this->pfx . $key);
    }

}
