<?php
/**
 * Created by PhpStorm.
 * User: User40
 * Date: 31.01.2017
 * Time: 10:40
 */

namespace Feedify\BaseBundle\Service\Management;


use Feedify\BaseBundle\Service\Management\Contracts\UserDbAbstract;

class FoxmarketUserDb extends  UserDbAbstract
{
    protected $managerName = 'customer';
    /**
     * @param string $username
     * @return string
     */
    public function getDbName()
    {
        return 'db_'.$this->user->getUsername();
    }


}