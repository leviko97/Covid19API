<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    protected $casts = [
        'name' => 'object'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function statistics(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Statistic::class);
    }
}
