<?php
$image_extensions = array('jpg', 'jpeg', 'png');
$max_chars = 80;
$edit_deadline_days = 3;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">
<style>
.text-truncate-hover { position:relative; cursor:default; }
.text-truncate-hover .full-text-popup { display:none; position:fixed; z-index:9999; min-width:280px; max-width:400px; background:#fff; border:1px solid #dee2e6; border-radius:8px; padding:12px 14px; box-shadow:0 4px 16px rgba(0,0,0,.18); white-space:pre-line; word-break:break-word; font-size:13px; line-height:1.5; color:#333; }

/* Generic hover popover (dipakai oleh Lampiran & Aktivitas) */
.hover-popover-js { display:none; position:fixed; z-index:9999; background:#fff; border:1px solid #dee2e6; border-radius:8px; padding:10px 14px; box-shadow:0 4px 16px rgba(0,0,0,.18); }
.hover-popover-js .pop-label { display:block; font-size:11px; color:#6c757d; margin-bottom:6px; text-align:center; }

.lampiran-popover-js { min-width:120px; }
.lampiran-popover-js .att-item { display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:6px; border:1px solid #dee2e6; background:#f8f9fa; margin:3px; cursor:pointer; transition:all .2s; text-decoration:none; }
.lampiran-popover-js .att-item:hover { border-color:#0d6efd; background:#e7f1ff; transform:scale(1.15); }
.lampiran-popover-js .att-item i { font-size:18px; }
.lampiran-popover-js .att-thumb { width:40px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #dee2e6; margin:3px; cursor:pointer; transition:all .2s; }
.lampiran-popover-js .att-thumb:hover { border-color:#0d6efd; transform:scale(1.15); }
.lampiran-badge { cursor:pointer; }

.aktivitas-popover-js { min-width:280px; max-width:380px; }
.aktivitas-popover-js .akt-item { border:1px solid #e9ecef; border-radius:6px; padding:8px 10px; margin-bottom:6px; background:#fafbfc; font-size:13px; line-height:1.45; }
.aktivitas-popover-js .akt-item:last-child { margin-bottom:0; }
.aktivitas-popover-js .akt-desc { color:#333; white-space:pre-line; word-break:break-word; }
.aktivitas-popover-js .akt-mh { display:inline-block; margin-top:4px; font-size:11px; color:#0d6efd; font-weight:600; }
.aktivitas-badge { cursor:pointer; }
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-tasks me-2"></i> Daftar Aktivitas Non Project</h5>
        <a href="<?= site_url('non_project_activities/create'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus me-1"></i> Tambah Aktivitas</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-activities" class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="4%">No</th>
                        <?php if ($is_admin): ?><th>User</th><?php endif; ?>
                        <th width="9%">Tanggal</th>
                        <th width="12%">Aktivitas</th>
                        <th width="7%">MH</th>
                        <th>Keterangan</th>
                        <th width="8%">Lampiran</th>
                        <th width="14%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($date_groups)): ?>
                <?php $no = 1; foreach ($date_groups as $group):
                    $created_time = strtotime($group['created_at']);
                    $deadline = $created_time + ($edit_deadline_days * 86400);
                    $can_edit = (time() <= $deadline) || $is_admin;
                    $first_id = $group['items'][0]['id'];
                    $item_count = count($group['items']);

                    // Combine remarks (Keterangan column stays as-is)
                    $rmks = array();
                    foreach ($group['items'] as $item) {
                        if (!empty($item['remarks'])) $rmks[] = $item['remarks'];
                    }
                    $combined_rmk = implode("\n---\n", $rmks);
                    $rmk_full = htmlspecialchars($combined_rmk, ENT_QUOTES, 'UTF-8');
                    $rmk_is_long = mb_strlen($combined_rmk) > $max_chars;
                    $rmk_short = $rmk_is_long ? htmlspecialchars(mb_substr($combined_rmk, 0, $max_chars), ENT_QUOTES, 'UTF-8') . '...' : $rmk_full;
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <?php if ($is_admin): ?><td><?= htmlspecialchars($group['user_name'] ?: '-', ENT_QUOTES, 'UTF-8'); ?></td><?php endif; ?>
                        <td><?= date('d-m-Y', strtotime($group['activity_date'])); ?></td>
                        <td class="text-center">
                            <span class="badge bg-primary aktivitas-badge" data-first-id="<?= $first_id; ?>">
                                <i class="fa fa-align-left me-1"></i><?= $item_count; ?> aktivitas
                            </span>
                            <div class="hover-popover-js aktivitas-popover-js" id="aktivitas-pop-<?= $first_id; ?>">
                                <span class="pop-label">Detail aktivitas</span>
                                <?php foreach ($group['items'] as $item): ?>
                                <div class="akt-item">
                                    <div class="akt-desc"><?= nl2br(htmlspecialchars($item['activity_description'], ENT_QUOTES, 'UTF-8')); ?></div>
                                    <span class="akt-mh"><i class="fa fa-clock-o me-1"></i><?= number_format($item['manhour'], 1); ?> MH</span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </td>
                        <td class="text-center"><?= number_format($group['total_manhour'], 1); ?></td>
                        <td>
                            <?php if (empty($combined_rmk)): ?><span class="text-muted">-</span>
                            <?php elseif ($rmk_is_long): ?><span class="text-truncate-hover"><?= $rmk_short; ?><span class="full-text-popup"><?= nl2br($rmk_full); ?></span></span>
                            <?php else: ?><?= nl2br($rmk_full); ?><?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (!empty($group['attachments'])): ?>
                            <span class="badge bg-info lampiran-badge" data-first-id="<?= $first_id; ?>"><i class="fa fa-paperclip me-1"></i><?= $group['attachment_count']; ?> file</span>
                            <div class="hover-popover-js lampiran-popover-js" id="lampiran-pop-<?= $first_id; ?>">
                                <span class="pop-label">Klik untuk lihat/download</span>
                                <div class="d-flex flex-wrap justify-content-center" id="att-pop-<?= $first_id; ?>">
                                <?php foreach ($group['attachments'] as $att):
                                    $ae = strtolower(pathinfo($att['file_name_original'], PATHINFO_EXTENSION));
                                    $ai = in_array($ae, $image_extensions);
                                ?>
                                    <?php if ($ai): ?>
                                    <img src="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>" class="att-thumb viewer-img-<?= $first_id; ?>" alt="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>" data-original="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>" title="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php else: ?>
                                    <a href="<?= site_url('non_project_activities/download/' . $att['id']); ?>" class="att-item" title="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-file-<?= ($ae==='pdf')?'pdf-o text-danger':(in_array($ae,['xls','xlsx'])?'excel-o text-success':(in_array($ae,['doc','docx'])?'word-o text-primary':'o text-secondary')); ?>"></i></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </div>
                            </div>
                            <?php else: ?><span class="text-muted small">-</span><?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= site_url('non_project_activities/view/' . $first_id); ?>" class="btn btn-sm btn-outline-info me-1" title="Lihat"><i class="fa fa-eye"></i></a>
                            <?php if ($can_edit): ?>
                            <a href="<?= site_url('non_project_activities/edit/' . $first_id); ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="fa fa-pencil"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-date="<?= $group['activity_date']; ?>" title="Hapus"><i class="fa fa-trash"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>
<script>
$(document).ready(function() {
    $('#table-activities').DataTable({
        responsive:true, order:[[<?= $is_admin?'2':'1'; ?>,'desc']],
        language:{emptyTable:"Belum ada aktivitas yang dicatat",zeroRecords:"Tidak ada data yang cocok",info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",infoEmpty:"Menampilkan 0 sampai 0 dari 0 data",infoFiltered:"(disaring dari _MAX_ total data)",search:"Cari:",paginate:{first:"Pertama",last:"Terakhir",next:"Selanjutnya",previous:"Sebelumnya"},lengthMenu:"Tampilkan _MENU_ data per halaman"}
    });

    // Text popup hover (kolom Keterangan yang panjang)
    $(document).on('mouseenter','.text-truncate-hover',function(e){
        var p=$(this).find('.full-text-popup'); p.appendTo('body').css({display:'block',top:(e.clientY+12)+'px',left:Math.min(e.clientX-20,window.innerWidth-420)+'px'});
    }).on('mousemove','.text-truncate-hover',function(e){
        $('body>.full-text-popup:visible').css({top:(e.clientY+12)+'px',left:Math.min(e.clientX-20,window.innerWidth-420)+'px'});
    }).on('mouseleave','.text-truncate-hover',function(){
        $('body>.full-text-popup:visible').hide().appendTo($(this));
    });

    // Generic hover popover: dipakai oleh badge Lampiran & badge Aktivitas
    $('.hover-popover-js').appendTo('body');
    function bindHoverBadge(badgeSelector, popSelectorFn) {
        $(document).on('mouseenter', badgeSelector, function(e) {
            var id = $(this).data('first-id'), pop = $(popSelectorFn(id));
            var t = e.clientY + 12, l = e.clientX - 80;
            if (l + 220 > window.innerWidth) l = window.innerWidth - 240;
            if (l < 10) l = 10;
            if (t + 160 > window.innerHeight) t = e.clientY - 160;
            pop.css({ display:'block', top:t+'px', left:l+'px' });
        });
        $(document).on('mouseleave', badgeSelector, function() {
            var id = $(this).data('first-id'), pop = $(popSelectorFn(id));
            setTimeout(function(){ if(!pop.is(':hover')) pop.hide(); }, 200);
        });
    }
    bindHoverBadge('.lampiran-badge', function(id){ return '#lampiran-pop-'+id; });
    bindHoverBadge('.aktivitas-badge', function(id){ return '#aktivitas-pop-'+id; });
    $(document).on('mouseleave', '.hover-popover-js', function(){ $(this).hide(); });

    // Viewer.js
    <?php if (!empty($date_groups)): foreach ($date_groups as $group):
        $has_img=false; foreach($group['attachments'] as $a){if(in_array(strtolower(pathinfo($a['file_name_original'],PATHINFO_EXTENSION)),$image_extensions)){$has_img=true;break;}}
        if($has_img): $fid=$group['items'][0]['id']; ?>
    (function(){var c=document.getElementById('att-pop-<?= $fid; ?>');if(c)new Viewer(c,{filter:function(i){return i.classList.contains('viewer-img-<?= $fid; ?>');},toolbar:{zoomIn:1,zoomOut:1,oneToOne:1,reset:1,prev:1,play:0,next:1,rotateLeft:1,rotateRight:1,flipHorizontal:1,flipVertical:1},title:true,navbar:true});})();
    <?php endif; endforeach; endif; ?>

    // Delete (by date)
    $(document).on('click','.btn-delete',function(){
        var date=$(this).data('date');
        Swal.fire({title:'Konfirmasi Hapus',text:'Semua aktivitas pada tanggal ini akan dihapus. Lanjutkan?',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, Hapus!',cancelButtonText:'Batal'}).then(function(r){
            if(r.isConfirmed){$.ajax({url:'<?= site_url("non_project_activities/delete"); ?>',type:'POST',data:{date:date,<?= $this->security->get_csrf_token_name(); ?>:'<?= $this->security->get_csrf_hash(); ?>'},dataType:'json',
                success:function(r){if(r.status==='success'){Swal.fire({title:'Berhasil!',text:r.message,icon:'success',timer:1500,showConfirmButton:false}).then(function(){location.reload();});}else{Swal.fire('Gagal!',r.message,'error');}},
                error:function(){Swal.fire('Error!','Terjadi kesalahan.','error');}});}
        });
    });
});
</script>