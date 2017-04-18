<?php

namespace Feedify\BaseBundle\Service\Management\Contracts;

interface UserDbInterface
{

    public function getDbServer();

    public function getDbUser();

    public function getDbName();

    public function getDbPassword();

    public function getDbTemplateName();

    /**
     * @return bool
     */
    public function create();

    /**
     * @return bool|Statement
     * @throws DBALException
     */
    public function drop();

    /**
     * @return bool|Statement
     * @throws DBALException
     */
    public function change();
}