<div class="modal fade" id="bannerEmbedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Embed Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="bannerEmbedTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-banner"
                            type="button" role="tab" aria-controls="new-banner" aria-selected="true">New
                            Banner</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-list"
                            type="button" role="tab" aria-controls="active-list" aria-selected="false">Active
                            Banners</button>
                    </li>
                </ul>
                <div class="tab-content" id="bannerEmbedTabContent">
                    <div class="tab-pane fade show active" id="new-banner" role="tabpanel" aria-labelledby="new-tab">
                        <form id="bannerEmbedForm">
                            <div class="mb-3">
                                <label for="embed_banner_group_id" class="form-label">Banner Group</label>
                                <select class="form-select" id="embed_banner_group_id" required>
                                    <option value="">Loading...</option>
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
                    <div class="tab-pane fade" id="active-list" role="tabpanel" aria-labelledby="active-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="activeBannersTable">
                                <thead>
                                    <tr>
                                        <th>Group</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBannerEmbed">Insert Banner</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editActiveBannerModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Active Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editActiveBannerForm">
                    <input type="hidden" id="edit_active_banner_id">
                    <div class="mb-3">
                        <label for="edit_start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" id="edit_start_date">
                    </div>
                    <div class="mb-3">
                        <label for="edit_end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" id="edit_end_date">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateActiveBannerBtn">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        let activeEditor = null;
        const bannerEmbedModal = document.getElementById('bannerEmbedModal');

        // Fetch Banner Groups on Modal Open
        bannerEmbedModal.addEventListener('show.bs.modal', function() {
            fetchBannerGroups();
            fetchActiveBanners();
        });

        // Listen for event to set active editor
        document.addEventListener('set-active-editor', (e) => {
            activeEditor = e.detail.editor;
        });

        function fetchBannerGroups() {
            fetch("{{ route('admin.banner.list-json') }}")
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('embed_banner_group_id');
                    select.innerHTML = '<option value="">Select Group</option>';
                    data.forEach(group => {
                        const option = document.createElement('option');
                        option.value = group.id;
                        option.textContent = `${group.title} (${group.items_count || 0} banners)`;
                        select.appendChild(option);
                    });
                })
                .catch(err => console.error("Error fetching groups:", err));
        }

        function fetchActiveBanners() {
            fetch("{{ route('admin.banner.active.list') }}")
                .then(res => res.json())
                .then(data => {
                    const tbody = document.querySelector('#activeBannersTable tbody');
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="5" class="text-center">No active banners found</td></tr>';
                        return;
                    }
                    data.forEach(banner => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${banner.group_title}</td>
                        <td>${banner.start_date}</td>
                        <td>${banner.end_date}</td>
                        <td><span class="badge ${banner.status === 'Active' ? 'bg-success' : 'bg-secondary'}">${banner.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-icon btn-light-primary edit-active-banner" 
                                data-id="${banner.id}" 
                                data-start="${formatDateForInput(banner.start_date)}" 
                                data-end="${formatDateForInput(banner.end_date)}">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                     `;
                        tbody.appendChild(tr);
                    });

                    // Bind edit buttons
                    document.querySelectorAll('.edit-active-banner').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const start = this.getAttribute('data-start');
                            const end = this.getAttribute('data-end');

                            document.getElementById('edit_active_banner_id').value = id;
                            document.getElementById('edit_start_date').value = start;
                            document.getElementById('edit_end_date').value = end;

                            const editModal = new bootstrap.Modal(document.getElementById(
                                'editActiveBannerModal'));
                            editModal.show();
                        });
                    });
                })
                .catch(err => console.error("Error fetching active banners:", err));
        }

        function formatDateForInput(dateStr) {
            if (!dateStr) return '';
            // Convert "Y-m-d H:i" to "Y-m-d\TH:i" for datetime-local input
            return dateStr.replace(' ', 'T');
        }

        document.getElementById('saveBannerEmbed').addEventListener('click', function() {
            // Only handle insertion if "New Banner" tab is active. 
            // If "Active Banners" tab is active, this button does nothing or could be hidden.
            const groupId = document.getElementById('embed_banner_group_id').value;
            const startDate = document.getElementById('embed_start_date').value;
            const endDate = document.getElementById('embed_end_date').value;

            if (!groupId) {
                Swal.fire({
                    text: "Please select a banner group",
                    icon: "warning",
                    confirmButtonText: "Ok"
                });
                return;
            }

            const formData = new FormData();
            formData.append('banner_group_id', groupId);
            if (startDate) formData.append('start_date', startDate);
            if (endDate) formData.append('end_date', endDate);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content'));

            fetch("{{ route('admin.banner.active.embedded') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (activeEditor) {
                            activeEditor.model.change(writer => {
                                const bannerElement = writer.createElement(
                                'embeddedBanner', {
                                    id: data.id,
                                    title: data.group_title,
                                    startDate: data.start_date,
                                    endDate: data.end_date
                                });
                                activeEditor.model.insertContent(bannerElement, activeEditor
                                    .model.document.selection);
                            });

                            // Close modal and refresh list
                            const modalEl = document.getElementById('bannerEmbedModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();

                            document.getElementById('bannerEmbedForm').reset();
                            fetchActiveBanners(); // Refresh list if reopened
                        }
                    } else {
                        Swal.fire({
                            text: data.message || 'Error saving',
                            icon: "error"
                        });
                    }
                })
                .catch(console.error);
        });

        // Update Active Banner
        document.getElementById('updateActiveBannerBtn').addEventListener('click', function() {
            const id = document.getElementById('edit_active_banner_id').value;
            const startDate = document.getElementById('edit_start_date').value;
            const endDate = document.getElementById('edit_end_date').value;

            const formData = new FormData();
            if (startDate) formData.append('start_date', startDate);
            if (endDate) formData.append('end_date', endDate);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content'));

            fetch("{{ route('admin.banner.active.update', ['id' => ':id']) }}".replace(':id', id), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const modalEl = document.getElementById('editActiveBannerModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                        fetchActiveBanners(); // Refresh the list
                        Swal.fire({
                            text: "Banner updated successfully",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: "Update failed",
                            icon: "error"
                        });
                    }
                })
                .catch(console.error);
        });
    });
</script>
