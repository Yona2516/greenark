<?php
// app/Http/Controllers/Api/ProjectController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::published()->orderBy('sort_order');

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        $projects = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function show($slug)
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->with('media')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    public function featured()
    {
        $projects = Project::published()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function categories()
    {
        $categories = [
            'residential' => 'Residential Properties',
            'commercial' => 'Commercial Real Estate',
            'custom-builds' => 'Custom Builds',
            'renovations' => 'Renovations'
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}