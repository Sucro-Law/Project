<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Organization extends Model
{
    protected $primaryKey = 'org_id';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'org_id',
        'org_name',
        'description',
        'status',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getMemberships()
    {
        return DB::select(
            "SELECT * FROM memberships WHERE org_id = ? ORDER BY created_at DESC",
            [$this->org_id]
        );
    }

    public function getMembers()
    {
        return DB::select(
            "SELECT u.* FROM users u
             INNER JOIN memberships m ON u.user_id = m.user_id
             WHERE m.org_id = ?
             ORDER BY u.full_name ASC",
            [$this->org_id]
        );
    }

    public function getActiveMemberships()
    {
        return DB::select(
            "SELECT * FROM memberships 
             WHERE org_id = ? AND status = 'Active'
             ORDER BY created_at DESC",
            [$this->org_id]
        );
    }

    public function getPendingMemberships()
    {
        return DB::select(
            "SELECT * FROM memberships 
             WHERE org_id = ? AND status = 'Pending'
             ORDER BY created_at DESC",
            [$this->org_id]
        );
    }

    public function getOfficers()
    {
        return DB::select(
            "SELECT m.*, u.full_name, u.email, u.school_id
             FROM memberships m
             INNER JOIN users u ON m.user_id = u.user_id
             WHERE m.org_id = ? 
             AND m.membership_role = 'Officer' 
             AND m.status = 'Active'
             ORDER BY u.full_name ASC",
            [$this->org_id]
        );
    }

    public function getMemberCountAttribute()
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) as count FROM memberships 
             WHERE org_id = ? AND status = 'Active'",
            [$this->org_id]
        );
        
        return $result ? $result->count : 0;
    }

    public static function getActiveOrganizations()
    {
        return DB::select(
            "SELECT * FROM organizations WHERE status = 'Active' ORDER BY org_name ASC"
        );
    }

    public static function active()
    {
        return new class {
            private $query = "SELECT * FROM organizations WHERE status = 'Active'";
            private $params = [];
            private $withCounts = [];
            private $orderBy = null;
            private $limitValue = null;
            
            public function withCount($relations)
            {
                if (is_string($relations)) {
                    $relations = [$relations];
                }
                $this->withCounts = array_merge($this->withCounts, $relations);
                return $this;
            }
            
            public function latest($column = 'created_at')
            {
                $this->orderBy = "ORDER BY {$column} DESC";
                return $this;
            }
            
            public function limit($value)
            {
                $this->limitValue = "LIMIT {$value}";
                return $this;
            }
            
            public function get()
            {
                $sql = $this->query;
                
                if ($this->orderBy) {
                    $sql .= " " . $this->orderBy;
                }
                
                if ($this->limitValue) {
                    $sql .= " " . $this->limitValue;
                }
                
                $results = DB::select($sql, $this->params);
                
                if (!empty($this->withCounts) && !empty($results)) {
                    foreach ($results as $result) {
                        foreach ($this->withCounts as $relation) {
                            if ($relation === 'activeMemberships as member_count') {
                                $countResult = DB::selectOne(
                                    "SELECT COUNT(*) as count FROM memberships 
                                     WHERE org_id = ? AND status = 'Active'",
                                    [$result->org_id]
                                );
                                $result->member_count = $countResult ? $countResult->count : 0;
                            }
                        }
                    }
                }
                
                return $results;
            }
        };
    }

    public static function findById($orgId)
    {
        return DB::selectOne(
            "SELECT * FROM organizations WHERE org_id = ? LIMIT 1",
            [$orgId]
        );
    }

    public static function findByName($orgName)
    {
        return DB::selectOne(
            "SELECT * FROM organizations WHERE org_name = ? LIMIT 1",
            [$orgName]
        );
    }

    public static function createOrganization($data)
    {
        DB::insert(
            "INSERT INTO organizations (org_name, description, status, created_at, updated_at) 
             VALUES (?, ?, ?, NOW(), NOW())",
            [
                $data['org_name'],
                $data['description'] ?? null,
                $data['status'] ?? 'Active'
            ]
        );
        
        return DB::selectOne(
            "SELECT * FROM organizations WHERE org_name = ? ORDER BY created_at DESC LIMIT 1",
            [$data['org_name']]
        );
    }
    
    public static function updateOrganization($orgId, $data)
    {
        $fields = [];
        $values = [];
        
        if (isset($data['org_name'])) {
            $fields[] = 'org_name = ?';
            $values[] = $data['org_name'];
        }
        
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $values[] = $data['description'];
        }
        
        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $values[] = $data['status'];
        }
        
        $fields[] = 'updated_at = NOW()';
        $values[] = $orgId;
        
        if (!empty($fields)) {
            DB::update(
                "UPDATE organizations SET " . implode(', ', $fields) . " WHERE org_id = ?",
                $values
            );
        }
        
        return self::findById($orgId);
    }

    public static function deleteOrganization($orgId)
    {
        return DB::delete(
            "DELETE FROM organizations WHERE org_id = ?",
            [$orgId]
        );
    }

    public function getShortNameAttribute()
    {
        preg_match_all('/\b([A-Z])/u', $this->org_name, $matches);
        $acronym = implode('', $matches[1]);
        
        return !empty($acronym) && strlen($acronym) >= 2 
            ? $acronym 
            : strtoupper(substr($this->org_name, 0, 3));
    }

    public function getYearAttribute()
    {
        if ($this->created_at) {
            return is_string($this->created_at) 
                ? date('Y', strtotime($this->created_at))
                : $this->created_at->format('Y');
        }
        return null;
    }

    public function getMembershipStats()
    {
        return DB::selectOne(
            "SELECT 
                COUNT(*) as total_members,
                SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_members,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_members,
                SUM(CASE WHEN status = 'Inactive' THEN 1 ELSE 0 END) as inactive_members,
                SUM(CASE WHEN membership_role = 'Officer' AND status = 'Active' THEN 1 ELSE 0 END) as officers,
                SUM(CASE WHEN membership_role = 'Member' AND status = 'Active' THEN 1 ELSE 0 END) as regular_members
             FROM memberships 
             WHERE org_id = ?",
            [$this->org_id]
        );
    }
}