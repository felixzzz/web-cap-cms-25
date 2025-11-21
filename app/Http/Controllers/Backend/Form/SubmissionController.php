<?php

namespace App\Http\Controllers\Backend\Form;

use App\Http\Controllers\Controller;
use App\Models\Form\Form;
use App\Models\Form\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubmissionController extends Controller
{
    public function index(Form $form)
    {
        Gate::authorize('admin.access.form-submission.show');
        return view('backend.form.submission.index', compact('form'));
    }

    public function show(Form $form, Submission $submission)
    {
        Gate::authorize('admin.access.form-submission.show');
        return view('backend.form.submission.show', compact('form', 'submission'));
    }

    public function delete(Form $form, Submission $submission)
    {
        Gate::authorize('admin.access.form-submission.delete');
        $submission->delete();
        return back()->withFlashSuccess('The submission has been deleted');
    }
}
