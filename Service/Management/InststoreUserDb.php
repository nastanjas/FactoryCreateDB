<?php

namespace Feedify\BaseBundle\Service\Management;

use Feedify\BaseBundle\Service\Management\Contracts\UserDbAbstract;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Request;

class InststoreUserDb extends UserDbAbstract
{
    protected $managerName = 'default';

    protected $devHost = [
        'dev1.inst.store',
        'dev2.inst.store',
        'dev3.inst.store',
    ];
    
    protected $devIP = [
        '127.0.0.1',
        'fe80::1',
        '::1',
        '113.0.0.1',
        '10.0.0.1'
    ];
    
    /**
     * @param string $username
     * @return string
     */
    public function getDbName()
    {
        $request = Request::createFromGlobals();
        $host = $request->getHost();
        $pref = '';
        $idStringType = $this->user->getId();
        while(strlen($idStringType) !== 5) {
            $idStringType = '0' . $idStringType;
        }
        if(
            in_array( $host, $this->devHost)
            || in_array(@$_SERVER['REMOTE_ADDR'], $this->devIP)
        ) {
            $pref = '_dev';
        }
        return 'syl_user_'.$idStringType.$pref;
    }
    
}