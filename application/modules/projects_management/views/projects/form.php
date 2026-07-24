<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    .bg-excel-yellow { background-color: #fff2cc !important; }
    .bg-excel-blue { background-color: #d9ebd9 !important; }
    .bg-excel-header { background-color: #003366 !important; color: white !important; }
    .select2-container { width: 100% !important; }
</style>

<div class="modal-header bg-primary text-white py-2">
    <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-plus-circle me-1"></i> New Project</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="form-create-project">
    <div class="modal-body p-3" style="max-height: 80vh; overflow-y: auto;">
        
        <!-- HEADER FORM SECTION (LIKE EXCEL MOCKUP) -->
        <div class="card border mb-3">
            <div class="card-body p-3">
                <div class="row g-3">
                    <!-- LEFT COLUMN: INPUT / SELECTS -->
                    <div class="col-12 col-lg-7">
                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm fw-bold">Client <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="client_id" id="client_id" class="form-select form-select-sm select2" required>
                                    <option value="">Select Client</option>
                                    <?php if (!empty($clients)): foreach ($clients as $c): ?>
                                        <option value="<?= $c['id']; ?>"><?= html_escape($c['name_app']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm fw-bold">Project <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="project_name" class="form-control form-control-sm bg-excel-yellow" placeholder="Nama Project / Pengembangan Program" required />
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm fw-bold">Target date selesai <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="end_date" id="end_date" class="form-control form-control-sm flatpickr-date bg-excel-yellow" placeholder="Select date" required />
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm fw-bold">Bisnis analis</label>
                            <div class="col-sm-8">
                                <select name="ba_id" id="ba_id" class="form-select form-select-sm select2 role-user-select" data-role="ba">
                                    <option value="">Select bisnis analis</option>
                                    <?php if (!empty($users)): foreach ($users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm fw-bold">Programmer</label>
                            <div class="col-sm-8">
                                <select name="programmer_id" id="programmer_id" class="form-select form-select-sm select2 role-user-select" data-role="programmer">
                                    <option value="">Select programmer</option>
                                    <?php if (!empty($users)): foreach ($users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm fw-bold">PM <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="pm_id" id="pm_id" class="form-select form-select-sm select2" required>
                                    <option value="">Select all / PM</option>
                                    <?php if (!empty($users)): foreach ($users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label col-form-label-sm fw-bold">QA Program</label>
                            <div class="col-sm-8">
                                <select name="qa_id" id="qa_id" class="form-select form-select-sm select2 role-user-select" data-role="qa">
                                    <option value="">Select QA program</option>
                                    <?php if (!empty($users)): foreach ($users as $u): ?>
                                        <option value="<?= $u['id_user']; ?>"><?= html_escape($u['nm_lengkap'] ? $u['nm_lengkap'] : $u['username']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: TARGET MAN-HOURS SUMMARY -->
                    <div class="col-12 col-lg-5 border-start ps-3">
                        <h6 class="fw-bold border-bottom pb-1 text-primary">Summary Target Manhour</h6>
                        
                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-6 col-form-label col-form-label-sm fw-bold">Target manhour PM</label>
                            <div class="col-sm-6">
                                <input type="number" step="0.5" name="target_mh_pm" class="form-control form-control-sm bg-excel-yellow fw-bold" placeholder="0" value="0" />
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-6 col-form-label col-form-label-sm fw-bold">Target manhour QA</label>
                            <div class="col-sm-6">
                                <input type="number" step="0.5" name="target_mh_qa" class="form-control form-control-sm bg-excel-yellow fw-bold" placeholder="0" value="0" />
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-6 col-form-label col-form-label-sm fw-bold">Target manhour bisnis analis</label>
                            <div class="col-sm-6">
                                <input type="number" step="0.5" name="target_mh_ba" id="target_mh_ba" class="form-control form-control-sm bg-excel-blue fw-bold" value="0" readonly />
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <label class="col-sm-6 col-form-label col-form-label-sm fw-bold">Target manhour programmer</label>
                            <div class="col-sm-6">
                                <input type="number" step="0.5" name="target_mh_programmer" id="target_mh_programmer" class="form-control form-control-sm bg-excel-blue fw-bold" value="0" readonly />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DYNAMIC MODUL & TAHAPAN GRID SECTION -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <button type="button" class="btn btn-sm btn-dark fw-bold" id="btn-add-module"><i class="fa fa-plus me-1"></i> Create Modul</button>
            <span class="text-muted small">Tahapan otomatis di-generate per modul baru.</span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle" id="table-tahapan">
                <thead>
                    <tr class="table text-center">
                        <th width="40">No</th>
                        <th width="140">Modul</th>
                        <th>Tahapan</th>
                        <th width="160">PIC</th>
                        <th width="100">Manhour</th>
                        <th width="140">Due Date</th>
                        <th width="40"><i class="fa fa-trash"></i></th>
                    </tr>
                </thead>
                <tbody id="tahapan-tbody">
                    <!-- Dynamic rows inserted here -->
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal-footer py-2 bg-light">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-sm btn-primary fw-bold" id="btn-save-project"><i class="fa fa-save me-1"></i> Save Project</button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Initialize Select2 in Modal
    $('.select2').select2({
        dropdownParent: $('#modal-project'),
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Initialize Flatpickr for End Date
    flatpickr('.flatpickr-date', {
        dateFormat: 'Y-m-d',
        allowInput: true
    });

    var moduleCount = 0;
    var rowNo = 0;

    // Standard fixed steps from Excel concept
    var fixedTahapan = [
        { name: "Konsep (harus plus test case)", role: "ba" },
        { name: "Approval konsep oleh client", role: "ba" },
        { name: "Coding, sampai trial dan FAT coding", role: "programmer" },
        { name: "FAT Coding", role: "qa" },
        { name: "FAT--> FAT ditutup setelah perbaikan inputan dari client saat sosialisasi", role: "ba" },
        { name: "Perbaikan setelah FAT", role: "programmer" },
        { name: "Sosialisasi + IK penggunaan", role: "ba" },
        { name: "UAT, ditutup setelah dapat approval UAT", role: "ba" },
        { name: "Perbaikan setelah UAT", role: "programmer" },
        { name: "Go live, sampai go live approval", role: "ba" },
        { name: "Perbaikan setelah Go live approval", role: "programmer" },
        { name: "Others, meeting", role: "others", no_date: true }
    ];

    // Helper to get PIC display text
    function getPicText(role) {
        if (role === 'ba') {
            var baText = $('#ba_id option:selected').text();
            return baText && $('#ba_id').val() ? baText : 'Bisnis analis';
        } else if (role === 'programmer') {
            var progText = $('#programmer_id option:selected').text();
            return progText && $('#programmer_id').val() ? progText : 'Programmer';
        } else if (role === 'qa') {
            var qaText = $('#qa_id option:selected').text();
            return qaText && $('#qa_id').val() ? qaText : 'QA Program';
        } else {
            return 'Team';
        }
    }

    // Function to add a new Module with its 12 fixed Tahapan
    function createModuleBlock(moduleName) {
        moduleCount++;
        var modClass = 'mod-group-' + moduleCount;

        fixedTahapan.forEach(function(step, idx) {
            rowNo++;
            var picDisplay = getPicText(step.role);

            var dateInputHtml = step.no_date 
                ? '<span class="text-muted small">-</span>' 
                : '<input type="text" name="tahapan_due_date[]" class="form-control form-control-sm flatpickr-row bg-excel-yellow" placeholder="Select date" />';

            var rowHtml = '<tr class="' + modClass + '">' +
                '<td class="text-center font-monospace small">' + rowNo + '</td>';

            if (idx === 0) {
                rowHtml += '<td rowspan="12" class="fw-bold align-top bg-light">' +
                    '<span>' + moduleName + '</span>' +
                    '<input type="hidden" name="module_names[]" value="' + moduleName + '" />' +
                    '</td>';
            }

            rowHtml += '<td>' +
                '<span class="small">' + step.name + '</span>' +
                '<input type="hidden" name="tahapan_name[]" value="' + step.name + '" />' +
                '<input type="hidden" name="tahapan_module[]" value="' + moduleName + '" />' +
                '<input type="hidden" name="tahapan_role[]" value="' + step.role + '" />' +
                '</td>' +
                '<td class="pic-cell-' + step.role + ' small fw-bold">' + picDisplay + '</td>' +
                '<td><input type="number" step="0.5" min="0" name="tahapan_manhour[]" class="form-control form-control-sm mh-input bg-excel-yellow fw-bold" data-role="' + step.role + '" placeholder="0" value="0" /></td>' +
                '<td>' + dateInputHtml + '</td>' +
                '<td class="text-center">';

            if (idx === 0) {
                rowHtml += '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-mod" data-group="' + modClass + '"><i class="fa fa-trash"></i></button>';
            }

            rowHtml += '</td></tr>';

            $('#tahapan-tbody').append(rowHtml);
        });

        // Re-initialize flatpickr on new row inputs
        flatpickr('.flatpickr-row', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    }

    // Default create 1st Module on open
    createModuleBlock('Modul 1');

    // Add module button
    $('#btn-add-module').click(function() {
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
                if (!value || !value.trim()) {
                    return 'Nama modul tidak boleh kosong!';
                }
            }
        }).then(function(result) {
            if (result.isConfirmed && result.value) {
                createModuleBlock(result.value.trim());
            }
        });
    });

    // Remove Module block
    $(document).on('click', '.btn-remove-mod', function() {
        var groupClass = $(this).data('group');
        Swal.fire({
            title: 'Hapus Modul?',
            text: 'Modul ini dan 12 tahapannya akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $('.' + groupClass).remove();
                recalculateManhours();
            }
        });
    });

    // Update PIC text live when roles selected in header dropdowns
    $('.role-user-select').change(function() {
        var role = $(this).data('role');
        var selectedText = $(this).find('option:selected').text();
        if ($(this).val()) {
            $('.pic-cell-' + role).text(selectedText);
        }
    });

    // Recalculate Manhours
    function recalculateManhours() {
        var totalBA = 0;
        var totalProg = 0;

        $('.mh-input').each(function() {
            var val = parseFloat($(this).val()) || 0;
            var role = $(this).data('role');

            if (role === 'ba') {
                totalBA += val;
            } else if (role === 'programmer') {
                totalProg += val;
            }
        });

        $('#target_mh_ba').val(totalBA);
        $('#target_mh_programmer').val(totalProg);
    }

    $(document).on('input change', '.mh-input', function() {
        recalculateManhours();
    });

    // Form Submit AJAX
    $('#form-create-project').on('submit', function(e) {
        e.preventDefault();
        $('#btn-save-project').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

        $.post('<?= site_url("projects_management/create"); ?>', $(this).serialize(), function(res) {
            $('#btn-save-project').prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Project');
            if (res.status === 1) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.pesan, timer: 1500, showConfirmButton: false }).then(function() { location.reload(); });
            } else {
                Swal.fire('Gagal', res.pesan, 'error');
            }
        }, 'json');
    });
});
</script>
