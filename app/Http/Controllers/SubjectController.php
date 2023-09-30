<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Str;
use App\AppMain\Services\SubjectService;

class SubjectController extends Controller
{
    protected $subjectService;
    public function __construct(SubjectService $subjectService) {
        $this->subjectService = $subjectService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $list = $this->subjectService->all();

            return response()->json(['data'=> $list], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $data = $request->all();
            $data['slug'] = $data['slug']??Str::slug($data['name']);
            $user = $this->subjectService->store($data);

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $data = $request->all();
            $user = $this->subjectService->update($data, $id);

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->subjectService->destroy($id);

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
