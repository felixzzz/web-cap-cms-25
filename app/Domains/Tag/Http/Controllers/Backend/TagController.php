<?php

namespace App\Domains\Tag\Http\Controllers\Backend;

use App\Domains\Tag\Http\Requests\TagRequest;
use App\Domains\Tag\Services\TagService;
use App\Exceptions\GeneralException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Tags\Tag;

/**
 * Class TagController.
 */
class TagController
{
    private TagService $tagService;
    private $heading;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
        $this->heading = 'Tags';
    }

    /**
     * @return mixed
     * */
    public function index()
    {
        Gate::authorize('admin.access.tag.read');

        return view('backend.tag.index',[
            'heading' => $this->heading
        ]);
    }

    /**
     * @return mixed
     */
    public function create(Request $request)
    {
        Gate::authorize('admin.access.tag.create');

        return view('backend.tag.create', [
            'heading' => $this->heading
        ]);
    }

    /**
     * @param  TagRequest  $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(TagRequest $request)
    {
        Gate::authorize('admin.access.tag.create');

        $model = $this->tagService->create($request->validated());
        return redirect()->route('admin.tag.show', $model)->withFlashSuccess(__("The $this->heading was successfully created."));
    }

    /**
     * @param  Tag $tag
     *
     * @return mixed
     */
    public function show(Tag $tag)
    {
        Gate::authorize('admin.access.tag.read');

        return view('backend.tag.show', [
            'model' => $tag,
            'heading' => $this->heading
        ]);
    }

    /**
     * @param  Tag $tag
     *
     * @return mixed
     */
    public function edit(Tag $tag)
    {
        Gate::authorize('admin.access.tag.update');

        return view('backend.tag.edit', [
            'model' => $tag,
            'heading' => $this->heading
        ]);
    }

    /**
     * @param  TagRequest $request
     * @param  Tag $tag
     *
     * @return mixed
     * @throws \Throwable
     */
    public function update(TagRequest $request, Tag $tag)
    {
        Gate::authorize('admin.access.tag.update', $tag);

        $model = $this->tagService->update($tag, $request->validated());
        return redirect()->route('admin.tag.show', $model)->withFlashSuccess(__("$this->heading was successfully updated."));
    }

    /**
     * @param  Request  $request
     * @param  Tag $tag
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function delete(Request $request, Tag $tag)
    {
        Gate::authorize('admin.access.tag.delete', $tag);

        if (!$this->tagService->deleteById($tag->id)) {
            throw new GeneralException("There was a problem deleting this $this->heading. Please try again.");
        };

        return redirect()->route('admin.tag.index')->withFlashSuccess(__($this->heading . $tag->id .' was successfully deleted.'));
    }

    /**
     * @return mixed
     */
    public function trashed()
    {
        Gate::authorize('admin.access.tag.read');

        return view('backend.tag.deleted');
    }

    /**
     * @param  Int  $deletedPostId
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function restore(Int $deletedPostId)
    {
        Gate::authorize('admin.access.tag.delete');

        $deletedPost = Tag::onlyTrashed()->findOrFail($deletedPostId);

        if (!$deletedPost->restore()) {
            throw new GeneralException(__("There was a problem restoring this $this->heading. Please try again."));
        }

        return redirect()->route('admin.tag.index')->withFlashSuccess(__($this->heading . $deletedPost->id .' was successfully restored.'));
    }

    /**
     * @param  Int  $deletedPostId
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function destroy(Int $deletedPostId)
    {
        Gate::authorize('admin.access.tag.delete');

        $deletedPost = Tag::onlyTrashed()->findOrFail($deletedPostId);

        Gate::authorize('admin.access.tag.delete', $deletedPost);

        if (!$deletedPost->forceDelete()) {
            throw new GeneralException(__("There was a problem permanently deleting this $this->heading. Please try again."));
        }

        return redirect()->route('admin.tag.deleted')->withFlashSuccess(__("$this->heading was permanently deleted."));
    }
}
