<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Model\UserAttributeTrait;
use App\Traits\Model\UserRelationshipTrait;
use App\Traits\Model\UserRolesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use UserAttributeTrait, UserRelationshipTrait, UserRolesTrait;
}
