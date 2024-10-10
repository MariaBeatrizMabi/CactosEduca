<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\StudentInClass;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function all()
    {
        return Poll::all();
    }

    public function create(Request $request)
    {
        $poll = Poll::create([
            'name' => $request->input('name'),
            'school_id' => $request->input('school_id'),
            'class_id' => $request->input('class_id'),
            'active' => 1,
            'year' => Carbon::now()->year
        ]);
        return response()->json($poll, 201);
    }

    public function getClassOfPoll($pollId)
    {
        $poll = Poll::findOrFail($pollId);
        $class = $poll->class;

        return response()->json($class);
    }

}
