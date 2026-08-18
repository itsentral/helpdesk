<?php
$is_edit     = isset($is_edit) ? $is_edit : !empty($activities);
$is_readonly = isset($readonly) && $readonly === true;
$today       = date('Y-m-d');
$image_extensions = array('jpg', 'jpeg', 'png');
$disabled    = $is_readonly ? 'disabled' : '';
$act_date    = isset($activity_date) ? $activity_date : $today;
$act_items   = isset($activities) && is_array($activities) ? $activities : array();
?>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<!-- Viewer.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

<style>
.attachment-thumbnail { width:80px; height:80px; object-fit:cover; border-radius:6px; cursor:pointer; border:2px solid #dee2e6; transition:border-color .2s; }
.attachment-thumbnail:hover { border-color:#0d6efd; }
.existing-attachment-card { border:1px solid #e9ecef; border-radius:8px; padding:15px; margin-bottom:12px; background:#fafbfc; }
.edit-catatan-input { display:none; }
.edit-catatan-input.active { display:block; }
.catatan-display.hidden { display:none; }

/* --- Revisi layout aktivitas --- */
.activity-date-inline { display:flex; align-items:center; gap:8px; }
.activity-date-inline label { margin:0; white-space:nowrap; font-size:.875rem; color:#6c757d; }
.activity-date-inline input { width:170px; }

.activity-row { border:1px solid #e5e7eb; border-radius:8px; overflow:hidden; margin-bottom:14px; }
.activity-row-header {
    background:#f8f9fa;
    padding:8px 12px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:8px;
    border-bottom:1px solid #e5e7eb;
}
.activity-row-header .activity-index { font-weight:600; color:#495057; font-size:.9rem; }
.activity-row-header .mh-inline { display:flex; align-items:center; gap:6px; }
.activity-row-header .mh-inline label { margin:0; font-size:.8rem; color:#6c757d; white-space:nowrap; }
.activity-row-header .mh-inline input { width:90px; text-align:center; }
.activity-row-body { padding:12px; }
</style>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa fa-exclamation-circle me-1"></i> <?= $this->session->flashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa fa-check-circle me-1"></i> <?= $this->session->flashdata('success'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<form action="<?= $is_readonly ? '#' : $form_action; ?>" method="post" enctype="multipart/form-data" id="form-activity">
    <?php if ($is_edit && !empty($act_items)): ?>
        <input type="hidden" name="reference_id" value="<?= $act_items[0]['id']; ?>">
    <?php endif; ?>

    <!-- Activity Details -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <!-- <h5 class="card-title m-0 font-weight-bold text-primary">
                <i class="fa fa-<?= $is_readonly ? 'eye' : 'edit'; ?> me-2"></i>
                <?= $is_readonly ? 'Detail Aktivitas (View Only)' : 'Detail Aktivitas'; ?>
            </h5> -->

            <div class="activity-date-inline">
                <label for="activity_date">Tanggal Aktivitas <?php if (!$is_readonly): ?><span class="text-danger">*</span><?php endif; ?></label>
                <input type="text" class="form-control form-control-sm" id="activity_date" name="activity_date"
                       value="<?= $act_date; ?>" placeholder="Pilih tanggal..." <?= $disabled; ?>>
            </div>

            <?php if (!$is_readonly): ?>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-activity-row">
                <i class="fa fa-plus me-1"></i> Tambah Aktivitas
            </button>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div id="activity-rows-container">
            <?php if (!empty($act_items)): ?>
                <?php foreach ($act_items as $idx => $act): ?>
                <div class="activity-row">
                    <input type="hidden" name="existing_id[]" value="<?= $act['id']; ?>">
                    <div class="activity-row-header">
                        <span class="activity-index">Aktivitas #<?= $idx + 1; ?></span>
                        <div class="mh-inline">
                            <label>Man Hour <?php if (!$is_readonly): ?><span class="text-danger">*</span><?php endif; ?></label>
                            <input type="number" class="form-control form-control-sm" name="manhour[]" step="0.5" min="0.5" value="<?= $act['manhour']; ?>" <?= $disabled; ?>>
                        </div>
                        <?php if (!$is_readonly): ?>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-activity-row" title="Hapus"><i class="fa fa-times"></i></button>
                        <?php endif; ?>
                    </div>
                    <div class="activity-row-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Aktivitas <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="activity_description[]" rows="3" <?= $disabled; ?>><?= htmlspecialchars($act['activity_description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="remarks[]" rows="3" <?= $disabled; ?>><?= $act['remarks'] ? htmlspecialchars($act['remarks'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="activity-row">
                    <input type="hidden" name="existing_id[]" value="">
                    <div class="activity-row-header">
                        <span class="activity-index">Aktivitas #1</span>
                        <div class="mh-inline">
                            <label>Man Hour <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" name="manhour[]" step="0.5" min="0.5" placeholder="0.5" required>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-activity-row" title="Hapus"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="activity-row-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Aktivitas <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="activity_description[]" rows="3" placeholder="Deskripsi aktivitas..." required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="remarks[]" rows="3" placeholder="Opsional"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Existing Attachments -->
    <?php if ($is_edit && !empty($attachments)): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3"><h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-paperclip me-2"></i> Lampiran Tersimpan</h5></div>
        <div class="card-body">
            <div id="existing-attachments-container">
            <?php foreach ($attachments as $att):
                $ext = strtolower(pathinfo($att['file_name_original'], PATHINFO_EXTENSION));
                $is_image = in_array($ext, $image_extensions);
            ?>
            <div class="existing-attachment-card" id="att-card-<?= $att['id']; ?>">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <?php if ($is_image): ?>
                        <img src="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>" class="attachment-thumbnail viewer-image" alt="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>" data-original="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>">
                        <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center" style="width:80px;height:80px;margin:0 auto;"><i class="fa fa-file-<?= ($ext==='pdf')?'pdf-o':(in_array($ext,['xls','xlsx'])?'excel-o':(in_array($ext,['doc','docx'])?'word-o':'o')); ?>" style="font-size:40px;color:#6c757d;"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-<?= $is_readonly ? '7' : '5'; ?>">
                        <strong><a href="<?= site_url('non_project_activities/download/' . $att['id']); ?>" title="Download"><i class="fa fa-download me-1"></i><?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?></a></strong>
                        <div class="catatan-display" id="catatan-display-<?= $att['id']; ?>"><small class="text-muted">Catatan:</small> <span class="catatan-text"><?= $att['catatan'] ? htmlspecialchars($att['catatan'], ENT_QUOTES, 'UTF-8') : '<em class="text-muted">-</em>'; ?></span></div>
                        <?php if (!$is_readonly): ?>
                        <div class="edit-catatan-input" id="catatan-edit-<?= $att['id']; ?>"><textarea class="form-control form-control-sm mt-1" id="catatan-input-<?= $att['id']; ?>" rows="2"><?= $att['catatan'] ? htmlspecialchars($att['catatan'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea></div>
                        <?php endif; ?>
                    </div>
                    <?php if (!$is_readonly): ?>
                    <div class="col-md-2"><?php if ($is_image): ?><div class="edit-file-input" id="file-edit-<?= $att['id']; ?>" style="display:none;"><input type="file" class="form-control form-control-sm" data-id="<?= $att['id']; ?>" accept=".jpg,.jpeg,.png"><small class="text-muted">Ganti gambar</small></div><?php endif; ?></div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-attachment me-1" data-id="<?= $att['id']; ?>" <?= $is_image ? 'data-is-image="1"' : ''; ?>><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-success btn-save-attachment me-1" data-id="<?= $att['id']; ?>" style="display:none;"><i class="fa fa-check"></i></button>
                        <button type="button" class="btn btn-sm btn-secondary btn-cancel-edit me-1" data-id="<?= $att['id']; ?>" style="display:none;"><i class="fa fa-times"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-attachment" data-id="<?= $att['id']; ?>"><i class="fa fa-trash"></i></button>
                    </div>
                    <?php else: ?>
                    <div class="col-md-3 text-end"><a href="<?= site_url('non_project_activities/download/' . $att['id']); ?>" class="btn btn-sm btn-outline-success"><i class="fa fa-download"></i> Download</a></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!$is_readonly): ?>
    <!-- New Attachments -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-upload me-2"></i> <?= $is_edit ? 'Tambah Lampiran Baru' : 'Lampiran'; ?></h5>
            <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-attachment"><i class="fa fa-plus me-1"></i> Tambah Lampiran</button>
        </div>
        <div class="card-body">
            <div id="attachment-container">
                <div class="attachment-row border rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-md-5"><label class="form-label">File</label><input type="file" class="form-control form-control-sm" name="attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"><small class="text-muted">Max 5MB</small></div>
                        <div class="col-md-5"><label class="form-label">Catatan</label><textarea class="form-control form-control-sm" name="catatan_attachment[]" rows="2" placeholder="Catatan lampiran..."></textarea></div>
                        <div class="col-md-2 d-flex align-items-end"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-row mb-2"><i class="fa fa-times"></i></button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between">
        <a href="<?= site_url('non_project_activities'); ?>" class="btn btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
        <?php if (!$is_readonly): ?><button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button><?php endif; ?>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<script>
$(document).ready(function() {
    <?php if (!$is_readonly): ?>
    flatpickr('#activity_date', { dateFormat:'Y-m-d', altInput:true, altFormat:'d F Y', locale:'id', defaultDate:'<?= $act_date; ?>', allowInput:true });
    <?php endif; ?>

    <?php if ($is_edit && !empty($attachments)): ?>
    var viewerContainer = document.getElementById('existing-attachments-container');
    if (viewerContainer) { var viewer = new Viewer(viewerContainer, { filter:function(img){return img.classList.contains('viewer-image');}, toolbar:{zoomIn:1,zoomOut:1,oneToOne:1,reset:1,prev:1,play:0,next:1,rotateLeft:1,rotateRight:1,flipHorizontal:1,flipVertical:1}, title:true, navbar:true }); }
    <?php endif; ?>

    <?php if (!$is_readonly): ?>
    // Re-number "Aktivitas #x" headers after add/remove
    function renumberActivityRows() {
        $('#activity-rows-container .activity-row').each(function(i) {
            $(this).find('.activity-index').text('Aktivitas #' + (i + 1));
        });
    }

    // Add activity row
    $('#btn-add-activity-row').on('click', function() {
        var newRow = `<div class="activity-row">
            <input type="hidden" name="existing_id[]" value="">
            <div class="activity-row-header">
                <span class="activity-index">Aktivitas</span>
                <div class="mh-inline">
                    <label>Man Hour <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" name="manhour[]" step="0.5" min="0.5" placeholder="0.5" required>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-activity-row" title="Hapus"><i class="fa fa-times"></i></button>
            </div>
            <div class="activity-row-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Aktivitas <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="activity_description[]" rows="3" placeholder="Deskripsi aktivitas..." required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="remarks[]" rows="3" placeholder="Opsional"></textarea>
                    </div>
                </div>
            </div>
        </div>`;
        $('#activity-rows-container').append(newRow);
        renumberActivityRows();
    });
    $(document).on('click', '.btn-remove-activity-row', function() {
        if ($('#activity-rows-container .activity-row').length > 1) {
            $(this).closest('.activity-row').remove();
            renumberActivityRows();
        } else {
            var r = $(this).closest('.activity-row');
            r.find('textarea').val('');
            r.find('input[type="number"]').val('');
        }
    });

    // Add attachment row
    $('#btn-add-attachment').on('click', function() {
        var r = `<div class="attachment-row border rounded p-3 mb-3"><div class="row"><div class="col-md-5"><label class="form-label">File</label><input type="file" class="form-control form-control-sm" name="attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"><small class="text-muted">Max 5MB</small></div><div class="col-md-5"><label class="form-label">Catatan</label><textarea class="form-control form-control-sm" name="catatan_attachment[]" rows="2" placeholder="Catatan lampiran..."></textarea></div><div class="col-md-2 d-flex align-items-end"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-row mb-2"><i class="fa fa-times"></i></button></div></div></div>`;
        $('#attachment-container').append(r);
    });
    $(document).on('click', '.btn-remove-row', function() {
        if ($('#attachment-container .attachment-row').length > 1) { $(this).closest('.attachment-row').remove(); }
        else { var r=$(this).closest('.attachment-row'); r.find('input[type="file"]').val(''); r.find('textarea').val(''); }
    });

    // Form submit with validation + confirmation
    $('#form-activity').on('submit', function(e) {
        e.preventDefault(); var form=this, err='', valid=0;
        $('textarea[name="activity_description[]"]').each(function(i) {
            if ($(this).val().trim()) { valid++; var mh=parseFloat($('input[name="manhour[]"]').eq(i).val()); if(isNaN(mh)||mh<0.5){err='Man hour pada aktivitas ke-'+(i+1)+' wajib minimal 0.5';return false;} }
        });
        if (!valid && !err) err='Minimal satu aktivitas wajib diisi';
        if (err) { Swal.fire('Peringatan',err,'warning'); return false; }
        // File validation
        var maxSz=5*1024*1024, allowed=['pdf','doc','docx','xls','xlsx','jpg','jpeg','png'], fErr=false;
        $('input[type="file"][name="attachments[]"]').each(function(){
            if(this.files.length>0){var f=this.files[0],ext=f.name.split('.').pop().toLowerCase();
            if(allowed.indexOf(ext)===-1){Swal.fire('Peringatan','Tipe file "'+f.name+'" tidak diizinkan','warning');fErr=true;return false;}
            if(f.size>maxSz){Swal.fire('Peringatan','File "'+f.name+'" melebihi 5MB','warning');fErr=true;return false;}}
        });
        if(fErr) return false;
        Swal.fire({title:'Konfirmasi Simpan',text:'Apakah Anda yakin ingin menyimpan?',icon:'question',showCancelButton:true,confirmButtonText:'Ya, Simpan!',cancelButtonText:'Batal'}).then(function(r){if(r.isConfirmed)form.submit();});
    });

    // Edit attachment
    $(document).on('click','.btn-edit-attachment',function(){var id=$(this).data('id'),img=$(this).data('is-image');$('#catatan-display-'+id).addClass('hidden');$('#catatan-edit-'+id).addClass('active');if(img)$('#file-edit-'+id).show();$(this).hide();$('#att-card-'+id+' .btn-save-attachment').show();$('#att-card-'+id+' .btn-cancel-edit').show();});
    $(document).on('click','.btn-cancel-edit',function(){var id=$(this).data('id');$('#catatan-display-'+id).removeClass('hidden');$('#catatan-edit-'+id).removeClass('active');$('#file-edit-'+id).hide();$('#file-edit-'+id+' input').val('');$(this).hide();$('#att-card-'+id+' .btn-save-attachment').hide();$('#att-card-'+id+' .btn-edit-attachment').show();});
    $(document).on('click','.btn-save-attachment',function(){
        var id=$(this).data('id'),cat=$('#catatan-input-'+id).val(),fi=$('#file-edit-'+id+' input[type="file"]')[0],fd=new FormData();
        fd.append('id',id);fd.append('catatan',cat);fd.append('<?= $this->security->get_csrf_token_name(); ?>','<?= $this->security->get_csrf_hash(); ?>');
        if(fi&&fi.files.length>0){var f=fi.files[0],ext=f.name.split('.').pop().toLowerCase();if(['jpg','jpeg','png'].indexOf(ext)===-1){Swal.fire('Peringatan','Hanya gambar yang diperbolehkan','warning');return;}if(f.size>5*1024*1024){Swal.fire('Peringatan','File melebihi 5MB','warning');return;}fd.append('attachment_file',f);}
        $.ajax({url:'<?= site_url("non_project_activities/update_attachment"); ?>',type:'POST',data:fd,processData:false,contentType:false,dataType:'json',
            success:function(r){if(r.status==='success'){var ct=r.data.catatan||'<em class="text-muted">-</em>';$('#catatan-display-'+id+' .catatan-text').html(ct);if(r.data.file_name_hash){var s='<?= base_url("uploads/non_project/"); ?>'+r.data.file_name_hash;$('#att-card-'+id+' .viewer-image').attr('src',s).attr('data-original',s);if(typeof viewer!=='undefined')viewer.update();}$('#catatan-display-'+id).removeClass('hidden');$('#catatan-edit-'+id).removeClass('active');$('#file-edit-'+id).hide();$('#att-card-'+id+' .btn-save-attachment,.btn-cancel-edit').hide();$('#att-card-'+id+' .btn-edit-attachment').show();Swal.fire({title:'Berhasil!',text:r.message,icon:'success',timer:1500,showConfirmButton:false});}else{Swal.fire('Gagal!',r.message,'error');}},
            error:function(){Swal.fire('Error!','Terjadi kesalahan.','error');}});
    });

    // Delete attachment
    $(document).on('click','.btn-delete-attachment',function(){
        var id=$(this).data('id'),card=$('#att-card-'+id);
        Swal.fire({title:'Hapus Lampiran?',text:'Lampiran akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, Hapus!',cancelButtonText:'Batal'}).then(function(r){
            if(r.isConfirmed){$.ajax({url:'<?= site_url("non_project_activities/delete_attachment"); ?>',type:'POST',data:{id:id,<?= $this->security->get_csrf_token_name(); ?>:'<?= $this->security->get_csrf_hash(); ?>'},dataType:'json',success:function(r){if(r.status==='success'){card.fadeOut(300,function(){$(this).remove();if(typeof viewer!=='undefined')viewer.update();});Swal.fire({title:'Berhasil!',text:r.message,icon:'success',timer:1500,showConfirmButton:false});}else{Swal.fire('Gagal!',r.message,'error');}},error:function(){Swal.fire('Error!','Terjadi kesalahan.','error');}});}
        });
    });
    <?php endif; ?>
});
</script>