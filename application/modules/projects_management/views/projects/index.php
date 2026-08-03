<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-cubes me-2"></i> Project Management</h5>
        <div>
            <a href="<?= site_url('projects_management/create'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus me-1"></i> New Project</a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Bar -->
        <form method="GET" action="<?= site_url('projects_management/master'); ?>" class="row g-2 align-items-center mb-4">
            <div class="col-auto">
                <label class="col-form-label fw-bold me-1">Status:</label>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="Planning" <?= ($this->input->get('status') == 'Planning') ? 'selected' : ''; ?>>Planning</option>
                    <option value="In Progress" <?= ($this->input->get('status') == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="On Hold" <?= ($this->input->get('status') == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                    <option value="Completed" <?= ($this->input->get('status') == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div class="col-auto ms-2">
                <label class="col-form-label fw-bold me-1">Client:</label>
            </div>
            <div class="col-auto">
                <select name="client_id" class="form-select form-select-sm">
                    <option value="">-- Semua Client --</option>
                    <?php if (!empty($clients)): foreach ($clients as $c): ?>
                            <option value="<?= $c['id']; ?>" <?= ($this->input->get('client_id') == $c['id']) ? 'selected' : ''; ?>><?= html_escape($c['name_app']); ?></option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>
            <div class="col-auto ms-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter me-1"></i> Filter</button>
                <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-secondary btn-sm ms-1"><i class="fa fa-refresh me-1"></i> Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0" id="table-projects">
                <thead class="table text-center">
                    <tr>
                        <th width="40">No</th>
                        <th>Client</th>
                        <th>Project</th>
                        <th>PM</th>
                        <th>Bisnis Analis</th>
                        <th>Programmer</th>
                        <th>QA</th>
                        <th width="80">Total Modul</th>
                        <th width="80">Modul Finish</th>
                        <th width="90">Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): ?>
                        <?php $no = 1;
                        foreach ($projects as $p): ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= html_escape($p['client_name'] ? $p['client_name'] : '-'); ?></td>
                                <td>
                                    <strong><?= html_escape($p['project_name']); ?></strong>
                                    <br><small class="text-muted"><?= html_escape($p['project_code']); ?></small>
                                </td>
                                <td><?= html_escape($p['pm_name'] ? $p['pm_name'] : '-'); ?></td>
                                <!-- Kolom Bisnis Analis -->
                                <td>
                                    <?php
                                    if (!empty($p['ba_names'])) {
                                        $ba_list = array_map('trim', explode(',', $p['ba_names']));
                                        if (count($ba_list) > 1) {
                                            echo '<ul class="mb-0 ps-3">';
                                            foreach ($ba_list as $ba) {
                                                echo '<li>' . html_escape($ba) . '</li>';
                                            }
                                            echo '</ul>';
                                        } else {
                                            echo html_escape($ba_list[0]);
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>

                                <!-- Kolom Programmer -->
                                <td>
                                    <?php
                                    if (!empty($p['programmer_names'])) {
                                        $prog_list = array_map('trim', explode(',', $p['programmer_names']));
                                        if (count($prog_list) > 1) {
                                            echo '<ul class="mb-0 ps-3">';
                                            foreach ($prog_list as $prog) {
                                                echo '<li>' . html_escape($prog) . '</li>';
                                            }
                                            echo '</ul>';
                                        } else {
                                            echo html_escape($prog_list[0]);
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?= html_escape(isset($p['qa_names']) && $p['qa_names'] ? $p['qa_names'] : '-'); ?></td>
                                <td class="text-center fw-bold"><?= $p['total_modules']; ?></td>
                                <td class="text-center fw-bold text-success"><?= $p['finished_modules']; ?></td>
                                <td class="text-center">
                                    <?php
                                    $lbl = 'bg-secondary';
                                    if ($p['status'] == 'In Progress') $lbl = 'bg-warning text-dark';
                                    elseif ($p['status'] == 'Completed') $lbl = 'bg-success';
                                    elseif ($p['status'] == 'On Hold') $lbl = 'bg-danger';
                                    elseif ($p['status'] == 'Planning') $lbl = 'bg-info text-white';
                                    ?>
                                    <span class="badge <?= $lbl; ?>"><?= html_escape($p['status']); ?></span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-1">
                                        <a href="<?= site_url('projects_management/detail/' . $p['id']); ?>" class="btn btn-sm btn-outline-info" title="View" style="width: 100px;">
                                            <i class="fa fa-eye me-1"></i> View
                                        </a>
                                        <?php if ($p['status'] !== 'Completed'): ?>
                                            <a href="<?= site_url('projects_management/update/' . $p['id']); ?>" class="btn btn-sm btn-outline-primary" title="Update" style="width: 100px;">
                                                <i class="fa fa-pencil me-1"></i> Update
                                            </a>
                                        <?php endif; ?>
                                        <?php
                                        $is_admin_user = isset($is_admin) && $is_admin;
                                        $is_project_pm = $is_admin_user || ($current_user_id == $p['pm_id']);
                                        ?>
                                            <a href="<?= site_url('projects_management/edit/' . $p['id']); ?>" class="btn btn-sm btn-outline-secondary" title="Edit Data" style="width: 100px;">
                                                <i class="fa fa-cog me-1"></i> Edit
                                            </a>
                                        <?php if ($p['status'] === 'Planning' && $is_project_pm): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-project" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="Delete" style="width: 100px;">
                                                <i class="fa fa-trash me-1"></i> Delete
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($p['total_modules'] > 0 && $p['finished_modules'] >= $p['total_modules'] && $p['status'] !== 'Completed' && $is_project_pm): ?>
                                            <button type="button" class="btn btn-sm btn-success btn-finish-project-list" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="Finish Project" style="width: 100px;">
                                                <i class="fa fa-check-circle me-1"></i> Finish
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($p['status'] !== 'Completed' && $p['status'] !== 'On Hold' && $is_project_pm): ?>
                                            <button type="button" class="btn btn-sm btn-outline-warning btn-hold-project-list" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="On Hold" style="width: 100px;">
                                                <i class="fa fa-pause me-1"></i> Hold
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($p['status'] === 'On Hold' && $is_project_pm): ?>
                                            <button type="button" class="btn btn-sm btn-outline-info btn-resume-project-list" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="Resume" style="width: 100px;">
                                                <i class="fa fa-play me-1"></i> Resume
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- DataTables handles empty state -->
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table-projects').DataTable({
            pageLength: 25,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                emptyTable: "Belum ada project terdaftar.",
                zeroRecords: "Tidak ada data yang cocok.",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });

        // Delete project (soft delete, hanya Planning)
        $(document).on('click', '.btn-delete-project', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Project?',
                html: 'Project <strong>"' + name + '"</strong> akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.post('<?= site_url("projects_management/delete_project"); ?>', {
                        project_id: id
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Dihapus!',
                                text: res.pesan,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', res.pesan, 'error');
                        }
                    }, 'json');
                }
            });
        });

        // Finish project (manual, semua modul harus sudah finish)
        $(document).on('click', '.btn-finish-project-list', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Finish Project?',
                html: 'Semua modul pada project <strong>"' + name + '"</strong> sudah selesai.<br>Tandai project sebagai <strong>Completed</strong>?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: '<i class="fa fa-check-circle me-1"></i> Finish Project',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.post('<?= site_url("projects_management/set_project_status"); ?>', {
                        project_id: id,
                        status: 'Completed'
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Project Completed!',
                                text: res.pesan,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', res.pesan, 'error');
                        }
                    }, 'json');
                }
            });
        });

        // Hold project
        $(document).on('click', '.btn-hold-project-list', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            Swal.fire({
                title: 'On Hold Project?',
                html: 'Project <strong>"' + name + '"</strong> akan di-pause.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                confirmButtonText: '<i class="fa fa-pause me-1"></i> On Hold',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.post('<?= site_url("projects_management/set_project_status"); ?>', {
                        project_id: id,
                        status: 'On Hold'
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'On Hold',
                                text: res.pesan,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', res.pesan, 'error');
                        }
                    }, 'json');
                }
            });
        });

        // Resume project
        $(document).on('click', '.btn-resume-project-list', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Resume Project?',
                text: 'Project akan dilanjutkan kembali.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0dcaf0',
                confirmButtonText: '<i class="fa fa-play me-1"></i> Resume',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.post('<?= site_url("projects_management/set_project_status"); ?>', {
                        project_id: id,
                        status: 'In Progress'
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Resumed',
                                text: res.pesan,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', res.pesan, 'error');
                        }
                    }, 'json');
                }
            });
        });
    });
</script>