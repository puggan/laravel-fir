<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * User constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = [
            'name',
            'email',
            'password',
        ];
        $this->hidden = [
            'password',
            'remember_token',
        ];
        parent::__construct($attributes);
    }
}
