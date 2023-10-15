<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\CategoryService;
use Exception;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $list = $this->categoryService->all();

            return response()->json(['data'=> $list], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->show($id);

            return response()->json(['data'=> $category], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'nullable|unique:categories',
            'grade_id' => 'required',
            'subject_id' => 'required',
        ]);
        try {
            $inputs = $request->all();
            $category = $this->categoryService->create($inputs);

            return response()->json(['data'=> $category], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'nullable|unique:categories',
            'grade_id' => 'required',
            'subject_id' => 'required',
        ]);
        try {
            $inputs = $request->all();
            $category = $this->categoryService->update($id, $inputs);

            return response()->json(['data'=> $category], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete($id) 
    {
        return $this->categoryService->delete($id);
    }
}
