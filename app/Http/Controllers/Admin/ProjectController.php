<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\ResponseHandler;
use App\Helpers\UploadHelper;
use App\Models\ExceptionLog;
use App\Models\InventoryComponentMapping;
use App\Models\Project;
use App\Models\ProjectInventory;
use App\Models\ProjectStage;
use App\Models\ProjectStageAttachment;
use App\Models\RolloutAttachment;
use App\Models\Stage;
use App\Models\UserProject;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            $projects = Project::getAllProjects($request);
            return ResponseHandler::success($projects);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        try {

            $validationErrors = Helper::validationErrors($request, [
                'country_id' => 'required|integer',
                'shop_id' => 'required|integer',
                'project_generation' => 'required|string',
                'name' => 'required|string',
                'vendor_id' => 'required|integer',
            ]);

            if ($validationErrors) {
                return ResponseHandler::validationError($validationErrors);
            }

            DB::beginTransaction();

            $project = Project::createProject($request);

            if (isset($request->project_id) && $request->project_id) {
                $project->load(Project::projectRelations());
                $project = $this->addAttachmentSize( $project );
                $project = $this->addDuration( $project );
            }

            $stages = Stage::where('is_active', true)->get()->toTree();

            $project->project_stages = $this->joinNestedStages( $stages, $project->projectStages );

            $project->unsetRelation('projectStages');

            return ResponseHandler::success($project);
        } catch (\Exception $e) {
            DB::rollBack();

            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        } finally {
            DB::commit();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project, Request $request) {
        $user = Auth::user();
        if (Helper::isRegional($user)) {
            if (!UserProject::getProjectByUserId($user->id)) {
                return ResponseHandler::authorizationError();
            }
            if (!UserProject::checkUserAccessForProject($project->id, $user->id)) {
                return ResponseHandler::authorizationError();
            }
        }
        try {
            $project->load(Project::projectRelations());
            $project = $this->addAttachmentSize($project);
            $project = $this->addDuration($project);

            $stages = Stage::where('is_active', true)->get()->toTree();

            $project->project_stages = $this->joinNestedStages( $stages, $project->projectStages );

            $project->unsetRelation('projectStages');
            return ResponseHandler::success($project);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    private function joinNestedStages ( $stages, $projectStages ) {
// optimize this
        foreach ($stages as $stage) {
            foreach ($projectStages as $projectStage) {
                if ($projectStage->stage_id == $stage->id) {
                    $stage->start_date = $projectStage->start_date;
                    $stage->end_date = $projectStage->end_date;
                    $stage->status = $projectStage->status;
                    $stage->is_issue = $projectStage->is_issue;
                    $stage->user_id = $projectStage->user_id;
                    $stage->duration = $projectStage->duration;
                    $stage->duration_dates = $projectStage->duration_dates;
                    $stage->user = $projectStage->user;
                    $stage->comments = $projectStage->comments;
                    $stage->attachments = $projectStage->attachments;
                    $stage->id = $projectStage->id;

                    if ($projectStage->start_date && $projectStage->end_date) {
                        $to = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->start_date);
                        $from = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->end_date);
                        $diffInDays = $to->diffInDays($from);
                        $stage->working_days = $diffInDays ? $diffInDays : 1;
                    }
                }
            }

            foreach ($stage->children as $stage1) {
                foreach ($projectStages as $projectStage) {
                    if ($projectStage->stage_id == $stage1->id) {
                        $stage1->start_date = $projectStage->start_date;
                        $stage1->end_date = $projectStage->end_date;
                        $stage1->status = $projectStage->status;
                        $stage1->is_issue = $projectStage->is_issue;
                        $stage1->user_id = $projectStage->user_id;
                        $stage1->duration = $projectStage->duration;
                        $stage1->duration_dates = $projectStage->duration_dates;
                        $stage1->user = $projectStage->user;
                        $stage1->comments = $projectStage->comments;
                        $stage1->attachments = $projectStage->attachments;
                        $stage1->id = $projectStage->id;

                        if ($projectStage->start_date && $projectStage->end_date) {
                            $to = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->start_date);
                            $from = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->end_date);
                            $diffInDays = $to->diffInDays($from);
                            $stage1->working_days = $diffInDays ? $diffInDays : 1;;
                        }
                    }
                }

                foreach ($stage1->children as $stage2) {
                    foreach ($projectStages as $projectStage) {
                        if ($projectStage->stage_id == $stage2->id) {
                            $stage2->start_date = $projectStage->start_date;
                            $stage2->end_date = $projectStage->end_date;
                            $stage2->status = $projectStage->status;
                            $stage2->is_issue = $projectStage->is_issue;
                            $stage2->user_id = $projectStage->user_id;
                            $stage2->duration = $projectStage->duration;
                            $stage2->duration_dates = $projectStage->duration_dates;
                            $stage2->user = $projectStage->user;
                            $stage2->comments = $projectStage->comments;
                            $stage2->attachments = $projectStage->attachments;
                            $stage2->id = $projectStage->id;

                            if ($projectStage->start_date && $projectStage->end_date) {
                                $to = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->start_date);
                                $from = Carbon::createFromFormat(Constant::DATE_FORMAT, $projectStage->end_date);
                                $diffInDays = $to->diffInDays($from);
                                $stage2->working_days = $diffInDays ? $diffInDays : 1;;
                            }
                        }
                    }
                }
            }
        }

        return $stages;
    }

    private function addDuration($project) {

        $fresh = false;
        $startDate = $project->projectStages->pluck('start_date')->min();
        if (!$startDate) {
            $startDate = date(Constant::DATE_FORMAT, strtotime($project->created_at));
            $fresh = true;
        }

        $endDate = $project->projectStages->pluck('end_date')->max();

        $project->duration = !$fresh ? date('d M', strtotime($startDate)) . ' - ' . date('d M', strtotime($endDate)) : '';
        $project->current_date = date(Constant::DATE_FORMAT);
        $project->fresh = $fresh;
        $project->start_date = $startDate;
        $project->end_date = $endDate ? $endDate : $startDate;

        return $project;
    }

    private function addAttachmentSize($project) {
        $totalSize = 0;
        $project->is_issue = false;
        foreach ($project->projectStages as $projectStage) {
            foreach ($projectStage->attachments as $attachment)
                $totalSize += $attachment->size;

            if ($projectStage->is_issue) {
                $project->is_issue = true;
            }
        }

        $project->total_attachment_size = Helper::formatSizeUnits($totalSize);
        return $project;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Project $project) {
        try {
            DB::beginTransaction();

            $project = Project::deleteProject($project);
            return ResponseHandler::success($project);
        } catch (\Exception $e) {
            DB::rollBack();

            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        } finally {
            DB::commit();
        }
    }

    public function addTofavourite(Request $request) {
        try {
            $project = Project::addProjectToFavourite($request);
            return ResponseHandler::success($project);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    function updateProjectStage(Request $request, ProjectStage $projectStage) {
        try {

            if ($request->start_date && $request->end_date) {
                $error = '';
                if ($request->end_date < $request->start_date) {
                    $error = 'End Date Must Be Greater Than Start Date';
                } elseif (date(Constant::DATE_FORMAT, strtotime($request->start_date)) < date(Constant::DATE_FORMAT)) {
//                    $error = 'Start Date Must Be Greater Than Current Date';
                }

                if ($error) {
                    return ResponseHandler::validationError([$error]);
                }
            }

            DB::beginTransaction();
            $projectStage = ProjectStage::updateProjectStage($request, $projectStage);
            $projectStage->load([
                'comments.user.userDetails',
                'attachments'
            ]);

            if ($request->start_date && $request->end_date) {

                ProjectStage::ongoingToOverdue( $projectStage->id );
                ProjectStage::pendingToOngoing( $projectStage->id );

            } elseif ($request->status) {
                Project::updateProjectStatus( $projectStage->project_id );
            }  elseif ($request->is_issue) {
                ProjectStage::updateProjectStage($request, $projectStage );
            } elseif (isset($request->member_id)) {
                ProjectStage::updateProjectStage($request, $projectStage );
            }

            $projectStage->name = $projectStage->stage->name;

            $projectStage->load('project');

            $startDate = $projectStage->project->projectStages->pluck('start_date')->min();
            if (!$startDate) {
                $startDate = date(Constant::DATE_FORMAT, strtotime($projectStage->project->created_at));
            }

            $endDate = $projectStage->project->projectStages->pluck('end_date')->max();
            $projectStage->project->start_date = $startDate;
            $projectStage->project->end_date = $endDate ? $endDate : $startDate;

            return ResponseHandler::success( $projectStage );
        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        } finally {
            DB::commit();
        }
    }

    function removeAttachment(ProjectStageAttachment $projectStageAttachment) {
        try {
            $projectStageAttachment->delete();
            return ResponseHandler::success($projectStageAttachment);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    static function exportInventoryUsed(Request $request) {
        try {
            $inventory = ProjectInventory::select('inventory_used')->where('project_id', $request->project_id)
                ->where('is_active', 1)
                ->first();

            $finalResult = $all_items = [];

            if ($inventory) {

                if (!is_array($inventory->inventory_used) || count($inventory->inventory_used) == 0) {
                    return ResponseHandler::validationError(['No Inventory']);
                }

                foreach ($inventory->inventory_used as $inventoryItem) {

                    if (!isset($inventoryItem['item_id']) || !isset($inventoryItem['item_count'])) {
                        return ResponseHandler::validationError(['Invalid Json Structure']);
                    }

                    $item_components = isset($inventoryItem['components']) ? $inventoryItem['components'] : [];

                    for ($i = 0; $i < $inventoryItem['item_count']; $i++) {
                        if (count($item_components) > 0) {
                            foreach ($item_components as $item_component) {
                                $display_name = isset($item_component['display_name']) ? trim($item_component['display_name']) : '';
                                if ($display_name != null && $display_name != '') {

                                    $all_items['detail'][$inventoryItem['type']][$item_component['component_id']][] = [
                                        "TYPE" => $inventoryItem['type'],
                                        "CATEGORY" => $inventoryItem['category'],
                                        "GROUP" => $inventoryItem['group'],
                                        "IMAGE" => '',
                                        "COMPONENT" => $display_name,
                                        "CODE" => $item_component['component_id'],
                                        "DIMENSIONS" => $item_component['dimensions'],
                                        "TOTAL" => $item_component['quantity'],
                                    ];

                                    if (isset($all_items['summary'][$inventoryItem['type']][$item_component['component_id']])) {
                                        $total = $all_items['summary'][$inventoryItem['type']][$item_component['component_id']][0]['TOTAL'] + $item_component['quantity'];
                                        $all_items['summary'][$inventoryItem['type']][$item_component['component_id']][0]['TOTAL'] = $total;
                                        continue;
                                    }
                                    $all_items['summary'][$inventoryItem['type']][$item_component['component_id']][] = [
                                        "TYPE" => $inventoryItem['type'],
                                        "CATEGORY" => $inventoryItem['category'],
                                        "IMAGE" => '',
                                        "COMPONENT" => $display_name,
                                        "CODE" => $item_component['component_id'],
                                        "DIMENSIONS" => $item_component['dimensions'],
                                        "TOTAL" => $item_component['quantity'],
                                    ];
                                }
                            }
                        }
                    }
                }

                $sortKeys = [
                    'Wall Panel',
                    'Wall Fixture',
                    'Loose Fixture',
                    'Miscellaneous',
                    'Excluded',
                ];

                if ($all_items) {
                    $all_items['summary'] = array_merge(array_flip($sortKeys), $all_items['summary']);
                    $all_items['detail'] = array_merge(array_flip($sortKeys), $all_items['detail']);

                    foreach ($all_items as $fileType => $fileTypeArr) {
                        foreach ($fileTypeArr as $itemTypeArr) {
                            if (is_array($itemTypeArr)) {
                                foreach ($itemTypeArr as $componentCodeArr) {
                                    foreach ($componentCodeArr as $component) {
                                        $finalResult[$fileType][] = $component;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return ResponseHandler::success($finalResult);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    function saveAttachments (Request $request, $rolllutId) {
        try {

            $userId = $request->user()->id;
            $data = [];
            foreach ($request->attachments as $attachment) {

                $folder = 'rollouts/' . $rolllutId;
                $attachmentUrl = UploadHelper::UploadFile( $attachment, $folder );

                $data[] = [
                    'user_id'       => $userId,
                    'rollout_id'    => $rolllutId,
                    'attachment'    => $attachmentUrl,
                    'name'          => $attachment->getClientOriginalName(),
                    'size'          => $attachment->getSize(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            $attachments = RolloutAttachment::insert($data);
            return ResponseHandler::success($attachments);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }

    function removeRolloutAttachment(RolloutAttachment $rolloutAttachment) {
        try {
            $rolloutAttachment->delete();
            return ResponseHandler::success($rolloutAttachment);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }
    function getAttachments($projectId) {
        try {

            $project = Project::select('id', 'name')->with([
                'attachments' => function ($q) {
                    $q->select('id', 'rollout_id', 'attachment', 'name', 'size')->where('is_active', true);
                },
                'projectStages:id,project_id',
                'projectStages.attachments' => function ($q) {
                    $q->select('id', 'project_stage_id', 'attachment', 'name', 'size')->where('is_active', true);
                }
            ])->where('id', $projectId)->first();

            return ResponseHandler::success($project);
        } catch (\Exception $e) {
            ExceptionLog::log($e, self::$routeAction);
            return ResponseHandler::serverError($e);
        }
    }
}
