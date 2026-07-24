<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-folder me-2"></i> Document & File Management</h5>
    </div>
    <div class="card-body">
        <!-- Form Upload Quick -->
        <div class="card border mb-4">
            <div class="card-header bg-light fw-bold">
                <i class="fa fa-upload me-1"></i> Upload Dokumen Project
            </div>
            <div class="card-body">
                <form id="form-doc-index-upload" enctype="multipart/form-data" class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold">Project Target:</label>
                        <select name="project_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Project --</option>
                            <?php if (!empty($projects)): foreach ($projects as $p): ?>
                                <option value="<?= $p['id']; ?>" <?= ($project_id == $p['id']) ? 'selected' : ''; ?>><?= html_escape($p['project_code'] . ' - ' . $p['project_name']); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold">Kategori Dokumen:</label>
                        <select name="category" class="form-select form-select-sm" required>
                            <option value="SRS / FSD">SRS / FSD</option>
                            <option value="User Manual">User Manual</option>
                            <option value="UAT Sign-Off">UAT Sign-Off</option>
                            <option value="Design Mockup">Design Mockup</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold">File (PDF, Doc, XLS, Gambar):</label>
                        <input type="file" name="document_file" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary w-100 mt-4"><i class="fa fa-upload me-1"></i> Upload File</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="<?= site_url('projects_management/documents'); ?>" class="row g-2 align-items-center mb-4">
            <div class="col-auto">
                <label class="col-form-label fw-bold me-1">Filter Project: </label>
            </div>
            <div class="col-auto">
                <select name="project_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Project --</option>
                    <?php if (!empty($projects)): foreach ($projects as $p): ?>
                        <option value="<?= $p['id']; ?>" <?= ($project_id == $p['id']) ? 'selected' : ''; ?>><?= html_escape($p['project_code'] . ' - ' . $p['project_name']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-auto ms-2">
                <label class="col-form-label fw-bold me-1">Kategori: </label>
            </div>
            <div class="col-auto">
                <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    <option value="SRS / FSD" <?= ($this->input->get('category') == 'SRS / FSD') ? 'selected' : ''; ?>>SRS / FSD</option>
                    <option value="User Manual" <?= ($this->input->get('category') == 'User Manual') ? 'selected' : ''; ?>>User Manual</option>
                    <option value="UAT Sign-Off" <?= ($this->input->get('category') == 'UAT Sign-Off') ? 'selected' : ''; ?>>UAT Sign-Off</option>
                    <option value="Design Mockup" <?= ($this->input->get('category') == 'Design Mockup') ? 'selected' : ''; ?>>Design Mockup</option>
                    <option value="Other" <?= ($this->input->get('category') == 'Other') ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-3">No</th>
                        <th>Kode Project</th>
                        <th>Nama File Dokumen</th>
                        <th>Kategori</th>
                        <th>Tipe File</th>
                        <th>Ukuran</th>
                        <th>Uploaded By</th>
                        <th>Tanggal Upload</th>
                        <th width="120" class="pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($documents)): $no = 1; foreach ($documents as $doc): ?>
                        <tr>
                            <td class="ps-3"><?= $no++; ?></td>
                            <td><span class="badge bg-primary"><?= html_escape($doc['project_code']); ?></span></td>
                            <td><strong><?= html_escape($doc['file_name']); ?></strong></td>
                            <td><span class="badge bg-info text-white"><?= html_escape($doc['category']); ?></span></td>
                            <td><span class="badge bg-secondary"><?= strtoupper(html_escape($doc['file_type'])); ?></span></td>
                            <td><?= round($doc['file_size'] / 1024, 1); ?> KB</td>
                            <td><?= html_escape($doc['uploader_name']); ?></td>
                            <td><?= date('d M Y H:i', strtotime($doc['created_at'])); ?></td>
                            <td class="pe-3">
                                <a href="<?= site_url('projects_management/documents/download/' . $doc['id']); ?>" class="btn btn-sm btn-outline-success"><i class="fa fa-download me-1"></i> Download</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="9" class="text-center p-4 text-muted">Belum ada dokumen terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#form-doc-index-upload').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '<?= site_url("projects_management/documents/upload"); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === 1) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.pesan, timer: 1500, showConfirmButton: false }).then(function() { location.reload(); });
                } else {
                    Swal.fire('Gagal', res.pesan, 'error');
                }
            }
        });
    });
});
</script>