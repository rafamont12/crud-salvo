<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** Mass assignable attributes.
     * @var string[]
     */
    protected $fillable = ['name', 'quantity'];


}
