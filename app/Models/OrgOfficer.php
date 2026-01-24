<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgOfficer extends Model
{
    protected $table = 'org_officers';

    protected $primaryKey = 'officer_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'membership_id',
        'org_id',
        'position',
        'term_start',
        'term_end',
    ];

    protected $casts = [
        'term_start' => 'date',
        'term_end' => 'date',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'membership_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'org_id');
    }

    public function isActiveTerm()
    {
        $now = now();

        if ($this->term_start && $now->lt($this->term_start)) {
            return false;
        }

        if ($this->term_end && $now->gt($this->term_end)) {
            return false;
        }

        return true;
    }
}
