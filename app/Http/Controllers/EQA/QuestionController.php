<?php

namespace App\Http\Controllers\EQA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('adder')->orderBy('question_id', 'desc')->paginate(20);
        return view('eqa.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('eqa.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['q_text' => 'required|string']);

        $q = new Question();
        $q->q_text = $request->q_text;
        $q->added_by = auth()->id() ?? 0;
        $q->added_date = now();
        $q->save();

        return redirect()->route('eqa.questions.index')->with('success', 'Question Added');
    }
}
