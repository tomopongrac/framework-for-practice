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

    /**
     * Validation rule to check if in given table exist row with field name for given value.
     *
     * @param  string  $field
     * @param  string  $value
     * @param  array  $params
     * @param  array  $fields
     * @return bool
     */
    public function validate(string $field, string $value, array $params, array $fields): bool
    {
        $result = $this->entity->getRepository($params[0])
            ->findOneBy([
                $field => $value,
            ]);

        return $result === null;
    }
}
