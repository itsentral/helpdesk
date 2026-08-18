<?php
$is_edit     = !empty($activity);
$is_readonly = isset($readonly) && $readonly === true;
$today       = date('Y-m-d');
$image_extensions = array('jpg', 'jpeg', 'png');
$disabled    = $is_readonly ? 'disabled' : '';
?>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

<!-- Viewer.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

<style>
.attachment-thumbnail {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid #dee2e6;
    transition: border-color 0.2s;
}
.attachment-thumbnail:hover {
    border-color: #0d6efd;
}
.existing-attachment-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 12px;
    background: #fafbfc;
    transition: box-shadow 0.2s;
}
.existing-attachment-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.edit-catatan-input {
    display: none;
}
.edit-catatan-input.active {
    display: block;
}
.catatan-display.hidden {
    display: none;
}
</style>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-1"></i> <?= $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-1"></i> <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?= $is_readonly ? '#' : $form_action; ?>" method="post" enctype="multipart/form-data" id="form-activity">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?= $activity['id']; ?>">
    <?php endif; ?>

    <!-- Activity Details Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title m-0 font-weight-bold text-primary">
                <i class="fa fa-<?= $is_readonly ? 'eye' : 'edit'; ?> me-2"></i> <?= $is_readonly ? 'Detail Aktivitas' : 'Detail Aktivitas'; ?>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="activity_date" class="form-label">Tanggal Aktivitas <?php if (!$is_readonly): ?><span class="text-danger">*</span><?php endif; ?></label>
                        <input type="text" class="form-control" id="activity_date" name="activity_date"
                               value="<?= $is_edit ? $activity['activity_date'] : $today; ?>"
                               placeholder="Pilih tanggal..." <?= $disabled; ?> <?= $is_readonly ? '' : 'required'; ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="manhour" class="form-label">Man Hour <?php if (!$is_readonly): ?><span class="text-danger">*</span><?php endif; ?></label>
                        <input type="number" class="form-control" id="manhour" name="manhour"
                               step="0.5" min="0.5" placeholder="Minimal 0.5"
                               value="<?= $is_edit ? $activity['manhour'] : ''; ?>" <?= $disabled; ?> <?= $is_readonly ? '' : 'required'; ?>>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="activity_description" class="form-label">Aktivitas <?php if (!$is_readonly): ?><span class="text-danger">*</span><?php endif; ?></label>
                <textarea class="form-control" id="activity_description" name="activity_description"
                          rows="4" placeholder="Deskripsi aktivitas yang dilakukan..." <?= $disabled; ?> <?= $is_readonly ? '' : 'required'; ?>><?= $is_edit ? htmlspecialchars($activity['activity_description'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="remarks" class="form-label">Keterangan</label>
                <textarea class="form-control" id="remarks" name="remarks"
                          rows="2" placeholder="Keterangan tambahan (opsional)" <?= $disabled; ?>><?= $is_edit && $activity['remarks'] ? htmlspecialchars($activity['remarks'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </div>
        </div>
    </div>

    <!-- Existing Attachments -->
    <?php if ($is_edit && !empty($attachments)): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title m-0 font-weight-bold text-primary">
                <i class="fa fa-paperclip me-2"></i> Lampiran Tersimpan
            </h5>
        </div>
        <div class="card-body">
            <div id="existing-attachments-container">
                <?php foreach ($attachments as $att):
                    $ext = strtolower(pathinfo($att['file_name_original'], PATHINFO_EXTENSION));
                    $is_image = in_array($ext, $image_extensions);
                ?>
                <div class="existing-attachment-card" id="att-card-<?= $att['id']; ?>">
                    <div class="row align-items-center">
                        <!-- Preview / Icon -->
                        <div class="col-md-1 text-center">
                            <?php if ($is_image): ?>
                                <img src="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>"
                                     class="attachment-thumbnail viewer-image"
                                     alt="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>"
                                     data-original="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center" style="width:80px;height:80px;margin:0 auto;">
                                   <?php
                                    $icon_name = 'fa-file';
                                    $icon_color = '#6c757d'; // default abu-abu

                                    if ($ext === 'pdf') {
                                        $icon_name = 'fa-file-pdf';
                                        $icon_color = '#dc3545'; // merah
                                    } elseif (in_array($ext, ['xls', 'xlsx'])) {
                                        $icon_name = 'fa-file-excel';
                                        $icon_color = '#198754'; // hijau
                                    } elseif (in_array($ext, ['doc', 'docx'])) {
                                        $icon_name = 'fa-file-word';
                                        $icon_color = '#0d6efd'; // biru
                                    }
                                    ?>
                                    <i class="fas <?= $icon_name; ?>" style="font-size:40px;color:<?= $icon_color; ?>;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info -->
                        <div class="col-md-<?= $is_readonly ? '7' : '5'; ?>">
                            <div class="mb-1">
                                <strong>
                                    <a href="<?= site_url('non_project_activities/download/' . $att['id']); ?>" title="Download">
                                        <i class="fa fa-download me-1"></i> <?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </strong>
                            </div>
                            <div class="catatan-display" id="catatan-display-<?= $att['id']; ?>">
                                <small class="text-muted">Catatan:</small>
                                <span class="catatan-text"><?= $att['catatan'] ? htmlspecialchars($att['catatan'], ENT_QUOTES, 'UTF-8') : '<em class="text-muted">Belum ada catatan</em>'; ?></span>
                            </div>
                            <?php if (!$is_readonly): ?>
                            <div class="edit-catatan-input" id="catatan-edit-<?= $att['id']; ?>">
                                <textarea class="form-control form-control-sm mt-1" id="catatan-input-<?= $att['id']; ?>"
                                          rows="2" placeholder="Tulis catatan..."><?= $att['catatan'] ? htmlspecialchars($att['catatan'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!$is_readonly): ?>
                        <!-- Replace file (for images) -->
                        <div class="col-md-2">
                            <?php if ($is_image): ?>
                            <div class="edit-file-input" id="file-edit-<?= $att['id']; ?>" style="display:none;">
                                <input type="file" class="form-control form-control-sm replace-file-input"
                                       data-id="<?= $att['id']; ?>" accept=".jpg,.jpeg,.png">
                                <small class="text-muted">Ganti gambar</small>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-attachment me-1" data-id="<?= $att['id']; ?>" <?= $is_image ? 'data-is-image="1"' : ''; ?> title="Edit">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-success btn-save-attachment me-1" data-id="<?= $att['id']; ?>" style="display:none;" title="Simpan">
                                <i class="fa fa-check"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary btn-cancel-edit me-1" data-id="<?= $att['id']; ?>" style="display:none;" title="Batal">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-attachment" data-id="<?= $att['id']; ?>" title="Hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <?php else: ?>
                        <!-- Readonly: only download -->
                        <div class="col-md-4 text-end">
                            <a href="<?= site_url('non_project_activities/download/' . $att['id']); ?>" class="btn btn-sm btn-outline-success" title="Download">
                                <i class="fa fa-download"></i> Download
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!$is_readonly): ?>
    <!-- New Attachments Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 font-weight-bold text-primary">
                <i class="fa fa-upload me-2"></i> <?= $is_edit ? 'Tambah Lampiran Baru' : 'Lampiran'; ?>
            </h5>
            <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-attachment">
                <i class="fa fa-plus me-1"></i> Tambah Lampiran
            </button>
        </div>
        <div class="card-body">
            <div id="attachment-container">
                <div class="attachment-row border rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-2">
                                <label class="form-label">File</label>
                                <input type="file" class="form-control form-control-sm" name="attachments[]"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                <small class="text-muted">Max 5MB. Format: pdf, doc, docx, xls, xlsx, jpg, jpeg, png</small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-2">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control form-control-sm" name="catatan_attachment[]"
                                          rows="2" placeholder="Catatan untuk lampiran ini..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row mb-2" title="Hapus baris">
                                <i class="fa fa-times"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-muted small mb-0"><i class="fa fa-info-circle me-1"></i> Klik "Tambah Lampiran" untuk menambah file baru.</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form Actions -->
    <div class="d-flex justify-content-between pb-4">
        <a href="<?= site_url('non_project_activities'); ?>" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
        <?php if (!$is_readonly): ?>
        <button type="submit" class="btn btn-primary" id="btn-submit">
            <i class="fa fa-save me-1"></i> Simpan
        </button>
        <?php endif; ?>
    </div>
</form>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<!-- Viewer.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<script>
$(document).ready(function() {

    // ========== FLATPICKR INIT ==========
    <?php if (!$is_readonly): ?>
    flatpickr('#activity_date', {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        locale: 'id',
        defaultDate: '<?= $is_edit ? $activity['activity_date'] : $today; ?>',
        allowInput: true
    });
    <?php endif; ?>

    // ========== VIEWER.JS INIT (for existing image attachments) ==========
    <?php if ($is_edit && !empty($attachments)): ?>
    var viewerContainer = document.getElementById('existing-attachments-container');
    if (viewerContainer) {
        var viewer = new Viewer(viewerContainer, {
            filter: function(image) {
                return image.classList.contains('viewer-image');
            },
            toolbar: {
                zoomIn: 1, zoomOut: 1, oneToOne: 1, reset: 1,
                prev: 1, play: 0, next: 1,
                rotateLeft: 1, rotateRight: 1, flipHorizontal: 1, flipVertical: 1
            },
            title: true,
            navbar: true
        });
    }
    <?php endif; ?>

    <?php if (!$is_readonly): ?>
    // ========== DYNAMIC ADD/REMOVE ATTACHMENT ROWS ==========
    var attachmentIndex = 1;

    $('#btn-add-attachment').on('click', function() {
        attachmentIndex++;
        var newRow = `
            <div class="attachment-row border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <div class="mb-2">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control form-control-sm" name="attachments[]"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <small class="text-muted">Max 5MB. Format: pdf, doc, docx, xls, xlsx, jpg, jpeg, png</small>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-2">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control form-control-sm" name="catatan_attachment[]"
                                      rows="2" placeholder="Catatan untuk lampiran ini..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row mb-2" title="Hapus baris">
                            <i class="fa fa-times"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#attachment-container').append(newRow);
    });

    $(document).on('click', '.btn-remove-row', function() {
        var container = $('#attachment-container');
        if (container.find('.attachment-row').length > 1) {
            $(this).closest('.attachment-row').remove();
        } else {
            var row = $(this).closest('.attachment-row');
            row.find('input[type="file"]').val('');
            row.find('textarea').val('');
        }
    });

    // ========== FORM SUBMIT WITH CONFIRMATION ==========
    $('#form-activity').on('submit', function(e) {
        e.preventDefault();
        var form = this;

        var description = $('#activity_description').val().trim();
        var manhour = parseFloat($('#manhour').val());

        if (!description) {
            Swal.fire('Peringatan', 'Aktivitas wajib diisi', 'warning');
            $('#activity_description').focus();
            return false;
        }

        if (isNaN(manhour) || manhour < 0.5) {
            Swal.fire('Peringatan', 'Man hour wajib diisi minimal 0.5', 'warning');
            $('#manhour').focus();
            return false;
        }

        // Validate file inputs
        var allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
        var maxSize = 5 * 1024 * 1024;
        var fileInputs = $('input[type="file"][name="attachments[]"]');
        var hasError = false;

        fileInputs.each(function() {
            if (this.files.length > 0) {
                var file = this.files[0];
                var ext = file.name.split('.').pop().toLowerCase();

                if (allowedTypes.indexOf(ext) === -1) {
                    Swal.fire('Peringatan', 'Tipe file "' + file.name + '" tidak diizinkan. Format yang diperbolehkan: ' + allowedTypes.join(', '), 'warning');
                    hasError = true;
                    return false;
                }

                if (file.size > maxSize) {
                    Swal.fire('Peringatan', 'Ukuran file "' + file.name + '" melebihi batas maksimum 5MB', 'warning');
                    hasError = true;
                    return false;
                }
            }
        });

        if (hasError) return false;

        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: 'Apakah Anda yakin ingin menyimpan data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // ========== EDIT EXISTING ATTACHMENT ==========
    $(document).on('click', '.btn-edit-attachment', function() {
        var id = $(this).data('id');
        var isImage = $(this).data('is-image');

        $('#catatan-display-' + id).addClass('hidden');
        $('#catatan-edit-' + id).addClass('active');
        if (isImage) { $('#file-edit-' + id).show(); }

        $(this).hide();
        $('#att-card-' + id + ' .btn-save-attachment').show();
        $('#att-card-' + id + ' .btn-cancel-edit').show();
    });

    $(document).on('click', '.btn-cancel-edit', function() {
        var id = $(this).data('id');

        $('#catatan-display-' + id).removeClass('hidden');
        $('#catatan-edit-' + id).removeClass('active');
        $('#file-edit-' + id).hide();
        $('#file-edit-' + id + ' input[type="file"]').val('');

        $(this).hide();
        $('#att-card-' + id + ' .btn-save-attachment').hide();
        $('#att-card-' + id + ' .btn-edit-attachment').show();
    });

    $(document).on('click', '.btn-save-attachment', function() {
        var id = $(this).data('id');
        var catatan = $('#catatan-input-' + id).val();
        var fileInput = $('#file-edit-' + id + ' input[type="file"]')[0];

        var formData = new FormData();
        formData.append('id', id);
        formData.append('catatan', catatan);
        formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');

        if (fileInput && fileInput.files.length > 0) {
            var file = fileInput.files[0];
            var ext = file.name.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png'].indexOf(ext) === -1) {
                Swal.fire('Peringatan', 'Hanya file gambar (jpg, jpeg, png) yang diperbolehkan', 'warning');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Peringatan', 'Ukuran file melebihi 5MB', 'warning');
                return;
            }
            formData.append('attachment_file', file);
        }

        $.ajax({
            url: '<?= site_url("non_project_activities/update_attachment"); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var catatanText = response.data.catatan ? response.data.catatan : '<em class="text-muted">Belum ada catatan</em>';
                    $('#catatan-display-' + id + ' .catatan-text').html(catatanText);

                    if (response.data.file_name_hash) {
                        var newSrc = '<?= base_url("uploads/non_project/"); ?>' + response.data.file_name_hash;
                        $('#att-card-' + id + ' .viewer-image').attr('src', newSrc).attr('data-original', newSrc);
                        $('#att-card-' + id + ' a[title="Download"]').html('<i class="fa fa-download me-1"></i> ' + response.data.file_name_original);
                        if (typeof viewer !== 'undefined') viewer.update();
                    }

                    $('#catatan-display-' + id).removeClass('hidden');
                    $('#catatan-edit-' + id).removeClass('active');
                    $('#file-edit-' + id).hide();
                    $('#file-edit-' + id + ' input[type="file"]').val('');
                    $('#att-card-' + id + ' .btn-save-attachment').hide();
                    $('#att-card-' + id + ' .btn-cancel-edit').hide();
                    $('#att-card-' + id + ' .btn-edit-attachment').show();

                    Swal.fire({ title: 'Berhasil!', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            error: function() { Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error'); }
        });
    });

    // ========== DELETE EXISTING ATTACHMENT ==========
    $(document).on('click', '.btn-delete-attachment', function() {
        var attachmentId = $(this).data('id');
        var card = $('#att-card-' + attachmentId);

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus lampiran ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url("non_project_activities/delete_attachment"); ?>',
                    type: 'POST',
                    data: {
                        id: attachmentId,
                        <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            card.fadeOut(300, function() {
                                $(this).remove();
                                if (typeof viewer !== 'undefined') viewer.update();
                            });
                            Swal.fire({ title: 'Berhasil!', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false });
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function() { Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error'); }
                });
            }
        });
    });
    <?php endif; // end !$is_readonly ?>

});
</script>
