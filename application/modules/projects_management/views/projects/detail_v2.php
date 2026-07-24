<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Viewer.js for image preview in modals -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<style>
    .tahapan-finish {
        background-color: #d4edda !important;
    }

    .tahapan-active {
        background-color: #fff3cd !important;
    }

    .tahapan-locked {
        background-color: #f8f9fa !important;
        opacity: 0.7;
    }

    .toggle-header {
        cursor: pointer;
        transition: background 0.2s, box-shadow 0.2s;
        border-radius: 8px;
        padding: 10px 16px !important;
    }

    .toggle-header:hover {
        filter: brightness(0.97);
    }

    .toggle-header .toggle-chevron {
        transition: transform 0.3s;
        width: 18px;
        text-align: center;
    }

    .toggle-header[aria-expanded="false"] .toggle-chevron {
        transform: rotate(-90deg);
    }

    /* --- Module header: netral, status cukup ditunjukkan lewat badge --- */
    .module-header {
        background: #f8f9fc;
        border: 1px solid #e9ecef;
    }

    .module-header:hover {
        background: #f1f3f8;
    }

    .module-header .module-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #e7f1ff;
        color: #0d6efd;
        margin-right: 8px;
        font-size: 12px;
    }

    .module-header.is-finish .module-icon {
        background: #e6f7ec;
        color: #198754;
    }

    .select2-container {
        width: 100% !important;
    }

    /* --- Info stat cards (PM s/d Finish) --- */
    .info-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    @media (max-width: 991px) {
        .info-stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .info-stat-card {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 1px solid #eef0f3;
        border-radius: 10px;
        padding: 10px 12px;
        transition: box-shadow .2s, transform .2s;
    }

    .info-stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        transform: translateY(-1px);
    }

    .info-stat-icon {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .info-stat-label {
        font-size: 11px;
        color: #8a93a3;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 1px;
    }

    .info-stat-value {
        font-size: 13.5px;
        font-weight: 700;
        color: #212529;
        line-height: 1.25;
    }

    /* Palet per-role, dipakai juga untuk badge di tabel anggota */
    .role-pm .info-stat-icon,
    .badge-role-pm {
        background: #eef0ff;
        color: #5b5fef;
    }

    .role-ba .info-stat-icon,
    .badge-role-ba {
        background: #e6f6fd;
        color: #0ea5e9;
    }

    .role-prog .info-stat-icon,
    .badge-role-prog {
        background: #f2ecfd;
        color: #8b5cf6;
    }

    .role-qa .info-stat-icon,
    .badge-role-qa {
        background: #e6faf5;
        color: #14b8a6;
    }

    .role-date .info-stat-icon {
        background: #fef3e2;
        color: #f59e0b;
    }

    .role-mod .info-stat-icon {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .role-fin .info-stat-icon {
        background: #e6f7ec;
        color: #198754;
    }

    .badge-role-pm,
    .badge-role-ba,
    .badge-role-prog,
    .badge-role-qa {
        font-weight: 600;
        font-size: 11px;
        padding: 5px 10px;
        border-radius: 20px;
    }

    /* --- Anggota Project section --- */
    .team-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        margin-right: 8px;
        flex-shrink: 0;
    }

    .team-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        border: 1px solid #f0f1f4;
        border-radius: 8px;
        margin-bottom: 6px;
        background: #fff;
    }

    .team-row:hover {
        background: #fafbfd;
    }
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">

            <!-- SISI KIRI: Info Proyek (Sejajar Kesamping) -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- Kode Proyek -->
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6 px-2 py-1.5 fw-semibold">
                    <?= html_escape($project['project_code']); ?>
                </span>

                <!-- Nama Proyek -->
                <h5 class="mb-0 fw-bold text-dark me-1 align-middle">
                    <?= html_escape($project['project_name']); ?>
                </h5>

                <!-- Separator Bar (Pemisah Visual Ringan) -->
                <span class="text-black-50 d-none d-sm-inline">|</span>

                <!-- Nama Klien -->
                <span class="badge bg-light text-dark border align-middle px-2 py-1.5">
                    <i class="fa fa-building text-muted me-1"></i>
                    <?= html_escape($project['client_name']); ?>
                </span>

                <!-- Status Proyek -->
                <?php
                $status_config = [
                    'In Progress' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                    'Completed'   => 'bg-success-subtle text-success border-success-subtle',
                    'On Hold'     => 'bg-danger-subtle text-danger border-danger-subtle',
                    'Planning'    => 'bg-info-subtle text-info-emphasis border-info-subtle',
                ];
                $st_class = $status_config[$project['status']] ?? 'bg-secondary-subtle text-secondary border-secondary-subtle';
                ?>
                <span class="badge border rounded-pill align-middle px-2 py-1.5 <?= $st_class; ?>">
                    <i class="fa fa-circle fs-8 me-1"></i>
                    <?= html_escape($project['status']); ?>
                </span>
            </div>

            <!-- SISI KANAN: Tombol Aksi -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <?php
                $fin = 0;
                foreach ($modules as $m) {
                    if ($m['status'] === 'finish') $fin++;
                }
                $all_modules_finished = (!empty($modules) && $fin === count($modules));
                $is_on_hold = ($project['status'] === 'On Hold');
                $is_completed = ($project['status'] === 'Completed');
                $is_readonly = isset($readonly) && $readonly;
                $is_admin = isset($is_admin) && $is_admin;
                $is_pm = $is_admin || (isset($current_user_id) && isset($project['pm_id']) && $current_user_id == $project['pm_id']);
                ?>

                <?php if (!$is_completed && !$is_on_hold): ?>
                    <button type="button" class="btn btn-sm btn-primary shadow-sm" id="btn-add-module">
                        <i class="fa fa-plus me-1"></i> Add Modul
                    </button>
                <?php endif; ?>

                <?php if (!$is_readonly && $is_pm): ?>
                    <?php if ($all_modules_finished && !$is_completed): ?>
                        <button type="button" class="btn btn-sm btn-success shadow-sm" id="btn-finish-project">
                            <i class="fa fa-check-circle me-1"></i> Finish Project
                        </button>
                    <?php endif; ?>

                    <?php if (!$is_completed): ?>
                        <?php if ($is_on_hold): ?>
                            <button type="button" class="btn btn-sm btn-info text-white shadow-sm" id="btn-resume-project">
                                <i class="fa fa-play me-1"></i> Resume
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark shadow-sm" id="btn-onhold-project">
                                <i class="fa fa-pause me-1"></i> On Hold
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-sm btn-outline-secondary shadow-sm">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

        </div>
    </div>
    <div class="card-body">
        <!-- Project Info -->
        <!-- Project Info -->
        <div class="info-stat-grid mb-4">
            <div class="info-stat-card role-pm">
                <span class="info-stat-icon"><i class="fa fa-user-tie"></i></span>
                <div>
                    <div class="info-stat-label">PM</div>
                    <div class="info-stat-value"><?= html_escape($project['pm_name'] ? $project['pm_name'] : '-'); ?></div>
                </div>
            </div>
            <div class="info-stat-card role-ba">
                <span class="info-stat-icon"><i class="fa fa-chart-line"></i></span>
                <div>
                    <div class="info-stat-label">Bisnis Analis</div>
                    <div class="info-stat-value"><?= html_escape($project['ba_names'] ? $project['ba_names'] : '-'); ?></div>
                </div>
            </div>
            <div class="info-stat-card role-prog">
                <span class="info-stat-icon"><i class="fa fa-code"></i></span>
                <div>
                    <div class="info-stat-label">Programmer</div>
                    <div class="info-stat-value"><?= html_escape($project['programmer_names'] ? $project['programmer_names'] : '-'); ?></div>
                </div>
            </div>
            <div class="info-stat-card role-qa">
                <span class="info-stat-icon"><i class="fa fa-vial"></i></span>
                <div>
                    <div class="info-stat-label">QA</div>
                    <div class="info-stat-value"><?= html_escape($project['qa_names'] ? $project['qa_names'] : '-'); ?></div>
                </div>
            </div>
            <div class="info-stat-card role-date">
                <span class="info-stat-icon"><i class="fa fa-calendar-check"></i></span>
                <div>
                    <div class="info-stat-label">Target Selesai</div>
                    <div class="info-stat-value"><?= date('d M Y', strtotime($project['end_date'])); ?></div>
                </div>
            </div>
            <div class="info-stat-card role-mod">
                <span class="info-stat-icon"><i class="fa fa-cubes"></i></span>
                <div>
                    <div class="info-stat-label">Modul</div>
                    <div class="info-stat-value"><?= count($modules); ?></div>
                </div>
            </div>
            <div class="info-stat-card role-fin">
                <span class="info-stat-icon"><i class="fa fa-flag-checkered"></i></span>
                <?php $fin = 0;
                foreach ($modules as $m) {
                    if ($m['status'] === 'finish') $fin++;
                } ?>
                <div>
                    <div class="info-stat-label">Finish</div>
                    <div class="info-stat-value"><?= $fin; ?> / <?= count($modules); ?></div>
                </div>
            </div>
        </div>

        <!-- ========== ANGGOTA PROJECT (Toggle) ========== -->
        <div class="card border mb-4">
            <div class="card-header toggle-header bg-light py-2 d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" data-bs-target="#team-section" aria-expanded="false">
                <span class="fw-bold small">
                    <i class="fa fa-users me-1 text-primary"></i> Anggota Project
                    <span class="badge bg-secondary ms-1"><?= count($ba_users) + count($prog_users) + count($qa_users); ?></span>
                </span>
                <i class="fa fa-chevron-down toggle-chevron text-muted"></i>
            </div>
            <div class="collapse" id="team-section">
                <div class="card-body p-3">
                    <?php if (!$is_readonly && $is_pm): ?>
                        <form id="form-add-role-member" class="row g-2 align-items-end mb-3">
                            <input type="hidden" name="project_id" value="<?= $project['id']; ?>">
                            <div class="col-12 col-md-5">
                                <label class="form-label small fw-bold mb-1">Pilih User</label>
                                <select name="user_id" id="select-add-member" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih User --</option>
                                    <?php
                                    $all_users = $this->db->get_where('users', array('st_aktif' => 1))->result_array();
                                    foreach ($all_users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold mb-1">Role</label>
                                <select name="role" class="form-select form-select-sm" required>
                                    <option value="ba">Bisnis Analis</option>
                                    <option value="programmer">Programmer</option>
                                    <option value="qa">QA</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fa fa-plus me-1"></i> Tambah Anggota</button>
                            </div>
                        </form>
                    <?php endif; ?>

                    <div class="team-list">
                        <?php foreach ($ba_users as $u): ?>
                            <div class="team-row">
                                <div class="d-flex align-items-center">
                                    <span class="team-avatar badge-role-ba"><?= strtoupper(substr($u['nm_lengkap'], 0, 1)); ?></span>
                                    <span class="fw-semibold small"><?= html_escape($u['nm_lengkap']); ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-role-ba">Bisnis Analis</span>
                                    <?php if (!$is_readonly && $is_pm): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-role" data-id="<?= $u['role_id']; ?>"><i class="fa fa-trash"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach ($prog_users as $u): ?>
                            <div class="team-row">
                                <div class="d-flex align-items-center">
                                    <span class="team-avatar badge-role-prog"><?= strtoupper(substr($u['nm_lengkap'], 0, 1)); ?></span>
                                    <span class="fw-semibold small"><?= html_escape($u['nm_lengkap']); ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-role-prog">Programmer</span>
                                    <?php if (!$is_readonly && $is_pm): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-role" data-id="<?= $u['role_id']; ?>"><i class="fa fa-trash"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach ($qa_users as $u): ?>
                            <div class="team-row">
                                <div class="d-flex align-items-center">
                                    <span class="team-avatar badge-role-qa"><?= strtoupper(substr($u['nm_lengkap'], 0, 1)); ?></span>
                                    <span class="fw-semibold small"><?= html_escape($u['nm_lengkap']); ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-role-qa">QA</span>
                                    <?php if (!$is_readonly && $is_pm): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-role" data-id="<?= $u['role_id']; ?>"><i class="fa fa-trash"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($ba_users) && empty($prog_users) && empty($qa_users)): ?>
                            <div class="text-center text-muted py-3 small">Belum ada anggota.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== MODULES & TAHAPAN ========== -->
        <?php if (!empty($modules)): ?>
            <?php foreach ($modules as $mod_idx => $mod): ?>
                <div class="card border mb-3">
                    <div class="card-header toggle-header module-header <?= ($mod['status'] === 'finish') ? 'is-finish' : ''; ?> d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#module-collapse-<?= $mod['id']; ?>"
                        aria-expanded="<?= ($mod['status'] !== 'finish') ? 'true' : 'false'; ?>">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-chevron-down toggle-chevron me-2 text-muted"></i>
                            <span class="module-icon"><i class="fa fa-cube"></i></span>
                            <strong class="text-dark"><?= html_escape($mod['module_name']); ?></strong>
                            <?php if ($mod['status'] === 'finish'): ?>
                                <span class="badge bg-success ms-2"><i class="fa fa-check me-1"></i> Finish</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark ms-2"><?= $mod['finished_tahapan']; ?>/<?= $mod['total_tahapan']; ?> tahapan</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if (!$is_readonly && $is_pm && $mod['status'] !== 'finish' && $mod['all_finished']): ?>
                                <button type="button" class="btn btn-sm btn-success btn-finish-module" data-id="<?= $mod['id']; ?>" data-name="<?= html_escape($mod['module_name']); ?>">
                                    <i class="fa fa-check-circle me-1"></i> Modul Finish
                                </button>
                            <?php endif; ?>
                            <?php if (!$is_readonly && $is_pm && $mod['status'] !== 'finish'): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-module" data-id="<?= $mod['id']; ?>" data-name="<?= html_escape($mod['module_name']); ?>">
                                    <i class="fa fa-trash"></i> Hapus Modul
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="collapse <?= ($mod['status'] !== 'finish') ? 'show' : ''; ?>" id="module-collapse-<?= $mod['id']; ?>">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle mb-0">
                                    <thead class="table text-center small">
                                        <tr>
                                            <th width="35">No</th>
                                            <th>Tahapan</th>
                                            <th width="110">PIC</th>
                                            <th width="55">Plan MH</th>
                                            <th width="85">Due Date</th>
                                            <th width="55">Aktual MH</th>
                                            <th width="85">Aktual Date</th>
                                            <th width="65">Status</th>
                                            <th width="155">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($mod['tahapan'] as $t):
                                            $row_class = '';
                                            if ($t['status'] === 'finish') $row_class = 'tahapan-finish';
                                            elseif ($t['status'] === 'active') $row_class = 'tahapan-active';
                                            else $row_class = 'tahapan-locked';
                                        ?>
                                            <tr class="<?= $row_class; ?>">
                                                <td class="text-center small"><?= $t['tahapan_order']; ?></td>
                                                <td class="small"><?= html_escape($t['tahapan_name']); ?></td>
                                                <td class="text-center small fw-bold"><?= html_escape($t['pic_name'] ? $t['pic_name'] : '-'); ?></td>
                                                <td class="text-center fw-bold"><?= $t['plan_manhour'] > 0 ? $t['plan_manhour'] : '-'; ?></td>
                                                <td class="text-center small"><?= $t['plan_due_date'] ? date('d-M-y', strtotime($t['plan_due_date'])) : '-'; ?></td>
                                                <td class="text-center fw-bold text-primary"><?= $t['actual_manhour'] > 0 ? $t['actual_manhour'] : '0'; ?></td>
                                                <td class="text-center small"><?= $t['actual_finish_date'] ? date('d-M-y', strtotime($t['actual_finish_date'])) : ''; ?></td>
                                                <td class="text-center">
                                                    <?php if ($t['status'] === 'finish'): ?>
                                                        <span class="badge bg-success" style="font-size:10px;">Finish</span>
                                                    <?php elseif ($t['status'] === 'active'): ?>
                                                        <span class="badge bg-warning text-dark" style="font-size:10px;">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary" style="font-size:10px;">Locked</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($t['status'] === 'finish'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info btn-view-task" data-id="<?= $t['id']; ?>" style="width: 90px;"><i class="fa fa-eye"></i> View</button>
                                                    <?php elseif ($t['status'] === 'active' && !$is_on_hold && !$is_completed && !$is_readonly): ?>
                                                        <?php $is_my_task = $is_admin || (isset($current_user_id) && $t['pic_user_id'] == $current_user_id); ?>
                                                        <?php if ($is_my_task): ?>
                                                            <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                                                <button type="button" class="btn btn-sm btn-primary btn-input-task" data-id="<?= $t['id']; ?>" style="width: 90px;"><i class="fa fa-pencil"></i> Isi Task</button>
                                                                <button type="button" class="btn btn-sm btn-success btn-finish-tahapan" data-id="<?= $t['id']; ?>" data-name="<?= html_escape($t['tahapan_name']); ?>" style="width: 90px;"><i class="fa fa-check"></i> Finish</button>
                                                                <?php if ($t['tahapan_order'] > 1):
                                                                    $prev_steps = array();
                                                                    foreach ($mod['tahapan'] as $pt) {
                                                                        if ($pt['tahapan_order'] < $t['tahapan_order']) {
                                                                            $prev_steps[$pt['tahapan_order']] = $pt['tahapan_name'];
                                                                        }
                                                                    }
                                                                ?>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-rollback" data-module-id="<?= $mod['id']; ?>" data-module-name="<?= html_escape($mod['module_name']); ?>" data-current-order="<?= $t['tahapan_order']; ?>" data-prev-steps='<?= json_encode($prev_steps); ?>' style="width: 90px;"><i class="fa fa-undo"></i> Rollback</button>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted small"><i class="fa fa-clock-o me-1"></i>Menunggu PIC</span>
                                                        <?php endif; ?>
                                                    <?php elseif ($t['status'] === 'active' && $is_on_hold): ?>
                                                        <span class="badge bg-secondary" style="font-size:10px;"><i class="fa fa-pause me-1"></i>Hold</span>
                                                    <?php elseif ($t['status'] === 'active'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-info btn-view-task" data-id="<?= $t['id']; ?>"><i class="fa fa-eye"></i> View</button>
                                                    <?php else: ?>
                                                        <span class="text-muted"><i class="fa fa-lock"></i></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Meeting/Others Card (terpisah dari tahapan) -->
                        <div class="card-footer bg-light p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="small"><i class="fa fa-comments me-1"></i> Others / Meeting</strong>
                                <?php if (!$is_readonly && !$is_on_hold && !$is_completed): ?>
                                    <button type="button" class="btn btn-sm btn-dark btn-add-meeting" data-module-id="<?= $mod['id']; ?>" data-project-id="<?= $project['id']; ?>"><i class="fa fa-plus me-1"></i> Tambah</button>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($mod['meetings'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <thead class="table-light text-center small">
                                            <tr>
                                                <th width="90">Tanggal</th>
                                                <th>Aktivitas</th>
                                                <th width="50">MH</th>
                                                <th>Oleh</th>
                                                <th>Ket</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($mod['meetings'] as $mt): ?>
                                                <tr>
                                                    <td class="text-center small"><?= date('d M Y', strtotime($mt['task_date'])); ?></td>
                                                    <td class="small"><?= html_escape($mt['task_description']); ?></td>
                                                    <td class="text-center fw-bold"><?= $mt['manhour']; ?></td>
                                                    <td class="small"><?= html_escape($mt['user_name']); ?></td>
                                                    <td class="small"><?= html_escape($mt['remarks'] ? $mt['remarks'] : '-'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table">
                                                <td colspan="2" class="text-end fw-bold small">Total MH Meeting:</td>
                                                <td class="text-center fw-bold"><?= $mod['meeting_manhour']; ?></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small mb-0">Belum ada catatan meeting/others.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted py-4">Belum ada modul. Klik "Add Modul" untuk menambahkan.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tahapan -->
<div class="modal fade" id="modal-tahapan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="modal-tahapan-content"></div>
    </div>
</div>
<!-- Modal Add Module -->
<div class="modal fade" id="modal-add-module" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="modal-add-module-content"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Init Select2 for add member dropdown
        $('#select-add-member').select2({
            width: '100%',
            placeholder: '-- Cari & pilih user --'
        });

        // === TEAM ===
        $('#form-add-role-member').on('submit', function(e) {
            e.preventDefault();
            $.post('<?= site_url("projects_management/add_role_member"); ?>', $(this).serialize(), function(res) {
                if (res.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
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
        });

        $(document).on('click', '.btn-remove-role', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus anggota?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.post('<?= site_url("projects_management/remove_role_member"); ?>', {
                        id: id
                    }, function(res) {
                        if (res.status === 1) location.reload();
                        else Swal.fire('Gagal', res.pesan, 'error');
                    }, 'json');
                }
            });
        });

        // === ADD MODULE ===
        $('#btn-add-module').click(function() {
            $.get('<?= site_url("projects_management/get_add_module_form/" . $project["id"]); ?>', function(html) {
                $('#modal-add-module-content').html(html);
                new bootstrap.Modal(document.getElementById('modal-add-module')).show();
            });
        });

        // === ISI TASK ===
        $(document).on('click', '.btn-input-task', function() {
            $.get('<?= site_url("projects_management/input_pekerjaan/"); ?>' + $(this).data('id'), function(html) {
                $('#modal-tahapan-content').html(html);
                new bootstrap.Modal(document.getElementById('modal-tahapan')).show();
            });
        });

        // === VIEW TASK ===
        $(document).on('click', '.btn-view-task', function() {
            $.get('<?= site_url("projects_management/view_pekerjaan/"); ?>' + $(this).data('id'), function(html) {
                $('#modal-tahapan-content').html(html);
                new bootstrap.Modal(document.getElementById('modal-tahapan')).show();
            });
        });

        // === FINISH TAHAPAN ===
        $(document).on('click', '.btn-finish-tahapan', function() {
            var id = $(this).data('id'),
                name = $(this).data('name');
            Swal.fire({
                title: 'Selesaikan Tahapan?',
                html: 'Tandai <strong>"' + name + '"</strong> selesai?<br><small>Tahapan berikutnya otomatis terbuka.</small>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: '<i class="fa fa-check"></i> Finish',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.post('<?= site_url("projects_management/finish_tahapan"); ?>', {
                        tahapan_id: id
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
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

        // === FINISH MODULE ===
        $(document).on('click', '.btn-finish-module', function() {
            var id = $(this).data('id'),
                name = $(this).data('name');
            Swal.fire({
                title: 'Modul Finish?',
                text: 'Tandai modul "' + name + '" sebagai FINISH?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: '<i class="fa fa-check"></i> Finish',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.post('<?= site_url("projects_management/finish_module"); ?>', {
                        module_id: id
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
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

        // === ON HOLD PROJECT ===
        $('#btn-onhold-project').click(function() {
            Swal.fire({
                title: 'On Hold Project?',
                text: 'Project akan di-hold. Tidak ada modul baru yang bisa ditambahkan selama On Hold.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                confirmButtonText: '<i class="fa fa-pause"></i> On Hold',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.post('<?= site_url("projects_management/set_project_status"); ?>', {
                        project_id: '<?= $project["id"]; ?>',
                        status: 'On Hold'
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Project On Hold',
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

        // === RESUME PROJECT ===
        $('#btn-resume-project').click(function() {
            Swal.fire({
                title: 'Resume Project?',
                text: 'Project akan dilanjutkan kembali.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0dcaf0',
                confirmButtonText: '<i class="fa fa-play"></i> Resume',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.post('<?= site_url("projects_management/set_project_status"); ?>', {
                        project_id: '<?= $project["id"]; ?>',
                        status: 'In Progress'
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Project Resumed',
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

        // === FINISH PROJECT (manual, hanya jika semua modul finish) ===
        $('#btn-finish-project').click(function() {
            Swal.fire({
                title: 'Finish Project?',
                text: 'Semua modul sudah selesai. Tandai project sebagai COMPLETED? Setelah ini tidak bisa menambah modul lagi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: '<i class="fa fa-check-circle"></i> Finish Project',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.post('<?= site_url("projects_management/set_project_status"); ?>', {
                        project_id: '<?= $project["id"]; ?>',
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

        // === ROLLBACK TAHAPAN ===
        $(document).on('click', '.btn-rollback', function() {
            var moduleId = $(this).data('module-id');
            var moduleName = $(this).data('module-name');
            var currentOrder = $(this).data('current-order');
            var prevSteps = $(this).data('prev-steps'); // {order: name, ...}

            // Build options with step name
            var options = {};
            for (var key in prevSteps) {
                options[key] = 'Step ' + key + ' - ' + prevSteps[key];
            }

            Swal.fire({
                title: 'Kembalikan ke Step Sebelumnya',
                html: '<p class="small text-muted">Modul: <strong>' + moduleName + '</strong></p>' +
                    '<p class="small">Pilih tahapan yang ingin dikembalikan:</p>',
                input: 'select',
                inputOptions: options,
                inputPlaceholder: '-- Pilih step tujuan --',
                inputValidator: function(value) {
                    if (!value) return 'Pilih step tujuan!';
                },
                showCancelButton: true,
                confirmButtonText: 'Lanjut',
                cancelButtonText: 'Batal'
            }).then(function(r1) {
                if (r1.isConfirmed) {
                    Swal.fire({
                        title: 'Alasan Rollback',
                        input: 'textarea',
                        inputPlaceholder: 'Masukkan alasan kenapa dikembalikan...',
                        inputValidator: function(value) {
                            if (!value || !value.trim()) return 'Alasan wajib diisi!';
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: '<i class="fa fa-undo me-1"></i> Kembalikan',
                        cancelButtonText: 'Batal'
                    }).then(function(r2) {
                        if (r2.isConfirmed) {
                            $.post('<?= site_url("projects_management/rollback_tahapan"); ?>', {
                                module_id: moduleId,
                                target_order: r1.value,
                                from_order: currentOrder,
                                reason: r2.value
                            }, function(res) {
                                if (res.status === 1) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
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
                }
            });
        });

        // === DELETE MODULE ===
        $(document).on('click', '.btn-delete-module', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Modul?',
                html: 'Modul <strong>"' + name + '"</strong> beserta semua tahapan dan task-nya akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Hapus',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.post('<?= site_url("projects_management/delete_module"); ?>', { module_id: id }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({ icon:'success', title:'Dihapus!', text:res.pesan, timer:1500, showConfirmButton:false }).then(function(){ location.reload(); });
                        } else { Swal.fire('Gagal', res.pesan, 'error'); }
                    }, 'json');
                }
            });
        });

        // === ADD MEETING ===
        $(document).on('click', '.btn-add-meeting', function() {
            var moduleId = $(this).data('module-id');
            var projectId = $(this).data('project-id');

            Swal.fire({
                title: 'Tambah Meeting / Others',
                html: '<div class="text-start">' +
                    '<div class="mb-2"><label class="form-label small fw-bold">Aktivitas <span class="text-danger">*</span></label><textarea id="swal-meeting-desc" class="form-control form-control-sm" rows="3" placeholder="Deskripsi kegiatan..."></textarea></div>' +
                    '<div class="mb-2"><label class="form-label small fw-bold">Manhour <span class="text-danger">*</span></label><input id="swal-meeting-mh" type="number" step="0.5" min="0.5" class="form-control form-control-sm" placeholder="0" /></div>' +
                    '<div class="mb-2"><label class="form-label small fw-bold">Keterangan</label><textarea id="swal-meeting-remarks" class="form-control form-control-sm" rows="2" placeholder="Opsional"></textarea></div>' +
                    '</div>',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-save me-1"></i> Simpan',
                cancelButtonText: 'Batal',
                preConfirm: function() {
                    var desc = document.getElementById('swal-meeting-desc').value.trim();
                    var mh = document.getElementById('swal-meeting-mh').value;
                    if (!desc || !mh) {
                        Swal.showValidationMessage('Aktivitas dan Manhour wajib diisi');
                        return false;
                    }
                    return {
                        desc: desc,
                        mh: mh,
                        remarks: document.getElementById('swal-meeting-remarks').value
                    };
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.post('<?= site_url("projects_management/save_meeting"); ?>', {
                        module_id: moduleId,
                        project_id: projectId,
                        task_description: result.value.desc,
                        manhour: result.value.mh,
                        remarks: result.value.remarks
                    }, function(res) {
                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
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