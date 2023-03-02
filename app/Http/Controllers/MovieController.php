<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resources
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $movies = Movie::all();

        return response()->json([
            'data'=>$movies
        ], 200);
    }
    
    /**
     * Store a newly created resource in storage
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // TO-DO: CREATE UNIT TEST FOR MOVIE VALIDATOR
        $validator = $this->validateMovie($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        if (Movie::create($validator->validate())) {
            return response()
                    ->json(['data'=>$validator->validate()], 201);
        }

        return response()
                ->json(['data'=>null, 'error'=>'An odd error ocurred'], 500);
    }

    /**
     * Display the specific resource
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id) {
        return response()
                ->json(['data'=> Movie::findOrFail($id)], 200);
    }

    /**
     * Update the specific resource
     * 
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request) {
        $validator = $this->validateMovie($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        $movie = Movie::findOrFail($id);

        $movie->name = $request->input('name');
        $movie->director = $request->input('director');
        $movie->releaseDate = $request->input('releaseDate');
        $movie->genre = $request->input('genre');

        $movie->save();

        return response()
                ->json(['data'=>$movie], 200); 
    }

    /**
     * Remove the specified reousrce from storage
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) {
        $movie = Movie::findOrFail($id);

        if ($movie->delete()) {
            return response()
                    ->json(['data'=>'Article Deleted'], 202);
        }

        return response()
                ->json(['data'=>null, 'error'=>'An odd error ocurred'], 500);
    }

    public function validateMovie(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'releaseDate' => 'required|string|min:3|max:100',
            'genre' => 'required|string|min:3|max:100',
            'director' => 'required|string|min:3|max:100',
        ]);
    }
}
