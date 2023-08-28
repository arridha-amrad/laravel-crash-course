<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
  use HasUlids;

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  protected $fillable = [
    'user_id',
    'phone_number',
    'city',
    'postal_code'
  ];
}
