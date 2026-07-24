<!-- Select2 CSS (CDN untuk memastikan multi-select tampil benar) & Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    .select2-container {
        width: 100% !important;
    }
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 fw-bold text-primary"><i class="fa fa-plus-circle me-2"></i> New Project</h5>
        <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
    </div>

    <form id="form-create-project">
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
                                    <select name="client_id" id="client_id" class="form-select form-select-sm select2-single" required>
                                        <option value="">Select Client</option>
                                        <?php foreach ($clients as $c): ?>
                                            <option value="<?= $c['id']; ?>"><?= html_escape($c['name_app']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Project <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="project_name" class="form-control form-control-sm " placeholder="Nama Project" required />
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Target date selesai <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="end_date" id="end_date" class="form-control form-control-sm  flatpickr-date" placeholder="Pilih Tanggal" required />
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Bisnis Analis <small class="text-muted">(multi)</small></label>
                                <div class="col-sm-8">
                                    <select name="ba_ids[]" id="ba_ids" class="form-select form-select-sm select2-multi" multiple>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Programmer <small class="text-muted">(multi)</small></label>
                                <div class="col-sm-8">
                                    <select name="programmer_ids[]" id="programmer_ids" class="form-select form-select-sm select2-multi" multiple>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">PM <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select name="pm_id" id="pm_id" class="form-select form-select-sm select2-single" required>
                                        <option value="">Select PM</option>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">QA Program</label>
                                <div class="col-sm-8">
                                    <select name="qa_id" id="qa_id" class="form-select form-select-sm select2-single">
                                        <option value="">Select QA</option>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
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
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_pm" class="form-control form-control-sm " value="0" /></div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH QA</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_qa" class="form-control form-control-sm " value="0" /></div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH Bisnis Analis</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_ba" id="target_mh_ba" class="form-control form-control-sm" value="0" readonly /></div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-6 col-form-label fw-bold small">Target MH Programmer</label>
                                <div class="col-sm-6"><input type="number" step="0.5" name="target_mh_programmer" id="target_mh_programmer" class="form-control form-control-sm" value="0" readonly /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODUL & TAHAPAN -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn btn-dark btn-sm fw-bold" id="btn-create-modul"><i class="fa fa-plus me-1"></i> Create Modul</button>
                <span class="text-muted small">Klik "Create Modul" untuk menambah modul beserta 12 tahapan.</span>
            </div>

            <div id="modules-container">
                <!-- Dynamic module blocks here -->
            </div>

            <div class="text-end border-top pt-3 mt-3">
                <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary fw-bold px-4" id="btn-save-project"><i class="fa fa-save me-1"></i> Save Project</button>
            </div>
        </div>
    </form>
</div>

<script>
    // Pastikan Select2 versi terbaru untuk multi-select
    if (typeof $.fn.select2 === 'undefined' || !$.fn.select2.defaults) {
        // fallback jika Select2 belum loaded
    }
    $(document).ready(function() {
        // Init Select2 - single select
        $('.select2-single').select2({
            width: '100%',
            allowClear: true,
            placeholder: '-- Pilih --'
        });
        // Init Select2 - multi select (BA & Programmer bisa lebih dari 1)
        $('#ba_ids').select2({
            width: '100%',
            placeholder: 'Pilih Bisnis Analis',
            closeOnSelect: false
        });
        $('#programmer_ids').select2({
            width: '100%',
            placeholder: 'Pilih Programmer (bisa lebih dari 1)',
            closeOnSelect: false
        });

        // Init Flatpickr
        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        var moduleCount = 0;

        // Master tahapan dari database
        var fixedTahapan = <?= json_encode($master_tahapan); ?>;

        // Get selected users for a role
        function getUserOptionsForRole(role) {
            var options = '<option value="">-- Pilih PIC --</option>';
            var selected = [];

            if (role === 'ba') {
                selected = $('#ba_ids').select2('data');
            } else if (role === 'programmer') {
                selected = $('#programmer_ids').select2('data');
            } else if (role === 'qa') {
                var qaVal = $('#qa_id').val();
                var qaText = $('#qa_id option:selected').text();
                if (qaVal) selected = [{
                    id: qaVal,
                    text: qaText
                }];
            }

            if (selected.length === 1) {
                // Hanya 1 orang -> auto select
                options += '<option value="' + selected[0].id + '" selected>' + selected[0].text + '</option>';
            } else if (selected.length > 1) {
                // > 1 orang -> harus pilih
                selected.forEach(function(u) {
                    options += '<option value="' + u.id + '">' + u.text + '</option>';
                });
            }

            return {
                options: options,
                autoSelected: (selected.length === 1),
                count: selected.length
            };
        }

        function createModuleBlock(moduleName) {
            var idx = moduleCount;
            moduleCount++;

            var html = '<div class="card border mb-3 module-block" data-idx="' + idx + '">';
            html += '<div class="card-header d-flex justify-content-between align-items-center py-2" style="background:#e3f2fd;">';
            html += '<strong class="text-primary"><i class="fa fa-cube me-1"></i> ' + moduleName + '</strong>';
            html += '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-module" data-idx="' + idx + '"><i class="fa fa-trash"></i></button>';
            html += '</div>';
            html += '<div class="card-body p-0"><div class="table-responsive">';
            html += '<table class="table table-bordered table-sm align-middle mb-0">';
            html += '<thead class="table text-center"><tr><th width="35">No</th><th>Tahapan</th><th width="150">PIC</th><th width="90">Manhour</th><th width="130">Due Date</th></tr></thead>';
            html += '<tbody>';
            html += '<input type="hidden" name="module_names[' + idx + ']" value="' + moduleName + '" />';

            fixedTahapan.forEach(function(step) {
                var picInfo = getUserOptionsForRole(step.role);
                var picSelect = '';

                if (step.role === 'others') {
                    picSelect = '<span class="text-muted small">Team</span>';
                } else {
                    picSelect = '<select name="tahapan_pic[' + idx + '][' + step.order + ']" class="form-select form-select-sm">' + picInfo.options + '</select>';
                }

                var dueDateHtml = (step.role === 'others') ?
                    '<span class="text-muted small">-</span>' :
                    '<input type="text" name="tahapan_duedate[' + idx + '][' + step.order + ']" class="form-control form-control-sm flatpickr-row " placeholder="Pilih" />';

                html += '<tr>';
                html += '<td class="text-center small">' + step.order + '</td>';
                html += '<td class="small">' + step.name + '</td>';
                html += '<td>' + picSelect + '</td>';
                html += '<td><input type="number" step="0.5" min="0" name="tahapan_manhour[' + idx + '][' + step.order + ']" class="form-control form-control-sm mh-input " data-role="' + step.role + '" value="0" /></td>';
                html += '<td>' + dueDateHtml + '</td>';
                html += '</tr>';
            });

            html += '</tbody></table></div></div></div>';

            $('#modules-container').append(html);

            // Init flatpickr for new inputs
            flatpickr('.flatpickr-row:not(.flatpickr-input)', {
                dateFormat: 'Y-m-d',
                allowInput: false
            });
        }

        // Create Modul button
        $('#btn-create-modul').click(function() {
            // Check header sudah diisi
            var baCount = $('#ba_ids').select2('data').length;
            var progCount = $('#programmer_ids').select2('data').length;

            if (baCount === 0 && progCount === 0 && !$('#qa_id').val()) {
                Swal.fire('Perhatian', 'Pilih minimal 1 Bisnis Analis atau Programmer di header terlebih dahulu.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Nama Modul Baru',
                input: 'text',
                inputValue: 'Modul ' + (moduleCount + 1),
                inputPlaceholder: 'Masukkan nama modul...',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-plus me-1"></i> Create',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0d6efd',
                inputValidator: function(value) {
                    if (!value || !value.trim()) return 'Nama modul tidak boleh kosong!';
                }
            }).then(function(result) {
                if (result.isConfirmed && result.value) {
                    createModuleBlock(result.value.trim());
                    recalculateManhours();
                }
            });
        });

        // Remove module
        $(document).on('click', '.btn-remove-module', function() {
            var $block = $(this).closest('.module-block');
            Swal.fire({
                title: 'Hapus Modul?',
                text: 'Modul ini dan tahapannya akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $block.remove();
                    recalculateManhours();
                }
            });
        });

        // Recalculate manhours
        function recalculateManhours() {
            var totalBA = 0,
                totalProg = 0;
            $('.mh-input').each(function() {
                var val = parseFloat($(this).val()) || 0;
                var role = $(this).data('role');
                if (role === 'ba') totalBA += val;
                else if (role === 'programmer') totalProg += val;
            });
            $('#target_mh_ba').val(totalBA);
            $('#target_mh_programmer').val(totalProg);
        }

        $(document).on('input change', '.mh-input', function() {
            recalculateManhours();
        });

        // Form Submit
        $('#form-create-project').on('submit', function(e) {
            e.preventDefault();

            // Tampilkan Konfirmasi Sebelum Save
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan project ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-check me-1"></i> Ya, Simpan!',
                cancelButtonText: '<i class="fa fa-times me-1"></i> Batal'
            }).then((result) => {
                // Jika pengguna menekan tombol "Ya, Simpan!"
                if (result.isConfirmed) {

                    // Atur tombol ke status loading
                    $('#btn-save-project').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

                    // Eksekusi Simpan via AJAX
                    $.post('<?= site_url("projects_management/create"); ?>', $('#form-create-project').serialize(), function(res) {
                        $('#btn-save-project').prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Project');

                        if (res.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.pesan,
                                timer: 1500,
                                showConfirmBtn: false
                            }).then(function() {
                                window.location.href = '<?= site_url("projects_management/master"); ?>';
                            });
                        } else {
                            Swal.fire('Gagal', res.pesan, 'error');
                        }
                    }, 'json').fail(function() {
                        // Tambahan fallback jika server error/lost connection
                        $('#btn-save-project').prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Project');
                        Swal.fire('Error', 'Terjadi kesalahan pada sistem. Silakan coba lagi.', 'error');
                    });

                }
            });
        });
    });
</script>