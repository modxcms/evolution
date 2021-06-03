<?php

namespace Doctrine\DBAL\Driver\SQLSrv;

/**
 * Last Id Data Container.
 *
 * @internal
 */
class LastInsertId
{
    /** @var int */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
