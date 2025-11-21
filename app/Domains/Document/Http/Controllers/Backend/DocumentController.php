<?php

namespace App\Domains\Document\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Domains\Document\Http\Requests\Backend\StoreDocumentRequest;
use App\Domains\Document\Models\Document;
use App\Domains\Document\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request,$template = null)
    {
        $documents = Document::all();
        // Gate::authorize('admin.access.document.read');
        return view('backend.document.index', compact('documents','template'));
    }

    /**
     * Show the form for creating a new document.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create($template = null)
    {
        // Gate::authorize('admin.access.document.create');
        $categories = DocumentCategory::all();
        if($template){
            $categories = DocumentCategory::where('page', $template)->get();
        }
        $path = 'templates/pages.json';
        $pagesJson = [];
        if (file_exists(resource_path($path))) {
            $fileJson = file_get_contents(resource_path($path));
            $pagesJson = json_decode($fileJson, true);
        }
        return view('backend.document.create', [
            'pages' => $pagesJson,
            'categories' => $categories,
            'template' => $template
        ]);
    }

    /**
     * Store a newly created document in storage.
     *
     * @param StoreDocumentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDocumentRequest $request)
    {
        // Gate::authorize('admin.access.document.create');
        // Handle file uploads
        $documentFileIdPath = null;
        $documentFileEnPath = null;
        $imagePath = null;
        if ($request->hasFile('document_file_id')) {
            $documentFileId = $request->file('document_file_id');
            $newDocumentFileIdName = $this->renameFile($documentFileId, $request->input('document_name_id'),'id');
            $documentFileIdPath = $documentFileId->storeAs('images/post', $newDocumentFileIdName);
        }
    
        // Process document_file_en if uploaded
        if ($request->hasFile('document_file_en')) {
            $documentFileEn = $request->file('document_file_en');
            $newDocumentFileEnName = $this->renameFile($documentFileEn, $request->input('document_name_en'),'en');
            $documentFileEnPath = $documentFileEn->storeAs('images/post', $newDocumentFileEnName);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->storePublicly('images/post');
        }

        try {
            Document::create([
                'lang' => $request->input('lang'),
                'page' => $request->input('page'),
                'section' => $request->input('section'),
                'category' => $request->input('category'),
                'category_id' => $request->input('category_id') ?? null,
                'document_name_id' => $request->input('document_name_id'),
                'document_name_en' => $request->input('document_name_en'),
                'document_type' => '',
                'description_id' => $request->description_id,
                'description_en' => $request->description_en,
                'language' => $request->language,
                'author' => $request->author,
                'publisher' => $request->publisher,
                'release_year' => $request->release_year,
                'pages' => $request->pages,
                'format' => $request->format,
                'document_file_id' => $documentFileIdPath,
                'document_file_en' => $documentFileEnPath,
                'image' => $imagePath,
                'alt_image' => $request->alt_image,
                'alt_image_en' => $request->alt_image_en,
                'published_at' => $request->input('published_at'),
            ]);        

            DB::commit();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors('There was a problem creating the document. Please try again.');
        }
        return redirect()->route('admin.document.index', ['template' => $request->template])
            ->with('flash_success', 'Document was successfully created.');
    }
    private function renameFile($file, $baseName, $lang)
    {
        // Sanitize base name
        $sanitizedBaseName = preg_replace('/[^a-zA-Z0-9-_]/', '', $baseName);

        // Get file extension
        $extension = $file->getClientOriginalExtension();

        // Return new file name
        return $sanitizedBaseName . '_' .$lang.'_'. time() . '.' . $extension;
    }
    protected function uploadFile($file, $directory)
    {
        $originalName = $file->getClientOriginalName();
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $fileName . '_' . time() . '.' . $extension;

        // Move the file to the specified directory
        $file->move(public_path($directory), $fileName);

        // Return the file path or URL
        return $directory . '/' . $fileName;
    }
    /**
     * Display the specified document.
     *
     * @param Document $document
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Document $document, $template="")
    {
        // Gate::authorize('admin.access.document.read');
        return view('backend.document.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     *
     * @param Document $document
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Document $document, $template = null)
    {
        // Gate::authorize('admin.access.document.update');
        $categories = DocumentCategory::all();
        if($template){
            $categories = DocumentCategory::where('page', $template)->get();
        }
        $path = 'templates/pages.json';
        $pagesJson = [];
        $sections = [];
        if (file_exists(resource_path($path))) {
            $fileJson = file_get_contents(resource_path($path));
            $pagesJson = json_decode($fileJson, true);
        }
        $selectedPage = $document->page;
        if ($pagesJson) {
            foreach ($pagesJson as $page) {
                if ($page['name'] === $selectedPage) {
                    $sections = $page['components'] ?? [];
                    break;
                }
            }
        }
        $document->published_date = \Carbon\Carbon::parse($document->published_at)->format('Y-m-d');
        return view('backend.document.edit', [
            'pages' => $pagesJson,
            'sections' => $sections,
            'document' => $document,
            'categories' => $categories,
            'template' => $template,
            'published_date' => $document->published_date
        ]);
    }

    /**
     * Update the specified document in storage.
     *
     * @param StoreDocumentRequest $request
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreDocumentRequest $request, Document $document, $template=null)
    {
        // Gate::authorize('admin.access.document.update');
    
        // Validate the request data
        $validatedData = $request->validated();
    
        // Begin a database transaction
        DB::beginTransaction();
    
        try {
            $document->page = $validatedData['page'];
            $document->section = $validatedData['section'];
            $document->category = $validatedData['category'] ?? null;
            $document->document_name_id = $validatedData['document_name_id'];
            $document->document_name_en = $validatedData['document_name_en'];
            $document->document_type = $validatedData['document_type'] ?? ''; // Use default or validate if needed
            $document->published_at = $validatedData['published_at'];
            $document->category_id = $validatedData['category_id'] ?? null;
            $document->description_id = $validatedData['description_id'] ?? null; // Use null if not provided
            $document->description_en = $validatedData['description_en'] ?? null;
            $document->language = $validatedData['language'] ?? null;
            $document->author = $validatedData['author'] ?? null;
            $document->publisher = $validatedData['publisher'] ?? null;
            $document->release_year = $validatedData['release_year'] ?? null;
            $document->pages = $validatedData['pages'] ?? null;
            $document->format = $validatedData['format'] ?? null;
            $document->alt_image = $validatedData['alt_image'] ?? null;
            $document->alt_image_en = $validatedData['alt_image_en'] ?? null;
            
            
            // Handle file uploads and removals
            if ($request->hasFile('document_file_id') || $request->input('remove_document_file_id')) {
                // Check if the remove flag is set
                if ($request->input('remove_document_file_id')) {
                    // Remove the old file if it exists
                    if ($document->document_file_id) {
                        Storage::disk('s3')->delete($document->document_file_id);
                        $document->document_file_id = null; // Clear the path
                    }
                }else if ($request->hasFile('document_file_id')) {
                    // Remove the old file if it exists
                    if ($document->document_file_id) {
                        Storage::disk('s3')->delete($document->document_file_id);
                    }
                    $documentFileId = $request->file('document_file_id');
                    $newDocumentFileIdName = $this->renameFile($documentFileId, $request->input('document_name_id'), 'id');
                    $document->document_file_id = $documentFileId->storeAs('images/post', $newDocumentFileIdName);
                }
            }
    
            if ($request->hasFile('document_file_en') || $request->input('remove_document_file_en')) {
                // Check if the remove flag is set
                if ($request->input('remove_document_file_en')) {
                    // Remove the old file if it exists
                    if ($document->document_file_en) {
                        Storage::disk('s3')->delete($document->document_file_en);
                        $document->document_file_en = null; // Clear the path
                    }
                }else if ($request->hasFile('document_file_en')) {
                    // Remove the old file if it exists
                    if ($document->document_file_en) {
                        Storage::disk('s3')->delete($document->document_file_en);
                    }
                    $documentFileEn = $request->file('document_file_en');
                    $newDocumentFileEnName = $this->renameFile($documentFileEn, $request->input('document_name_en'), 'en');
                    $document->document_file_en = $documentFileEn->storeAs('images/post', $newDocumentFileEnName);
                }
            }
            if ($request->hasFile('image') || $request->input('image') != null) {
                // Check if the remove flag is set
                if ($request->input('remove_image')) {
                    // Remove the old file if it exists
                    if ($document->image) {
                        Storage::disk('s3')->delete($document->image);
                        $document->image = null; // Clear the path
                    }
                } else if ($request->hasFile('image')) {
                    // Remove the old file if it exists
                    if ($document->image) {
                        Storage::disk('s3')->delete($document->image);
                        
                    }

                    $file = $request->image;
                    $document->image = $file->storePublicly('images/post');
                }
            }
            // Save the updated document
            $document->save();
    
            // Commit the transaction
            DB::commit();
        } catch (Exception $exception) {
            // Rollback the transaction on error
            Log::error($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors('There was a problem updating the document. Please try again.');
        }
    
        return redirect()->route('admin.document.index', ['template' => $request->template])
            ->with('flash_success', 'Document was successfully updated.');
    }
    

    /**
     * Remove the specified document from storage.
     *
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Document $document, $template=null)
    {
        // Gate::authorize('admin.access.document.delete');

        try {
            $document->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->withErrors('There was a problem deleting the document. Please try again.');
        }

        return redirect()->route('admin.document.index', ['template' => $template])
         ->with('flash_success', 'Document was successfully deleted.');
    }
    public function renames()
    {
        try {
            // Fetch all documents from the database
            $documents = Document::all();

            foreach ($documents as $document) {
                // Rename 'document_file_id' if it exists
                if ($document->document_file_id) {
                    $this->renameFiles(
                        $document->document_file_id,
                        'documents/' . $document->document_name_id . '_id.pdf'
                    );
                }

                // Rename 'document_file_en' if it exists
                if ($document->document_file_en) {
                    $this->renameFiles(
                        $document->document_file_en,
                        'documents/' . $document->document_name_en . '_en.pdf'
                    );
                }

                // Update database paths
                $document->update([
                    'document_file_id' => isset($document->document_file_id)
                        ? 'documents/' . $document->document_name_id . '_id.pdf'
                        : null,
                    'document_file_en' => isset($document->document_file_en)
                        ? 'documents/' . $document->document_name_en . '_en.pdf'
                        : null,
                ]);
            }

            return response()->json(['message' => 'All documents have been renamed successfully.']);
        } catch (\Exception $e) {
            Log::error('Error renaming documents: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while renaming documents.'], 500);
        }
    }
    public function rename(Document $document, $template=null)
    {
        try {
            if ($document->document_file_id) {
                $this->renameFiles(
                    $document->document_file_id,
                    'documents/' . $document->document_name_id . '_id.pdf'
                );
            }

            // Rename 'document_file_en' if it exists
            if ($document->document_file_en) {
                $this->renameFiles(
                    $document->document_file_en,
                    'documents/' . $document->document_name_en . '_en.pdf'
                );
            }

            // Update database paths
            $document->update([
                'document_file_id' => isset($document->document_file_id)
                    ? 'documents/' . $document->document_name_id . '_id.pdf'
                    : null,
                'document_file_en' => isset($document->document_file_en)
                    ? 'documents/' . $document->document_name_en . '_en.pdf'
                    : null
            ]);
            return response()->json(['message' => 'All documents have been renamed successfully.']);
        } catch (\Exception $e) {
            Log::error('Error renaming documents: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while renaming documents.'], 500);
        }
    }


    private function renameFiles($oldPath, $newPath)
    {
        if (Storage::exists($oldPath)) {
            Storage::move($oldPath, $newPath);
        }
    }
}