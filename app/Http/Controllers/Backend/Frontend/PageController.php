<?php

namespace App\Http\Controllers\Backend\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_page');
        if (request()->ajax()) {
            $roles = Page::select();

            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('frontend.page.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_page'],
                        ['url' => route('frontend.page.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_page'],
                        ['url' => route('frontend.page.destroy', $data->id), 'type' => 'delete', 'id' => false, 'can' => 'delete_page'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.frontend.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_page');

        return view('pages.frontend.page.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        $this->authorize('create_page');
        $input = $request->all();
        $input['slug'] = generate_slug($request->name);

        Page::create($input);

        return response()->json(['message' => 'Page created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        $this->authorize('show_page');

        return view('pages.frontend.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $this->authorize('edit_page');

        return view('pages.frontend.page.form', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $this->authorize('edit_page');
        $input = $request->all();
        $input['slug'] = generate_slug($request->name);

        $page->update($input);

        return response()->json(['message' => 'Page updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $this->authorize('delete_page');
        $page->delete();

        return response()->json(['message' => 'Page delete successfully']);
    }
}
