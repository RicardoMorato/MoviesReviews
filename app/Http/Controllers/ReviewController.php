<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Movie;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resources
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $reviews = Review::all();

        return response()->json([
            'data'=>$reviews
        ], 200);
    }

    /**
     * Store a newly created resource in storage
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // TO-DO: CREATE UNIT TEST FOR REVIEW VALIDATOR
        $validator = $this->validateReviewCreation($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        $movie = Movie::findOrFail($request->movie_id);

        $validated_content = $validator->validate();
        $validated_content['user_id'] = $request->user()->id;

        $review = Review::create($validated_content);

        if ($review) {
            return response()
                    ->json(['data'=>$review], 201);
        }

        return response()
                ->json(['data'=>null, 'error'=>'An odd error ocurred'], 500);
    }

    public function validateReviewCreation(Request $request) {
        return Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:25',
            'content' => 'required|string|min:3|max:255',
            'movie_id' => 'required|integer|numeric',
        ]);
    }
}
