<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => $this->categoryService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'parents' => Category::query()->with(['translations', 'parent.translations'])->get(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category->load('translations'),
            'parents' => Category::query()
                ->whereKeyNot($category->id)
                ->with(['translations', 'parent.translations'])
                ->get(),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $this->categoryService->update($category, $request->validated());
        } catch (\RuntimeException $e) {
            return back()->withErrors(['parent_id' => $e->getMessage()]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->delete($category);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['category' => $e->getMessage()]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
