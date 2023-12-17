<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'icon',
        'company_id'
    ];

    public function company()
    {
        // One to Many
        return $this->belongsTo(Company::class);
    }

    public function employees()
    {
        // One to Many
        return $this->hasMany(Employee::class);
    }
}
