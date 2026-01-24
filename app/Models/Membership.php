<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $primaryKey = 'membership_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'org_id',
        'academic_year',
        'membership_role',
        'joined_at',
        'status',
    ];

    protected $casts = [
        'joined_at' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'org_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function officerDetails()
    {
        return $this->hasOne(OrgOfficer::class, 'membership_id', 'membership_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    public function scopeAlumni($query)
    {
        return $query->where('status', 'Alumni');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('membership_role', $role);
    }

    public function scopeOfficers($query)
    {
        return $query->where('membership_role', 'Officer')
            ->where('status', 'Active');
    }

    public function scopeMembers($query)
    {
        return $query->where('membership_role', 'Member')
            ->where('status', 'Active');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function isActive()
    {
        return $this->status === 'Active';
    }

    public function isPending()
    {
        return $this->status === 'Pending';
    }

    public function isRejected()
    {
        return $this->status === 'Rejected';
    }

    public function isAlumni()
    {
        return $this->status === 'Alumni';
    }

    public function isOfficer()
    {
        return $this->membership_role === 'Officer' && $this->status === 'Active';
    }
}
