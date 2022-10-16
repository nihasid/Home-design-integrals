<?php

namespace App\Models;

use App\Helpers\Constant;
use App\Helpers\UploadHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProjectStage extends Model
{

    protected $appends = [
        'duration',
        'duration_dates'
    ];

    function user () {
        return $this->belongsTo('App\Models\User');
    }

    function getDurationAttribute () {
        if ($this->start_date && $this->end_date) {
            return date('j M', strtotime($this->start_date)) . ' - ' . date('j M', strtotime($this->end_date));
        }
    }

    function getDurationDatesAttribute () {
        if ($this->start_date && $this->end_date) {

            $period = CarbonPeriod::create($this->start_date, $this->end_date);

            $dates = [];
            foreach ($period as $date) {
                $dates[] = [
                    'date' => $date->format(Constant::DATE_FORMAT)
                ];
            }

            return $dates;
        }
    }

    function comments () {
        return $this->hasMany('App\Models\ProjectStageComment');
    }

    function stage () {
        return $this->belongsTo('App\Models\Stage');
    }

    function project () {
        return $this->belongsTo('App\Models\Project');
    }

    function attachments () {
        return $this->hasMany('App\Models\ProjectStageAttachment');
    }

    protected $fillable = [
        'project_id',
        'stage_id',
        'start_date',
        'end_date',
        'status',
        'is_active',
		'is_issue',
        'user_id'
    ];

    static function updateProjectStage ( $request, $projectStage ) {

        $userId = Auth::id();
        if ($request->comment) {

            $projectStage->comments()->create([
                'user_id' => $userId,
                'comment' => $request->comment
            ]);

        } elseif ($request->status) {

            $projectStage->status = $request->status;

        } elseif (isset($request->is_issue)) {
            $projectStage->is_issue = (int)$request->is_issue;

        } elseif (trim($request->action) == 'member_add' || trim($request->action) == 'member_delete') {
            $projectStage->user_id = (trim($request->action) == "member_add") ? $request->member_id : 0;

        } elseif ($request->start_date && $request->end_date) {

            $startDate = date(Constant::DATE_FORMAT, strtotime($request->start_date));
            $endDate = date(Constant::DATE_FORMAT, strtotime($request->end_date));

            $projectStage->start_date = $startDate;
            $projectStage->end_date = $endDate;

        } elseif ($request->attachments) {
            $data = [];

            foreach ($request->attachments as $attachment) {

                $folder = 'projects/' . $projectStage->project_id . '/stages/' . $projectStage->id;
                $attachmentUrl = UploadHelper::UploadFile( $attachment, $folder );

                $data[] = [
                    'user_id'       => $userId,
                    'attachment'    => $attachmentUrl,
                    'name'          => $attachment->getClientOriginalName(),
                    'size'          => $attachment->getSize()
                ];
            }

            $projectStage->attachments()->createMany($data);
        }

        $projectStage->save();

        // generate automated duration for other stages
        if ( $projectStage->stage_id == 1 && $request->start_date) {
            $startDate = date(Constant::DATE_FORMAT, strtotime($request->start_date));
            $projectStage->start_date = $startDate;
            $projectStage->save();
            self::generateAutoDurations( $projectStage );
        }
        return $projectStage;
    }

    static function pendingToOngoing ($stageId = 0)
    {
        $data = [
            'updated_at' => now(),
            'status' => 'ongoing'
        ];

        $condition = [
            ['start_date', '<=', date(Constant::DATE_FORMAT)],
            ['status', '=', 'pending']
        ];

        if ($stageId) {
            $condition[] = ['id', '=', $stageId];
        }

        return self::where($condition)->update($data);
    }

    static function ongoingToOverdue($stageId = 0)
    {
        $data = [
            'updated_at' => now(),
            'status'     => 'overdue'
        ];

        $condition = [
            ['end_date', '<', date(Constant::DATE_FORMAT)],
        ];

        if ($stageId) {
            $condition[] = ['id', '=', $stageId];
        }

        return self::where($condition)->whereIn('status', ['pending', 'ongoing'])->update($data);
    }

    static function generateAutoDurations ( $projectStage ) {
        $stages = Stage::select('id', 'working_days', 'start_after_days')->where('is_active', true)->get();

        foreach ($stages as $stage) {
            $startDate = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->start_date);
            $endDate = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->start_date);

            $startDate = $startDate->addDays($stage->start_after_days);
            $endDate = $endDate->addDays(($stage->start_after_days + $stage->working_days));

            ProjectStage::where([
                'project_id' => $projectStage->project_id,
                'stage_id' => $stage->id
            ])->update([
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }
    }
}
