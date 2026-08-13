<?php $is_editable = ($tahapan['status'] === 'active'); ?>

<div class="modal-header bg-primary text-white py-2">
    <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-pencil me-1"></i> Isi Task / Pekerjaan</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-3" style="max-height: 75vh; overflow-y: auto;">
    <!-- Info Header -->
    <div class="card border mb-3">
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-6 col-md-4">
                    <small class="text-muted d-block">Modul</small>
                    <strong class="text-primary"><?= html_escape($tahapan['module_name']); ?></strong>
                </div>
                <div class="col-12 col-md-2">
                    <small class="text-muted d-block">Tahapan</small>
                    <strong><?= html_escape($tahapan['tahapan_name']); ?></strong>
                </div>
                <div class="col-6 col-md-2">
                    <small class="text-muted d-block">PIC</small>
                    <strong><?= html_escape($tahapan['pic_name'] ? $tahapan['pic_name'] : '-'); ?></strong>
                </div>
                <div class="col-4 col-md-1">
                    <small class="text-muted d-block">Plan MH</small>
                    <strong><?= $tahapan['plan_manhour']; ?></strong>
                </div>
                <div class="col-4 col-md-2">
                    <small class="text-muted d-block">Due Date</small>
                    <strong><?= $tahapan['plan_due_date'] ? date('d M Y', strtotime($tahapan['plan_due_date'])) : '-'; ?></strong>
                </div>
                <div class="col-4 col-md-1">
                    <small class="text-muted d-block">Aktual MH</small>
                    <strong class="text-success"><?= $total_manhour; ?></strong>
                </div>
            </div>
        </div>
    </div>

    <?php if ($is_editable): ?>
    <!-- Tombol Task Baru -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong class="small text-primary"><i class="fa fa-list me-1"></i> Task Baru</strong>
        <button type="button" class="btn btn-sm btn-dark" id="btn-add-task-row"><i class="fa fa-plus me-1"></i> Task</button>
    </div>

    <!-- Container task rows (dynamic) -->
    <form id="form-save-tasks">
        <input type="hidden" name="tahapan_id" value="<?= $tahapan['id']; ?>">
        <input type="hidden" name="module_id" value="<?= $tahapan['module_id']; ?>">
        <input type="hidden" name="project_id" value="<?= $tahapan['project_id']; ?>">
        <div id="task-rows-container"></div>
    </form>
    <?php endif; ?>

    <!-- Riwayat Task (grouped by version) -->
    <?php if (!empty($tasks_by_version)): ?>
        <div class="card border mb-3">
            <div class="card-header bg-light py-2">
                <strong class="small"><i class="fa fa-history me-1"></i> Riwayat Pekerjaan</strong>
            </div>
            <div class="card-body p-2">
                <?php $total_versions = count($tasks_by_version); ?>
                <?php foreach ($tasks_by_version as $version => $tasks): ?>
                <div class="mb-2">
                    <?php if ($total_versions > 1): ?>
                        <span class="badge bg-dark mb-1">Versi <?= $version; ?></span>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th width="90">Tanggal</th>
                                    <th>Aktivitas</th>
                                    <th width="50">MH</th>
                                    <th>Keterangan</th>
                                    <th width="120">File</th>
                                    <?php if ($is_editable): ?>
                                    <th width="100">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tasks as $t):
                                    $file_url = !empty($t['file_name_hash']) ? base_url('uploads/projects_management/' . $t['file_name_hash']) : '';
                                    $ext = !empty($t['file_name_hash']) ? strtolower(pathinfo($t['file_name_hash'], PATHINFO_EXTENSION)) : '';
                                    $is_image = in_array($ext, array('jpg', 'jpeg', 'png'));
                                ?>
                                <tr class="task-row" data-task-id="<?= $t['id']; ?>">
                                    <!-- VIEW MODE -->
                                    <td class="text-center small task-view-mode"><?= date('d M Y', strtotime($t['task_date'])); ?></td>
                                    <td class="small task-view-mode"><?= html_escape($t['task_description']); ?></td>
                                    <td class="text-center fw-bold task-view-mode"><?= $t['manhour']; ?></td>
                                    <td class="small task-view-mode"><?= html_escape($t['remarks'] ? $t['remarks'] : '-'); ?></td>
                                    <td class="text-center task-view-mode">
                                        <?php if (!empty($t['file_name_hash'])): ?>
                                            <?php if ($is_image): ?>
                                                <img src="<?= $file_url; ?>" class="viewer-img" style="width:28px;height:28px;object-fit:cover;cursor:pointer;border-radius:3px;" />
                                                <a href="<?= $file_url; ?>" download class="btn btn-sm btn-outline-success ms-1"><i class="fa fa-download"></i></a>
                                            <?php else: ?>
                                                <a href="<?= $file_url; ?>" download class="btn btn-sm btn-outline-success"><i class="fa fa-download"></i></a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <!-- EDIT MODE (hidden by default) -->
                                    <?php if ($is_editable): ?>
                                    <td class="task-edit-mode small" style="display:none;">
                                        <?= date('d M Y', strtotime($t['task_date'])); ?>
                                    </td>
                                    <td class="task-edit-mode" style="display:none;">
                                        <textarea class="form-control form-control-sm edit-description" rows="2"><?= html_escape($t['task_description']); ?></textarea>
                                    </td>
                                    <td class="task-edit-mode" style="display:none;">
                                        <input type="number" step="0.5" min="0.5" class="form-control form-control-sm edit-manhour" value="<?= $t['manhour']; ?>" style="width:65px;" />
                                    </td>
                                    <td class="task-edit-mode" style="display:none;">
                                        <textarea class="form-control form-control-sm edit-remarks" rows="2"><?= html_escape($t['remarks'] ? $t['remarks'] : ''); ?></textarea>
                                    </td>
                                    <td class="task-edit-mode" style="display:none;">
                                        <?php if (!empty($t['file_name_hash'])): ?>
                                            <div class="mb-1">
                                                <span class="badge bg-secondary small">
                                                    <i class="fa fa-file me-1"></i><?= html_escape($t['file_name_original'] ? $t['file_name_original'] : $t['file_name_hash']); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control form-control-sm edit-file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" />
                                        <small class="text-muted">Kosongkan jika tidak ganti</small>
                                    </td>
                                    <?php endif; ?>

                                    <?php if ($is_editable): ?>
                                    <td class="text-center">
                                        <!-- View mode buttons -->
                                        <span class="task-view-mode">
                                            <button type="button" class="btn btn-sm btn-outline-warning btn-edit-task" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-task" data-id="<?= $t['id']; ?>" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </span>
                                        <!-- Edit mode buttons -->
                                        <span class="task-edit-mode" style="display:none;">
                                            <button type="button" class="btn btn-sm btn-success btn-save-edit-task" title="Simpan">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary btn-cancel-edit-task" title="Batal">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Rollback History -->
    <?php if (!empty($rollback_history)): ?>
    <div class="card border border-warning mb-3">
        <div class="card-header bg-warning bg-opacity-10 p-2">
            <strong class="small text-warning"><i class="fa fa-undo me-1"></i> Riwayat Rollback</strong>
        </div>
        <div class="card-body p-2">
            <?php foreach ($rollback_history as $rh): ?>
            <div class="d-flex align-items-start gap-2 mb-2 p-2 border-bottom">
                <i class="fa fa-exclamation-circle text-warning mt-1"></i>
                <div class="small">
                    <strong><?= html_escape($rh['user_name']); ?></strong> mengembalikan dari 
                    <strong>Step <?= $rh['rolled_back_from_order']; ?> (<?= html_escape($rh['from_tahapan_name']); ?>)</strong>
                    ke <strong>Step <?= $rh['rolled_back_to_order']; ?> (<?= html_escape($rh['to_tahapan_name']); ?>)</strong>
                    <br>
                    <span class="text-muted"><?= date('d M Y H:i', strtotime($rh['created_at'])); ?></span>
                    <br>
                    <span class="text-danger">Alasan: <?= html_escape($rh['reason']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<div class="modal-footer py-2 bg-light">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <?php if ($is_editable): ?>
    <button type="button" class="btn btn-sm btn-primary fw-bold" id="btn-save-all-tasks" style="display:none;">
        <i class="fa fa-save me-1"></i> Save Semua Task
    </button>
    <?php endif; ?>
</div>

<script>
(function() {
    var tahapanId = '<?= $tahapan['id']; ?>';

    // Function to reload modal content without full page reload
    function reloadModalContent() {
        $.get('<?= site_url("projects_management/input_pekerjaan/"); ?>' + tahapanId, function(html) {
            $('#modal-tahapan-content').html(html);
        });
    }

    // Init Viewer.js on images
    var container = document.getElementById('riwayat-images-container');
    if (container && typeof Viewer !== 'undefined') {
        new Viewer(container, {
            toolbar: { zoomIn: true, zoomOut: true, rotateLeft: true, rotateRight: true, reset: true },
            navbar: false,
            title: true
        });
    }

    <?php if ($is_editable): ?>
    var taskRowCount = 0;

    function addTaskRow() {
        taskRowCount++;
        var idx = taskRowCount - 1;

        var html = '<div class="card border mb-2 task-row-card" data-idx="' + idx + '">';
        html += '<div class="card-body p-2">';
        html += '<div class="d-flex justify-content-between align-items-center mb-2">';
        html += '<span class="badge bg-dark">Task #' + taskRowCount + '</span>';
        html += '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-task-row"><i class="fa fa-times"></i></button>';
        html += '</div>';
        html += '<div class="row g-2">';
        html += '<div class="col-12 col-md-5">';
        html += '<textarea name="tasks[' + idx + '][task_description]" class="form-control form-control-sm" rows="2" placeholder="Aktivitas / deskripsi pekerjaan" required></textarea>';
        html += '</div>';
        html += '<div class="col-6 col-md-2">';
        html += '<input type="number" step="0.5" min="0.5" name="tasks[' + idx + '][manhour]" class="form-control form-control-sm" placeholder="Manhour" required />';
        html += '</div>';
        html += '<div class="col-6 col-md-2">';
        html += '<textarea name="tasks[' + idx + '][remarks]" class="form-control form-control-sm" rows="2" placeholder="Keterangan"></textarea>';
        html += '</div>';
        html += '<div class="col-12 col-md-3">';
        html += '<input type="file" name="task_files[' + idx + ']" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" />';
        html += '</div>';
        html += '</div>';
        html += '</div></div>';

        $('#task-rows-container').append(html);
        $('#btn-save-all-tasks').show();
    }

    $('#btn-add-task-row').click(function() {
        addTaskRow();
    });

    $(document).on('click', '.btn-remove-task-row', function() {
        $(this).closest('.task-row-card').remove();
        if ($('.task-row-card').length === 0) $('#btn-save-all-tasks').hide();
    });

    // === SAVE NEW TASKS (BULK) ===
    $('#btn-save-all-tasks').click(function() {
        var $rows = $('.task-row-card');
        if ($rows.length === 0) {
            Swal.fire('Perhatian', 'Tambahkan minimal 1 task.', 'warning');
            return;
        }

        var valid = true;
        $rows.find('input[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        if (!valid) {
            Swal.fire('Perhatian', 'Lengkapi semua field wajib.', 'warning');
            return;
        }

        var formData = new FormData(document.getElementById('form-save-tasks'));
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: '<?= site_url("projects_management/save_tasks_bulk"); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Semua Task');
                if (res.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.pesan,
                        timer: 1200,
                        showConfirmButton: false
                    }).then(function() {
                        reloadModalContent();
                    });
                } else {
                    Swal.fire('Gagal', res.pesan, 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Semua Task');
                Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
            }
        });
    });

    // === INLINE EDIT TASK ===
    $(document).on('click', '.btn-edit-task', function() {
        var $row = $(this).closest('tr.task-row');
        $row.find('.task-view-mode').hide();
        $row.find('.task-edit-mode').show();
    });

    $(document).on('click', '.btn-cancel-edit-task', function() {
        var $row = $(this).closest('tr.task-row');
        $row.find('.task-edit-mode').hide();
        $row.find('.task-view-mode').show();
    });

    $(document).on('click', '.btn-save-edit-task', function() {
        var $row = $(this).closest('tr.task-row');
        var taskId = $row.data('task-id');
        var desc = $row.find('.edit-description').val().trim();
        var mh = parseFloat($row.find('.edit-manhour').val());
        var remarks = $row.find('.edit-remarks').val().trim();
        var fileInput = $row.find('.edit-file')[0];

        if (!desc) {
            $row.find('.edit-description').addClass('is-invalid');
            return;
        } else {
            $row.find('.edit-description').removeClass('is-invalid');
        }
        if (!mh || mh <= 0) {
            $row.find('.edit-manhour').addClass('is-invalid');
            return;
        } else {
            $row.find('.edit-manhour').removeClass('is-invalid');
        }

        var formData = new FormData();
        formData.append('task_id', taskId);
        formData.append('task_description', desc);
        formData.append('manhour', mh);
        formData.append('remarks', remarks);

        if (fileInput && fileInput.files.length > 0) {
            formData.append('task_file', fileInput.files[0]);
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: '<?= site_url("projects_management/update_task"); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i>');
                if (res.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.pesan,
                        timer: 1200,
                        showConfirmButton: false
                    }).then(function() {
                        reloadModalContent();
                    });
                } else {
                    Swal.fire('Gagal', res.pesan, 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i>');
                Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
            }
        });
    });

    // === DELETE TASK ===
    $(document).on('click', '.btn-delete-task', function() {
        var taskId = $(this).data('id');
        Swal.fire({
            title: 'Hapus Task?',
            text: 'Task yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url("projects_management/delete_task"); ?>',
                    type: 'POST',
                    data: { task_id: taskId },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.pesan,
                                timer: 1200,
                                showConfirmButton: false
                            }).then(function() {
                                reloadModalContent();
                            });
                        } else {
                            Swal.fire('Gagal', res.pesan, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });
    <?php endif; ?>
})();
</script>
