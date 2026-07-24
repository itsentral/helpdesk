<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-primary fs-6 me-2"><?= html_escape($project['project_code']); ?></span>
            <h4 class="d-inline font-weight-bold align-middle"><?= html_escape($project['project_name']); ?></h4>
        </div>
        <div>
            <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-sm btn-secondary me-1"><i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar Project</a>
            <button type="button" class="btn btn-sm btn-success" id="btn-add-task-detail"><i class="fa fa-plus me-1"></i> Tambah Task</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-8">
                <p class="text-muted mb-2"><?= nl2br(html_escape($project['description'] ? $project['description'] : 'Tidak ada deskripsi.')); ?></p>
                <div class="row g-2 mt-2">
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Client / App:</small>
                        <strong><?= html_escape($project['client_name'] ? $project['client_name'] : '-'); ?></strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Project Manager:</small>
                        <strong><?= html_escape($project['pm_name'] ? $project['pm_name'] : '-'); ?></strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Start Date:</small>
                        <strong><?= date('d M Y', strtotime($project['start_date'])); ?></strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">End Date:</small>
                        <strong><?= date('d M Y', strtotime($project['end_date'])); ?></strong>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 bg-light p-3 rounded">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold">Overall Progress:</span>
                    <span class="fw-bold text-success"><?= $project['progress']; ?>%</span>
                </div>
                <div class="progress mb-2" style="height: 12px;">
                    <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: <?= $project['progress']; ?>%"></div>
                </div>
                <small class="text-muted d-block mb-2"><?= $project['completed_tasks']; ?> dari <?= $project['total_tasks']; ?> pekerjaan selesai</small>

                <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Budget:</small>
                    <strong class="text-primary">Rp <?= number_format($project['budget'], 0, ',', '.'); ?></strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-muted">Status:</small>
                    <?php
                    $lbl = 'bg-secondary';
                    if ($project['status'] == 'In Progress') $lbl = 'bg-warning text-dark';
                    else if ($project['status'] == 'Completed') $lbl = 'bg-success';
                    else if ($project['status'] == 'On Hold') $lbl = 'bg-danger';
                    ?>
                    <span class="badge <?= $lbl; ?>"><?= html_escape($project['status']); ?></span>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs Bootstrap 5 -->
        <ul class="nav nav-tabs mb-3" id="projectTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tab-tasks" type="button" role="tab"><i class="fa fa-tasks me-1"></i> Tasks (<?= count($tasks); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#tab-members" type="button" role="tab"><i class="fa fa-users me-1"></i> Team Members (<?= count($members); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="milestones-tab" data-bs-toggle="tab" data-bs-target="#tab-milestones" type="button" role="tab"><i class="fa fa-flag me-1"></i> Milestones (<?= count($milestones); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#tab-documents" type="button" role="tab"><i class="fa fa-file me-1"></i> Dokumen (<?= count($documents); ?>)</button>
            </li>
        </ul>

        <div class="tab-content" id="projectTabContent">
            <!-- TAB TASKS -->
            <div class="tab-pane fade show active" id="tab-tasks" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40" class="ps-3">No</th>
                                <th>Nama Task</th>
                                <th>Assignee</th>
                                <th>Prioritas</th>
                                <th>Milestone</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th width="140">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tasks)): $no = 1; foreach ($tasks as $t): ?>
                                <tr>
                                    <td class="ps-3"><?= $no++; ?></td>
                                    <td>
                                        <strong><?= html_escape($t['task_name']); ?></strong>
                                        <?php if (!empty($t['no_ticket'])): ?>
                                            <br /><span class="badge bg-info text-white"><i class="fa fa-ticket me-1"></i> Tiket: <?= html_escape($t['no_ticket']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= html_escape($t['assignee_name'] ? $t['assignee_name'] : 'Unassigned'); ?></td>
                                    <td>
                                        <?php
                                        $pr_lbl = 'bg-secondary';
                                        if ($t['priority'] == 'Urgent') $pr_lbl = 'bg-danger';
                                        else if ($t['priority'] == 'High') $pr_lbl = 'bg-warning text-dark';
                                        else if ($t['priority'] == 'Medium') $pr_lbl = 'bg-info text-white';
                                        ?>
                                        <span class="badge <?= $pr_lbl; ?>"><?= html_escape($t['priority']); ?></span>
                                    </td>
                                    <td><?= html_escape($t['milestone_title'] ? $t['milestone_title'] : '-'); ?></td>
                                    <td><?= $t['due_date'] ? date('d M Y', strtotime($t['due_date'])) : '-'; ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= html_escape($t['status']); ?></span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $t['progress']; ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?= $t['progress']; ?>%</small>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-muted">Belum ada task.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB MEMBERS -->
            <div class="tab-pane fade" id="tab-members" role="tabpanel">
                <div class="card border mb-3">
                    <div class="card-body bg-light">
                        <form id="form-add-member" class="row g-2 align-items-center">
                            <input type="hidden" name="project_id" value="<?= $project['id']; ?>">
                            <div class="col-auto">
                                <label class="col-form-label fw-bold me-1">Tambah Member:</label>
                            </div>
                            <div class="col-12 col-sm-4">
                                <select name="user_id" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih User --</option>
                                    <?php if (!empty($all_users)): foreach ($all_users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-3">
                                <select name="role" class="form-select form-select-sm" required>
                                    <option value="Programmer">Programmer</option>
                                    <option value="System Analyst / BA">System Analyst / BA</option>
                                    <option value="Quality Assurance">Quality Assurance</option>
                                    <option value="Project Manager">Project Manager</option>
                                    <option value="Member">Member</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus me-1"></i> Tambah Member</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40" class="ps-3">No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Role Project</th>
                                <th>Tgl Bergabung</th>
                                <th width="100" class="pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($members)): $no = 1; foreach ($members as $m): ?>
                                <tr>
                                    <td class="ps-3"><?= $no++; ?></td>
                                    <td><strong><?= html_escape($m['nm_lengkap'] ? $m['nm_lengkap'] : $m['username']); ?></strong></td>
                                    <td><?= html_escape($m['username']); ?></td>
                                    <td><span class="badge bg-primary"><?= html_escape($m['role']); ?></span></td>
                                    <td><?= date('d M Y H:i', strtotime($m['created_at'])); ?></td>
                                    <td class="pe-3">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-member" data-id="<?= $m['id']; ?>"><i class="fa fa-trash me-1"></i> Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="text-center p-4 text-muted">Belum ada member terdaftar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB MILESTONES -->
            <div class="tab-pane fade" id="tab-milestones" role="tabpanel">
                <div class="card border mb-3">
                    <div class="card-body bg-light">
                        <form id="form-add-milestone" class="row g-2 align-items-center">
                            <input type="hidden" name="project_id" value="<?= $project['id']; ?>">
                            <div class="col-auto">
                                <label class="col-form-label fw-bold me-1">Judul Milestone:</label>
                            </div>
                            <div class="col-12 col-sm-4">
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="Contoh: Phase 1 UAT Release" required>
                            </div>
                            <div class="col-12 col-sm-3">
                                <input type="date" name="target_date" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-plus me-1"></i> Tambah Milestone</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40" class="ps-3">No</th>
                                <th>Judul Milestone</th>
                                <th>Target Date</th>
                                <th>Status</th>
                                <th>Total Task</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($milestones)): $no = 1; foreach ($milestones as $ms): ?>
                                <tr>
                                    <td class="ps-3"><?= $no++; ?></td>
                                    <td><strong><?= html_escape($ms['title']); ?></strong></td>
                                    <td><?= date('d M Y', strtotime($ms['target_date'])); ?></td>
                                    <td><span class="badge bg-warning text-dark"><?= html_escape($ms['status']); ?></span></td>
                                    <td><?= $ms['completed_tasks']; ?> / <?= $ms['total_tasks']; ?> tasks done</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $ms['progress']; ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?= $ms['progress']; ?>%</small>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="text-center p-4 text-muted">Belum ada milestone.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB DOCUMENTS -->
            <div class="tab-pane fade" id="tab-documents" role="tabpanel">
                <div class="card border mb-3">
                    <div class="card-body bg-light">
                        <form id="form-upload-document" enctype="multipart/form-data" class="row g-2 align-items-center">
                            <input type="hidden" name="project_id" value="<?= $project['id']; ?>">
                            <div class="col-12 col-sm-3">
                                <select name="category" class="form-select form-select-sm" required>
                                    <option value="SRS / FSD">SRS / FSD</option>
                                    <option value="User Manual">User Manual</option>
                                    <option value="UAT Sign-Off">UAT Sign-Off</option>
                                    <option value="Design Mockup">Design Mockup</option>
                                    <option value="Other">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <input type="file" name="document_file" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-upload me-1"></i> Upload File</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40" class="ps-3">No</th>
                                <th>Nama Dokumen</th>
                                <th>Kategori</th>
                                <th>Tipe</th>
                                <th>Ukuran</th>
                                <th>Uploader</th>
                                <th>Tgl Upload</th>
                                <th width="120" class="pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($documents)): $no = 1; foreach ($documents as $doc): ?>
                                <tr>
                                    <td class="ps-3"><?= $no++; ?></td>
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
                                    <td colspan="8" class="text-center p-4 text-muted">Belum ada dokumen di-upload.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Task Detail -->
<div class="modal fade" id="modal-task-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#form-add-member').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= site_url("projects_management/add_member"); ?>', $(this).serialize(), function(res) {
            if (res.status === 1) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.pesan, timer: 1500, showConfirmButton: false }).then(function() { location.reload(); });
            } else {
                Swal.fire('Gagal', res.pesan, 'error');
            }
        }, 'json');
    });

    $('.btn-remove-member').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Member?',
            text: 'Yakin ingin menghapus member ini dari project?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: '<i class="fa fa-trash"></i> Hapus',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post('<?= site_url("projects_management/remove_member"); ?>', { id: id }, function(res) {
                    if (res.status === 1) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.pesan, timer: 1500, showConfirmButton: false }).then(function() { location.reload(); });
                    } else {
                        Swal.fire('Gagal', res.pesan, 'error');
                    }
                }, 'json');
            }
        });
    });

    $('#form-add-milestone').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= site_url("projects_management/milestones/create"); ?>', $(this).serialize(), function(res) {
            if (res.status === 1) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.pesan, timer: 1500, showConfirmButton: false }).then(function() { location.reload(); });
            } else {
                Swal.fire('Gagal', res.pesan, 'error');
            }
        }, 'json');
    });

    $('#form-upload-document').on('submit', function(e) {
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

    $('#btn-add-task-detail').click(function() {
        $.get('<?= site_url("projects_management/tasks/create?project_id=" . $project["id"]); ?>', function(html) {
            $('#modal-task-detail .modal-content').html(html);
            var myModal = new bootstrap.Modal(document.getElementById('modal-task-detail'));
            myModal.show();
        });
    });
});
</script>