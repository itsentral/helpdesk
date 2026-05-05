<?php
$ENABLE_ADD     = has_permission('Helpdesk.Add');
$ENABLE_MANAGE  = has_permission('Helpdesk.Manage');
$ENABLE_VIEW    = has_permission('Helpdesk.View');
$ENABLE_DELETE  = has_permission('Helpdesk.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<style>
	#app-loader {
		display: none !important;
	}

	.skeleton {
		background: #f2f2f2;
		border-radius: 4px;
		animation: shimmer 1.5s infinite linear;
		background: linear-gradient(90deg, #f2f2f2 25%, #e0e0e0 50%, #f2f2f2 75%);
		background-size: 200% 100%;
	}

	@keyframes shimmer {
		0% {
			background-position: 200% 0;
		}

		100% {
			background-position: -200% 0;
		}
	}

	.skeleton-line {
		height: 20px;
		margin: 8px 0;
	}

	.skeleton-line.short {
		width: 60%;
	}

	.skeleton-line.medium {
		width: 80%;
	}

	.add-form-container {
		background-color: #f9f9f9;
		border: 2px solid #3c8dbc;
		border-radius: 5px;
		padding: 15px;
		margin-bottom: 15px;
	}

	.form-edit-row {
		background-color: #fff9e6;
	}

	.inline-form-input {
		width: 100%;
		padding: 5px 10px;
		border: 1px solid #d2d6de;
		border-radius: 3px;
	}

	.inline-form-textarea {
		width: 100%;
		padding: 5px 10px;
		border: 1px solid #d2d6de;
		border-radius: 3px;
		resize: vertical;
		min-height: 80px;
	}
</style>

<div class="card">
	<div class="card-body">
		<!-- Header Section -->
		<div class="mb-3">
			<div class="row align-items-center">
				<div class="col-md-8">
					<div id="button-add-container">
						<?php if ($ENABLE_ADD): ?>
							<button class="btn btn-success btn-sm add-category">
								<i class="fa-solid fa-plus"></i> Add Stages
							</button>
						<?php endif; ?>
					</div>

					<!-- Form Add Category -->
					<div id="form-add-container" style="display:none;">
						<div class="add-form-container">
							<div class="row g-3">
								<div class="col-md-5">
									<label for="new_category_name" class="form-label">
										Stages Name <span class="text-danger">*</span>
									</label>
									<input type="text" class="form-control" id="new_category_name"
										placeholder="Enter stages name">
								</div>
								<div class="col-md-5">
									<label for="new_remark" class="form-label">Remark</label>
									<textarea class="form-control" id="new_remark" rows="3"
										placeholder="Enter remark (optional)"></textarea>
								</div>
								<div class="col-md-2">
									<label class="form-label d-block">&nbsp;</label>
									<button type="button" class="btn btn-primary btn-sm w-100 save-new-category">
										<i class="fa-solid fa-floppy-disk"></i> Save
									</button>
									<button type="button" class="btn btn-secondary btn-sm w-100 mt-2 cancel-add-category">
										<i class="fa-solid fa-xmark"></i> Cancel
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 text-end">
					<button class="btn btn-primary btn-sm refresh-list-category">
						<i class="fa-solid fa-arrows-rotate"></i> Refresh
					</button>
				</div>
			</div>
		</div>

		<!-- Skeleton Loading -->
		<div id="skeleton-loading">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tbody>
						<?php for ($i = 0; $i < 5; $i++): ?>
							<tr>
								<td width="5%">
									<div class="skeleton skeleton-line short"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td width="10%">
									<div class="skeleton skeleton-line short"></div>
								</td>
								<td width="15%">
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td width="15%">
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td width="15%">
									<div class="skeleton skeleton-line short"></div>
								</td>
							</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Content Area -->
		<div id="category-content" style="display:none;"></div>
	</div>
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

<script>
	var dataTableInstance = null;

	function loadCategoryList() {
		$.ajax({
			url: siteurl + active_controller + 'get_list_category',
			type: 'GET',
			beforeSend: function() {
				$('#skeleton-loading').show();
				$('#category-content').hide();
			},
			success: function(response) {
				$('#skeleton-loading').hide();
				$('#category-content').html(response).fadeIn();
			},
			error: function() {
				$('#skeleton-loading').hide();
				$('#category-content')
					.html('<p class="text-danger">Gagal memuat data kategori.</p>')
					.show();
			}
		});
	}

	function resetAddForm() {
		$('#new_category_name').val('');
		$('#new_remark').val('');
		$('.save-new-category')
			.prop('disabled', false)
			.html('<i class="fa fa-save"></i> Simpan');
	}



	// Load Sub Categories
	function loadSubCategories(categoryId) {
		$.ajax({
			url: siteurl + active_controller + 'get_sub_categories',
			type: 'GET',
			data: {
				id_category: categoryId
			},
			success: function(response) {
				$('#sub-list-' + categoryId).html(response);
			},
			error: function() {
				$('#sub-list-' + categoryId).html('<p class="text-danger">Failed to load sub categories</p>');
			}
		});
	}

	// Update Badge Count Only (without reloading entire table)
	function updateCategoryBadge(categoryId) {
		$.ajax({
			url: siteurl + active_controller + 'get_sub_category_count',
			type: 'GET',
			data: {
				id_category: categoryId
			},
			dataType: 'json',
			success: function(response) {
				if (response.status == 1) {
					$('.toggle-sub-category[data-id="' + categoryId + '"] .badge').text(response.count);
				}
			}
		});
	}

	$(document).ready(function() {

		loadCategoryList();

		$(document).on('click', '.refresh-list-category', function(e) {
			e.preventDefault();
			loadCategoryList();
		});

		$(document).on('click', '.add-category', function() {

			if ($('.form-edit-row').length > 0) {
				Swal.fire(
					'Informasi',
					'Silakan selesaikan atau batalkan form edit terlebih dahulu.',
					'info'
				);
				return;
			}

			resetAddForm();

			$('#button-add-container').hide();
			$('#form-add-container').slideDown();
			$('#new_category_name').focus();
		});

		$(document).on('click', '.cancel-add-category', function() {
			$('#form-add-container').slideUp(function() {
				$('#button-add-container').show();
				resetAddForm();
			});
		});

		$(document).on('click', '.save-new-category', function() {
			var categoryName = $('#new_category_name').val().trim();
			var remark = $('#new_remark').val().trim();

			if (categoryName === '') {
				Swal.fire(
					'Peringatan',
					'Nama kategori wajib diisi!',
					'warning'
				);
				$('#new_category_name').focus();
				return;
			}

			var formData = {
				category_name: categoryName,
				remark: remark
			};

			$.ajax({
				url: siteurl + active_controller + 'add_category',
				type: 'POST',
				data: formData,
				dataType: 'json',
				beforeSend: function() {
					$('.save-new-category')
						.prop('disabled', true)
						.html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
				},
				success: function(response) {

					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil!',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						});

						$('.save-new-category')
							.prop('disabled', false)
							.html('<i class="fa fa-save"></i> Simpan');

						$('#form-add-container').slideUp(function() {
							resetAddForm();
						});
						$('#button-add-container').show();

						loadCategoryList();

					} else {
						$('.save-new-category')
							.prop('disabled', false)
							.html('<i class="fa fa-save"></i> Simpan');

						Swal.fire(
							'Gagal!',
							response.message,
							'error'
						);
					}
				},
				error: function() {
					$('.save-new-category')
						.prop('disabled', false)
						.html('<i class="fa fa-save"></i> Simpan');

					Swal.fire(
						'Kesalahan!',
						'Terjadi kesalahan pada sistem. Silakan coba lagi.',
						'error'
					);
				}
			});
		});

		$(document).on('keypress', '#new_category_name', function(e) {
			if (e.which == 13) {
				e.preventDefault();
				$('.save-new-category').click();
			}
		});

		// Toggle Sub Category
		$(document).on('click', '.toggle-sub-category', function() {
			var categoryId = $(this).data('id');
			var $subRow = $('#sub-row-' + categoryId);
			var $icon = $('#icon-' + categoryId);

			if ($subRow.is(':visible')) {
				$subRow.slideUp();
				$icon.removeClass('rotated');
			} else {
				loadSubCategories(categoryId);
				$subRow.slideDown();
				$icon.addClass('rotated');
			}
		});

		// Show Add Sub Category Form
		$(document).on('click', '.add-sub-category', function() {
			var categoryId = $(this).data('category-id');
			$(this).hide();
			$('#sub-form-' + categoryId).slideDown();
			$('#sub-name-' + categoryId).focus();
		});

		// Cancel Add Sub Category
		$(document).on('click', '.cancel-sub-category', function() {
			var categoryId = $(this).data('category-id');
			$('#sub-form-' + categoryId).slideUp();
			$('.add-sub-category[data-category-id="' + categoryId + '"]').show();
			$('#sub-name-' + categoryId).val('');
			$('#sub-remark-' + categoryId).val('');
		});

		// Simpan Sub Kategori
		$(document).on('click', '.save-sub-category', function() {
			var categoryId = $(this).data('category-id');
			var subName = $('#sub-name-' + categoryId).val().trim();
			var subRemark = $('#sub-remark-' + categoryId).val().trim();

			if (subName === '') {
				Swal.fire('Peringatan', 'Nama sub kategori wajib diisi!', 'warning');
				$('#sub-name-' + categoryId).focus();
				return;
			}

			$.ajax({
				url: siteurl + active_controller + 'add_sub_category',
				type: 'POST',
				data: {
					id_category: categoryId,
					sub_name: subName,
					remark: subRemark
				},
				dataType: 'json',
				beforeSend: function() {
					$('.save-sub-category[data-category-id="' + categoryId + '"]')
						.prop('disabled', true)
						.html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil!',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						});

						// Reset form
						$('#sub-name-' + categoryId).val('');
						$('#sub-remark-' + categoryId).val('');
						$('#sub-form-' + categoryId).slideUp();
						$('.add-sub-category[data-category-id="' + categoryId + '"]').show();

						loadSubCategories(categoryId);
						updateCategoryBadge(categoryId);
					} else {
						Swal.fire('Gagal!', response.message, 'error');
					}

					$('.save-sub-category[data-category-id="' + categoryId + '"]')
						.prop('disabled', false)
						.html('<i class="fa fa-save"></i> Simpan');
				},
				error: function() {
					$('.save-sub-category[data-category-id="' + categoryId + '"]')
						.prop('disabled', false)
						.html('<i class="fa fa-save"></i> Simpan');

					Swal.fire('Error!', 'Terjadi kesalahan pada sistem.', 'error');
				}
			});
		});

		// Edit Kategori
		$(document).on('click', '.edit-category', function() {
			var id = $(this).data('id');
			var name = $(this).data('name');
			var remark = $(this).data('remark');
			var $currentRow = $('#row-' + id);

			if ($('#form-add-container').is(':visible')) {
				Swal.fire('Informasi', 'Silakan selesaikan atau batalkan form tambah terlebih dahulu.', 'info');
				return;
			}

			var $otherEditingRows = $('.form-edit-row').not($currentRow);
			if ($otherEditingRows.length > 0) {
				Swal.fire('Informasi', 'Silakan selesaikan atau batalkan proses edit yang sedang berlangsung.', 'info');
				return;
			}

			$currentRow.data('original-name', name);
			$currentRow.data('original-remark', remark);
			$currentRow.addClass('form-edit-row');

			$currentRow.find('.category-name-display').html(
				'<input type="text" class="inline-form-input edit-category-name" ' +
				'value="' + name + '" data-id="' + id + '">'
			);

			$currentRow.find('.remark-display').html(
				'<textarea class="inline-form-textarea edit-remark" data-id="' + id + '">' +
				(remark || '') +
				'</textarea>'
			);

			$currentRow.find('.action-buttons').html(`
				<button type="button" class="btn btn-sm btn-primary save-edit-category" data-id="${id}">
					<i class="fa fa-save"></i> Simpan
				</button>
				<button type="button" class="btn btn-sm btn-default cancel-edit-category" data-id="${id}">
					<i class="fa fa-times"></i> Batal
				</button>
			`);

			$currentRow.find('.edit-category-name').focus();
		});


		$(document).on('click', '.cancel-edit-category', function() {
			var id = $(this).data('id');
			var $row = $('#row-' + id);
			var originalName = $row.data('original-name');
			var originalRemark = $row.data('original-remark');

			$row.removeClass('form-edit-row');
			$row.find('.category-name-display').html('<strong>' + originalName + '</strong>');
			$row.find('.remark-display').html(originalRemark ? originalRemark : '-');

			$row.find('.action-buttons').html(`
            <?php if ($ENABLE_MANAGE): ?>
                <button type="button" class="btn btn-sm btn-warning edit-category"
                    data-id="${id}"
                    data-name="${originalName}"
                    data-remark="${originalRemark}"
                    title="Edit">
                    <i class="fa fa-edit"></i>
                </button>
            <?php endif; ?>
            <?php if ($ENABLE_DELETE): ?>
                <button type="button" class="btn btn-sm btn-danger delete-category"
                    data-id="${id}"
                    data-name="${originalName}"
                    title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            <?php endif; ?>
        `);
		});

		$(document).on('click', '.save-edit-category', function() {
			var id = $(this).data('id');
			var categoryName = $('.edit-category-name[data-id="' + id + '"]').val().trim();
			var remark = $('.edit-remark[data-id="' + id + '"]').val().trim();

			if (categoryName === '') {
				Swal.fire('Warning', 'Nama Kategori wajib diisi.', 'warning');
				$('.edit-category-name[data-id="' + id + '"]').focus();
				return;
			}

			$.ajax({
				url: siteurl + active_controller + 'edit_category/' + id,
				type: 'POST',
				data: {
					category_name: categoryName,
					remark: remark
				},
				dataType: 'json',
				beforeSend: function() {
					$('.save-edit-category[data-id="' + id + '"]')
						.prop('disabled', true)
						.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						});
						loadCategoryList();
					} else {
						$('.save-edit-category[data-id="' + id + '"]')
							.prop('disabled', false)
							.html('<i class="fa fa-save"></i> Save');
						Swal.fire('Failed', response.message, 'error');
					}
				},
				error: function() {
					$('.save-edit-category[data-id="' + id + '"]')
						.prop('disabled', false)
						.html('<i class="fa fa-save"></i> Save');
					Swal.fire('Error', 'System error occurred.', 'error');
				}
			});
		});

		$(document).on('click', '.delete-category', function() {
			var id = $(this).data('id');
			var name = $(this).data('name');

			$.ajax({
				url: siteurl + active_controller + 'check_sub_category_count',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success: function(checkResponse) {
					var subCount = checkResponse.count;
					var warningText = '';
					var confirmButtonText = 'Ya, Hapus';

					if (subCount > 0) {
						warningText = 'Kategori ini memiliki <b>' + subCount + '</b> sub kategori. ' +
							'Semua sub kategori juga akan ikut dihapus!';
						confirmButtonText = 'Ya, Hapus Semua';
					} else {
						warningText = 'Apakah Anda yakin ingin menghapus kategori "<b>' + name + '</b>"?';
					}

					Swal.fire({
						title: 'Konfirmasi Hapus',
						html: '<p>' + warningText + '</p>' +
							(subCount > 0 ? '<p class="text-danger"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>' : ''),
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: confirmButtonText,
						cancelButtonText: 'Batal',
						confirmButtonColor: '#d33',
						cancelButtonColor: '#3085d6'
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								url: siteurl + active_controller + 'delete_category',
								type: 'POST',
								data: {
									id: id
								},
								dataType: 'json',
								success: function(response) {
									if (response.status == 1) {
										Swal.fire({
											icon: 'success',
											title: 'Berhasil!',
											text: response.message,
											timer: 2000,
											showConfirmButton: false
										});
										loadCategoryList();
									} else {
										Swal.fire('Gagal!', response.message, 'error');
									}
								},
								error: function() {
									Swal.fire('Error!', 'Terjadi kesalahan pada sistem.', 'error');
								}
							});
						}
					});
				}
			});
		});


		$(document).on('keypress', '.edit-category-name', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				var id = $(this).data('id');
				$('.save-edit-category[data-id="' + id + '"]').click();
			}
		});

		$(document).on('keypress', '.sub-category-name', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				var categoryId = $(this).attr('id').replace('sub-name-', '');
				$('.save-sub-category[data-category-id="' + categoryId + '"]').click();
			}
		});

		// Edit Sub Category
		$(document).on('click', '.edit-sub-category', function() {
			var id = $(this).data('id');
			var categoryId = $(this).data('category-id');
			var name = $(this).data('name');
			var remark = $(this).data('remark');
			var $item = $('#sub-item-' + id);

			// Save original data
			$item.data('original-name', name);
			$item.data('original-remark', remark);

			// Replace with form
			$item.find('.sub-name-display').html(
				'<input type="text" class="form-control input-sm edit-sub-name" ' +
				'value="' + name + '" data-id="' + id + '">'
			);

			$item.find('.sub-remark-display').html(
				'<textarea class="form-control input-sm edit-sub-remark" ' +
				'rows="2" data-id="' + id + '">' + (remark || '') + '</textarea>'
			);

			$item.find('.sub-action-buttons').html(`
            <button class="btn btn-xs btn-primary save-edit-sub-category" 
                    data-id="${id}" 
                    data-category-id="${categoryId}">
                <i class="fa fa-save"></i>
            </button>
            <button class="btn btn-xs btn-default cancel-edit-sub-category" 
                    data-id="${id}" 
                    data-category-id="${categoryId}">
                <i class="fa fa-times"></i>
            </button>
        `);

			$item.addClass('editing');
			$item.find('.edit-sub-name').focus();
		});

		// Cancel Edit Sub Category
		$(document).on('click', '.cancel-edit-sub-category', function() {
			var id = $(this).data('id');
			var categoryId = $(this).data('category-id');
			var $item = $('#sub-item-' + id);

			var originalName = $item.data('original-name');
			var originalRemark = $item.data('original-remark');

			$item.removeClass('editing');

			$item.find('.sub-name-display').html('<strong>' + originalName + '</strong>');
			$item.find('.sub-remark-display').html(
				'<small class="text-muted">' + (originalRemark || '-') + '</small>'
			);

			$item.find('.sub-action-buttons').html(`
            <?php if ($ENABLE_MANAGE): ?>
                <button class="btn btn-xs btn-warning edit-sub-category" 
                        data-id="${id}"
                        data-category-id="${categoryId}"
                        data-name="${originalName}"
                        data-remark="${originalRemark}"
                        title="Edit">
                    <i class="fa fa-edit"></i>
                </button>
            <?php endif; ?>
            <?php if ($ENABLE_DELETE): ?>
                <button class="btn btn-xs btn-danger delete-sub-category" 
                        data-id="${id}"
                        data-category-id="${categoryId}"
                        data-name="${originalName}"
                        title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            <?php endif; ?>
        `);
		});

		// Save Edit Sub Category
		$(document).on('click', '.save-edit-sub-category', function() {
			var id = $(this).data('id');
			var categoryId = $(this).data('category-id');
			var subName = $('.edit-sub-name[data-id="' + id + '"]').val().trim();
			var subRemark = $('.edit-sub-remark[data-id="' + id + '"]').val().trim();

			if (subName === '') {
				Swal.fire('Warning', 'Sub Kategori wajib diisi!', 'warning');
				$('.edit-sub-name[data-id="' + id + '"]').focus();
				return;
			}

			$.ajax({
				url: siteurl + active_controller + 'edit_sub_category/' + id,
				type: 'POST',
				data: {
					sub_name: subName,
					remark: subRemark
				},
				dataType: 'json',
				beforeSend: function() {
					$('.save-edit-sub-category[data-id="' + id + '"]')
						.prop('disabled', true)
						.html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						});
						loadSubCategories(categoryId);
					} else {
						$('.save-edit-sub-category[data-id="' + id + '"]')
							.prop('disabled', false)
							.html('<i class="fa fa-save"></i>');
						Swal.fire('Failed!', response.message, 'error');
					}
				},
				error: function() {
					$('.save-edit-sub-category[data-id="' + id + '"]')
						.prop('disabled', false)
						.html('<i class="fa fa-save"></i>');
					Swal.fire('Error!', 'System error occurred.', 'error');
				}
			});
		});

		// Delete Sub Category
		$(document).on('click', '.delete-sub-category', function() {
			var id = $(this).data('id');
			var categoryId = $(this).data('category-id');
			var name = $(this).data('name');

			Swal.fire({
				title: 'Konfirmasi Hapus',
				text: 'Apakah Anda yakin ingin menghapus sub kategori "' + name + '"?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_category',
						type: 'POST',
						data: {
							id: id
						},
						dataType: 'json',
						success: function(response) {
							if (response.status == 1) {
								Swal.fire({
									icon: 'success',
									title: 'Berhasil!',
									text: response.message,
									timer: 2000,
									showConfirmButton: false
								});
								loadSubCategories(categoryId);
								updateCategoryBadge(categoryId);
							} else {
								Swal.fire('Gagal!', response.message, 'error');
							}
						},
						error: function() {
							Swal.fire('Error!', 'Terjadi kesalahan pada sistem.', 'error');
						}
					});
				}
			});
		});


		// Enter key to save
		$(document).on('keypress', '.edit-sub-name', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				var id = $(this).data('id');
				$('.save-edit-sub-category[data-id="' + id + '"]').click();
			}
		});
	});
</script>