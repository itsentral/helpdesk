<!-- Select2 CSS & Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    .bg-excel-yellow { background-color: #fff2cc !important; }
    .bg-excel-green { background-color: #d9ebd9 !important; }
    .select2-container { width: 100% !important; }
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 fw-bold text-primary"><i class="fa fa-pencil me-2"></i> Edit Project - <?= html_escape($project['project_name']); ?></h5>
        <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
    </div>

    <form id="form-edit-project">
        <input type="hidden" name="project_id" value="<?= $project['id']; ?>">
        <div class="card-body p-4">
            <div class="card border mb-4">
                <div class="card-body p-3">
                    <div class="row g-4">
                        <!-- LEFT -->
                        <div class="col-12 col-lg-7">
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Client <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select name="client_id" class="form-select form-select-sm select2-single" required>
                                        <option value="">Select Client</option>
                                        <?php foreach ($clients as $c): ?>
                                            <option value="<?= $c['id']; ?>" <?= ($project['client_id'] == $c['id']) ? 'selected' : ''; ?>><?= html_escape($c['name_app']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Project <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="project_name" class="form-control form-control-sm bg-excel-yellow" value="<?= html_escape($project['project_name']); ?>" required />
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Target date selesai <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="end_date" class="form-control form-control-sm bg-excel-yellow flatpickr-date" value="<?= $project['end_date']; ?>" required />
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Bisnis Analis <small class="text-muted">(multi)</small></label>
                                <div class="col-sm-8">
                                    <select name="ba_ids[]" class="form-select form-select-sm select2-multi" multiple>
                                        <?php
                                        $ba_selected = array_column($ba_users, 'user_id');
                                        foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>" <?= in_array($u['id_user'], $ba_selected) ? 'selected' : ''; ?>><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Programmer <small class="text-muted">(multi)</small></label>
                                <div class="col-sm-8">
                                    <select name="programmer_ids[]" class="form-select form-select-sm select2-multi" multiple>
                                        <?php
                                        $prog_selected = array_column($prog_users, 'user_id');
                                        foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>" <?= in_array($u['id_user'], $prog_selected) ? 'selected' : ''; ?>><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">PM <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select name="pm_id" class="form-select form-select-sm select2-single" required>
                                        <option value="">Select PM</option>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>" <?= ($project['pm_id'] == $u['id_user']) ? 'selected' : ''; ?>><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">QA Program</label>
                                <div class="col-sm-8">
                                    <select name="qa_id" class="form-select form-select-sm select2-single">
                                        <option value="">Select QA</option>
                                        <?php
                                        $qa_selected = !empty($qa_users) ? $qa_users[0]['user_id'] : '';
                                        foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>" <?= ($qa_selected == $u['id_user']) ? 'selected' : ''; ?>><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- RIGHT: Manhour -->
                        <div class="col-12 col-lg-5 border-start ps-lg-4">
                            <h6 class="fw-bold border-bottom pb-2 text-primary mb-3"><i class="fa fa-calculator me-1"></i> Target Manhour</h6>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH PM</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_pm" class="form-control form-control-sm bg-excel-yellow" value="<?= $project['target_mh_pm']; ?>" /></div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH QA</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_qa" class="form-control form-control-sm bg-excel-yellow" value="<?= $project['target_mh_qa']; ?>" /></div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH Bisnis Analis</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_ba" class="form-control form-control-sm bg-excel-green" value="<?= $project['target_mh_ba']; ?>" /></div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH Programmer</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_programmer" class="form-control form-control-sm bg-excel-green" value="<?= $project['target_mh_programmer']; ?>" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary fw-bold px-4" id="btn-save-edit"><i class="fa fa-save me-1"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('.select2-single').select2({ width: '100%' });
    $('.select2-multi').select2({ width: '100%', placeholder: 'Pilih (bisa lebih dari 1)' });
    flatpickr('.flatpickr-date', { dateFormat: 'Y-m-d', allowInput: true });

    $('#form-edit-project').on('submit', function(e) {
        e.preventDefault();
        $('#btn-save-edit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

        $.post('<?= site_url("projects_management/update_project"); ?>', $(this).serialize(), function(res) {
            $('#btn-save-edit').prop('disabled', false).html('<i class="fa fa-save me-1"></i> Simpan Perubahan');
            if (res.status === 1) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.pesan, confirmButtonColor: '#0d6efd' }).then(function() {
                    window.location.href = '<?= site_url("projects_management/master"); ?>';
                });
            } else {
                Swal.fire('Gagal', res.pesan, 'error');
            }
        }, 'json');
    });
});
</script>
