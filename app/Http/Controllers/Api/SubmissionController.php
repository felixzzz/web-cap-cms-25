<?php

namespace App\Http\Controllers\Api;

use App\Domains\Document\Models\Document;
use App\Domains\Document\Models\DocumentCategory;
use App\Domains\Document\Models\DownloadDocument;
use App\Domains\Post\Models\ContactUs;
use App\Http\Controllers\Controller;
use App\Models\Form\Form;
use App\Notifications\AutoReply\FormAdmin;
use App\Notifications\AutoReply\FormUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Domains\PostCategory\Models\Category;
use Illuminate\Support\Facades\Mail;
use App\Mail\WhistleblowingEmail;
use App\Mail\ContactusEmail;
use ZipArchive;
use Storage;

class SubmissionController extends Controller
{

    public function getFields(Form $form)
    {
        if ($form->field()->count() > 0) {
            return response()->json($form->field()->ordered()->get(), 200);
        }
        return response()->json(['message' => 'This form is not available'], 404);

    }

    public function submitForm(Request $request, Form $form)
    {
        if ($form->field()->count() > 0) {
            $request->validate($this->validate_field_request($form));
            $values = $this->masking_request_values($form, $request);
//            $findEmailUser = $values->where('type', 'input')->where('input', 'email')->first();
            $create = $form->submission()->create([
                'fields' => $values->toJson(),
            ]);
            if ($create) {
                $this->auto_reply_email($form, $create, $values);
                return response()->json([
                    'message' => 'You have submitted the form of '.$form->name
                ], 200);
            }

            return response()->json([
                'message' => 'Fail to make a submission, please try again later.'
            ], 500);
        }
        return response()->json(['message' => 'This form is not available'], 404);
    }

    /**
     * @param Form $form
     * @return array
     */
    public function validate_field_request(Form $form): array
    {
        $arr = [];
        $fields = $form->field()->ordered()->get(['name', 'type', 'input', 'is_required', 'options']);
        foreach ($fields as $field) {
            if ($field->is_required) {
                $arr[$field->name][] = 'required';
            } else {
                $arr[$field->name][] = 'nullable';
            }

            if ($field->type === 'select') {
                if (count($field->options) > 0) {
                    $options = collect($field->options);
                    $arr[$field->name][] = 'in:' . $options->pluck('key')->implode(',');
                }
            } else if ($field->type === 'input') {
                if ($field->input === 'text') {
                    $arr[$field->name][] = 'string';
                } else if ($field->input === 'email') {
                    $arr[$field->name][] = 'email';
                } else if ($field->input === 'number') {
                    $arr[$field->name][] = 'numeric';
                } else if ($field->input === 'file') {
                    $arr[$field->name][] = 'file';
                } else if ($field->input === 'image') {
                    $arr[$field->name][] = 'image';
                }
            } else if ($field->type === 'textarea') {
                $arr[$field->name][] = 'string';
            }
        }
        return $arr;
    }

    /**
     * @param Form $form
     * @param Request $request
     */
    public function masking_request_values(Form $form, Request $request)
    {
        $arr = [];
        $fields = $form->field()->whereIn('name', $request->keys())->get();
        foreach ($fields as $field) {
            if ($field->type === 'select') {
                $options = collect($field->options);
                $findOpt = $options->where('key', $request->{$field->name})->first();
                if ($findOpt) {
                    $arr[] = [
                        'key' => $field->label,
                        'value' => $findOpt->value,
                        'field' => $field->id,
                        'type' => $field->type,
                        'input' => $field->input
                    ];
                } else {
                    $arr[] = [
                        'key' => $field->label,
                        'value' => '-',
                        'field' => $field->id,
                        'type' => $field->type,
                        'input' => $field->input
                    ];
                }

            } else {
                $arr[] = [
                    'key' => $field->label,
                    'value' => $request->{$field->name},
                    'field' => $field->id,
                    'type' => $field->type,
                    'input' => $field->input
                ];
            }
        }
        return collect($arr);
    }

    /**
     * @param Form $form
     * @param \Illuminate\Database\Eloquent\Model $create
     * @param \Illuminate\Support\Collection $values
     * @return void
     */
    public function auto_reply_email(Form $form, \Illuminate\Database\Eloquent\Model $create, \Illuminate\Support\Collection $values): void
    {
        if ($form->auto_reply) {
            if ($form->admin_email != null) {
                Notification::route('mail', $form->admin_email)->notify(new FormAdmin($form, $create));
            }

            $findEmailUser = $values->where('type', 'input')->where('input', 'email')->first();
            if ($findEmailUser) {
                if ($findEmailUser['value'] != null) {
                    Notification::route('mail', $findEmailUser['value'])->notify(new FormUser($form));
                }
            }
        }
    }
    public function contactUs(Request $request){
        try {
            $validatedData = $request->validate([
                'type' => ['required', 'string'],
                'firstname' => ['required', 'string'],
                'lastname' => ['required', 'string'],
                'email' => ['required', 'string'],
                'topic_id' => ['required', 'integer'],
                'country' => ['required', 'string'],
                'message' => ['required', 'string'],
            ]);

            $contact = ContactUs::create($validatedData);

            $admin = Category::where('id', $validatedData['topic_id'])->first();
            if(isset($admin->description)){
                if($validatedData['type']== 'Whistleblowing'){
                    Mail::to($admin->description)->send(new WhistleblowingEmail($contact, $admin));
                }else{
                    Mail::to($admin->description)->send(new ContactusEmail($contact, $admin));
                }
            }
            return response()->json(['message' => 'Success', 'data' => $contact], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['message' => 'Validation failed', 'errors' => $errors], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function documents(Request $request)
    {
        // Validate the request parameters
        $request->validate([
            'page' => ['nullable', 'string'],
            'document_page' => ['nullable', 'string'],
            'category_id' => ['nullable', 'string'],
            'section' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1'], // Pagination limit
            'sort' => ['nullable', 'string'],
            'order' => ['nullable', 'string'],
            'year' => ['nullable', 'integer'],
            'month' => ['nullable', 'integer', 'between:1,12'],
        ]);

        // Set default pagination limit
        $perPage = $request->input('per_page', 15); // 15 items per page by default

        // Build the query
        $query = Document::with('category');

        // Apply filters
        if ($request->has('document_page')) {
            $query->where('page', $request->input('document_page'));
        }
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('section')) {
            $query->where('section', $request->input('section'));
        }

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('document_name_id', 'like', "%{$searchTerm}%")
                  ->orWhere('document_name_en', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->has('type')) {
            $query->where('format', $request->input('type'));
        }
        if ($request->has('year')) {
            $year = $request->input('year');
            $query->whereYear('published_at', $year);
        }
        if ($request->has('month')) {
            $month = $request->input('month');
            $query->whereMonth('published_at', $month);
        }
        if ($request->has('sort') && $request->has('order')) {
            $sort = $request->input('sort');
            $order = $request->input('order');
            $query->orderBy($sort, $order);
        }else{
            $query->orderBy('sort', 'ASC');
        }

        $documents = $query->paginate($perPage);
        return response()->json(['data' => $documents], 200);
    }
    public function documentBySession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'documents' => 'required|array'
        ]);

        $sessionId = $request->session_id;
        $documents = $request->documents;

        foreach ($documents as $doc) {
            $path = Document::where('id',$doc['document_id'])->first();
            $attributes = [
                'document_id' => $doc['document_id'],
                'session_id' => $sessionId,
                'status' => 'pending'
            ];

            if ($doc['lang'] == 'all') {
                if (!empty($path->document_file_id)) {
                    DownloadDocument::updateOrCreate(
                        array_merge($attributes, ['lang' => 'id']),
                        ['path' => $path->document_file_id]
                    );
                }

                if (!empty($path->document_file_en)) {
                    DownloadDocument::updateOrCreate(
                        array_merge($attributes, ['lang' => 'en']),
                        ['path' => $path->document_file_en]
                    );
                }
            } else {
                $pathValue = $doc['lang'] == 'id' ? $path->document_file_id : $path->document_file_en;
                if (!empty($pathValue)) {
                    DownloadDocument::updateOrCreate(
                        array_merge($attributes, ['lang' => $doc['lang']]),
                        ['path' => $pathValue]
                    );
                }
            }
        }

        return response()->json(['message' => 'Documents have been added or updated.'], 200);
    }
    public function getPendingDocumentBySession($sessionId)
    {
        $documents = DownloadDocument::where('session_id', $sessionId)
            ->where('status', 'pending')
            ->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'No pending documents found.'], 404);
        }

        return response()->json(['data' => $documents], 200);
    }
    public function bulkDocumentDownload(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $sessionId = $request->session_id;

        // Get pending documents
        $documents = DownloadDocument::where('session_id', $sessionId)
            // ->where('status', 'pending')
            ->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'No pending documents found for this session.'], 404);
        }

        $zip = new ZipArchive;
        $zipFileName = 'documents_' . $sessionId . '_' . now()->format('Y_m_d_H_i_s') . '.zip';
        $zipFilePath = public_path('doc_zip/' . $zipFileName);

        // Ensure the directory exists
        if (!file_exists(public_path('doc_zip'))) {
            mkdir(public_path('doc_zip'), 0755, true);
        }
        // Create the ZIP file
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($documents as $document) {
                $fileS3 = Storage::url($document->path);
                // $fileName = basename($fileS3);
                // $path = Storage::disk('local')->put($fileName, file_get_contents($fileS3));
                $fileContent = Storage::get($fileS3);
                if (!$fileContent) {
                    $fileContent = Storage::get($document->path);
                }
                $fileName = basename($fileS3);
                $tempPath = storage_path('app/temp/' . $fileName);

                // Ensure the temp directory exists
                if (!file_exists(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }

                // Save to a temporary file
                file_put_contents($tempPath, $fileContent);
                $fullLocalPath = storage_path('app/' . $fileName);
                $zip->addFile($tempPath, $fileName);
            }
            $zip->close();

            DownloadDocument::where('session_id', $sessionId)
                ->where('status', 'pending')
                ->update(['status' => 'success']);

            $zipFileUrl = asset('doc_zip/' . $zipFileName);
            return response()->json(['data' => $zipFileUrl], 200);
        } else {
            return response()->json(['message' => 'Could not create ZIP file.'], 500);
        }
    }
    public function documentsCategories(Request $request){

        $query = DocumentCategory::select('*');
        if ($request->has('document_page')) {
            $query->where('page', $request->input('document_page'));
        }
        if ($request->document_page =='sustainability_reports' || $request->document_page =='investor_reports') {
        }else{
            $query->where('section', $request->input('section'));
        }
        if ($request->document_page =='investor' || $request->document_page =='investor_publicatios' || $request->document_page =='sustainability_reports') {
            $data = $query->orderBy('sort','ASC')->get();
        }else{
            $data = $query->get();
        }

        return response()->json(['data' => $data], 200);
    }

    public function getPublishedYears()
    {
        $years = \DB::table('documents')
                    ->selectRaw('YEAR(published_at) as year')
                    ->where('page','investor_reports')
                    ->groupBy('year')
                    ->orderBy('year', 'desc')
                    ->pluck('year');

    $filteredYears = $years->filter(function ($year) {
        return $year !== null;
    });

        return response()->json(['data' => ['years' => $filteredYears]], 200);
    }
}
