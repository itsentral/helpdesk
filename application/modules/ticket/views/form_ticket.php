<?php
$ENABLE_ADD     = has_permission('Ticket.Add');
$ENABLE_MANAGE  = has_permission('Ticket.Manage');

$mode = isset($helpdesk->id) ? (isset($view_mode) && $view_mode ? 'view' : 'edit') : 'add';
$is_readonly = ($mode === 'view');

$is_external = isset($is_external) ? $is_external : false;
$hide_for_external = ($is_external && !$is_readonly);

// Data
$id = isset($helpdesk->id) ? $helpdesk->id : '';
$no_ticket = isset($helpdesk->no_ticket) ? $helpdesk->no_ticket : '';
$report = isset($helpdesk->report) ? $helpdesk->report : '';
$category_id = isset($helpdesk->category_id) ? $helpdesk->category_id : '';
$category_name = isset($helpdesk->category_name) ? $helpdesk->category_name : '';
$sub_category_id = isset($helpdesk->sub_category_id) ? $helpdesk->sub_category_id : '';
$sub_category_name = isset($helpdesk->sub_category_name) ? $helpdesk->sub_category_name : '';
$attachments = isset($attachments) ? $attachments : [];
$sub_categories = isset($sub_categories) ? $sub_categories : [];
$causes = isset($helpdesk->causes) ? $helpdesk->causes : '';
$action_plan = isset($helpdesk->action_plan) ? $helpdesk->action_plan : '';
$due_date = isset($helpdesk->due_date) ? $helpdesk->due_date : '';
$man_hour_plan = isset($helpdesk->man_hour_plan) ? $helpdesk->man_hour_plan : '';
$pic_id = isset($helpdesk->pic_id) ? $helpdesk->pic_id : '';
$pic = isset($helpdesk->pic) ? $helpdesk->pic : '';
$status = isset($helpdesk->status) ? $helpdesk->status : 'Open';
$is_approve = isset($helpdesk->is_approve) ? $helpdesk->is_approve : '-';
$create_by = isset($helpdesk->create_by) ? $helpdesk->create_by : '';
$create_date = isset($helpdesk->create_date) ? $helpdesk->create_date : '';
$keterangan_penyelesaian = '';
if (isset($helpdesk->keterangan_penyelesaian)) {
    $lines = explode("\n", $helpdesk->keterangan_penyelesaian);
    $lines = array_map(function ($line) {
        return trim(preg_replace('/^[\s\x{00A0}]+|[\s\x{00A0}]+$/u', '', $line));
    }, $lines);
    $keterangan_penyelesaian = trim(implode("\n", $lines));
}
$file_done_original_name = isset($helpdesk->file_done_original_name) ? $helpdesk->file_done_original_name : '';
$file_done_hash_name     = isset($helpdesk->file_done_hash_name) ? $helpdesk->file_done_hash_name : '';
$has_done_info = !empty($keterangan_penyelesaian) || !empty($file_done_hash_name);
$colCategory = ($mode === 'view') ? 'col-md-6' : 'col-md-4';
?>

<!-- Select2 Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Flatpickr (Date Picker) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Viewer.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.css">

<style>
    .form-label.required:after {
        content: " *";
        color: #dc3545;
    }

    .info-box {
        background-color: #f8f9fa;
        border-left: 4px solid var(--bs-primary);
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .info-box-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .info-box-item:last-child {
        margin-bottom: 0;
    }

    .info-box-label {
        font-weight: 600;
        width: 120px;
        color: #6c757d;
    }

    .info-box-value {
        flex: 1;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* View mode styling */
    .view-field {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        min-height: 42px;
        display: flex;
        align-items: center;
    }

    .view-textarea {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        min-height: 100px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .attachment-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .attachment-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .upload-area {
        background: #f8f9fa;
        border: 2px dashed #dee2e6 !important;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #0d6efd !important;
        background: #e7f1ff;
    }

    .file-preview-item {
        position: relative;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .file-preview-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .file-preview-item .btn-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 10;
    }

    .file-preview-item {
        position: relative;
        padding-right: 35px;
    }

    .btn-remove {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        width: 26px;
        height: 26px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Timeline History */
    .timeline-item {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        position: relative;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 30px;
        bottom: -15px;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-marker {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        z-index: 1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .timeline-marker i {
        font-size: 13px;
    }

    .timeline-content {
        flex: 1;
        background: #f8f9fa;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 13px;
        line-height: 1.5;
    }

    .timeline-history::-webkit-scrollbar {
        width: 6px;
    }

    .timeline-history::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .timeline-history::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }
</style>

<div class="card">
    <div class="card-body">
        <?php if ($mode === 'view' && $id): ?>
            <div class="info-box">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-ticket text-primary"></i> Ticket No
                            </span>
                            <span class="info-box-value">
                                <strong>: <?= htmlspecialchars($no_ticket) ?></strong>
                            </span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-circle-info text-info"></i> Status
                            </span>
                            <span class="info-box-value">
                                <?php
                                $statusClass = 'bg-secondary';
                                $statusIcon  = 'fa-question';
                                $statusText  = 'Unknown';

                                $approveClass = '';
                                $approveIcon  = '';
                                $approveText  = '';

                                switch ($status) {
                                    case 0:
                                        $statusClass = 'bg-info';
                                        $statusIcon  = 'fa-circle-dot';
                                        $statusText  = 'Open';
                                        break;

                                    case 1:
                                        $statusClass = 'bg-warning';
                                        $statusIcon  = 'fa-spinner';
                                        $statusText  = 'Process';
                                        break;

                                    case 2:
                                        $statusClass = 'bg-secondary';
                                        $statusIcon  = 'fa-clock';
                                        $statusText  = 'Pending';
                                        break;

                                    case 3:
                                        $statusClass = 'bg-danger';
                                        $statusIcon  = 'fa-ban';
                                        $statusText  = 'Cancel';
                                        break;

                                    case 4:
                                        $statusClass = 'bg-success';
                                        $statusIcon  = 'fa-circle-check';
                                        $statusText  = 'Done';
                                        break;

                                    case 5:
                                        $statusClass = 'bg-dark';
                                        $statusIcon  = 'fa-lock';
                                        $statusText  = 'Close';

                                        // badge approval
                                        if ($is_approve == 1) {
                                            $approveClass = 'bg-success';
                                            $approveIcon  = 'fa-check';
                                            $approveText  = 'Approved';
                                        } elseif ($is_approve == 2) {
                                            $approveClass = 'bg-danger';
                                            $approveIcon  = 'fa-xmark';
                                            $approveText  = 'Rejected';
                                        }
                                        break;

                                    case 6:
                                        $statusClass = 'bg-primary';
                                        $statusIcon  = 'fa-solid fa-arrow-rotate-right';
                                        $statusText  = 'Revisi';
                                        break;
                                }
                                ?>

                                :
                                <span class="badge <?= $statusClass ?> status-badge">
                                    <i class="fa-solid <?= $statusIcon ?>"></i>
                                    <?= $statusText ?>
                                </span>

                                <?php if ($status == 5 && !empty($approveText)): ?>
                                    <span class="badge <?= $approveClass ?> status-badge ms-1">
                                        <i class="fa-solid <?= $approveIcon ?>"></i>
                                        <?= $approveText ?>
                                    </span>
                                <?php endif; ?>

                            </span>

                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-building text-primary"></i> Client
                            </span>
                            <span class="info-box-value">
                                <?php
                                $client_display = isset($helpdesk->client_name) ? htmlspecialchars($helpdesk->client_name) : '-';
                                if (isset($helpdesk->client_remark) && !empty($helpdesk->client_remark)) {
                                    $client_display .= ' - ' . htmlspecialchars($helpdesk->client_remark);
                                }
                                ?>
                                : <?= $client_display ?>
                            </span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-user text-success"></i> Created by
                            </span>
                            <span class="info-box-value">
                                : <?= htmlspecialchars($create_by) ?> | <span class="text-muted" style="font-size: 12px;"><?= $create_date ? date('d-m-Y H:i', strtotime($create_date)) : '-' ?></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form id="form-helpdesk" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="row g-3">
                <?php if ($mode !== 'view'): ?>
                    <div class="col-md-4">
                        <?php if (!$is_readonly): ?>
                            <div class="mb-3">
                                <label class="form-label required">
                                    <i class="fa-solid fa-building"></i> Client
                                </label>
                                <?php
                                $client_count = !empty($clients) ? count($clients) : 0;
                                $is_single_client = ($client_count === 1);
                                $auto_selected_client = $is_single_client ? $clients[0]->id : '';
                                ?>

                                <select class="form-select select2" name="client_id" id="client_id" required
                                    <?= $is_single_client ? 'disabled' : '' ?>>
                                    <option value="">- Select Client -</option>
                                    <?php if (!empty($clients)): ?>
                                        <?php foreach ($clients as $client): ?>
                                            <?php
                                            $client_info = htmlspecialchars($client->name_app);
                                            if (!empty($client->remark)) {
                                                $client_info .= ' - ' . htmlspecialchars($client->remark);
                                            }

                                            $selected = '';
                                            if ($is_single_client) {
                                                $selected = 'selected';
                                            } elseif (isset($helpdesk->client_id) && $helpdesk->client_id == $client->id) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?= $client->id ?>" <?= $selected ?>>
                                                <?= $client_info ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Tidak ada client tersedia</option>
                                    <?php endif; ?>
                                </select>

                                <?php if ($is_single_client): ?>
                                    <input type="hidden" name="client_id" value="<?= $auto_selected_client ?>">
                                <?php else: ?>
                                    <small class="text-muted">Pilih client terkait untuk ticket ini</small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
                <div class="<?= $colCategory ?>">
                    <label class="form-label <?= !$is_readonly ? 'required' : '' ?>">Stages</label>

                    <?php if ($is_readonly): ?>
                        <div class="view-field">
                            <?= !empty($category_name) ? htmlspecialchars($category_name) : '-' ?>
                        </div>
                    <?php else: ?>
                        <select class="form-select select2" name="category_id" id="category_id" required>
                            <option value="">- Select Stages -</option>

                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>"
                                        <?= (!empty($category_id) && $category_id == $cat->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat->category_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Tidak ada Stages tersedia</option>
                            <?php endif; ?>
                        </select>
                    <?php endif; ?>
                </div>


                <div class="<?= $colCategory ?>">
                    <label class="form-label <?= !$is_readonly ? 'required' : '' ?>">Category</label>

                    <?php if ($is_readonly): ?>
                        <div class="view-field">
                            <?= !empty($sub_category_name) ? htmlspecialchars($sub_category_name) : '-' ?>
                        </div>
                    <?php else: ?>
                        <select class="form-select select2" name="sub_category_id" id="sub_category_id" required>
                            <option value="">- Select Category -</option>

                            <?php if (!empty($sub_categories)): ?>
                                <?php foreach ($sub_categories as $sub): ?>
                                    <option value="<?= $sub->id ?>"
                                        <?= (!empty($sub_category_id) && $sub_category_id == $sub->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sub->sub_category_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Tidak ada Category tersedia</option>
                            <?php endif; ?>
                        </select>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Report -->
            <div class="mb-3">
                <label class="form-label <?= !$is_readonly ? 'required' : '' ?>">Report / Issue Description</label>
                <?php if ($is_readonly): ?>
                    <div class="view-textarea"><?= htmlspecialchars($report) ?></div>
                <?php else: ?>
                    <textarea class="form-control" name="report" id="report" rows="4"
                        placeholder="Jelaskan masalah yang terjadi..."
                        <?= $is_readonly ? 'readonly' : 'required' ?>><?= htmlspecialchars($report) ?></textarea>
                <?php endif; ?>
            </div>

            <!-- File Attachments -->
            <div class="mb-3">
                <label class="form-label">
                    <i class="fa-solid fa-paperclip"></i> Attachments (File/Gambar)
                </label>

                <?php if ($is_readonly): ?>
                    <!-- View Mode - Display Files -->
                    <div class="attachments-list">
                        <?php if (!empty($attachments)): ?>
                            <div class="row g-2" id="image-gallery">
                                <?php foreach ($attachments as $file): ?>
                                    <div class="col-md-3">
                                        <div class="attachment-item">
                                            <?php
                                            $file_ext = strtolower(pathinfo($file->file_name_original, PATHINFO_EXTENSION));
                                            $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $is_video = in_array($file_ext, ['mp4', 'avi', 'mov', 'mkv', 'webm', '3gp']);
                                            $file_url = site_url('ticket/download_attachment/' . $file->id);
                                            ?>

                                            <?php if ($is_image): ?>
                                                <!-- Image -->
                                                <img src="<?= $file_url ?>"
                                                    class="img-thumbnail viewer-image"
                                                    style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                                    alt="<?= htmlspecialchars($file->file_name_original) ?>"
                                                    data-original="<?= $file_url ?>">
                                            <?php elseif ($is_video): ?>
                                                <!-- Video Thumbnail -->
                                                <div class="video-thumb text-center bg-dark rounded d-flex flex-column justify-content-center align-items-center"
                                                    style="height: 150px; width: 100%; cursor: pointer;"
                                                    onclick="openVideoModal('<?= $file_url ?>', '<?= htmlspecialchars($file->file_name_original) ?>')">
                                                    <i class="fa-solid fa-circle-play fa-3x text-white"></i>
                                                    <p class="mb-0 mt-2 small text-white"><?= strtoupper($file_ext) ?></p>
                                                </div>
                                            <?php else: ?>
                                                <!-- Non-image files -->
                                                <div class="file-icon text-center p-3 bg-light border rounded d-flex flex-column justify-content-center align-items-center"
                                                    style="height: 150px; width: 100%;">
                                                    <i class="fa-solid fa-file fa-3x text-secondary"></i>
                                                    <p class="mb-0 mt-2 small"><?= strtoupper($file_ext) ?></p>
                                                </div>

                                            <?php endif; ?>

                                            <div class="mt-2">
                                                <small class="d-block text-truncate" title="<?= htmlspecialchars($file->file_name_original) ?>">
                                                    <?= htmlspecialchars($file->file_name_original) ?>
                                                </small>
                                                <small class="text-muted">
                                                    <?= number_format($file->file_size / 1024, 2) ?> KB
                                                </small>
                                            </div>

                                            <!-- ✅ Buttons -->
                                            <div class="d-flex gap-1 mt-2">
                                                <?php if ($is_image): ?>
                                                    <button type="button"
                                                        class="btn btn-sm btn-info flex-fill btn-view-image"
                                                        data-image-url="<?= $file_url ?>"
                                                        data-image-name="<?= htmlspecialchars($file->file_name_original) ?>">
                                                        <i class="fa-solid fa-eye"></i> View
                                                    </button>
                                                <?php elseif ($is_video): ?>
                                                    <button type="button"
                                                        class="btn btn-sm btn-info flex-fill"
                                                        onclick="openVideoModal('<?= $file_url ?>', '<?= htmlspecialchars($file->file_name_original) ?>')">
                                                        <i class="fa-solid fa-play"></i> Play
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?= $file_url ?>"
                                                    class="btn btn-sm btn-primary flex-fill"
                                                    download>
                                                    <i class="fa-solid fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="view-field">Tidak ada file yang dilampirkan</div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Edit/Add Mode - Upload Files -->
                    <div class="upload-area border rounded p-3 mb-3" style="border-style: dashed !important;">
                        <div class="text-center">
                            <i class="fa-solid fa-cloud-arrow-up fa-3x text-muted mb-2"></i>
                            <p class="mb-1">Klik, drag & drop, atau <kbd>Ctrl+V</kbd> untuk paste file</p>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="$('#attachments').click()">
                                <i class="fa-solid fa-folder-open"></i> Pilih File
                            </button>
                        </div>
                        <input type="file"
                            class="d-none"
                            name="attachments[]"
                            id="attachments"
                            multiple
                            accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx">
                    </div>
                    <small class="text-muted">
                        <i class="fa-solid fa-circle-info"></i> Maksimal 5 file. Ukuran: Gambar max 2MB, File max 10MB.
                        Format: JPG, PNG, PDF, DOC, XLS
                    </small>

                    <!-- Preview Container for New Files -->
                    <div id="file-preview" class="mt-3"></div>

                    <!-- Existing Files -->
                    <?php if ($mode === 'edit' && !empty($attachments)): ?>
                        <div class="existing-files mt-3">
                            <div class="d-flex mb-2">
                                <strong><i class="fa-solid fa-paperclip"></i> File yang sudah ada:</strong>
                                <span class="px-2 text-muted"><?= count($attachments) ?> file</span>
                            </div>
                            <div class="row g-2" id="existing-image-gallery">
                                <?php foreach ($attachments as $file): ?>
                                    <div class="col-md-3" id="existing-file-<?= $file->id ?>">
                                        <div class="card h-100">
                                            <div class="card-body p-2">
                                                <?php
                                                $file_ext = strtolower(pathinfo($file->file_name_original, PATHINFO_EXTENSION));
                                                $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                $is_video = in_array($file_ext, ['mp4', 'avi', 'mov', 'mkv', 'webm', '3gp']);
                                                $file_url = site_url('ticket/download_attachment/' . $file->id);
                                                ?>

                                                <?php if ($is_image): ?>
                                                    <!-- Image -->
                                                    <img src="<?= $file_url ?>"
                                                        class="img-thumbnail mb-2 viewer-image-edit"
                                                        style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                                        alt="<?= htmlspecialchars($file->file_name_original) ?>"
                                                        data-original="<?= $file_url ?>">
                                                <?php elseif ($is_video): ?>
                                                    <!-- Video Thumbnail -->
                                                    <div class="video-thumb text-center bg-dark rounded d-flex flex-column justify-content-center align-items-center"
                                                        style="height: 150px; width: 100%; cursor: pointer;"
                                                        onclick="openVideoModal('<?= $file_url ?>', '<?= htmlspecialchars($file->file_name_original) ?>')">
                                                        <i class="fa-solid fa-circle-play fa-3x text-white"></i>
                                                        <p class="mb-0 mt-2 small text-white"><?= strtoupper($file_ext) ?></p>
                                                    </div>
                                                <?php else: ?>
                                                    <!-- Non-Image -->
                                                    <div class="text-center p-2 bg-light rounded mb-2 d-flex flex-column justify-content-center align-items-center"
                                                        style="height: 150px; width: 100%; cursor: pointer;">
                                                        <i class="fa-solid fa-file fa-2x text-secondary"></i>
                                                        <p class="mb-0 mt-1 small"><?= strtoupper($file_ext) ?></p>
                                                    </div>
                                                <?php endif; ?>

                                                <small class="d-block text-truncate"
                                                    title="<?= htmlspecialchars($file->file_name_original) ?>">
                                                    <?= htmlspecialchars($file->file_name_original) ?>
                                                </small>
                                                <small class="text-muted d-block mb-2">
                                                    <?= number_format($file->file_size / 1024, 2) ?> KB
                                                </small>

                                                <div class="d-flex gap-1">
                                                    <?php if ($is_image): ?>
                                                        <button type="button"
                                                            class="btn btn-sm btn-info flex-fill btn-view-image-edit"
                                                            data-image-url="<?= $file_url ?>"
                                                            data-image-name="<?= htmlspecialchars($file->file_name_original) ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger flex-fill btn-delete-file"
                                                        data-file-id="<?= $file->id ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Causes -->
            <?php if (!$hide_for_external): ?>
                <div class="mb-3">
                    <label class="form-label">Causes / Root Cause Analysis</label>
                    <?php if ($is_readonly): ?>
                        <div class="view-textarea"><?= $causes ? htmlspecialchars($causes) : '-' ?></div>
                    <?php else: ?>
                        <textarea class="form-control" name="causes" id="causes" rows="3"
                            placeholder="Penyebab masalah (jika sudah diketahui)"
                            <?= $is_readonly ? 'readonly' : '' ?>><?= htmlspecialchars($causes) ?></textarea>
                    <?php endif; ?>
                </div>

                <!-- Action Plan -->
                <div class="mb-3">
                    <label class="form-label">Action Plan</label>
                    <?php if ($is_readonly): ?>
                        <div class="view-textarea"><?= $action_plan ? htmlspecialchars($action_plan) : '-' ?></div>
                    <?php else: ?>
                        <textarea class="form-control" name="action_plan" id="action_plan" rows="3"
                            placeholder="Rencana tindakan untuk menyelesaikan masalah"
                            <?= $is_readonly ? 'readonly' : '' ?>><?= htmlspecialchars($action_plan) ?></textarea>
                    <?php endif; ?>
                </div>

                <!-- Due Date, Man Hour, PIC & Approval -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Due Date</label>
                        <?php if ($is_readonly): ?>
                            <div class="view-field">
                                <?= $due_date ? date('d-m-Y', strtotime($due_date)) : '-' ?>
                            </div>
                        <?php else: ?>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                                <input type="text" class="form-control flatpickr" name="due_date" id="due_date"
                                    value="<?= $due_date ?>" placeholder="Select Date"
                                    <?= $is_readonly ? 'readonly' : 'required' ?>>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fa-solid fa-clock"></i> Man Hour Plan
                        </label>
                        <?php if ($is_readonly): ?>
                            <div class="view-field">
                                <?php
                                $man_hour_plan = isset($helpdesk->man_hour_plan) ? $helpdesk->man_hour_plan : '';
                                echo $man_hour_plan ? htmlspecialchars($man_hour_plan) . ' jam' : '-';
                                ?>
                            </div>
                        <?php else: ?>
                            <div class="input-group">
                                <input type="number" class="form-control" name="man_hour_plan" id="man_hour_plan"
                                    value="<?= isset($helpdesk->man_hour_plan) ? $helpdesk->man_hour_plan : '' ?>"
                                    placeholder="0" min="0" step="0.5"
                                    <?= $is_readonly ? 'readonly' : '' ?>>
                                <span class="input-group-text">jam</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fa-solid fa-user-check"></i> PIC (Person In Charge)
                        </label>
                        <?php if ($is_readonly): ?>
                            <div class="view-field">
                                <?php
                                $pic_display = isset($helpdesk->pic_name) ? htmlspecialchars($helpdesk->pic_name) : '-';
                                echo $pic_display;
                                ?>
                            </div>
                        <?php else: ?>
                            <select class="form-select select2" name="pic_id" id="pic_id"
                                <?= $is_readonly ? 'disabled' : '' ?>>
                                <option value="">- Select PIC -</option>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user->id_user ?>"
                                            <?= (isset($pic_id) && $pic_id == $user->id_user) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user->nm_lengkap) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fa-solid fa-user-shield"></i> Approval By
                        </label>

                        <?php if ($is_readonly): ?>
                            <div class="view-field">
                                <?php
                                $approval_display = '-';
                                if (isset($helpdesk->approval_name) && !empty($helpdesk->approval_name)) {
                                    $approval_display = htmlspecialchars($helpdesk->approval_name);
                                }
                                echo $approval_display;
                                ?>
                            </div>
                        <?php else: ?>
                            <select class="form-select select2" name="approval_by_id" id="approval_by_id"
                                <?= $is_readonly ? 'disabled' : '' ?>>
                                <option value="">- Select Approval -</option>

                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <?php
                                        $selected = '';
                                        if (isset($helpdesk->approval_by_id) && $helpdesk->approval_by_id == $user->id_user) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?= $user->id_user ?>" <?= $selected ?>>
                                            <?= htmlspecialchars($user->nm_lengkap) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </select>
                        <?php endif; ?>
                    </div>
                </div>

    </div>
<?php endif; ?>
</form>

        <div class="card-footer mb-0">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <?php
                $final_back_link = $back_url;
                if (strpos($back_url, 'ticket_management') === false) {
                    $final_back_link .= $back_params;
                }
                ?>

                <a href="<?= $final_back_link ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>

                <?php if (!$is_readonly): ?>
                    <button type="submit" form="form-helpdesk" class="btn btn-primary" id="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Save
                    </button>
                <?php endif; ?>

                <?php if ($is_readonly && isset($enable_manage) && $enable_manage && isset($helpdesk->id)):
                    $s              = (int) $helpdesk->status;
                    $picById        = trim((string)($helpdesk->pic_id ?? ''));
                    $createById     = trim((string)($helpdesk->create_by_id ?? ''));
                    $approvalById   = trim((string)($helpdesk->approval_by_id ?? ''));
                    $approvalLevel  = (int)($helpdesk->approval_level ?? 0);
                    $currLevel      = (int)($helpdesk->current_approval_level ?? 0);
                    $manHourPlan    = $helpdesk->man_hour_plan ?? 0;
                    $uid            = (string)($login_user_id ?? '');
                    $isBA       = isset($is_ba_user) && $is_ba_user;
                ?>

                    <!-- EDIT -->
                    <?php if ($mode === 'view' && $ENABLE_MANAGE && in_array($status, [0, 2, 6]) && ($isBA || $picById === $uid)): ?>
                        <?php
                        $separator = (!empty($back_params)) ? '&' : '?';
                        $src_param = (isset($source) && $source == 'management') ? $separator . 'src=management' : '';
                        $edit_link = site_url('ticket/edit_ticket/' . $helpdesk->id) . $back_params . $src_param;
                        ?>
                        <a href="<?= $edit_link ?>" class="btn btn-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Ticket
                        </a>
                    <?php endif; ?>

                    <!-- HISTORY -->
                    <button type="button" class="btn btn-outline-primary view-history"
                        data-id="<?= $helpdesk->id ?>"
                        data-ticket="<?= htmlspecialchars($no_ticket) ?>">
                        <i class="fa-solid fa-clock-rotate-left"></i> History
                    </button>

                    <!-- PROCESS -->
                    <?php if ($picById === $uid && in_array($s, [0, 2, 6])): ?>
                        <button type="button" class="btn btn-primary process-status"
                            data-id="<?= $helpdesk->id ?>"
                            data-current-status="<?= $s ?>"
                            data-causes="<?= htmlspecialchars($helpdesk->causes ?? '') ?>"
                            data-action-plan="<?= htmlspecialchars($helpdesk->action_plan ?? '') ?>"
                            data-man-hour-plan="<?= $manHourPlan ?>">
                            <i class="fa-solid fa-angles-right"></i> Process
                        </button>
                    <?php endif; ?>

                    <!-- PENDING -->
                    <?php if ($s === 1 && $picById === $uid): ?>
                        <button type="button" class="btn btn-warning pending-status"
                            data-id="<?= $helpdesk->id ?>">
                            <i class="fa-solid fa-hourglass-half"></i> Pending
                        </button>
                    <?php endif; ?>

                    <!-- DONE -->
                    <?php if ($s === 1 && $picById === $uid): ?>
                        <button type="button" class="btn btn-success done-status"
                            data-id="<?= $helpdesk->id ?>"
                            data-current-status="<?= $s ?>">
                            <i class="fa-solid fa-clipboard-check"></i> Done
                        </button>
                    <?php endif; ?>

                    <!-- CANCEL -->
                    <?php if ($s === 0 && $createById === $uid): ?>
                        <button type="button" class="btn btn-danger cancel-status"
                            data-id="<?= $helpdesk->id ?>">
                            <i class="fa-solid fa-ban"></i> Cancel
                        </button>
                    <?php endif; ?>

                    <!-- REJECT -->
                    <?php if (
                        $s === 4 && $currLevel < $approvalLevel &&
                        (
                            ($currLevel === 0 && $approvalById === $uid) ||
                            ($currLevel === 1 && $createById === $uid)
                        )
                    ): ?>
                        <button type="button" class="btn btn-danger reject-status"
                            data-id="<?= $helpdesk->id ?>">
                            <i class="fa-solid fa-xmark"></i> Reject
                        </button>
                    <?php endif; ?>

                    <!-- APPROVE LEVEL 1 -->
                    <?php if ($s === 4 && $approvalLevel >= 1 && $currLevel === 0 && $approvalById === $uid): ?>
                        <button type="button" class="btn btn-success approve-status"
                            data-id="<?= $helpdesk->id ?>"
                            data-level="1">
                            <i class="fa-solid fa-check"></i> Approve
                        </button>
                    <?php endif; ?>

                    <!-- APPROVE LEVEL 2 -->
                    <?php if ($s === 4 && $approvalLevel >= 2 && $currLevel === 1 && $createById === $uid): ?>
                        <button type="button" class="btn btn-success approve-status"
                            data-id="<?= $helpdesk->id ?>"
                            data-level="2">
                            <i class="fa-solid fa-check-double"></i> Approve
                        </button>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
</div>


<!-- Card Bukti & Keterangan Penyelesaian -->
<?php if ($is_readonly && $has_done_info): ?>
    <div class="card mt-3">
        <div class="card-header bg-success">
            <h6 class="mb-0 text-white">
                <i class="fa-solid fa-clipboard-check"></i> Bukti & Keterangan Penyelesaian
            </h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">
                    <i class="fa-solid fa-note-sticky text-success"></i> Keterangan Penyelesaian
                </label>
                <div class="view-textarea"><?= !empty(trim($keterangan_penyelesaian)) ? nl2br(htmlspecialchars(trim($keterangan_penyelesaian))) : '-' ?>
                </div>
            </div>

            <div class="mb-0">
                <label class="form-label">
                    <i class="fa-solid fa-paperclip text-success"></i> Lampiran Bukti Penyelesaian
                </label>

                <?php if (!empty($file_done_hash_name)):
                    $done_ext = strtolower(pathinfo($file_done_hash_name, PATHINFO_EXTENSION));
                    $done_is_image = in_array($done_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $done_download_url = site_url('ticket/download_done_file/' . $id . '/' . $file_done_hash_name);
                    $done_view_url = $done_download_url . '?view=1';
                ?>
                    <div class="attachment-item" style="max-width: 250px;">
                        <?php if ($done_is_image): ?>
                            <div id="done-file-gallery">
                                <img src="<?= $done_view_url ?>"
                                    class="img-thumbnail viewer-done-file"
                                    style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                    alt="<?= htmlspecialchars($file_done_original_name) ?>"
                                    data-original="<?= $done_view_url ?>">
                            </div>
                        <?php else: ?>
                            <div class="file-icon text-center p-3 bg-light border rounded d-flex flex-column justify-content-center align-items-center"
                                style="height: 150px; width: 100%;">
                                <i class="fa-solid fa-file fa-3x text-secondary"></i>
                                <p class="mb-0 mt-2 small"><?= strtoupper($done_ext) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="mt-2">
                            <small class="d-block text-truncate" title="<?= htmlspecialchars($file_done_original_name) ?>">
                                <?= htmlspecialchars($file_done_original_name) ?>
                            </small>
                        </div>

                        <div class="d-flex gap-1 mt-2">
                            <?php if ($done_is_image): ?>
                                <button type="button"
                                    class="btn btn-sm btn-info flex-fill btn-view-done-file"
                                    data-image-url="<?= $done_view_url ?>">
                                    <i class="fa-solid fa-eye"></i> View
                                </button>
                            <?php endif; ?>
                            <a href="<?= $done_download_url ?>"
                                class="btn btn-sm btn-primary flex-fill"
                                download>
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="view-field">Tidak ada bukti penyelesaian yang dilampirkan</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
<?php endif; ?>


<!-- Modal Video Player -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h6 class="modal-title text-white" id="videoModalTitle"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <video id="videoPlayer" controls style="width: 100%; max-height: 70vh;">
                    <source id="videoSource" src="" type="video/mp4">
                    Browser Anda tidak mendukung pemutaran video.
                </video>
            </div>
        </div>
    </div>
</div>

<!-- Modal History Ticket -->
<div class="modal fade" id="modalHistoryTicket" tabindex="-1" aria-labelledby="modalHistoryLabel" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 650px;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modalHistoryLabel">
                    <i class="fa-solid fa-clock-rotate-left"></i> History Ticket: <span id="historyTicketNo"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Loading State -->
                <div id="historyLoading" class="text-center py-5" style="display: none;">
                    <i class="fa-solid fa-spinner fa-spin fa-3x text-primary"></i>
                    <p class="mt-3">Loading history...</p>
                </div>

                <!-- Empty State -->
                <div id="historyEmpty" class="text-center py-5" style="display: none;">
                    <i class="fa-solid fa-inbox fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada history untuk ticket ini</p>
                </div>

                <!-- Timeline Content -->
                <div id="historyTimeline" class="timeline-history p-3" style="max-height: 600px; overflow-y: auto;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Viewer.js -->
<script src="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.js"></script>

<script src="<?= base_url('assets/js/ticket_actions.js') ?>"></script>

<script>
    let selectedFiles = [];
    let viewerNewFiles = null;

    function reinitNewFileViewer() {
        if (viewerNewFiles) {
            viewerNewFiles.destroy();
        }

        const container = document.getElementById('file-preview');
        if (container && container.querySelectorAll('.viewer-image-new').length > 0) {
            viewerNewFiles = new Viewer(container, {
                toolbar: {
                    zoomIn: 4,
                    zoomOut: 4,
                    oneToOne: 4,
                    reset: 4,
                    prev: 4,
                    play: false,
                    next: 4,
                    rotateLeft: 4,
                    rotateRight: 4,
                    flipHorizontal: 4,
                    flipVertical: 4,
                },
                title: [1, (image, imageData) => {
                    return `${image.alt} (${imageData.naturalWidth} × ${imageData.naturalHeight})`;
                }],
                filter(image) {
                    return image.classList.contains('viewer-image-new');
                }
            });
        }
    }

    function openVideoModal(url, name) {
        const player = document.getElementById('videoPlayer');
        const source = document.getElementById('videoSource');
        const title = document.getElementById('videoModalTitle');
        const ext = name.split('.').pop().toLowerCase();
        const mimeMap = {
            'mp4': 'video/mp4',
            'webm': 'video/webm',
            'mov': 'video/quicktime',
            'avi': 'video/x-msvideo',
            'mkv': 'video/x-matroska',
            '3gp': 'video/3gpp',
        };

        source.src = url;
        source.type = mimeMap[ext] || 'video/mp4';
        title.textContent = name;

        player.load(); // reload source baru

        const modal = new bootstrap.Modal(document.getElementById('videoModal'));
        modal.show();
    }

    function removeFilePreview(index) {
        $(`.file-preview-item[data-index="${index}"]`).remove();

        selectedFiles.splice(index, 1);

        $('.file-preview-item').each(function(i) {
            $(this).attr('data-index', i);
            $(this).find('.btn-remove').attr('onclick', `removeFilePreview(${i})`);
        });

        reinitNewFileViewer();
    }

    function loadPicByClient(clientId, selectedId = '') {
        if (clientId) {
            $.ajax({
                url: siteurl + active_controller + 'get_pic_by_client',
                type: 'POST',
                data: {
                    client_id: clientId
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#pic_id').html('<option value="">Loading...</option>');
                    $('#pic_id').prop('disabled', true);
                    $('#approval_by_id').html('<option value="">Loading...</option>');
                    $('#approval_by_id').prop('disabled', true);
                },
                success: function(response) {
                    var picOptions = '<option value="">- Select PIC -</option>';
                    var approvalOptions = '<option value="">- Select Approval -</option>';

                    if (response.status == 1 && response.data.length > 0) {
                        $.each(response.data, function(index, user) {
                            var selectedPic = (selectedId && selectedId == user.id_user) ? 'selected' : '';
                            var selectedApproval = '<?= isset($helpdesk->approval_by_id) ? $helpdesk->approval_by_id : "" ?>';

                            picOptions += '<option value="' + user.id_user + '" ' + selectedPic + '>' + user.nm_lengkap + '</option>';

                            var isApprovalSelected = (selectedApproval && selectedApproval == user.id_user) ? 'selected' : '';
                            approvalOptions += '<option value="' + user.id_user + '" ' + isApprovalSelected + '>' + user.nm_lengkap + '</option>';
                        });
                    } else {
                        picOptions = '<option value="">Tidak ada PIC tersedia untuk client ini</option>';
                        approvalOptions = '<option value="">Tidak ada approval tersedia untuk client ini</option>';
                    }

                    $('#pic_id').html(picOptions);
                    $('#pic_id').prop('disabled', false);

                    $('#approval_by_id').html(approvalOptions);
                    $('#approval_by_id').prop('disabled', false);
                },
                error: function() {
                    $('#pic_id').html('<option value="">- Select PIC -</option>');
                    $('#pic_id').prop('disabled', false);
                    $('#approval_by_id').html('<option value="">- Select Approval -</option>');
                    $('#approval_by_id').prop('disabled', false);
                }
            });
        } else {
            $('#pic_id').html('<option value="">- Select PIC -</option>');
            $('#pic_id').prop('disabled', false);
            $('#approval_by_id').html('<option value="">- Select Approval -</option>');
            $('#approval_by_id').prop('disabled', false);
        }
    }

    // Load PIC on page load if editing
    <?php if ($mode === 'edit' && !empty($helpdesk->client_id)): ?>
        var selectedPicId = '<?= isset($pic_id) ? $pic_id : "" ?>';
        loadPicByClient('<?= $helpdesk->client_id ?>', selectedPicId);
    <?php endif; ?>

    $(document).ready(function() {
        var isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;
        var mode = '<?= $mode ?>';
        var backParams = '<?= $back_params ?? '' ?>';

        // Initialize Viewer.js untuk View Mode
        <?php if ($is_readonly && !empty($attachments)): ?>
            const gallery = document.getElementById('image-gallery');
            if (gallery) {
                const viewer = new Viewer(gallery, {
                    toolbar: {
                        zoomIn: 4,
                        zoomOut: 4,
                        oneToOne: 4,
                        reset: 4,
                        prev: 4,
                        play: false,
                        next: 4,
                        rotateLeft: 4,
                        rotateRight: 4,
                        flipHorizontal: 4,
                        flipVertical: 4,
                    },
                    title: [1, (image, imageData) => {
                        return `${image.alt} (${imageData.naturalWidth} × ${imageData.naturalHeight})`;
                    }],
                    filter(image) {
                        // Hanya tampilkan gambar di viewer
                        return image.classList.contains('viewer-image');
                    }
                });

                // Button View untuk membuka viewer
                $(document).on('click', '.btn-view-image', function() {
                    const imageUrl = $(this).data('image-url');
                    const imageName = $(this).data('image-name');

                    // Cari index gambar
                    const images = $('.viewer-image');
                    let index = 0;
                    images.each(function(i) {
                        if ($(this).attr('src') === imageUrl) {
                            index = i;
                            return false;
                        }
                    });

                    viewer.view(index);
                });
            }
        <?php endif; ?>

         <?php if ($is_readonly && !empty($file_done_hash_name) && $done_is_image): ?>
            const doneGallery = document.getElementById('done-file-gallery');
            if (doneGallery) {
                const viewerDone = new Viewer(doneGallery, {
                    toolbar: {
                        zoomIn: 4,
                        zoomOut: 4,
                        oneToOne: 4,
                        reset: 4,
                        prev: false,
                        play: false,
                        next: false,
                        rotateLeft: 4,
                        rotateRight: 4,
                        flipHorizontal: 4,
                        flipVertical: 4,
                    },
                    filter(image) {
                        return image.classList.contains('viewer-done-file');
                    }
                });

                $(document).on('click', '.btn-view-done-file', function() {
                    viewerDone.view(0);
                });
            }
        <?php endif; ?>
        
        <?php if (!$is_readonly): ?>
            <?php if ($mode === 'edit' && !empty($attachments)): ?>
                const editGallery = document.getElementById('existing-image-gallery');
                if (editGallery) {
                    const viewerEdit = new Viewer(editGallery, {
                        toolbar: {
                            zoomIn: 4,
                            zoomOut: 4,
                            oneToOne: 4,
                            reset: 4,
                            prev: 4,
                            play: false,
                            next: 4,
                            rotateLeft: 4,
                            rotateRight: 4,
                            flipHorizontal: 4,
                            flipVertical: 4,
                        },
                        title: [1, (image, imageData) => {
                            return `${image.alt} (${imageData.naturalWidth} × ${imageData.naturalHeight})`;
                        }],
                        filter(image) {
                            return image.classList.contains('viewer-image-edit');
                        }
                    });

                    $(document).on('click', '.btn-view-image-edit', function() {
                        const imageUrl = $(this).data('image-url');

                        const images = $('.viewer-image-edit');
                        let index = 0;
                        images.each(function(i) {
                            if ($(this).attr('src') === imageUrl) {
                                index = i;
                                return false;
                            }
                        });

                        viewerEdit.view(index);
                    });
                }
            <?php endif; ?>

            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd-m-Y',
                minDate: 'today',
                locale: {
                    firstDayOfWeek: 1
                }
            });

            var selectedCategory = $('#category_id').val();
            var selectedSubCategory = '<?= $sub_category_id ?>';

            if (selectedCategory) {
                loadSubCategories(selectedCategory, selectedSubCategory);
            }

            $('#category_id').change(function() {
                var categoryId = $(this).val();
                loadSubCategories(categoryId);
            });

            function loadSubCategories(categoryId, selectedId = '') {
                if (categoryId) {
                    $.ajax({
                        url: siteurl + active_controller + 'get_sub_categories_select',
                        type: 'POST',
                        data: {
                            category_id: categoryId
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('#sub_category_id').html('<option value="">Loading...</option>');
                            $('#sub_category_id').prop('disabled', true);
                        },
                        success: function(response) {
                            var options = '<option value="">- Select Category -</option>';

                            if (response.status == 1 && response.data.length > 0) {
                                $.each(response.data, function(index, item) {
                                    var selected = (selectedId && selectedId == item.id) ? 'selected' : '';
                                    options += '<option value="' + item.id + '" ' + selected + '>' + item.sub_name + '</option>';
                                });
                            }

                            $('#sub_category_id').html(options);
                            $('#sub_category_id').prop('disabled', false);
                        },
                        error: function() {
                            $('#sub_category_id').html('<option value="">- Select Category -</option>');
                            $('#sub_category_id').prop('disabled', false);
                        }
                    });
                } else {
                    $('#sub_category_id').html('<option value="">- Select Category -</option>');
                    $('#sub_category_id').prop('disabled', false);
                }
            }

            // Form Submit
            $('#form-helpdesk').submit(function(e) {
                e.preventDefault();

                if (!$('#category_id').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Category harus dipilih'
                    });
                    return false;
                }

                if (!$('#sub_category_id').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Sub Category harus dipilih'
                    });
                    return false;
                }

                if (!$('#client_id').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Client harus dipilih'
                    });
                    return false;
                }

                if (!$('#report').val() || $('#report').val().trim().length < 10) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Report harus diisi minimal 10 karakter'
                    });
                    return false;
                }

                var formData = new FormData(this);
                formData.delete('attachments[]');
                selectedFiles.forEach(function(file, index) {
                    formData.append('attachments[]', file);
                });

                var url = siteurl + active_controller + 'save_ticket';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Simpan!',
                    cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            beforeSend: function() {
                                $('#btn-save').prop('disabled', true);
                                $('#btn-save').html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
                            },
                            success: function(response) {
                                $('#btn-save').prop('disabled', false);
                                $('#btn-save').html('<i class="fa-solid fa-floppy-disk"></i> Save');

                                if (response.redirect) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Session Expired',
                                        text: response.message,
                                        showConfirmButton: true,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = response.redirect;
                                    });
                                    return;
                                }

                                if (response.status == 1) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.href = siteurl + active_controller + backParams;
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                $('#btn-save').prop('disabled', false);
                                $('#btn-save').html('<i class="fa-solid fa-floppy-disk"></i> Save');

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat menyimpan data'
                                });
                            }
                        });
                    }
                });
            });

            $('#attachments').on('change', function(e) {
                const newFiles = Array.from(e.target.files);
                const preview = $('#file-preview');
                const currentCount = selectedFiles.length;
                const newCount = newFiles.length;
                const totalCount = currentCount + newCount;

                if (totalCount > 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: `Maksimal 5 file`
                    });
                    this.value = '';
                    return;
                }

                for (const file of newFiles) {
                    const fileExt = file.name.split('.').pop().toLowerCase();
                    const isImageFile = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(fileExt);
                    const isVideoFile = ['mp4', 'avi', 'mov', 'mkv', 'webm', '3gp'].includes(fileExt);

                    if (isVideoFile) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format Tidak Didukung',
                            text: `Maaf, attach format video belum bisa dilakukan.`
                        });
                        this.value = '';
                        return;
                    }

                    let maxSize, maxLabel;
                    if (isImageFile) {
                        maxSize = 2 * 1024 * 1024; // 2MB
                        maxLabel = '2MB';
                    } else {
                        maxSize = 10 * 1024 * 1024; // 10MB
                        maxLabel = '10MB';
                    }

                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Terlalu Besar',
                            text: `${file.name} melebihi ukuran maksimal ${maxLabel}`
                        });
                        continue;
                    }

                    selectedFiles.push(file);
                    const reader = new FileReader();
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt);
                    const fileIndex = selectedFiles.length - 1;

                    reader.onload = function(e) {
                        let previewHTML = `
                            <div class="file-preview-item" data-index="${fileIndex}">
                                <button type="button" class="btn btn-sm btn-danger btn-remove" onclick="removeFilePreview(${fileIndex})">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                <div class="row align-items-center">
                                    <div class="col-auto" style="width: 100px;">
                        `;

                        if (isImage) {
                            previewHTML += `<img src="${e.target.result}" 
                            class="img-thumbnail viewer-image-new" 
                            style="max-height: 80px; width: 80px; object-fit: cover; cursor: pointer;"
                            alt="${file.name}"
                            data-original="${e.target.result}">`;
                        } else {
                            previewHTML += `
                            <div class="text-center bg-light p-2 rounded" 
                                style="height: 80px; width: 80px; margin: 0 auto; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <i class="fa-solid fa-file fa-2x text-secondary"></i>
                                <small class="mt-1" style="font-size: 10px; line-height: 1;">${fileExt.toUpperCase()}</small>
                            </div>
                        `;
                        }

                        previewHTML += `
                                    </div>
                                    <div class="col">
                                        <strong class="d-block text-truncate">${file.name}</strong>
                                        <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                                    </div>
                                </div>
                            </div>
                        `;
                        preview.append(previewHTML);
                        reinitNewFileViewer();
                    };
                    reader.readAsDataURL(file);
                }

                this.value = '';
            });

            $(document).on('paste', function(e) {
                const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                if (!clipboardData || !clipboardData.items) return;

                const items = Array.from(clipboardData.items);
                const fileItems = items.filter(item => item.kind === 'file');

                if (fileItems.length === 0) return;

                const totalCount = selectedFiles.length + fileItems.length;
                if (totalCount > 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: `Maksimal 5 file. Saat ini sudah ada ${selectedFiles.length} file.`
                    });
                    return;
                }

                fileItems.forEach(item => {
                    const file = item.getAsFile();
                    if (!file) return;

                    const fileExt = (file.name.split('.').pop() || 'png').toLowerCase();
                    const isImageFile = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(fileExt);
                    const isVideoFile = ['mp4', 'avi', 'mov', 'mkv', 'webm', '3gp'].includes(fileExt);

                    if (isVideoFile) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format Tidak Didukung',
                            text: `File "${file.name}" adalah video. Maaf, attach format video belum bisa dilakukan.`
                        });
                        return;
                    }

                    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                    const pastedFile = new File([file], `paste-${timestamp}.${fileExt || 'png'}`, {
                        type: file.type
                    });

                    let maxSize, maxLabel;
                    if (isImageFile) {
                        maxSize = 2 * 1024 * 1024;
                        maxLabel = '2MB';
                    } else {
                        maxSize = 10 * 1024 * 1024;
                        maxLabel = '10MB';
                    }

                    if (pastedFile.size > maxSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Terlalu Besar',
                            text: `${pastedFile.name} melebihi ukuran maksimal ${maxLabel}`
                        });
                        return;
                    }

                    selectedFiles.push(pastedFile);

                    const reader = new FileReader();
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt);
                    const fileIndex = selectedFiles.length - 1;
                    const preview = $('#file-preview');

                    reader.onload = function(e) {
                        let previewHTML = `
                            <div class="file-preview-item" data-index="${fileIndex}">
                                <button type="button" class="btn btn-sm btn-danger btn-remove" onclick="removeFilePreview(${fileIndex})">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                <div class="row align-items-center">
                                    <div class="col-auto" style="width: 100px;">
                        `;

                        if (isImage) {
                            previewHTML += `
                            <img src="${e.target.result}"
                                class="img-thumbnail viewer-image-new"
                                style="max-height: 80px; width: 80px; object-fit: cover; cursor: pointer;"
                                alt="${pastedFile.name}"
                                data-original="${e.target.result}">
                        `;
                        } else {
                            previewHTML += `
                            <div class="text-center bg-light p-2 rounded" 
                                style="height: 80px; width: 80px; margin: 0 auto; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <i class="fa-solid fa-file fa-2x text-secondary"></i>
                                <small class="mt-1" style="font-size: 10px; line-height: 1;">${fileExt.toUpperCase()}</small>
                            </div>
                        `;
                        }

                        previewHTML += `
                                </div>
                                <div class="col">
                                    <strong class="d-block text-truncate">${pastedFile.name}</strong>
                                    <small class="text-muted">${(pastedFile.size / 1024).toFixed(2)} KB</small>
                                    <span class="badge bg-info ms-1" style="font-size: 10px;">
                                        <i class="fa-solid fa-clipboard"></i> Pasted
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;

                        preview.append(previewHTML);
                        reinitNewFileViewer();
                    };

                    reader.readAsDataURL(pastedFile);
                });
            });

            // $(document).on('paste', function(e) {
            //     const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
            //     if (!clipboardData || !clipboardData.items) return;

            //     const items = Array.from(clipboardData.items);
            //     const fileItems = items.filter(item => item.kind === 'file');

            //     if (fileItems.length === 0) return;

            //     const totalCount = selectedFiles.length + fileItems.length;
            //     if (totalCount > 5) {
            //         Swal.fire({
            //             icon: 'warning',
            //             title: 'Perhatian',
            //             text: `Maksimal 5 file. Saat ini sudah ada ${selectedFiles.length} file.`
            //         });
            //         return;
            //     }

            //     fileItems.forEach(item => {
            //         const file = item.getAsFile();
            //         if (!file) return;

            //         const fileExt = file.name.split('.').pop().toLowerCase();
            //         const isImageFile = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(fileExt);
            //         const isVideoFile = ['mp4', 'avi', 'mov', 'mkv', 'webm', '3gp'].includes(fileExt);

            //         const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            //         const pastedFile = new File([file], `paste-${timestamp}.${fileExt || 'png'}`, {
            //             type: file.type
            //         });

            //         let maxSize, maxLabel;
            //         if (isVideoFile) {
            //             maxSize = 100 * 1024 * 1024;
            //             maxLabel = '100MB';
            //         } else if (isImageFile) {
            //             maxSize = 2 * 1024 * 1024;
            //             maxLabel = '2MB';
            //         } else {
            //             maxSize = 10 * 1024 * 1024;
            //             maxLabel = '10MB';
            //         }

            //         if (pastedFile.size > maxSize) {
            //             Swal.fire({
            //                 icon: 'warning',
            //                 title: 'File Terlalu Besar',
            //                 text: `${pastedFile.name} melebihi ukuran maksimal ${maxLabel}`
            //             });
            //             return;
            //         }

            //         selectedFiles.push(pastedFile);

            //         const reader = new FileReader();
            //         const currentExt = pastedFile.name.split('.').pop().toLowerCase();
            //         const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(currentExt);
            //         const fileIndex = selectedFiles.length - 1;
            //         const preview = $('#file-preview');

            //         reader.onload = function(e) {
            //             let previewHTML = `
            //     <div class="file-preview-item" data-index="${fileIndex}">
            //         <button type="button" class="btn btn-sm btn-danger btn-remove" onclick="removeFilePreview(${fileIndex})">
            //             <i class="fa-solid fa-xmark"></i>
            //         </button>
            //         <div class="row align-items-center">
            //             <div class="col-auto" style="width: 100px;">
            //     `;

            //             if (isImage) {
            //                 previewHTML += `
            //         <img src="${e.target.result}"
            //             class="img-thumbnail viewer-image-new"
            //             style="max-height: 80px; width: 80px; object-fit: cover; cursor: pointer;"
            //             alt="${pastedFile.name}"
            //             data-original="${e.target.result}">
            //     `;
            //             } else {
            //                 previewHTML += `
            //         <div class="text-center bg-light p-2 rounded" style="height: 80px; display: flex; flex-direction: column; justify-content: center;">
            //             <i class="fa-solid fa-file fa-2x text-secondary"></i>
            //             <small class="mt-1">${currentExt.toUpperCase()}</small>
            //         </div>
            //     `;
            //             }

            //             previewHTML += `
            //             </div>
            //             <div class="col">
            //                 <strong class="d-block text-truncate">${pastedFile.name}</strong>
            //                 <small class="text-muted">${(pastedFile.size / 1024).toFixed(2)} KB</small>
            //                 <span class="badge bg-info ms-1" style="font-size: 10px;">
            //                     <i class="fa-solid fa-clipboard"></i> Pasted
            //                 </span>
            //             </div>
            //         </div>
            //     </div>
            //     `;

            //             preview.append(previewHTML);
            //             reinitNewFileViewer();
            //         };

            //         reader.readAsDataURL(pastedFile);
            //     });
            // });

            // $('#attachments').on('change', function(e) {
            //     const newFiles = Array.from(e.target.files);
            //     const preview = $('#file-preview');
            //     const currentCount = selectedFiles.length;
            //     const newCount = newFiles.length;
            //     const totalCount = currentCount + newCount;

            //     if (totalCount > 5) {
            //         Swal.fire({
            //             icon: 'warning',
            //             title: 'Perhatian',
            //             text: `Maksimal 5 file`
            //         });
            //         this.value = '';
            //         return;
            //     }

            //     newFiles.forEach((file, index) => {
            //         const fileExt = file.name.split('.').pop().toLowerCase();
            //         const isImageFile = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(fileExt);
            //         const isVideoFile = ['mp4', 'avi', 'mov', 'mkv', 'webm', '3gp'].includes(fileExt);

            //         let maxSize, maxLabel;
            //         if (isVideoFile) {
            //             maxSize = 100 * 1024 * 1024; // 100MB
            //             maxLabel = '100MB';
            //         } else if (isImageFile) {
            //             maxSize = 2 * 1024 * 1024; // 2MB
            //             maxLabel = '2MB';
            //         } else {
            //             maxSize = 10 * 1024 * 1024; // 10MB
            //             maxLabel = '10MB';
            //         }

            //         if (file.size > maxSize) {
            //             Swal.fire({
            //                 icon: 'warning',
            //                 title: 'File Terlalu Besar',
            //                 text: `${file.name} melebihi ukuran maksimal ${maxLabel}`
            //             });
            //             return;
            //         }

            //         selectedFiles.push(file);
            //         const reader = new FileReader();
            //         // const fileExt = file.name.split('.').pop().toLowerCase();
            //         const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt);
            //         const fileIndex = selectedFiles.length - 1;

            //         reader.onload = function(e) {
            //             let previewHTML = `
            //                 <div class="file-preview-item" data-index="${fileIndex}">
            //                     <button type="button" class="btn btn-sm btn-danger btn-remove" onclick="removeFilePreview(${fileIndex})">
            //                         <i class="fa-solid fa-xmark"></i>
            //                     </button>
            //                     <div class="row align-items-center">
            //                         <div class="col-auto" style="width: 100px;">
            //             `;

            //             if (isImage) {
            //                 previewHTML += `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 80px; width: 80px; object-fit: cover;">`;
            //             } else {
            //                 previewHTML += `
            //                 <div class="text-center bg-light p-2 rounded" style="height: 80px; display: flex; flex-direction: column; justify-content: center;">
            //                     <i class="fa-solid fa-file fa-2x text-secondary"></i>
            //                     <small class="mt-1">${fileExt.toUpperCase()}</small>
            //                 </div>
            //             `;
            //             }

            //             previewHTML += `
            //                     </div>
            //                         <div class="col">
            //                             <strong class="d-block text-truncate">${file.name}</strong>
            //                             <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
            //                         </div>
            //                     </div>
            //                 </div>
            //             `;

            //             preview.append(previewHTML);
            //         };

            //         reader.readAsDataURL(file);
            //     });

            //     this.value = '';
            // });

            const uploadArea = $('.upload-area');
            uploadArea.on('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('border-primary');
            });

            uploadArea.on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('border-primary');
            });

            uploadArea.on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('border-primary');

                const files = e.originalEvent.dataTransfer.files;
                const input = document.getElementById('attachments');
                input.files = files;
                $(input).trigger('change');
            });

            $(document).on('click', '.btn-delete-file', function() {
                const fileId = $(this).data('file-id');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus file ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Hapus!',
                    cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: siteurl + active_controller + 'delete_attachment',
                            type: 'POST',
                            data: {
                                id: fileId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status == 1) {
                                    $(`#existing-file-${fileId}`).fadeOut(300, function() {
                                        $(this).remove();

                                        const remaining = $('.existing-files .col-md-3').length;
                                        if (remaining === 0) {
                                            $('.existing-files').fadeOut(300, function() {
                                                $(this).remove();
                                            });
                                        } else {
                                            $('.existing-files .badge').text(remaining + ' file');
                                        }
                                    });

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'File berhasil dihapus',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat menghapus file'
                                });
                            }
                        });
                    }
                });
            });

            $('#client_id').change(function() {
                var clientId = $(this).val();

                if (clientId) {
                    loadPicByClient(clientId);
                } else {
                    $('#pic_id').html('<option value="">Pilih Client terlebih dahulu</option>');
                    $('#pic_id').prop('disabled', true);
                    $('#pic_id').val('').trigger('change');

                    $('#approval_by_id').html('<option value="">Pilih Client terlebih dahulu</option>');
                    $('#approval_by_id').prop('disabled', true);
                    $('#approval_by_id').val('').trigger('change');
                }
            });

            var initialClientId = $('#client_id').val();
            if (!initialClientId) {
                $('#pic_id').html('<option value="">Pilih Client terlebih dahulu</option>');
                $('#pic_id').prop('disabled', true);
                $('#approval_by_id').html('<option value="">Pilih Client terlebih dahulu</option>');
                $('#approval_by_id').prop('disabled', true);
            } else {
                <?php if ($mode === 'edit'): ?>
                    var selectedPicId = '<?= isset($pic_id) ? $pic_id : "" ?>';
                    loadPicByClient(initialClientId, selectedPicId);
                <?php else: ?>
                    loadPicByClient(initialClientId);
                <?php endif; ?>
            }

            $('#videoModal').on('hidden.bs.modal', function(e) {
                const player = document.getElementById('videoPlayer');
                player.pause();
                player.currentTime = 0;
            });

        <?php endif; ?>
    });
</script>

<?php if ($is_readonly && isset($helpdesk->id)): ?>
    <script>
        function isImageFile(fileName) {
            if (!fileName) return false;
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            const ext = fileName.split('.').pop().toLowerCase();
            return imageExtensions.includes(ext);
        }

        function formatDate(dateString) {
            if (!dateString) return '-';

            var date = new Date(dateString);
            var options = {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };

            return date.toLocaleDateString('id-ID', options);
        }

        function buildHistoryTimeline(historyData) {
            var timeline = '';

            var actionTypeLabels = {
                0: {
                    icon: 'fa-plus-circle',
                    text: 'Created',
                    color: '#28a745'
                },
                1: {
                    icon: 'fa-sync-alt',
                    text: 'Status Updated',
                    color: '#007bff'
                },
                2: {
                    icon: 'fa-hourglass-half',
                    text: 'Pending',
                    color: '#ffc107'
                },
                3: {
                    icon: 'fa-ban',
                    text: 'Cancelled',
                    color: '#dc3545'
                },
                4: {
                    icon: 'fa-check-circle',
                    text: 'Approval',
                    color: '#28a745'
                },
                5: {
                    icon: 'fa-times-circle',
                    text: 'Rejected',
                    color: '#dc3545'
                },
                6: {
                    icon: 'fa-lock',
                    text: 'Closed',
                    color: '#6c757d'
                },
                7: {
                    icon: 'fa-user-check',
                    text: 'Final Approval',
                    color: '#198754'
                },
                8: {
                    icon: 'fa-pen-to-square',
                    text: 'Data Updated',
                    color: '#0d6efd'
                }
            };

            var statusLabels = {
                0: 'Open',
                1: 'Process',
                2: 'Pending',
                3: 'Cancel',
                4: 'Done',
                5: 'Close',
                6: 'Revisi'
            };

            historyData.forEach(function(item) {
                var actionInfo = actionTypeLabels[item.action_type] || {
                    icon: 'fa-circle',
                    text: 'Unknown',
                    color: '#6c757d'
                };

                var description = item.description || '';

                if (item.old_status !== null && item.new_status !== null && item.old_status != item.new_status) {
                    var oldStatusText = statusLabels[item.old_status] || item.old_status;
                    var newStatusText = statusLabels[item.new_status] || item.new_status;

                    description += `
                <br>
                <small class="text-muted">
                    Status:
                    <strong>${oldStatusText}</strong> →
                    <strong>${newStatusText}</strong>
                </small>`;
                }

                if (item.action_type == 4 && item.old_status == item.new_status) {
                    description += `
                <br>
                <small class="text-secondary">
                    <i class="fa-solid fa-clock"></i>
                    Menunggu approval berikutnya
                </small>`;
                }

                if (item.action_type == 7) {
                    description += `
                <br>
                <small class="text-success">
                    <i class="fa-solid fa-lock"></i>
                    Ticket ditutup setelah final approval
                </small>`;
                }

                if (item.action_type == 5) {
                    description += `
                <br>
                <small class="text-warning">
                    <i class="fa-solid fa-rotate-left"></i>
                    Tiket dikembalikan ke revisi
                </small>`;
                }

                if (item.cause_pic && item.cause_pic.trim() !== '') {
                    description += `
                <br>
                <small class="text-info">
                    <i class="fa-solid fa-comment-dots"></i>
                    <strong>Remark:</strong> ${item.cause_pic}
                </small>`;
                }

                if (item.new_status == 4 && item.keterangan_penyelesaian && item.keterangan_penyelesaian.trim() !== '') {
                    description += `
                <br>
                <small class="text-info">
                    <i class="fa-solid fa-note-sticky"></i>
                    <strong>Keterangan Penyelesaian:</strong> ${item.keterangan_penyelesaian}
                </small>`;
                }

                if (item.new_status == 4 && item.file_done_hash_name) {
                    const downloadUrl = siteurl + active_controller + 'download_done_file/' + item.helpdesk_id + '/' + item.file_done_hash_name;
                    const viewUrl = downloadUrl + '?view=1';
                    const displayName = item.file_done_original_name || item.file_done_hash_name;

                    if (isImageFile(item.file_done_hash_name)) {
                        description += `
                    <br>
                    <div class="history-file mt-2">
                        <small class="d-block mb-1 text-primary">
                            <i class="fa-solid fa-paperclip"></i> <strong>Bukti Penyelesaian:</strong>
                        </small>
                        <img src="${viewUrl}"
                             alt="${displayName}"
                             class="history-image-preview"
                             data-viewer-src="${viewUrl}"
                             style="max-width: 150px; max-height: 150px; border-radius: 8px; cursor: pointer; border: 1px solid #dee2e6;">
                        <div class="mt-1">
                            <small>
                                <a href="${downloadUrl}" download>
                                    <i class="fa-solid fa-download"></i> ${displayName}
                                </a>
                            </small>
                        </div>
                    </div>`;
                    } else {
                        description += `
                    <br>
                    <small class="text-primary d-block mt-1">
                        <i class="fa-solid fa-paperclip"></i>
                        <strong>Bukti Penyelesaian:</strong>
                        <a href="${downloadUrl}" download>
                            <i class="fa-solid fa-file"></i> ${displayName}
                        </a>
                    </small>`;
                    }
                }

                timeline += `
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background-color: ${actionInfo.color};">
                            <i class="fa-solid ${actionInfo.icon}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="fw-bold" style="color: ${actionInfo.color};">
                                    ${actionInfo.text}
                                </span>
                                <small class="text-muted">
                                    <i class="fa-solid fa-clock"></i> ${formatDate(item.action_date)}
                                </small>
                            </div>
                            <div class="mb-1">${description}</div>
                            <small class="text-muted fst-italic">
                                <i class="fa-solid fa-user"></i> ${item.action_by || 'System'}
                            </small>
                        </div>
                    </div>
                `;
            });

            return timeline;
        }

        // Handler klik untuk lihat History
        $(document).on('click', '.view-history', function(e) {
            e.preventDefault();
            var ticketId = $(this).data('id');
            var ticketNo = $(this).data('ticket');
            viewTicketHistory(ticketId, ticketNo);
        });

        // Viewer.js untuk gambar bukti penyelesaian di History
        $(document).on('click', '.history-image-preview', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $allImages = $('#historyTimeline .history-image-preview');
            const currentIndex = $allImages.index(this);

            const $tempContainer = $('<div id="tempHistoryViewerContainer" style="display:none;"></div>');

            $allImages.each(function() {
                const $clone = $(this).clone();
                $tempContainer.append($clone);
            });

            $('body').append($tempContainer);

            const viewer = new Viewer($tempContainer[0], {
                inline: false,
                navbar: $allImages.length > 1,
                title: true,
                toolbar: {
                    zoomIn: 1,
                    zoomOut: 1,
                    oneToOne: 1,
                    reset: 1,
                    rotateLeft: 1,
                    rotateRight: 1,
                    download: 1,
                },
                hidden: () => {
                    setTimeout(() => {
                        try {
                            viewer.destroy();
                        } catch (e) {
                            console.log('Destroy error:', e);
                        }
                        $tempContainer.remove();
                    }, 100);
                }
            });

            viewer.view(currentIndex);
        });

        window.loadHelpdeskList = function() {
            setTimeout(() => {
                window.location.href = siteurl + active_controller;
            }, 1500);
        };

        $(document).on('click', '.process-status', function(e) {
            e.preventDefault();
            changeTicketStatus(
                $(this).data('id'),
                1,
                'Process',
                $(this).data('current-status'),
                $(this).data('man-hour-plan'),
                $(this).data('causes'),
                $(this).data('action-plan')
            );
        });

        $(document).on('click', '.pending-status', function(e) {
            e.preventDefault();
            changeTicketStatus($(this).data('id'), 2, 'Pending');
        });

        $(document).on('click', '.done-status', function(e) {
            e.preventDefault();
            changeTicketStatus($(this).data('id'), 4, 'Done', $(this).data('current-status'));
        });

        $(document).on('click', '.cancel-status', function(e) {
            e.preventDefault();
            changeTicketStatus($(this).data('id'), 3, 'Cancel');
        });

        $(document).on('click', '.approve-status', function(e) {
            e.preventDefault();
            updateTicketApproval($(this).data('id'), 'approve');
        });

        $(document).on('click', '.reject-status', function(e) {
            e.preventDefault();
            updateTicketApproval($(this).data('id'), 'reject');
        });
    </script>
<?php endif; ?>