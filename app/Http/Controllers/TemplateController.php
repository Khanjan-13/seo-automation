<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    // Show templates page
    public function page()
    {
        return view('normal.templates');
    }

    // Get all templates for authenticated user
    public function index()
    {
        $templates = Auth::guard('normaluser')->user()->templates()->orderBy('created_at', 'desc')->get();
        return response()->json($templates);
    }

    // Save new template
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $template = Auth::guard('normaluser')->user()->templates()->create([
            'name' => $request->name,
            'content' => $request->content,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template saved successfully',
            'template' => $template
        ]);
    }

    // Get specific template
    public function show($id)
    {
        $template = Template::where('id', $id)
            ->where('user_id', Auth::guard('normaluser')->id())
            ->firstOrFail();

        return response()->json($template);
    }

    // Update template
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $template = Template::where('id', $id)
            ->where('user_id', Auth::guard('normaluser')->id())
            ->firstOrFail();

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully',
            'template' => $template
        ]);
    }

    // Delete template
    public function destroy($id)
    {
        $template = Template::where('id', $id)
            ->where('user_id', Auth::guard('normaluser')->id())
            ->firstOrFail();

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully'
        ]);
    }
}
