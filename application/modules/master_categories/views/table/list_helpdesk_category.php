<?php
$ENABLE_ADD     = has_permission('Helpdesk.Add');
$ENABLE_MANAGE  = has_permission('Helpdesk.Manage');
$ENABLE_VIEW    = has_permission('Helpdesk.View');
$ENABLE_DELETE  = has_permission('Helpdesk.Delete');
?>

<style>
    /* Table Header */
    #table_category thead {
        background: #3498db;
        color: white;
    }

    /* Main Table Rows */
    #table_category tbody tr.data-row {
        background-color: #ffffff;
        transition: background-color 0.2s;
    }

    #table_category tbody tr.data-row:nth-child(4n+3) {
        background-color: #f7f9fb;
    }

    #table_category tbody tr.data-row:hover {
        background-color: #eaf4fb !important;
    }

    /* Sub Category Row Background */
    .sub-category-row {
        background: #ecf6fc !important;
        display: none;
    }

    .sub-category-container {
        padding: 18px;
        background: #f8fcff;
        border-left: 4px solid #3498db;
        border-radius: 0 6px 6px 0;
    }

    /* Sub Category Items */
    .sub-category-item {
        background: #ffffff;
        border: 1px solid #d6e9f5;
        border-left: 3px solid #3498db;
        border-radius: 5px;
        padding: 12px;
        margin-bottom: 10px;
        transition: all 0.2s;
    }

    .sub-category-item:hover {
        background: #f0f8ff;
        border-left-color: #2980b9;
        box-shadow: 0 2px 6px rgba(52, 152, 219, 0.15);
        transform: translateX(2px);
    }

    /* Add Form */
    .sub-add-form {
        background: #e8f5fc;
        border: 2px dashed #3498db;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 12px;
        display: none;
    }

    /* Icons & Badges */
    .rotate-icon {
        transition: transform 0.3s;
        color: #3498db;
    }

    .rotate-icon.rotated {
        transform: rotate(90deg);
        color: #2980b9;
    }

    .toggle-sub-category .badge {
        background: #3498db !important;
        padding: 4px 9px;
        font-weight: 600;
    }

    .toggle-sub-category:hover {
        background-color: #eaf4fb !important;
    }
</style>

<table id="table_category" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th>Stages Name</th>
            <th>Remark</th>
            <th width="10%" class="text-center">Categories</th>
            <th width="15%">Create By</th>
            <th width="15%" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($helpdesk)): ?>
            <?php $no = 1;
            foreach ($helpdesk as $item): ?>
                <tr id="row-<?= $item->id; ?>" class="data-row">
                    <td class="text-center"><?= $no++; ?></td>
                    <td class="category-name-display">
                        <strong><?= $item->category_name; ?></strong>
                    </td>
                    <td class="remark-display">
                        <?= $item->remark ?: '-'; ?>
                    </td>
                    <td class="text-center toggle-sub-category" data-id="<?= $item->id; ?>" style="cursor: pointer;">
                        <?php
                        $count_sub = $this->Master_categories_model->count_sub_category($item->id);
                        ?>
                        <i class="fa fa-chevron-right rotate-icon" id="icon-<?= $item->id; ?>"></i>
                        <span class="badge bg-blue"><?= $count_sub; ?></span>
                    </td>
                    <td>
                        <?= $item->create_by; ?><br>
                        <small class="text-muted" style="font-style: italic; font-size: 12px;">
                            <?= date('d M Y H:i', strtotime($item->create_date)); ?>
                        </small>
                    </td>
                    <td class="text-center action-buttons">
                        <?php if ($ENABLE_MANAGE): ?>
                            <button type="button" class="btn btn-sm btn-warning edit-category"
                                data-id="<?= $item->id; ?>"
                                data-name="<?= htmlspecialchars($item->category_name); ?>"
                                data-remark="<?= htmlspecialchars($item->remark); ?>"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                        <?php endif; ?>

                        <?php if ($ENABLE_DELETE): ?>
                            <button type="button" class="btn btn-sm btn-danger delete-category"
                                data-id="<?= $item->id; ?>"
                                data-name="<?= $item->category_name; ?>"
                                title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Sub Category Row -->
                <tr id="sub-row-<?= $item->id; ?>" class="sub-category-row">
                    <td colspan="7">
                        <div class="sub-category-container">
                            <!-- Form Add Sub Category -->
                            <div class="sub-add-form" id="sub-form-<?= $item->id; ?>">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control input-sm sub-category-name"
                                            placeholder="Sub Category Name"
                                            id="sub-name-<?= $item->id; ?>">
                                    </div>
                                    <div class="col-md-5">
                                        <textarea class="form-control input-sm sub-category-remark"
                                            rows="2"
                                            placeholder="Remark (optional)"
                                            id="sub-remark-<?= $item->id; ?>"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-primary btn-block save-sub-category"
                                            data-category-id="<?= $item->id; ?>">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                        <button class="btn btn-sm btn-default btn-block cancel-sub-category"
                                            data-category-id="<?= $item->id; ?>"
                                            style="margin-top: 5px;">
                                            <i class="fa fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Button -->
                            <?php if ($ENABLE_ADD): ?>
                                <button class="btn btn-sm btn-success add-sub-category"
                                    data-category-id="<?= $item->id; ?>"
                                    style="margin-bottom: 10px;">
                                    <i class="fa fa-plus"></i> Add Category
                                </button>
                            <?php endif; ?>

                            <!-- Sub Category List -->
                            <div id="sub-list-<?= $item->id; ?>">
                                <!-- Will be loaded via AJAX -->
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No data available</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>