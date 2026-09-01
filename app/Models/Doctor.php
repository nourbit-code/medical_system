<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Doctor extends Model { use HasFactory; protected $fillable = ['user_id','first_name','last_name','specialization','phone','email','date_of_birth']; protected $casts = ['date_of_birth'=>'date']; public function user() { return $this->belongsTo(User::class); } public function appointments() { return $this->hasMany(Appointment::class); } public function availabilities() { return $this->hasMany(DoctorAvailability::class); } public function getAgeAttribute() { return $this->date_of_birth ? $this->date_of_birth->age : null; } }
