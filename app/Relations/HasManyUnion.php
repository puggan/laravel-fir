<?php

/**
 * Created by PhpStorm.
 * User: puggan
 * Date: 2018-10-05
 * Time: 18:33
 */

namespace App\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @noinspection LongInheritanceChainInspection */

/**
 * Class HasManyUnion
 * @package App\Relations
 * @property string[] foreignKey
 */
class HasManyUnion extends HasMany
{
    /**
     * HasManyUnion constructor.
     *
     * @param Builder $query
     * @param Model $parent
     * @param string[] $foreignKey
     * @param string $localKey
     */
    public function __construct(Builder $query, Model $parent, array $foreignKey, string $localKey)
    {
        parent::__construct($query, $parent, $foreignKey, $localKey);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function addConstraints(): void
    {
        if (static::$constraints) {
            $keys = $this->foreignKey;
            $this->query->where(
                function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        /** @var Builder $query */
                        $query->orWhere($key, '=', $this->getParentKey());
                    }
                }
            );
        }
    }
}
