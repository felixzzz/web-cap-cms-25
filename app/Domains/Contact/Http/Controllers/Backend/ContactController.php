<?php

namespace App\Domains\Contact\Http\Controllers\Backend;

use App\Domains\Post\Models\ContactUs;
use App\Domains\PostCategory\Models\Category;
use Illuminate\Support\Facades\Log;
use Exception;

class ContactController extends Controller
{
    /**
     * @param $type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view("backend.contact.index");
    }
    public function whistleblowing()
    {
        return view("backend.contact.whistleblowing");
    }
    public function whistleblowingDelete(ContactUs $contact)
    {
        try {
            $contact->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->withErrors('There was a problem deleting the data. Please try again.');
        }

        return redirect()->route('admin.whistleblowing.whistleblowing');
    }
    /**
     * @param Category $contact
     * @return mixed
     */
    public function show(ContactUs $contact)
    {
        $contact = $contact->load('topic');
        return view('backend.contact.show', compact('contact'));
    }
    public function delete(ContactUs $contact)
    {
        try {
            $contact->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->withErrors('There was a problem deleting the data. Please try again.');
        }

        return redirect()->route('admin.contact.index');
    }
}
