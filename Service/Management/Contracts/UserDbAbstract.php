<?php

namespace Feedify\BaseBundle\Service\Management\Contracts;

use Doctrine\DBAL\DBALException;
use Feedify\BaseBundle\Entity\Management\Customer;
use Feedify\BaseBundle\Service\Management\Contracts\UserDbInterface;
use Symfony\Component\DependencyInjection\ContainerInterfaceas as Container;
use Symfony\Component\HttpFoundation\Request;

abstract class UserDbAbstract  implements UserDbInterface
{
    /**
     * @var UserDbInterface
     */
    protected $driver;

    /**
     * @var Customer
     */
    protected $user;

    /**
     * @var Customer
     */
    protected $dbHost;

    /**
     * @var Customer
     */
    protected $dbUser;

    /**
     * @var Customer
     */
    protected $dbPassword;
    
    /**
     * @var Customer
     */
    protected $dbTemplateName;
    
    /**
     * @var Customer
     */
    protected $entityManager;

    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected  $container;
    /**
     * @var  Doctrine\DBAL\Conenction
     */
    protected  $connection;

    protected  $doctrine;

    public function __construct($entityManager, $connection, $doctrine, $user)
    {
        $this->entityManager = $entityManager;
        $this->connection = $connection;
        $this->doctrine = $doctrine;
        $this->dbHost = $this->entityManager->getConnection()->getHost();
        $this->dbUser = $this->entityManager->getConnection()->getUsername();
        $this->dbPassword = $this->entityManager->getConnection()->getPassword();
        $this->dbTemplateName = $this->entityManager->getConnection()->getDatabase();
        $this->setUser($user);
    }

    /**
     * @param string $username
     * @return string
     */
    abstract public function getDbName();

    /**
     * @param integer $username
     * @return bool
     */
    public function create()
    {
        return $this->generateDb();
    }

    /**
     * @return bool|Statement
     * @throws DBALException
     */
    public function drop()
    {
    }

    /**
     * @param Customer $customer
     */
    public function change()
    {
        $connection = $this->connection;
        $params     = $this->connection->getParams();
        $db_name  = $this->getDbName();
        if ($db_name != $params['dbname']) {
            $params['dbname'] = $db_name;
            if ($connection->isConnected()) {
                $connection->close();
            }
            $connection->__construct(
                $params, $connection->getDriver(), $connection->getConfiguration(),
                $connection->getEventManager()
            );
            $this->doctrine->resetEntityManager($this->managerName);
            try {
                $connection->connect();
            } catch (Exception $e) {
                // log and handle exception
            }
        }
    }

    /**
     * @param string $username
     * @return mixed
     * @throws DBALException
     */
    public function checkCustomerDatabase()
    {
        return $this->customerEm
            ->getConnection()
            ->fetchColumn('SHOW DATABASES LIKE :username', ['username' => 'db_'.$this->user->getUsername()]);
    }

    public function getDbServer()
    {
        return $this->dbHost;
    }

    /**
     * @return string
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * @return string
     */
    public function getDbPassword()
    {
        return $this->dbPassword;
    }
    
    /**
     * @return string
     */
    public function getDbTemplateName()
    {
        return $this->dbTemplateName;
    }

    /**
     * @return bool
     */
    public function generateDb()
    {
        try {
            $sql = 'create database if not exists '.$this->getDbName().' CHARACTER SET UTF8;';
            $this->entityManager->getConnection()->exec($sql);
        } catch (\Exception $e) {
            return false;
        }
        $sql = 'mysqldump --no-data -h '.$this->getDbServer().' -u '.$this->getDbUser().'  -p'.$this
                ->getDbPassword().' '.$this->getDbTemplateName().' | mysql -h '.$this->getDbServer().' -u '.$this
                ->getDbUser().' -p'.$this->getDbPassword().' '.$this->getDbName().'';

        try {
            exec($sql);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * Set a user
     *
     * @param object $user
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     */
    protected function setUser($user)
    {
        if($user == null) {
            throw new \Exception('User not created');
        }
        $this->user = $user;
    }
}