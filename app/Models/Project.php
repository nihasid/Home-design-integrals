<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use function foo\func;

class Project extends Model {
    use SoftDeletes;

    protected $fillable = [
        'region_id',
        'country_id',
        'shop_id',
        'vendor_id',
        'project_generation',
        'name',
        'project_status',
        'is_active',
        'created_by',
    ];

    static function boot() {
        parent::boot();

        static::created(function ($project) {

            $projectStages = [];
            $stages = Stage::select('id', 'working_days', 'start_after_days')->where('is_active', true)->get();

            foreach ($stages as $stage) {
                $projectStages[] = [
                    'stage_id' => $stage->id,
                    'status' => 'ongoing'
                ];
            }

            $project->projectStages()->createMany($projectStages);
        });
    }

    function getCreatedAtAttribute($value) {
        return date(Constant::DATE_DISPLAY, strtotime($value));
    }

    function getUpdatedAtAttribute($value) {
        $now = Carbon::now();
        $updatedAt = Carbon::parse($value);

        if ($updatedAt->diffInDays() > 1) {
            $diff = $updatedAt->diffInDays() . ' days ago';
        } else {
            $diff = $updatedAt->diffForHumans();
        }

        return $diff;
    }

    function attachments () {
        return $this->hasMany('App\Models\RolloutAttachment', 'rollout_id');
    }

    function inventoryComponentMapping() {
        return $this->hasOne('App\Models\InventoryComponentMapping', 'project_id', 'id');
    }

    function projectInventories() {
        return $this->hasOne('App\Models\ProjectInventory', 'project_id', 'id');
    }

    function vendor() {
        return $this->belongsTo('App\Models\Vendor');
    }

    function shop() {
        return $this->belongsTo('App\Models\Shop');
    }

    function userProjects() {
        return $this->hasMany('App\Models\UserProject');
    }

    function country() {
        return $this->belongsTo('App\Models\Country');
    }

    function region() {
        return $this->belongsTo('App\Models\Region');
    }

    function favouriteProject() {
        return $this->hasOne('App\Models\UserFavouriteProject')->whereUserId(Auth::id());
    }

    function projectStages() {
        return $this->hasMany('App\Models\ProjectStage');
    }

    function createdBy() {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    static function getAllProjects($request) {

        $condition = [
            'is_active' => true
        ];

        /*Filters conditions Start*/
        if ($request->shop_id) {
            $condition['shop_id'] = $request->shop_id;
        }

        if ($request->vendor_id) {
            $condition['vendor_id'] = $request->vendor_id;
        }

        if ($request->country_id) {
            $condition['country_id'] = $request->country_id;
        }
        /*Filters conditions End*/

        $user = Auth::user();

        $query = Project::select([
            'id',
            'name',
            'shop_id',
            'vendor_id',
            'region_id',
            'country_id',
            'project_status',
            'updated_at',
        ])->with([
            'shop' => function ($q) {
                $q->select('id', 'name');
            },
            'vendor' => function ($q) {
                $q->select('id', 'name');
            },
            'country' => function ($q) {
                $q->select('id', 'name');
            },
            'region' => function ($q) {
                $q->select('id', 'name');
            },
            'favouriteProject' => function ($q) {
                $q->select('project_id');
            }
        ]);

        $query->where($condition);

        if ($request->user_id) {
            $query->whereHas('userProjects', function ($q) use ($request) {
                $q->where('user_id', $request->user_id)->where('is_active', true);
            });
        }

        if (Helper::isRegional($user) || Helper::isVendor($user)) {
            $query->whereHas('userProjects', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('is_active', true);
            });
        }

        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->region_id) {
            $country_ids = Country::select('id')->where('region_id', $request->region_id)->pluck('id');
            $query->whereIn('country_id', $country_ids);
        }

        if ($request->is_favourite) {
            $favourite_project_ids = UserFavouriteProject::select('project_id')->where('user_id', Auth::id())->where('is_active', true)->pluck('project_id');
            $query->whereIn('id', $favourite_project_ids);
        }

        if ($request->limit) {
            $query->offset($request->offset)->limit($request->limit);
        }

        $query->orderBy('id', 'DESC');
        return $query->get();;
    }

    static function deleteUserProjects($project) {
        $userProjects = UserProject::where('project_id', $project->id);
        return $userProjects->delete();
    }

    static function deleteProject($project) {

        /*
        $project->userProjects()->delete();
        foreach ($project->projectStages as $projectStage) {
            $projectStage->attachments()->delete();
            $projectStage->comments()->delete();
        }
        $project->projectStages()->delete();
        */

        return $project->delete();
    }

    static function createProject($request) {

        if (isset($request->project_id) && $request->project_id) {
            $project = Project::find($request->project_id);
            self::deleteUserProjects($project);
        } else {
            $project = new self;
        }

        $project->fill([
            'region_id' => 0,
            'country_id' => $request->country_id,
            'shop_id' => $request->shop_id,
            'project_generation' => $request->project_generation,
            'name' => $request->name,
            'vendor_id' => $request->vendor_id,
            'created_by' => Auth::id()
        ]);

        $project->save();

        // add region_id derived from country
        $project->region_id = $project->country->region_id;
        $project->save();

        $user_ids = isset($request->members) ? $request->members : [];
        if (count($user_ids)) {
            $users = [];
            foreach ($user_ids as $user_id) {
                $users[] = [
                    'user_id' => $user_id
                ];
            }
            $project->userProjects()->createMany($users);
        }

        return $project;
    }

    static function addProjectToFavourite($request) {
        $favProject = UserFavouriteProject::firstOrCreate([
            'user_id' => Auth::id(),
            'project_id' => $request->project_id
        ]);

        if (!$request->is_favourite) {
            $favProject->delete();
            return NULL;
        } else {
            return $favProject;
        }
    }

    static function projectRelations() {
        return [
            'attachments' => function ($q) {
                $q->select('id', 'rollout_id', 'attachment', 'name', 'size');
            },
            'favouriteProject' => function ($q) {
                $q->select('project_id');
            },
            'shop' => function ($q) {
                $q->select('id', 'name', 'country_id');
            },
            'country' => function ($q) {
                $q->select('id', 'name');
            },
            'region' => function ($q) {
                $q->select('id', 'name');
            },
            'vendor' => function ($q) {
                $q->select('id', 'name');
            },
            'projectStages' => function ($q) {
                $q->select('id', 'project_id', 'stage_id', 'start_date', 'end_date', 'status', 'is_issue', 'user_id');
            },
            'projectStages.user' => function ($q) {
                $q->select('id');
            },
            'projectStages.user.userDetails' => function ($q) {
                $q->select('user_id', 'full_name', 'short_name', 'avatar');
            },
            'projectStages.user.userRole' => function ($q) {
                $q->select('id', 'user_id', 'role_id');
            },
            'projectStages.user.userRole.role' => function ($q) {
                $q->select('id', 'name');
            },
            'projectStages.comments' => function ($q) {
                $q->select('id', 'project_stage_id', 'user_id', 'comment', 'created_at');
            },
            'projectStages.comments.user' => function ($q) {
                $q->select('id');
            },
            'projectStages.comments.user.userDetails' => function ($q) {
                $q->select('user_id', 'full_name', 'short_name', 'avatar');
            },
            'projectStages.attachments' => function ($q) {
                $q->select('id', 'project_stage_id', 'attachment', 'name', 'size');
            },
            'userProjects' => function ($q) {
                $q->select('id', 'user_id', 'project_id');
            },
            'userProjects.user' => function ($q) {
                $q->select('id');
            },
            'userProjects.user.userDetails' => function ($q) {
                $q->select('user_id', 'full_name', 'short_name', 'avatar');
            },
            'userProjects.user.userRole' => function ($q) {
                $q->select('id', 'user_id', 'role_id');
            },
            'userProjects.user.userRole.role' => function ($q) {
                $q->select('id', 'name');
            },
            'createdBy' => function ($q) {
                $q->select('id');
            },
            'createdBy.userDetails' => function ($q) {
                $q->select('user_id', 'full_name');
            }
        ];
    }

    static function updateProjectStatus ($projectId = 0) {

        $projectCondition = [];

        if ($projectId) {
            $projectCondition['id'] = $projectId;
        }

        $projects = Project::select('id', 'project_status')->withCount([
            'projectStages as stages_overdue' => function ($q) {
                $q->where('status', 'overdue');
            },
            'projectStages as stages_completed' => function ($q) {
                $q->whereIn('status', ['completed', 'approved']);
            }
        ])->where($projectCondition)->get();

        foreach ($projects as $project) {
            if ($project->stages_overdue > 0) {
                $project->project_status = 'Overdue';
            } elseif ($project->stages_completed == 12) {
                $project->project_status = 'Completed';
            } else {
                $project->project_status = 'Ongoing';
            }
            $project->save();
        }
    }
}
