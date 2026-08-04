<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-cubes me-2"></i> Project Management</h5>
        <div>
            <a href="<?= site_url('projects_management/create'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus me-1"></i> New Project</a>
        </div>
    </div>
    <div class="card-body" style="overflow: visible;">
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

        <div class="table-responsive" style="overflow: visible;">
            <table class="table table-hover table-bordered align-middle mb-0" id="table-projects" style="min-height: 200px;">
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
                        <th width="60">Action</th>
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
                                    <?php
                                    $is_admin_user = isset($is_admin) && $is_admin;
                                    $is_project_pm = $is_admin_user || (isset($current_user_id) && $current_user_id == $p['pm_id']);
                                    ?>
                                    <div class="action-btn-wrapper position-relative">
                                        <button class="btn btn-sm btn-light border btn-action-toggle" type="button" title="Aksi">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <div class="action-popover shadow-lg">
                                            <a class="action-popover-item" href="<?= site_url('projects_management/detail/' . $p['id']); ?>" title="View">
                                                <span class="action-icon bg-info text-white"><i class="fa fa-eye"></i></span>
                                                <span class="action-label">View</span>
                                            </a>
                                            <?php if ($p['status'] !== 'Completed'): ?>
                                                <a class="action-popover-item" href="<?= site_url('projects_management/update/' . $p['id']); ?>" title="Isi Task">
                                                    <span class="action-icon bg-primary text-white"><i class="fa fa-pencil"></i></span>
                                                    <span class="action-label">Isi Task</span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($p['status'] === 'Planning' && $is_project_pm): ?>
                                                <a class="action-popover-item" href="<?= site_url('projects_management/edit/' . $p['id']); ?>" title="Edit Data">
                                                    <span class="action-icon bg-secondary text-white"><i class="fa fa-cog"></i></span>
                                                    <span class="action-label">Edit</span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($p['total_modules'] > 0 && $p['finished_modules'] >= $p['total_modules'] && $p['status'] !== 'Completed' && $is_project_pm): ?>
                                                <a class="action-popover-item btn-finish-project-list" href="javascript:void(0)" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="Finish Project">
                                                    <span class="action-icon bg-success text-white"><i class="fa fa-check-circle"></i></span>
                                                    <span class="action-label">Finish</span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($p['status'] !== 'Completed' && $p['status'] !== 'On Hold' && $is_project_pm): ?>
                                                <a class="action-popover-item btn-hold-project-list" href="javascript:void(0)" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="On Hold">
                                                    <span class="action-icon bg-warning text-dark"><i class="fa fa-pause"></i></span>
                                                    <span class="action-label">Hold</span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($p['status'] === 'On Hold' && $is_project_pm): ?>
                                                <a class="action-popover-item btn-resume-project-list" href="javascript:void(0)" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="Resume">
                                                    <span class="action-icon bg-info text-white"><i class="fa fa-play"></i></span>
                                                    <span class="action-label">Resume</span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($p['status'] === 'Planning' && $is_project_pm): ?>
                                                <a class="action-popover-item text-danger btn-delete-project" href="javascript:void(0)" data-id="<?= $p['id']; ?>" data-name="<?= html_escape($p['project_name']); ?>" title="Delete">
                                                    <span class="action-icon bg-danger text-white"><i class="fa fa-trash"></i></span>
                                                    <span class="action-label">Delete</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
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

<style>
    /* Action Popover Styles */
    .action-btn-wrapper {
        display: inline-block;
    }
    .btn-action-toggle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
    }
    .btn-action-toggle:hover,
    .btn-action-toggle.active {
        background: #e3e8f0;
        transform: rotate(90deg);
    }

    .action-popover {
        position: absolute;
        top: 50%;
        right: 100%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        background: #fff;
        border-radius: 30px;
        border: 1px solid #e0e0e0;
        white-space: nowrap;
        z-index: 2000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        margin-right: 8px;
    }
    .action-popover.show {
        opacity: 1;
        pointer-events: auto;
    }

    .action-popover-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(.4,2,.6,1);
        opacity: 0;
        transform: scale(0.3);
        position: relative;
    }
    .action-popover.show .action-popover-item {
        opacity: 1;
        transform: scale(1);
    }
    /* Staggered animation delays */
    .action-popover.show .action-popover-item:nth-child(1) { transition-delay: 0.03s; }
    .action-popover.show .action-popover-item:nth-child(2) { transition-delay: 0.06s; }
    .action-popover.show .action-popover-item:nth-child(3) { transition-delay: 0.09s; }
    .action-popover.show .action-popover-item:nth-child(4) { transition-delay: 0.12s; }
    .action-popover.show .action-popover-item:nth-child(5) { transition-delay: 0.15s; }
    .action-popover.show .action-popover-item:nth-child(6) { transition-delay: 0.18s; }
    .action-popover.show .action-popover-item:nth-child(7) { transition-delay: 0.21s; }

    .action-popover-item:hover {
        transform: scale(1.25) !important;
        z-index: 2;
    }
    .action-popover-item .action-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .action-popover-item .action-label {
        display: none;
    }

    /* Tooltip on hover */
    .action-popover-item::after {
        content: attr(title);
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s;
    }
    .action-popover-item:hover::after {
        opacity: 1;
    }
</style>

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

        // Toggle action popover
        $(document).on('click', '.btn-action-toggle', function(e) {
            e.stopPropagation();
            var $wrapper = $(this).closest('.action-btn-wrapper');
            var $popover = $wrapper.find('.action-popover');
            var isOpen = $popover.hasClass('show');

            // Close all other popovers
            $('.action-popover.show').removeClass('show');
            $('.btn-action-toggle.active').removeClass('active');

            if (!isOpen) {
                $popover.addClass('show');
                $(this).addClass('active');
            }
        });

        // Close popover when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.action-btn-wrapper').length) {
                $('.action-popover.show').removeClass('show');
                $('.btn-action-toggle.active').removeClass('active');
            }
        });

        // Delete project (soft delete, hanya Planning)
        $(document).on('click', '.btn-delete-project', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('.action-popover.show').removeClass('show');
            $('.btn-action-toggle.active').removeClass('active');
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
            $('.action-popover.show').removeClass('show');
            $('.btn-action-toggle.active').removeClass('active');
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
            $('.action-popover.show').removeClass('show');
            $('.btn-action-toggle.active').removeClass('active');
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
            $('.action-popover.show').removeClass('show');
            $('.btn-action-toggle.active').removeClass('active');
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