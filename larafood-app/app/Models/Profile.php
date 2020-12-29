<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchNameDescriptionTrait;

class Profile extends Model
{
    use SearchNameDescriptionTrait;
    
    protected $fillable = ['name', 'description'];

    public function search($filter = null)
    {
        $results = $this->SearchNameDescription($filter);
        
        return $results;
    }

    /**
     * Get Permissions
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * permissions not linked with this profile
     */
    public function permissionsAvailable($filter = null)
    {
        $permissions = Permission::whereNotIn('permissions.id', function($query){
            $query->select('permission_profile.permission_id');
            $query->from('permission_profile');
            $query->whereRaw("permission_profile.profile_id={$this->id}");
        })
            ->where(function($queryFilter) use ($filter) {
               if($filter) {
                    $queryFilter->where('permissions.name', 'LIKE', "%{$filter}%");
               }
            })
            ->paginate();

        return $permissions;
    }

    /**
     * permissions linked with this profile
     */
    public function permissionsLinked($filter = null) 
    {
        $permissions = Permission::whereIn('permissions.id', function($query){
            $query->select('permission_profile.permission_id');
            $query->from('permission_profile');
            $query->whereRaw("permission_profile.profile_id={$this->id}");
        })
            ->where(function($queryFilter) use ($filter) {
               if($filter) {
                    $queryFilter->where('permissions.name', 'LIKE', "%{$filter}%");
               }
            })
            ->paginate();

        return $permissions;
    }
}
