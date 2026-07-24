<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    .tahapan-finish {
        background-color: #d4edda !important;
    }

    .tahapan-progress {}

    .module-header-row {
        background-color: #e3f2fd !important;
    }

    .badge-finish {
        background-color: #198754;
        color: #fff;
    }

    .badge-progress {
        background-color: #ffc107;
        color: #000;
    }
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-primary fs-6 me-2"><?= html_escape($project['project_code']); ?></span>
            <h5 class="d-inline fw-bold align-middle"><?= html_escape($project['project_name']); ?></h5>
            <span class="badge bg-info text-white ms-2"><?= html_escape($project['client_name']); ?></span>
        </div>
        <div>
            <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <!-- Project Info Summary -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-2">
                <small class="text-muted d-block">PM</small>
                <strong><?= html_escape($project['pm_name'] ? $project['pm_name'] : '-'); ?></strong>
            </div>
            <div class="col-6 col-md-2">
                <small class="text-muted d-block">Target Selesai</small>
                <strong><?= date('d M Y', strtotime($project['end_date'])); ?></strong>
            </div>
            <div class="col-6 col-md-2">
                <small class="text-muted d-block">Total Modul</small>
                <strong class="text-primary"><?= count($modules); ?></strong>
            </div>
            <div class="col-6 col-md-2">
                <small class="text-muted d-block">Modul Finish</small>
                <?php
                $finished_mod_count = 0;
                foreach ($modules as $m) {
                    if ($m['status'] === 'finish') $finished_mod_count++;
                }
                ?>
                <strong class="text-success"><?= $finished_mod_count; ?></strong>
            </div>
            <div class="col-6 col-md-2">
                <small class="text-muted d-block">Status</small>
                <?php
                $lbl = 'bg-secondary';
                if ($project['status'] == 'In Progress') $lbl = 'bg-warning text-dark';
                else if ($project['status'] == 'Completed') $lbl = 'bg-success';
                ?>
                <span class="badge <?= $lbl; ?>"><?= html_escape($project['status']); ?></span>
            </div>
        </div>

        <!-- Modules & Tahapan Table -->
        <?php if (!empty($modules)): ?>
            <?php $global_no = 0;
            foreach ($modules as $mod): ?>
                <div class="card border mb-4">
                    <div class="card-header module-header-row d-flex justify-content-between align-items-center py-2">
                        <div>
                            <strong class="text-primary fs-6"><i class="fa fa-cube me-1"></i> <?= html_escape($mod['module_name']); ?></strong>
                            <?php if ($mod['status'] === 'finish'): ?>
                                <span class="badge badge-finish ms-2"><i class="fa fa-check me-1"></i> Finish</span>
                            <?php else: ?>
                                <span class="badge badge-progress ms-2">Progress</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if ($mod['status'] !== 'finish' && $mod['all_finished']): ?>
                                <button type="button" class="btn btn-sm btn-success btn-finish-module" data-id="<?= $mod['id']; ?>" data-name="<?= html_escape($mod['module_name']); ?>">
                                    <i class="fa fa-check-circle me-1"></i> Select Finish
                                </button>
                            <?php elseif ($mod['status'] === 'finish'): ?>
                                <span class="text-success fw-bold"><i class="fa fa-check-circle"></i> Modul Selesai</span>
                            <?php else: ?>
                                <span class="text-muted small">(Selesaikan semua tahapan terlebih dahulu)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle mb-0">
                                <thead class="table text-center">
                                    <tr>
                                        <th width="40">No</th>
                                        <th>Tahapan</th>
                                        <th width="130">PIC</th>
                                        <th width="80">Plan MH</th>
                                        <th width="110">Plan Due Date</th>
                                        <th width="80">Aktual MH</th>
                                        <th width="110">Aktual Date</th>
                                        <th width="80">Status</th>
                                        <th width="160">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($mod['tahapan'])): foreach ($mod['tahapan'] as $t):
                                            $global_no++;
                                            $row_class = ($t['tahapan_status'] === 'finish') ? 'tahapan-finish' : 'tahapan-progress';
                                    ?>
                                            <tr class="<?= $row_class; ?>">
                                                <td class="text-center"><?= $global_no; ?></td>
                                                <td><span class="small"><?= html_escape($t['task_name']); ?></span></td>
                                                <td class="text-center small fw-bold"><?= html_escape($t['assignee_name'] ? $t['assignee_name'] : '-'); ?></td>
                                                <td class="text-center fw-bold"><?= $t['estimated_hours'] ? $t['estimated_hours'] : '-'; ?></td>
                                                <td class="text-center small"><?= $t['due_date'] ? date('d-M-Y', strtotime($t['due_date'])) : '-'; ?></td>
                                                <td class="text-center fw-bold text-primary"><?= $t['actual_manhour'] ? $t['actual_manhour'] : 0; ?></td>
                                                <td class="text-center small"><?= $t['actual_finish_date'] ? date('d-M-Y', strtotime($t['actual_finish_date'])) : ''; ?></td>
                                                <td class="text-center">
                                                    <?php if ($t['tahapan_status'] === 'finish'): ?>
                                                        <span class="badge badge-finish">Finish</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-progress">Progress</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($t['tahapan_status'] === 'finish'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info btn-view-pekerjaan" data-id="<?= $t['id']; ?>">
                                                            <i class="fa fa-eye me-1"></i> View
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info btn-view-pekerjaan me-1" data-id="<?= $t['id']; ?>">
                                                            <i class="fa fa-eye me-1"></i> View
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-input-pekerjaan" data-id="<?= $t['id']; ?>">
                                                            <i class="fa fa-pencil me-1"></i> Input
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="fa fa-info-circle me-1"></i> Belum ada modul pada project ini.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Input Pekerjaan -->
<div class="modal fade" id="modal-input-pekerjaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

<!-- Modal View Pekerjaan -->
<div class="modal fade" id="modal-view-pekerjaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Input Pekerjaan Button
        $(document).on('click', '.btn-input-pekerjaan', function() {
            var taskId = $(this).data('id');
            $.get('<?= site_url("projects_management/input_pekerjaan/"); ?>' + taskId, function(html) {
                $('#modal-input-pekerjaan .modal-content').html(html);
                var myModal = new bootstrap.Modal(document.getElementById('modal-input-pekerjaan'));
                myModal.show();
            });
        });

        // View Pekerjaan Button
        $(document).on('click', '.btn-view-pekerjaan', function() {
            var taskId = $(this).data('id');
            $.get('<?= site_url("projects_management/view_pekerjaan/"); ?>' + taskId, function(html) {
                $('#modal-view-pekerjaan .modal-content').html(html);
                var myModal = new bootstrap.Modal(document.getElementById('modal-view-pekerjaan'));
                myModal.show();
            });
        });

        // Finish Module
        $(document).on('click', '.btn-finish-module', function() {
            var moduleId = $(this).data('id');
            var moduleName = $(this).data('name');

            Swal.fire({
                title: 'Finish Modul?',
                text: 'Apakah Anda yakin ingin menandai modul "' + moduleName + '" sebagai FINISH? Aksi ini tidak bisa dibatalkan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-check"></i> Ya, Finish!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.post('<?= site_url("projects_management/finish_module"); ?>', {
                        module_id: moduleId
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
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

    });
</script>