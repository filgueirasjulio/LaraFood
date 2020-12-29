<?php

namespace App\Models;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SearchNameDescriptionTrait;

class Permission extends Model
{
    use SearchNameDescriptionTrait;
    
    protected $fillable = ['name', 'description'];

    public function search($filter = null)
    {
        $results = $this->SearchNameDescription($filter);
        
        return $results;
    }

    /**
     * Get Profiles
     */
    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }

    /**
     * profiles linked with this permission
     */
    public function profilesLinked($filter = null) 
    {
        $permissions = Profile::whereIn('profiles.id', function($query){
            $query->select('permission_profile.profile_id');
            $query->from('permission_profile');
            $query->whereRaw("permission_profile.permission_id={$this->id}");
        })
            ->where(function($queryFilter) use ($filter) {
               if($filter) {
                    $queryFilter->where('profiles.name', 'LIKE', "%{$filter}%");
               }
            })
            ->paginate();

        return $permissions;
    }
}
