# /posts/news Upload Logic Documentation

This document explains the technical flow of file uploads (specifically `featured_image`) when creating or updating a News post (`/posts/news`).

## 1. Overview

The upload process uses a **FilePond** implementation where files are first uploaded to a temporary location, and then moved to the final storage (Spatie MediaLibrary) upon form submission.

**Techniques Involved:**

-   **Frontend**: FilePond (uploads to temporary endpoint).
-   **Backend Controller**: `PostController`.
-   **Service Layer**: `PostService` -> `CrudService`.
-   **Storage**: `InteractsWithMedia` (Spatie MediaLibrary).

---

## 2. Detailed Flow

### Step 1: Frontend Upload (Temporary)

1.  User selects a file in the FilePond widget.
2.  FilePond sends an async request to a temporary upload endpoint (e.g., `/api/upload` or similar).
3.  The backend saves the file to a temporary location and creates a `TemporaryUpload` record.
4.  The backend returns the `id` of the `TemporaryUpload` record.
5.  The frontend embeds this `id` into the form input named `featured_image`.

### Step 2: Form Submission

The user submits the form to the backend route.

-   **Route**: `POST /admin/posts/news` (mapped to `admin.post.store`) or `PATCH /admin/posts/news/{id}` (mapped to `admin.post.update`).
-   **Controller**: `App\Domains\Post\Http\Controllers\Backend\PostController`

### Step 3: Controller Processing

In `PostController::store` (or `update`):

1.  **Validation**: The request is validated. `featured_image` is expected to be present (standard input).

    ```php
    // PostController.php
    $requestValidated = Validator::make($request->all(), $rules)->validate();
    ```

2.  **Service Delegation**: The controller calls `PostService`.
    ```php
    // PostController.php
    $post = (new PostService())->create_post_handler($requestValidated, $type['type']);
    ```

### Step 4: Service Resolution (`PostService`)

In `App\Domains\Post\Services\PostService`:

1.  **Handler**: `create_post_handler` (or `update_post_handler`) checks for `featured_image` in the data.
    ```php
    // PostService.php
    if (isset($data['featured_image'])) {
        $this->filepond_resolver($data['featured_image'], 'featured_image', $post);
    }
    ```
2.  **Parent Method**: `filepond_resolver` is inherited from `App\Services\Antikode\CrudService`.

### Step 5: Final Move (`CrudService`)

In `App\Services\Antikode\CrudService`:

1.  **Find Temporary Record**: It looks up the `TemporaryUpload` model using the ID from the request.
    ```php
    // CrudService.php
    $temp = TemporaryUpload::where('id', $id)->first();
    ```
2.  **Move Media**: It moves the media file from the `TemporaryUpload` model to the target `Post` model.
    ```php
    // CrudService.php
    $move = $temp->getFirstMedia()->move($model, $targetCollection); // $targetCollection is 'featured_image'
    ```
3.  **Cleanup**: The `TemporaryUpload` record is deleted.
    ```php
    // CrudService.php
    if ($move) {
        $temp->delete();
    }
    ```

## 3. Database & Models

-   **Post Model**: `App\Domains\Post\Models\Post`
    -   Stores the post data.
    -   Linked to `media` table via Spatie MediaLibrary.
-   **TemporaryUpload Model**: `App\Models\Extra\TemporaryUpload`
    -   Temporarily holds the file reference before it is associated with the Post.
    -   Also uses Spatie MediaLibrary.

## 4. Summary

The `featured_image` sent in the main Post request is **not** the file binary itself, but a **Temporary ID**. The `PostService` uses this ID to "claim" the file from the temporary holding area and permanently attach it to the Post.
