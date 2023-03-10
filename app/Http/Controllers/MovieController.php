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
        $validator = $this->validateMovie($request);

        if ($validator->fails()) {
            return response()
                    ->json(['data'=>null, 'error'=>$validator->messages()], 400);
        }

        $movie = Movie::create($validator->validate());

        if ($movie) {
            return response()
                    ->json(['data'=> $movie], 201);
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
        $movie = Movie::findOrFail($id);
        $movie->reviews;

        return response()
                ->json(['data'=> [
                    'movie' => $movie,
                ]], 200);
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

        $movie->name = $request->name;
        $movie->director = $request->director;
        $movie->releaseDate = $request->releaseDate;
        $movie->genre = $request->genre;

        $movie->save();

        return response()
                ->json(['data'=>$movie], 200); 
    }

    /**
     * Remove the specified resource from storage
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) {
        $movie = Movie::findOrFail($id);

        if ($movie->delete()) {
            return response()
                    ->json(['data'=>'Movie Deleted'], 200);
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
