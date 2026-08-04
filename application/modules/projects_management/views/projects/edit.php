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
            <!-- HEADER -->
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
                                    <select name="ba_ids[]" id="ba_ids" class="form-select form-select-sm select2-multi" multiple>
                                        <?php $ba_selected = array_column($ba_users, 'user_id'); foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>" <?= in_array($u['id_user'], $ba_selected) ? 'selected' : ''; ?>><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Programmer <small class="text-muted">(multi)</small></label>
                                <div class="col-sm-8">
                                    <select name="programmer_ids[]" id="programmer_ids" class="form-select form-select-sm select2-multi" multiple>
                                        <?php $prog_selected = array_column($prog_users, 'user_id'); foreach ($users as $u): ?>
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
                                        <?php $qa_selected = !empty($qa_users) ? $qa_users[0]['user_id'] : ''; foreach ($users as $u): ?>
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
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_ba" id="target_mh_ba" class="form-control form-control-sm bg-excel-green" value="<?= $project['target_mh_ba']; ?>" readonly disabled /></div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH Programmer</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_programmer" id="target_mh_programmer" class="form-control form-control-sm bg-excel-green" value="<?= $project['target_mh_programmer']; ?>" readonly disabled /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EXISTING MODULES -->
            <?php if (!empty($modules)): ?>
            <div class="card border mb-4">
                <div class="card-header bg-light py-2">
                    <strong class="small"><i class="fa fa-cube me-1"></i> Modul yang sudah ada</strong>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr><th width="40">No</th><th>Nama Modul</th><th width="100">Tahapan</th><th width="90">Status</th></tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach ($modules as $mod): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><strong><?= html_escape($mod['module_name']); ?></strong></td>
                                    <td class="text-center"><?= $mod['finished_tahapan']; ?>/<?= $mod['total_tahapan']; ?></td>
                                    <td class="text-center">
                                        <?php if ($mod['status'] === 'finish'): ?>
                                            <span class="badge bg-success">Finish</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Progress</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ADD NEW MODULES -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong class="small text-primary"><i class="fa fa-plus-circle me-1"></i> Tambah Modul Baru (opsional)</strong>
                <button type="button" class="btn btn-sm btn-dark" id="btn-create-modul-edit"><i class="fa fa-plus me-1"></i> Create Modul</button>
            </div>
            <div id="modules-container-edit"></div>

            <div class="text-end border-top pt-3 mt-3">
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

    // Master tahapan from DB
    var fixedTahapan = <?= json_encode($master_tahapan); ?>;
    var moduleCount = 0;

    // Auto-calculate MH BA & Programmer from modules
    function recalculateManhours() {
        var totalBA = 0, totalProg = 0;
        $('.mh-input-edit').each(function() {
            var val = parseFloat($(this).val()) || 0;
            var role = $(this).data('role');
            if (role === 'ba') totalBA += val;
            else if (role === 'programmer') totalProg += val;
        });
        // Also add existing modules MH
        <?php
        $existing_ba_mh = 0; $existing_prog_mh = 0;
        foreach ($modules as $mod) {
            foreach ($mod['tahapan'] as $t) {
                if ($t['tahapan_role'] === 'ba') $existing_ba_mh += (float)$t['plan_manhour'];
                elseif ($t['tahapan_role'] === 'programmer') $existing_prog_mh += (float)$t['plan_manhour'];
            }
        }
        ?>
        totalBA += <?= $existing_ba_mh; ?>;
        totalProg += <?= $existing_prog_mh; ?>;
        $('#target_mh_ba').val(totalBA);
        $('#target_mh_programmer').val(totalProg);
    }

    $(document).on('input change', '.mh-input-edit', function() { recalculateManhours(); });
    recalculateManhours();

    // Get PIC options
    function getUserOptionsForRole(role) {
        var options = '<option value="">-- Pilih PIC --</option>';
        var selected = [];
        if (role === 'ba') { selected = $('#ba_ids').select2('data'); }
        else if (role === 'programmer') { selected = $('#programmer_ids').select2('data'); }
        else if (role === 'qa') {
            var qaVal = $('select[name="qa_id"]').val();
            var qaText = $('select[name="qa_id"] option:selected').text();
            if (qaVal) selected = [{ id: qaVal, text: qaText }];
        }
        if (selected.length === 1) { options += '<option value="' + selected[0].id + '" selected>' + selected[0].text + '</option>'; }
        else if (selected.length > 1) { selected.forEach(function(u) { options += '<option value="' + u.id + '">' + u.text + '</option>'; }); }
        return options;
    }

    function createModuleBlock(moduleName) {
        var idx = moduleCount; moduleCount++;
        var html = '<div class="card border mb-3 module-block-edit" data-idx="' + idx + '">';
        html += '<div class="card-header d-flex justify-content-between align-items-center py-2" style="background:#e3f2fd;">';
        html += '<strong class="text-primary"><i class="fa fa-cube me-1"></i> ' + moduleName + '</strong>';
        html += '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-module-edit"><i class="fa fa-trash"></i></button>';
        html += '</div><div class="card-body p-0"><div class="table-responsive"><table class="table table-bordered table-sm align-middle mb-0">';
        html += '<thead class="table-dark text-center"><tr><th width="35">No</th><th>Tahapan</th><th width="150">PIC</th><th width="90">Manhour</th><th width="130">Due Date</th></tr></thead><tbody>';
        html += '<input type="hidden" name="module_names[' + idx + ']" value="' + moduleName + '" />';

        fixedTahapan.forEach(function(step) {
            var picHtml = (step.role === 'others') ? '<span class="text-muted small">Team</span>' : '<select name="tahapan_pic[' + idx + '][' + step.order + ']" class="form-select form-select-sm">' + getUserOptionsForRole(step.role) + '</select>';
            var dateHtml = (step.role === 'others') ? '<span class="text-muted small">-</span>' : '<input type="text" name="tahapan_duedate[' + idx + '][' + step.order + ']" class="form-control form-control-sm flatpickr-row-edit bg-excel-yellow" placeholder="Pilih" />';
            html += '<tr><td class="text-center small">' + step.order + '</td><td class="small">' + step.name + '</td><td>' + picHtml + '</td>';
            html += '<td><input type="number" step="0.5" min="0" name="tahapan_manhour[' + idx + '][' + step.order + ']" class="form-control form-control-sm mh-input-edit bg-excel-yellow" data-role="' + step.role + '" value="0" /></td>';
            html += '<td>' + dateHtml + '</td></tr>';
        });

        html += '</tbody></table></div></div></div>';
        $('#modules-container-edit').append(html);
        flatpickr('.flatpickr-row-edit:not(.flatpickr-input)', { dateFormat: 'Y-m-d', allowInput: true });
        recalculateManhours();
    }

    $('#btn-create-modul-edit').click(function() {
        Swal.fire({
            title: 'Nama Modul Baru', input: 'text', inputValue: '', inputPlaceholder: 'Masukkan nama modul...',
            showCancelButton: true, confirmButtonText: '<i class="fa fa-plus me-1"></i> Create', cancelButtonText: 'Batal',
            inputValidator: function(v) { if (!v || !v.trim()) return 'Nama modul tidak boleh kosong!'; }
        }).then(function(r) { if (r.isConfirmed && r.value) createModuleBlock(r.value.trim()); });
    });

    $(document).on('click', '.btn-remove-module-edit', function() {
        $(this).closest('.module-block-edit').remove(); recalculateManhours();
    });

    // Submit
    $('#form-edit-project').on('submit', function(e) {
        e.preventDefault();
        $('#btn-save-edit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
        $.post('<?= site_url("projects_management/update_project"); ?>', $(this).serialize(), function(res) {
            $('#btn-save-edit').prop('disabled', false).html('<i class="fa fa-save me-1"></i> Simpan Perubahan');
            if (res.status === 1) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.pesan, confirmButtonColor: '#0d6efd' }).then(function() {
                    window.location.href = '<?= site_url("projects_management/master"); ?>';
                });
            } else { Swal.fire('Gagal', res.pesan, 'error'); }
        }, 'json');
    });
});
</script>
