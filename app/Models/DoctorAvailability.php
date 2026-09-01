<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DoctorAvailability extends Model
{
    use HasFactory;
    protected $fillable = ['doctor_id', 'available_date', 'available_time', 'is_booked'];
    protected $casts = ['available_date' => 'date', 'is_booked' => 'boolean'];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'availability_id');
    }
}