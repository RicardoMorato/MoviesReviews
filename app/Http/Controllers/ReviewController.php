<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Movie;
use Illuminate\Support\Facades\Gate;

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
     * Display the specific resource
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id) {
        return response()
                ->json(['data'=> Review::findOrFail($id)], 200);
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

    /**
     * Update the specific resource
     * 
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request) {
        $validator = $this->validateReviewUpdate($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        $review = Review::findOrFail($id);

        if (!Gate::allows('update-review', $review)) {
            return response()
                    ->json(['error' => 'Forbidden operation, you cannot update a review from another user', 'data' => null], 403);
        }

        $review->title = $request->title;
        $review->content = $request->content;

        $review->save();

        return response()
                ->json(['data'=>$review], 200); 
    }

    /**
     * Remove the specified resource from storage
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) {
        $review = Review::findOrFail($id);

        if (!Gate::allows('delete-review', $review)) {
            return response()
                    ->json(['error' => 'Forbidden operation, you cannot delete a review from another user', 'data' => null], 403);
        }

        if ($review->delete()) {
            return response()
                    ->json(['data'=>'Review Deleted'], 200);
        }

        return response()
                ->json(['data'=>null, 'error'=>'An odd error ocurred'], 500);
    }

    public function validateReviewCreation(Request $request) {
        return Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:50',
            'content' => 'required|string|min:3|max:255',
            'movie_id' => 'required|integer|numeric',
        ]);
    }

    public function validateReviewUpdate(Request $request) {
        return Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:50',
            'content' => 'required|string|min:3|max:255',
        ]);
    }
}
