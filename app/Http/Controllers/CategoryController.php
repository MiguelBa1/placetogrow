<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::select('id', 'name')->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function store(CreateCategoryRequest $request): HttpFoundationResponse
    {
        Category::create($request->validated());

        return Inertia::location(route('categories.index'));
    }

    public function update(CreateCategoryRequest $request, Category $category): HttpFoundationResponse
    {
        $category->update($request->validated());

        return Inertia::location(route('categories.index'));
    }

    public function destroy(Category $category): HttpFoundationResponse
    {
        $category->delete();

        return Inertia::location(route('categories.index'));
    }
}
