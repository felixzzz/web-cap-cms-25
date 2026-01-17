<div class="modal fade" id="bannerEmbedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Embed Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bannerEmbedForm">
                    <div class="mb-3">
                        <label for="embed_banner_group_id" class="form-label">Banner Group</label>
                        <select class="form-select" id="embed_banner_group_id" required>
                            <option value="">Select Group</option>
                            @if(isset($bannerGroups))
                                @foreach($bannerGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->title }} ({{ $group->banners_count ?? 0 }}
                                        banners)</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="embed_start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" id="embed_start_date">
                    </div>
                    <div class="mb-3">
                        <label for="embed_end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" id="embed_end_date">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBannerEmbed">Insert Banner</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        let activeEditor = null;

        // Listen for event to set active editor (triggered from editor script)
        document.addEventListener('set-active-editor', (e) => {
            activeEditor = e.detail.editor;
        });

        document.getElementById('saveBannerEmbed').addEventListener('click', function () {
            const groupId = document.getElementById('embed_banner_group_id').value;
            const startDate = document.getElementById('embed_start_date').value;
            const endDate = document.getElementById('embed_end_date').value;

            if (!groupId) {
                alert('Please select a banner group');
                return;
            }

            // AJAX call to store
            const formData = new FormData();
            formData.append('banner_group_id', groupId);
            if (startDate) formData.append('start_date', startDate);
            if (endDate) formData.append('end_date', endDate);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch("{{ route('admin.banner.active.embedded') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const shortcode = `###banner###${data.id}###banner###`;

                        if (activeEditor) {
                            activeEditor.model.change(writer => {
                                const insertPosition = activeEditor.model.document.selection.getFirstPosition();
                                writer.insertText(shortcode, insertPosition);
                            });

                            // Close modal
                            const modalEl = document.getElementById('bannerEmbedModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            modal.hide();

                            // Clear form
                            document.getElementById('bannerEmbedForm').reset();
                        } else {
                            console.error("No active editor found");
                        }
                    } else {
                        alert('Error saving banner configuration');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        });
    });
</script>