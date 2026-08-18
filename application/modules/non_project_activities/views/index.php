<?php
$image_extensions = array('jpg', 'jpeg', 'png');
$max_chars = 80;
$edit_deadline_days = 3;
?>

<!-- Viewer.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

<style>
    /* Tooltip teks hover */
    .text-truncate-hover {
        position: relative;
        cursor: default;
    }

    .text-truncate-hover .full-text-popup {
        display: none;
        position: fixed;
        z-index: 9999;
        min-width: 280px;
        max-width: 400px;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
        white-space: pre-line;
        word-break: break-word;
        font-size: 13px;
        line-height: 1.5;
        color: #333;
    }

    /* Lampiran popover - diatur via JS */
    .lampiran-popover-js {
        display: none;
        position: fixed;
        z-index: 9999;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
        min-width: 120px;
    }

    .lampiran-popover-js .att-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        margin: 3px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .lampiran-popover-js .att-item:hover {
        border-color: #0d6efd;
        background: #e7f1ff;
        transform: scale(1.15);
    }

    .lampiran-popover-js .att-item i {
        font-size: 18px;
    }

    .lampiran-popover-js .att-thumb {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        margin: 3px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .lampiran-popover-js .att-thumb:hover {
        border-color: #0d6efd;
        transform: scale(1.15);
    }

    .lampiran-popover-js .att-label {
        display: block;
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 6px;
        text-align: center;
    }

    .lampiran-badge {
        cursor: pointer;
    }
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-end align-items-center">
            <a href="<?= site_url('non_project_activities/create'); ?>" class="btn btn-success btn-sm">
                <i class="fa fa-plus me-1"></i> Tambah Aktivitas
            </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-activities" class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="4%">No</th>
                        <?php if ($is_admin): ?>
                            <th>User</th>
                        <?php endif; ?>
                        <th width="9%">Tanggal</th>
                        <th>Aktivitas</th>
                        <th width="7%">Man Hour</th>
                        <th>Keterangan</th>
                        <th width="8%">Lampiran</th>
                        <th width="14%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activities)): ?>
                        <?php $no = 1;
                        foreach ($activities as $activity):
                            $created_time = strtotime($activity['created_at']);
                            $deadline = $created_time + ($edit_deadline_days * 86400);
                            $can_edit = (time() <= $deadline) || $is_admin;

                            $desc_raw = $activity['activity_description'];
                            $desc_full = htmlspecialchars($desc_raw, ENT_QUOTES, 'UTF-8');
                            $desc_is_long = mb_strlen($desc_raw) > $max_chars;
                            $desc_short = $desc_is_long
                                ? htmlspecialchars(mb_substr($desc_raw, 0, $max_chars), ENT_QUOTES, 'UTF-8') . '...'
                                : $desc_full;

                            $remarks_raw = isset($activity['remarks']) && $activity['remarks'] ? $activity['remarks'] : '';
                            $remarks_full = htmlspecialchars($remarks_raw, ENT_QUOTES, 'UTF-8');
                            $remarks_is_long = mb_strlen($remarks_raw) > $max_chars;
                            $remarks_short = $remarks_is_long
                                ? htmlspecialchars(mb_substr($remarks_raw, 0, $max_chars), ENT_QUOTES, 'UTF-8') . '...'
                                : $remarks_full;
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <?php if ($is_admin): ?>
                                    <td><?= htmlspecialchars(isset($activity['user_name']) ? $activity['user_name'] : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                <?php endif; ?>
                                <td><?= date('d-m-Y', strtotime($activity['activity_date'])); ?></td>
                                <td>
                                    <?php if ($desc_is_long): ?>
                                        <span class="text-truncate-hover">
                                            <?= $desc_short; ?>
                                            <span class="full-text-popup"><?= nl2br($desc_full); ?></span>
                                        </span>
                                    <?php else: ?>
                                        <?= nl2br($desc_full); ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= number_format((float) $activity['manhour'], 1); ?></td>
                                <td>
                                    <?php if (empty($remarks_raw)): ?>
                                        <span class="text-muted">-</span>
                                    <?php elseif ($remarks_is_long): ?>
                                        <span class="text-truncate-hover">
                                            <?= $remarks_short; ?>
                                            <span class="full-text-popup"><?= nl2br($remarks_full); ?></span>
                                        </span>
                                    <?php else: ?>
                                        <?= nl2br($remarks_full); ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($activity['attachments'])): ?>
                                        <span class="badge bg-info lampiran-badge" data-activity-id="<?= $activity['id']; ?>">
                                            <i class="fa fa-paperclip me-1"></i><?= (int) $activity['attachment_count']; ?> file
                                        </span>
                                        <!-- Hidden popover content -->
                                        <div class="lampiran-popover-js" id="lampiran-pop-<?= $activity['id']; ?>">
                                            <span class="att-label">Klik file untuk lihat/download</span>
                                            <div class="d-flex flex-wrap justify-content-center" id="att-pop-<?= $activity['id']; ?>">
                                                <?php foreach ($activity['attachments'] as $att):
                                                    $att_ext = strtolower(pathinfo($att['file_name_original'], PATHINFO_EXTENSION));
                                                    $is_img = in_array($att_ext, $image_extensions);
                                                ?>
                                                    <?php if ($is_img): ?>
                                                        <img src="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>"
                                                            class="att-thumb viewer-img-<?= $activity['id']; ?>"
                                                            alt="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-original="<?= base_url('uploads/non_project/' . $att['file_name_hash']); ?>"
                                                            title="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php else: ?>
                                                        <a href="<?= site_url('non_project_activities/download/' . $att['id']); ?>"
                                                            class="att-item" title="<?= htmlspecialchars($att['file_name_original'], ENT_QUOTES, 'UTF-8'); ?>">
                                                            <i class="fas fa-file-<?php
                                                                                    if ($att_ext === 'pdf') {
                                                                                        echo 'pdf text-danger';
                                                                                    } elseif (in_array($att_ext, ['xls', 'xlsx'])) {
                                                                                        echo 'excel text-success';
                                                                                    } elseif (in_array($att_ext, ['doc', 'docx'])) {
                                                                                        echo 'word text-primary';
                                                                                    } else {
                                                                                        echo 'text-secondary';
                                                                                    }
                                                                                    ?>"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('non_project_activities/view/' . $activity['id']); ?>" class="btn btn-sm btn-outline-info me-1" title="Lihat Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <?php if ($can_edit): ?>
                                        <a href="<?= site_url('non_project_activities/edit/' . $activity['id']); ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $activity['id']; ?>" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
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

<!-- Viewer.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<script>
    $(document).ready(function() {

        // ========== DATATABLES ==========
        $('#table-activities').DataTable({
            responsive: true,
            order: [
                [<?= $is_admin ? '2' : '1'; ?>, 'desc']
            ],
            language: {
                emptyTable: "Belum ada aktivitas yang dicatat",
                zeroRecords: "Tidak ada data yang cocok",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                lengthMenu: "Tampilkan _MENU_ data per halaman"
            }
        });

        // ========== HOVER TEXT POPUP (position via JS) ==========
        $(document).on('mouseenter', '.text-truncate-hover', function(e) {
            var popup = $(this).find('.full-text-popup');
            // Append to body temporarily for proper positioning
            popup.appendTo('body');
            popup.css({
                display: 'block',
                top: (e.clientY + 12) + 'px',
                left: Math.min(e.clientX - 20, window.innerWidth - 420) + 'px'
            });
            $(this).data('popup-id', popup.attr('id') || popup.addClass('active-popup').attr('class'));
        }).on('mousemove', '.text-truncate-hover', function(e) {
            var popup = $('body > .full-text-popup:visible');
            if (popup.length) {
                popup.css({
                    top: (e.clientY + 12) + 'px',
                    left: Math.min(e.clientX - 20, window.innerWidth - 420) + 'px'
                });
            }
        }).on('mouseleave', '.text-truncate-hover', function() {
            var popup = $('body > .full-text-popup:visible');
            // Move back to original parent and hide
            popup.hide().appendTo($(this));
        });

        // ========== LAMPIRAN POPOVER (JS-based positioning) ==========
        var activePopover = null;

        // Move all popover elements to body to avoid overflow clipping
        $('.lampiran-popover-js').appendTo('body');

        $(document).on('mouseenter', '.lampiran-badge', function(e) {
            var activityId = $(this).data('activity-id');
            var popover = $('#lampiran-pop-' + activityId);

            // Position below cursor
            var top = e.clientY + 12;
            var left = e.clientX - 80;

            // Prevent overflow right
            if (left + 200 > window.innerWidth) {
                left = window.innerWidth - 220;
            }
            if (left < 10) left = 10;

            // If not enough space below, show above
            if (top + 130 > window.innerHeight) {
                top = e.clientY - 130;
            }

            popover.css({
                display: 'block',
                top: top + 'px',
                left: left + 'px'
            });
            activePopover = popover;
        });

        $(document).on('mouseleave', '.lampiran-badge', function(e) {
            var activityId = $(this).data('activity-id');
            var popover = $('#lampiran-pop-' + activityId);

            setTimeout(function() {
                if (!popover.is(':hover') && !popover.find(':hover').length) {
                    popover.hide();
                    activePopover = null;
                }
            }, 200);
        });

        $(document).on('mouseleave', '.lampiran-popover-js', function() {
            $(this).hide();
            activePopover = null;
        });

        // Close popover when clicking elsewhere
        $(document).on('click', function(e) {
            if (activePopover && !$(e.target).closest('.lampiran-badge, .lampiran-popover-js').length) {
                activePopover.hide();
                activePopover = null;
            }
        });

        // ========== VIEWER.JS per activity ==========
        <?php if (!empty($activities)): ?>
            <?php foreach ($activities as $activity):
                $has_images = false;
                if (!empty($activity['attachments'])) {
                    foreach ($activity['attachments'] as $att) {
                        $ext = strtolower(pathinfo($att['file_name_original'], PATHINFO_EXTENSION));
                        if (in_array($ext, $image_extensions)) {
                            $has_images = true;
                            break;
                        }
                    }
                }
            ?>
                <?php if ($has_images): ?>
                        (function() {
                            var container = document.getElementById('att-pop-<?= $activity['id']; ?>');
                            if (container) {
                                new Viewer(container, {
                                    filter: function(image) {
                                        return image.classList.contains('viewer-img-<?= $activity['id']; ?>');
                                    },
                                    toolbar: {
                                        zoomIn: 1,
                                        zoomOut: 1,
                                        oneToOne: 1,
                                        reset: 1,
                                        prev: 1,
                                        play: 0,
                                        next: 1,
                                        rotateLeft: 1,
                                        rotateRight: 1,
                                        flipHorizontal: 1,
                                        flipVertical: 1
                                    },
                                    title: true,
                                    navbar: true
                                });
                            }
                        })();
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        // ========== DELETE CONFIRMATION ==========
        $(document).on('click', '.btn-delete', function() {
            var activityId = $(this).data('id');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus aktivitas ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url("non_project_activities/delete"); ?>',
                        type: 'POST',
                        data: {
                            id: activityId,
                            <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    })
                                    .then(function() {
                                        location.reload();
                                    });
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>