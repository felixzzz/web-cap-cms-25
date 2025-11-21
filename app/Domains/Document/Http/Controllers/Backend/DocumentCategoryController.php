<?php

namespace App\Domains\Document\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Domains\Document\Http\Requests\Backend\StoreDocumentCategoryRequest;
use App\Domains\Document\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of document categories.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index($template = null)
    {
        // Gate::authorize('admin.access.document-category.read');
        $categories = DocumentCategory::all();
        return view('backend.documentCategory.index', compact('categories','template'));
    }

    /**
     * Show the form for creating a new document category.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create($template = null)
    {
        // Gate::authorize('admin.access.document-category.create');
        $path = 'templates/pages.json';
        $pagesJson = [];
        if (file_exists(resource_path($path))) {
            $fileJson = file_get_contents(resource_path($path));
            $pagesJson = json_decode($fileJson, true);
        }
        if($template == 'investor'){
            $pagesJson = [
                [
                    "name" => "investor_reports",
                    "label" => "Page Investor - Reports",
                    "description" => "",
                    "is_document_category" => true,
                    "multilanguage" => "true",
                ],[
                    "name" => "investor_publicatios",
                    "label" => "Page Investor - Publications",
                    "description" => "",
                    "is_document_category" => true,
                    "multilanguage" => "true",
                ]
            ];
        }
        if($template == 'sustainability'){
            $pagesJson = [
                [
                    "name" => "sustainability_reports",
                    "label" => "Page Sustainability - Repots and Publication",
                    "description" => "",
                    "is_document_category" => true,
                    "multilanguage" => "true",
                ]
            ];
        }
        return view('backend.documentCategory.create', [
            'pages' => $pagesJson,
            'template' => $template
        ]);
    }

    /**
     * Store a newly created document category in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $template=null)
    {
        // Gate::authorize('admin.access.document-category.create');

        try {
            DocumentCategory::create([
                'name_id' => $request->input('name_id'),
                'name_en' => $request->input('name_en'),
                'page' => $request->input('page'),
                'section' => $request->input('section'),
            ]);

            DB::commit();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors('There was a problem creating the document category. Please try again.');
        }
        return redirect()->route('admin.document-categories.index', ['template' => $request->template])
            ->with('flash_success', 'Document was successfully created.');
    }

    /**
     * Display the specified document category.
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(DocumentCategory $category,$template = null)
    {
        // Gate::authorize('admin.access.document-category.read');
        return view('backend.documentCategory.show', compact('category','template'));
    }

    /**
     * Show the form for editing the specified document category.
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(DocumentCategory $category, $template = null)
    {
        // Gate::authorize('admin.access.document-category.update');
        $path = 'templates/pages.json';
        $pagesJson = [];
        $sections = [];
        if (file_exists(resource_path($path))) {
            $fileJson = file_get_contents(resource_path($path));
            $pagesJson = json_decode($fileJson, true);
        }
        $selectedPage = $category->page;
        if ($pagesJson) {
            foreach ($pagesJson as $page) {
                if ($page['name'] === $selectedPage) {
                    $sections = $page['components'] ?? [];
                    break;
                }
            }
        }
        if($template == 'investor'){
            $pagesJson = [
                [
                    "name" => "investor_reports",
                    "label" => "Page Investor - Reports",
                    "description" => "",
                    "is_document_category" => true,
                    "multilanguage" => "true",
                ],[
                    "name" => "investor_publicatios",
                    "label" => "Page Investor - Publications",
                    "description" => "",
                    "is_document_category" => true,
                    "multilanguage" => "true",
                ]
            ];
        }
        if($template == 'sustainability'){
            $pagesJson = [
                [
                    "name" => "sustainability_reports",
                    "label" => "Page Sustainability - Repots and Publication",
                    "description" => "",
                    "is_document_category" => true,
                    "multilanguage" => "true",
                ]
            ];
        }
        return view('backend.documentCategory.edit', [
            'pages' => $pagesJson,
            'sections' => $sections,
            'category' => $category,
            'template' => $template
        ]);
    }

    /**
     * Update the specified document category in storage.
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, DocumentCategory $category, $template=null)
    {
        // Gate::authorize('admin.access.document-category.update');

        try {
            $category->update([
                'name_id' => $request->input('name_id'),
                'name_en' => $request->input('name_en'),
                'page' => $request->input('page'),
                'section' => $request->input('section'),
            ]);

            DB::commit();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors('There was a problem updating the document category. Please try again.');
        }

        return redirect()->route('admin.document-categories.index', ['template' => $request->template])
            ->with('flash_success', 'Document was successfully updated.');
    }

    /**
     * Remove the specified document category from storage.
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(DocumentCategory $category, $template=null)
    {
        // Gate::authorize('admin.access.document-category.delete');

        try {
            $category->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->withErrors('There was a problem deleting the document category. Please try again.');
        }

        return redirect()->route('admin.document-categories.index', ['template' => $template])->with('flash_success', 'Document Category was successfully deleted.');
    }
}
