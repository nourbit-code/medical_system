<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','first_name','last_name','phone','email','date_of_birth','gender','address','emergency_contact'];
    protected $casts = ['date_of_birth'=>'date'];
    public function user() { return $this->belongsTo(User::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function getAgeAttribute() { return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null; }
}
