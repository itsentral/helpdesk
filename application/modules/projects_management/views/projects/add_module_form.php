<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="modal-header bg-primary">
    <h5 class="modal-title text-white fs-6 fw-bold"><i class="fa fa-plus-circle me-1"></i> Tambah Modul Baru - <?= html_escape($project['project_name']); ?></h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="form-add-module">
    <input type="hidden" name="project_id" value="<?= $project['id']; ?>">

    <div class="modal-body p-3" style="max-height: 75vh; overflow-y: auto;">
        <!-- Nama Modul -->
        <div class="mb-3">
            <label class="form-label fw-bold">Nama Modul <span class="text-danger">*</span></label>
            <input type="text" name="module_name" id="input_module_name" class="form-control form-control-sm" placeholder="Contoh: Asset, Inventory, Report..." required />
        </div>

        <!-- 12 Tahapan Fix -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table text-center">
                    <tr>
                        <th width="35">No</th>
                        <th>Tahapan</th>
                        <th width="150">PIC</th>
                        <th width="90">Manhour</th>
                        <th width="130">Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil dari master tahapan (dari controller)
                    foreach ($master_tahapan as $step):
                        $role = $step['role'];
                        $order = $step['order'];

                        // Build PIC options based on role
                        $pic_options = '<option value="">-- Pilih PIC --</option>';
                        if ($role === 'ba' && !empty($ba_users)) {
                            foreach ($ba_users as $u) {
                                $selected = (count($ba_users) === 1) ? 'selected' : '';
                                $pic_options .= '<option value="' . $u['user_id'] . '" ' . $selected . '>' . html_escape($u['nm_lengkap']) . '</option>';
                            }
                        } elseif ($role === 'programmer' && !empty($prog_users)) {
                            foreach ($prog_users as $u) {
                                $selected = (count($prog_users) === 1) ? 'selected' : '';
                                $pic_options .= '<option value="' . $u['user_id'] . '" ' . $selected . '>' . html_escape($u['nm_lengkap']) . '</option>';
                            }
                        } elseif ($role === 'qa' && !empty($qa_users)) {
                            foreach ($qa_users as $u) {
                                $selected = (count($qa_users) === 1) ? 'selected' : '';
                                $pic_options .= '<option value="' . $u['user_id'] . '" ' . $selected . '>' . html_escape($u['nm_lengkap']) . '</option>';
                            }
                        }
                    ?>
                        <tr>
                            <td class="text-center small"><?= $order; ?></td>
                            <td class="small"><?= html_escape($step['name']); ?></td>
                            <td>
                                <?php if ($role === 'others'): ?>
                                    <span class="text-muted small">Team</span>
                                <?php else: ?>
                                    <select name="tahapan_pic[<?= $order; ?>]" class="form-select form-select-sm"><?= $pic_options; ?></select>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="number" step="0.5" min="0" name="tahapan_manhour[<?= $order; ?>]" class="form-control form-control-sm " value="0" />
                            </td>
                            <td>
                                <?php if ($role === 'others'): ?>
                                    <span class="text-muted small">-</span>
                                <?php else: ?>
                                    <input type="text" name="tahapan_duedate[<?= $order; ?>]" class="form-control form-control-sm flatpickr-add-mod " placeholder="Pilih" />
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-footer py-2 bg-light">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-sm btn-primary fw-bold" id="btn-submit-add-module"><i class="fa fa-save me-1"></i> Simpan Modul</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        flatpickr('.flatpickr-add-mod', {
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        $('#form-add-module').on('submit', function(e) {
            e.preventDefault();

            // Validasi: nama modul harus diisi
            var moduleName = $('#input_module_name').val().trim();
            if (!moduleName) {
                Swal.fire('Perhatian', 'Nama modul harus diisi.', 'warning');
                $('#input_module_name').focus();
                return;
            }

            // Validasi: semua PIC harus dipilih (kecuali Others)
            var picValid = true;
            $(this).find('select[name^="tahapan_pic"]').each(function() {
                if (!$(this).val()) {
                    picValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (!picValid) {
                Swal.fire('Perhatian', 'Semua PIC harus dipilih.', 'warning');
                return;
            }

            // Validasi: semua manhour harus > 0
            var mhValid = true;
            $(this).find('input[name^="tahapan_manhour"]').each(function() {
                if (parseFloat($(this).val()) <= 0 || !$(this).val()) {
                    mhValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (!mhValid) {
                Swal.fire('Perhatian', 'Semua manhour harus diisi (minimal 0.5).', 'warning');
                return;
            }

            // Validasi: semua due date harus dipilih (kecuali Others / order 12)
            var dateValid = true;
            $(this).find('input[name^="tahapan_duedate"]').each(function() {
                if (!$(this).val()) {
                    dateValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (!dateValid) {
                Swal.fire('Perhatian', 'Semua due date harus dipilih.', 'warning');
                return;
            }

            var $btn = $('#btn-submit-add-module');
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

            $.post('<?= site_url("projects_management/add_module"); ?>', $(this).serialize(), function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Simpan Modul');
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
        });
    });
</script>