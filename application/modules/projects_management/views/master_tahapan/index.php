<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<style>
/* iOS Style Toggle Switch with Inner Text */
.ios-switch-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    vertical-align: middle;
}

.ios-switch-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    margin: 0;
    pointer-events: none;
}

.ios-switch-label {
    position: relative;
    display: flex;
    align-items: center;
    width: 86px;
    height: 30px;
    background-color: #e9e9ea;
    border: 1.5px solid #d1d1d6;
    border-radius: 30px;
    cursor: pointer;
    user-select: none;
    transition: background-color 0.35s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    margin: 0 auto;
    overflow: hidden;
}

/* Bulatan (Knob) */
.ios-switch-knob {
    position: absolute;
    top: 2px;
    left: 2px;
    width: 23px;
    height: 23px;
    background: #ffffff;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.25);
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), width 0.2s ease;
    z-index: 3;
}

/* Teks Active & Inactive di dalam toggle */
.ios-switch-text {
    position: absolute;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    transition: opacity 0.25s ease, transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
    z-index: 2;
}

.ios-switch-text-active {
    left: 10px;
    color: #ffffff;
    opacity: 0;
    transform: translateX(-12px);
}

.ios-switch-text-inactive {
    right: 9px;
    color: #8e8e93;
    opacity: 1;
    transform: translateX(0);
}

/* Checked State (Active) */
.ios-switch-input:checked + .ios-switch-label {
    background-color: #34c759;
    border-color: #34c759;
}

.ios-switch-input:checked + .ios-switch-label .ios-switch-knob {
    transform: translateX(55px);
}

.ios-switch-input:checked + .ios-switch-label .ios-switch-text-active {
    opacity: 1;
    transform: translateX(0);
}

.ios-switch-input:checked + .ios-switch-label .ios-switch-text-inactive {
    opacity: 0;
    transform: translateX(12px);
}

/* Sedikit efek elastis saat diklik */
.ios-switch-label:active .ios-switch-knob {
    width: 26px;
}
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 fw-bold text-primary"><i class="fa fa-list-ol me-2"></i> Master Tahapan</h5>
        <button type="button" class="btn btn-sm btn-success" id="btn-add-tahapan"><i class="fa fa-plus me-1"></i> Tambah Tahapan</button>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Kelola daftar tahapan yang akan otomatis di-generate setiap kali membuat modul baru. Anda dapat mengubah urutan dengan menggeser baris (drag & drop).</p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table text-center">
                    <tr>
                        <th width="80">Urutan</th>
                        <th>Nama Tahapan</th>
                        <th width="130">Default Role</th>
                        <th width="115">Status</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tahapan-table-body">
                    <?php if (!empty($tahapan)): foreach ($tahapan as $t): ?>
                    <tr class="tahapan-row" data-id="<?= $t['id']; ?>" data-order="<?= $t['tahapan_order']; ?>">
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa fa-grip-vertical text-muted drag-handle me-2" style="cursor: grab;" title="Geser untuk mengubah urutan"></i>
                                <span class="badge bg-dark rounded-pill px-2 py-1 order-number"><?= $t['tahapan_order']; ?></span>
                            </div>
                        </td>
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
                            <div class="ios-switch-wrapper">
                                <input type="checkbox" class="ios-switch-input toggle-status-switch" 
                                       id="switch-status-<?= $t['id']; ?>" 
                                       data-id="<?= $t['id']; ?>" 
                                       <?= $t['is_active'] ? 'checked' : ''; ?>>
                                <label class="ios-switch-label" for="switch-status-<?= $t['id']; ?>" title="Klik untuk mengubah status">
                                    <span class="ios-switch-knob"></span>
                                    <span class="ios-switch-text ios-switch-text-active">Active</span>
                                    <span class="ios-switch-text ios-switch-text-inactive">Inactive</span>
                                </label>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-tahapan"
                                data-id="<?= $t['id']; ?>"
                                data-order="<?= $t['tahapan_order']; ?>"
                                data-name="<?= html_escape($t['tahapan_name']); ?>"
                                data-role="<?= $t['default_role']; ?>"
                                title="Edit Tahapan">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-tahapan" 
                                data-id="<?= $t['id']; ?>" 
                                data-name="<?= html_escape($t['tahapan_name']); ?>"
                                title="Hapus Tahapan">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr id="empty-row"><td colspan="5" class="text-center text-muted py-3">Belum ada data tahapan.</td></tr>
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
                    <input type="hidden" name="conflict_action" id="tahapan-conflict-action" value="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Urutan <span class="text-danger">*</span></label>
                        <input type="number" name="tahapan_order" id="tahapan-order" class="form-control form-control-sm" min="1" required />
                        <div id="order-feedback" class="form-text small mt-1"></div>
                        <div class="mt-2" id="order-badges-wrapper">
                            <label class="small text-muted d-block mb-1"><i class="fa fa-info-circle"></i> Urutan yang sudah terdaftar:</label>
                            <div id="existing-orders-badges" class="d-flex flex-wrap gap-1" style="max-height: 120px; overflow-y: auto;"></div>
                        </div>
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
                    <button type="submit" class="btn btn-sm btn-primary fw-bold" id="btn-submit-tahapan"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var modal = new bootstrap.Modal(document.getElementById('modal-tahapan-form'));

    // Toast configuration untuk notifikasi singkat
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });

    // Data tahapan aktif dari server
    var tahapanList = <?= !empty($tahapan) ? json_encode(array_map(function($t) {
        return array(
            'id'    => (int)$t['id'],
            'order' => (int)$t['tahapan_order'],
            'name'  => $t['tahapan_name']
        );
    }, $tahapan)) : '[]'; ?>;

    // Helper: sinkronkan urutan angka di DOM dan kirim update ke server jika isServerSync true
    function syncOrderNumbers(isServerSync) {
        var $rows = $('#tahapan-table-body tr.tahapan-row');
        var orders = [];

        $rows.each(function(index) {
            var newOrder = index + 1;
            var rowId = parseInt($(this).data('id'), 10);

            $(this).data('order', newOrder);
            $(this).find('.order-number').text(newOrder);
            $(this).find('.btn-edit-tahapan').data('order', newOrder);

            // Update objek di tahapanList
            for (var i = 0; i < tahapanList.length; i++) {
                if (tahapanList[i].id === rowId) {
                    tahapanList[i].order = newOrder;
                    break;
                }
            }

            orders.push({ id: rowId, order: newOrder });
        });

        // Urutkan ulang tahapanList berdasarkan urutan baru
        tahapanList.sort(function(a, b) { return a.order - b.order; });

        if (isServerSync && orders.length > 0) {
            $.post('<?= site_url("projects_management/master_tahapan/reorder"); ?>', { orders: orders }, function(res) {
                if (res.status === 1) {
                    Toast.fire({ icon: 'success', title: res.pesan });
                } else {
                    Swal.fire('Gagal', res.pesan, 'error');
                }
            }, 'json');
        }
    }

    // Inisialisasi SortableJS untuk Drag & Drop
    var sortableEl = document.getElementById('tahapan-table-body');
    if (sortableEl && typeof Sortable !== 'undefined') {
        Sortable.create(sortableEl, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'table-primary',
            onEnd: function(evt) {
                // Jika posisi dilepas pada tempat semula, jangan jalankan apa pun
                if (evt.oldIndex === evt.newIndex) {
                    return;
                }
                syncOrderNumbers(true);
            }
        });
    }

    // Helper: render badge urutan yang sudah ada
    function renderOrderBadges(currentId) {
        var $container = $('#existing-orders-badges');
        $container.empty();

        if (!tahapanList || tahapanList.length === 0) {
            $container.html('<span class="text-muted small fst-italic">Belum ada tahapan terdaftar.</span>');
            return;
        }

        tahapanList.forEach(function(item) {
            var isCurrentEditing = (currentId && item.id === parseInt(currentId, 10));
            var badgeClass = isCurrentEditing ? 'bg-primary text-white border-primary' : 'bg-light text-dark border';
            var titleText = item.name + (isCurrentEditing ? ' (Sedang diedit)' : '');
            
            var badgeHtml = $('<span class="badge ' + badgeClass + ' me-1 mb-1 p-2" title="' + $('<div>').text(titleText).html() + '" style="cursor: default; font-weight: 500;">' +
                '<strong class="me-1">#' + item.order + '</strong>' + $('<div>').text(item.name).html() +
                (isCurrentEditing ? ' <span class="badge bg-warning text-dark ms-1">diedit</span>' : '') +
            '</span>');
            $container.append(badgeHtml);
        });
    }

    // Helper: validasi urutan secara realtime
    function validateOrderRealtime() {
        var inputVal = $('#tahapan-order').val().trim();
        var currentId = parseInt($('#tahapan-id').val(), 10) || 0;
        var $orderInput = $('#tahapan-order');
        var $feedback = $('#order-feedback');
        var $btnSubmit = $('#btn-submit-tahapan');

        $orderInput.removeClass('is-valid is-invalid border-warning');

        if (!inputVal) {
            $feedback.html('');
            $btnSubmit.prop('disabled', false);
            return;
        }

        var orderNum = parseInt(inputVal, 10);
        if (orderNum <= 0) {
            $orderInput.addClass('is-invalid');
            $feedback.html('<span class="text-danger"><i class="fa fa-times-circle me-1"></i>Urutan harus berupa angka minimal 1.</span>');
            $btnSubmit.prop('disabled', true);
            return;
        }

        // Cek apakah nomor urut sudah terpakai oleh tahapan lain
        var conflict = null;
        for (var i = 0; i < tahapanList.length; i++) {
            if (tahapanList[i].order === orderNum && tahapanList[i].id !== currentId) {
                conflict = tahapanList[i];
                break;
            }
        }

        if (conflict) {
            // Beri visual warning, tombol simpan tetap AKTIF
            $orderInput.addClass('border-warning');
            var actionText = currentId > 0 
                ? 'Jika disimpan, posisi urutan akan <b>saling bertukar</b> dengan tahapan ini.'
                : 'Jika disimpan, tahapan ini akan <b>disisipkan</b> di urutan ini dan tahapan setelahnya digeser ke bawah.';

            $feedback.html('<div class="alert alert-warning py-1 px-2 mb-0 mt-1 small d-flex align-items-start">' +
                '<i class="fa fa-exclamation-triangle text-warning me-2 mt-1"></i>' +
                '<div>Urutan ke-<strong>' + orderNum + '</strong> sudah dipakai oleh "<strong>' + $('<div>').text(conflict.name).html() + '</strong>".<br>' + actionText + '</div>' +
            '</div>');
            $btnSubmit.prop('disabled', false);
        } else {
            $orderInput.addClass('is-valid');
            $feedback.html('<span class="text-success small"><i class="fa fa-check-circle me-1"></i>Urutan ke-' + orderNum + ' tersedia.</span>');
            $btnSubmit.prop('disabled', false);
        }
    }

    // Event listener realtime saat mengetik/mengubah urutan
    $('#tahapan-order').on('input keyup change', validateOrderRealtime);

    // Add
    $('#btn-add-tahapan').click(function() {
        var maxOrder = 0;
        tahapanList.forEach(function(item) {
            if (item.order > maxOrder) maxOrder = item.order;
        });
        var nextOrder = maxOrder + 1;

        $('#tahapan-id').val('');
        $('#tahapan-conflict-action').val('');
        $('#tahapan-order').val(nextOrder);
        $('#tahapan-name').val('');
        $('#tahapan-role').val('ba');
        $('#modal-tahapan-title').html('<i class="fa fa-plus me-1"></i> Tambah Tahapan');
        
        renderOrderBadges(0);
        validateOrderRealtime();
        modal.show();
    });

    // Edit
    $(document).on('click', '.btn-edit-tahapan', function() {
        var id = $(this).data('id');
        var order = $(this).data('order');
        var name = $(this).data('name');
        var role = $(this).data('role');

        $('#tahapan-id').val(id);
        $('#tahapan-conflict-action').val('');
        $('#tahapan-order').val(order);
        $('#tahapan-name').val(name);
        $('#tahapan-role').val(role);
        $('#modal-tahapan-title').html('<i class="fa fa-pencil me-1"></i> Edit Tahapan');
        
        renderOrderBadges(id);
        validateOrderRealtime();
        modal.show();
    });

    // Submit form handler dengan dukungan konfirmasi aksi
    function submitTahapanForm(conflictAction) {
        if (conflictAction) {
            $('#tahapan-conflict-action').val(conflictAction);
        } else {
            $('#tahapan-conflict-action').val('');
        }

        var $btnSubmit = $('#btn-submit-tahapan');
        var originalBtnHtml = $btnSubmit.html();
        $btnSubmit.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');

        $.post('<?= site_url("projects_management/master_tahapan/save"); ?>', $('#form-save-tahapan').serialize(), function(res) {
            $btnSubmit.prop('disabled', false).html(originalBtnHtml);

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
            } else if (res.status === 'confirm') {
                var isSwap = (res.action_type === 'swap');
                var confirmTitle = isSwap ? 'Tukar Posisi Urutan?' : 'Sisipkan Urutan Tahapan?';
                var confirmBtnText = isSwap ? '<i class="fa fa-exchange me-1"></i> Ya, Tukar Urutan' : '<i class="fa fa-level-down me-1"></i> Ya, Sisipkan & Geser';
                var confirmBtnColor = isSwap ? '#0d6efd' : '#198754';

                Swal.fire({
                    title: confirmTitle,
                    text: res.pesan,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: confirmBtnColor,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: confirmBtnText,
                    cancelButtonText: 'Batal'
                }).then(function(r) {
                    if (r.isConfirmed) {
                        submitTahapanForm(res.action_type);
                    }
                });
            } else {
                Swal.fire('Gagal', res.pesan, 'error');
            }
        }, 'json').fail(function() {
            $btnSubmit.prop('disabled', false).html(originalBtnHtml);
            Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
        });
    }

    // Save
    $('#form-save-tahapan').on('submit', function(e) {
        e.preventDefault();
        submitTahapanForm();
    });

    // Toggle active via iOS Switch
    $(document).on('change', '.toggle-status-switch', function() {
        var $checkbox = $(this);
        var id = $checkbox.data('id');
        var isChecked = $checkbox.is(':checked');

        $.post('<?= site_url("projects_management/master_tahapan/toggle_active"); ?>', { id: id }, function(res) {
            if (res.status === 1) {
                Toast.fire({
                    icon: 'success',
                    title: isChecked ? 'Status diubah: Active' : 'Status diubah: Inactive'
                });
            } else {
                $checkbox.prop('checked', !isChecked);
                Swal.fire('Gagal', res.pesan, 'error');
            }
        }, 'json').fail(function() {
            $checkbox.prop('checked', !isChecked);
            Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
        });
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
