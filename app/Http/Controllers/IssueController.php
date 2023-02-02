<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Http\Resources\IssueResource;
use App\Models\DefaultStatuesScope;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Issue::class);

        $issues = Issue::when($request->get('status'), function($query, $status) {
            return $query->withoutGlobalScope(DefaultStatuesScope::class)->where('status', $status);
        })->get();
        return IssueResource::collection($issues);
    }

    public function create(CreateIssueRequest $request)
    {
        $issue = Issue::create($request->validated());
        return new IssueResource($issue);
    }

    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->status = $request->status;
        $issue->save();
        return new IssueResource($issue);
    }
}
