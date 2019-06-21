<?php

namespace App\Rules;

use Doctrine\ORM\EntityManager;

class ExistsRule
{
    /**
     * @var EntityManager
     */
    protected $entity;

    /**
     * ExistsRule constructor.
     */
    public function __construct(EntityManager $entity)
    {
        $this->entity = $entity;
    }

    public function validate($field, $value, $params, $fields)
    {
        $result = $this->entity->getRepository($params[0])
            ->findOneBy([
                $field => $value,
            ]);

        return $result === null;
    }
}
