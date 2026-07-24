<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 fw-bold text-primary"><i class="fa fa-list-ol me-2"></i> Master Tahapan</h5>
        <button type="button" class="btn btn-sm btn-success" id="btn-add-tahapan"><i class="fa fa-plus me-1"></i> Tambah Tahapan</button>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Kelola daftar tahapan yang akan otomatis di-generate setiap kali membuat modul baru. Urutannya menentukan alur pengerjaan (sequential).</p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table text-center">
                    <tr>
                        <th width="60">Urutan</th>
                        <th>Nama Tahapan</th>
                        <th width="130">Default Role</th>
                        <th width="80">Status</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tahapan)): foreach ($tahapan as $t): ?>
                    <tr>
                        <td class="text-center fw-bold"><?= $t['tahapan_order']; ?></td>
                        <td><?= html_escape($t['tahapan_name']); ?></td>
                        <td class="text-center">
                            <?php
                            $role_lbl = 'bg-secondary';
                            if ($t['default_role'] === 'ba') $role_lbl = 'bg-info';
                            elseif ($t['default_role'] === 'programmer') $role_lbl = 'bg-primary';
                            elseif ($t['default_role'] === 'qa') $role_lbl = 'bg-warning text-dark';
                            ?>
                            <span class="badge <?= $role_lbl; ?>"><?= html_escape($t['default_role']); ?></span>
                        </td>
                        <td class="text-center">
                            <?php if ($t['is_active']): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-tahapan"
                                data-id="<?= $t['id']; ?>"
                                data-order="<?= $t['tahapan_order']; ?>"
                                data-name="<?= html_escape($t['tahapan_name']); ?>"
                                data-role="<?= $t['default_role']; ?>">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-<?= $t['is_active'] ? 'warning' : 'success'; ?> btn-toggle-tahapan" data-id="<?= $t['id']; ?>">
                                <i class="fa fa-<?= $t['is_active'] ? 'eye-slash' : 'eye'; ?>"></i> <?= $t['is_active'] ? 'Nonaktif' : 'Aktifkan'; ?>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-tahapan" data-id="<?= $t['id']; ?>" data-name="<?= html_escape($t['tahapan_name']); ?>">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data tahapan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modal-tahapan-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6 fw-bold" id="modal-tahapan-title"><i class="fa fa-plus me-1"></i> Tambah Tahapan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-save-tahapan">
                <div class="modal-body">
                    <input type="hidden" name="id" id="tahapan-id" value="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Urutan <span class="text-danger">*</span></label>
                        <input type="number" name="tahapan_order" id="tahapan-order" class="form-control form-control-sm" min="1" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Tahapan <span class="text-danger">*</span></label>
                        <input type="text" name="tahapan_name" id="tahapan-name" class="form-control form-control-sm" placeholder="Contoh: Konsep (harus plus test case)" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Default Role <span class="text-danger">*</span></label>
                        <select name="default_role" id="tahapan-role" class="form-select form-select-sm" required>
                            <option value="ba">Bisnis Analis (BA)</option>
                            <option value="programmer">Programmer</option>
                            <option value="qa">QA</option>
                            <option value="others">Others / Team</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var modal = new bootstrap.Modal(document.getElementById('modal-tahapan-form'));

    // Add
    $('#btn-add-tahapan').click(function() {
        $('#tahapan-id').val('');
        $('#tahapan-order').val('');
        $('#tahapan-name').val('');
        $('#tahapan-role').val('ba');
        $('#modal-tahapan-title').html('<i class="fa fa-plus me-1"></i> Tambah Tahapan');
        modal.show();
    });

    // Edit
    $(document).on('click', '.btn-edit-tahapan', function() {
        $('#tahapan-id').val($(this).data('id'));
        $('#tahapan-order').val($(this).data('order'));
        $('#tahapan-name').val($(this).data('name'));
        $('#tahapan-role').val($(this).data('role'));
        $('#modal-tahapan-title').html('<i class="fa fa-pencil me-1"></i> Edit Tahapan');
        modal.show();
    });

    // Save
    $('#form-save-tahapan').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= site_url("projects_management/master_tahapan/save"); ?>', $(this).serialize(), function(res) {
            if (res.status === 1) {
                Swal.fire({ icon:'success', title:'Berhasil', text:res.pesan, timer:1500, showConfirmButton:false }).then(function(){ location.reload(); });
            } else { Swal.fire('Gagal', res.pesan, 'error'); }
        }, 'json');
    });

    // Toggle active
    $(document).on('click', '.btn-toggle-tahapan', function() {
        var id = $(this).data('id');
        $.post('<?= site_url("projects_management/master_tahapan/toggle_active"); ?>', { id: id }, function(res) {
            if (res.status === 1) location.reload();
            else Swal.fire('Gagal', res.pesan, 'error');
        }, 'json');
    });

    // Delete
    $(document).on('click', '.btn-delete-tahapan', function() {
        var id = $(this).data('id'), name = $(this).data('name');
        Swal.fire({
            title: 'Hapus "' + name + '"?',
            text: 'Tahapan ini akan dihapus permanen dari master.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc3545', confirmButtonText: 'Hapus', cancelButtonText: 'Batal'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.post('<?= site_url("projects_management/master_tahapan/delete"); ?>', { id: id }, function(res) {
                    if (res.status === 1) { Swal.fire({ icon:'success', title:'Dihapus', timer:1500, showConfirmButton:false }).then(function(){ location.reload(); }); }
                    else { Swal.fire('Gagal', res.pesan, 'error'); }
                }, 'json');
            }
        });
    });
});
</script>
