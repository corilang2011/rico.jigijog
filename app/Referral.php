<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    // Table Name
    protected $table = 'referrals';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
    
    public function user(){
    	return $this->belongsTo(User::class);
    }
}
