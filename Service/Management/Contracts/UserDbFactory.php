<?php

namespace Feedify\BaseBundle\Service\Management\Contracts;

use Feedify\BaseBundle\Service\Management\FoxmarketUserDb;
use Feedify\BaseBundle\Service\Management\InststoreUserDb;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;

class UserDbFactory
{
    static protected $customerEm;
    static protected $defaultEm;
    static protected $customerDbal;
    static protected $defaultDbal;
    static protected $container;
    static protected $doctrine;

    public function __construct($customerEm,$defaultEm,$customerDbal,$defaultDbal, $doctrine)
    {
        self::$customerEm = $customerEm;
        self::$defaultEm = $defaultEm;
        self::$customerDbal = $customerDbal;
        self::$defaultDbal = $defaultDbal;
        self::$doctrine = $doctrine;
    }

    public static function createUserDbManager($user)
    {
        $request = Request::createFromGlobals();
        $host = $request->getHost();
        if(strpos($host, 'inst.store') !== false ||  $host == '127.0.0.1') {
            $driver = new InststoreUserDb(self::$defaultEm, self::$defaultDbal, self::$doctrine, $user);
        } else {
            $driver = new FoxmarketUserDb(self::$customerEm, self::$customerDbal, self::$doctrine, $user);
        }
        if($driver == null) {
            throw new \Exception('Driver not created');
        }
        return $driver;
    }
}