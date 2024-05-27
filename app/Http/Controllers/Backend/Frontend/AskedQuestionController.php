<?php

namespace App\Http\Controllers\Backend\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AskedQuestionRequest;
use App\Models\AskedQuestion;
use Yajra\DataTables\Facades\DataTables;

class AskedQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_asked_question');
        if (request()->ajax()) {
            $subscribers = AskedQuestion::query();

            return DataTables::of($subscribers)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('frontend.asked-question.edit', $data->id), 'type' => 'edit', 'can' => 'edit_asked_question'],
                        ['url' => route('frontend.asked-question.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_asked_question'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.frontend.question.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.frontend.question.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AskedQuestionRequest $request)
    {
        AskedQuestion::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json(['message' => 'Asked question added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(AskedQuestion $askedQuestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AskedQuestion $askedQuestion)
    {
        return view('pages.frontend.question.form', compact('askedQuestion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AskedQuestionRequest $request, AskedQuestion $askedQuestion)
    {
        $askedQuestion->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json(['message' => 'Asked question updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AskedQuestion $askedQuestion)
    {
        $askedQuestion->delete();

        return response()->json(['message' => 'Asked question deleted successfully.']);
    }
}
