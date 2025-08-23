<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Group;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController
{
    protected $catService;

    public function __construct(CategoryService $catService)
    {
        $this->catService = $catService;
    }

    public function index(Request $request)
    {
        checkUserHasRolesOrRedirect('category.list');

        if ($request->ajax()) {
            return $this->catService->getCategories();
        }

        return view('admin.categories.index');
    }

    public function getSpecificCategory($id)
    {
        return $this->catService->getCategory($id);
    }

    public function create()
    {
        checkUserHasRolesOrRedirect('category.add');

        $groups = Group::all();
        return view('admin.categories.create', compact('groups'));
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->catService->createCategory(data: $request->all());
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function edit(Request $request, $id)
    {
        checkUserHasRolesOrRedirect('category.edit');

        $category = Category::findOrFail($id);
        $groups = Group::all();
        return view('admin.categories.edit', compact('category', 'groups'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = $this->catService->updateCategory(id: $id, data: $request->all());
        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        checkUserHasRolesOrRedirect('category.delete');

        $category = $this->catService->deleteCategory($id);
        return redirect()->back()->with('success', 'Deleted successfully');
    }
}
