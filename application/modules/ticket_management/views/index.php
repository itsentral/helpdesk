<?php
$ENABLE_ADD     = has_permission('Ticket_Management.Add');
$ENABLE_MANAGE  = has_permission('Ticket_Management.Manage');
$ENABLE_VIEW    = has_permission('Ticket_Management.View');
$ENABLE_DELETE  = has_permission('Ticket_Management.Delete');
?>

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

	.sort-per-pic {
		font-size: 11px;
		padding: 2px 8px;
		border-radius: 4px;
	}

	.sort-per-pic.active {
		font-weight: 600;
	}
</style>

<!-- Berry Card -->
<div class="card">
	<div class="card-body">
		<!-- Tab Navigation -->
		<div class="mb-3">
			<ul class="nav nav-tabs" id="helpdeskTabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="programmer-tab"
						data-bs-toggle="tab" data-bs-target="#tab-programmer"
						type="button" role="tab">
						<i class="fa-solid fa-code"></i> Programmer
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="ba-tab"
						data-bs-toggle="tab" data-bs-target="#tab-ba"
						type="button" role="tab">
						<i class="fa-solid fa-business-time"></i> Business Analyst
					</button>
				</li>
			</ul>
		</div>

		<!-- Tab Content -->
		<div class="tab-content" id="helpdeskTabContent">

			<!-- PROGRAMMER TAB -->
			<div class="tab-pane fade show active" id="tab-programmer" role="tabpanel">
				<div id="skeleton-loading-programmer"></div>
				<div id="helpdesk-content-programmer" style="display:none;"></div>
			</div>

			<!-- BA TAB -->
			<div class="tab-pane fade" id="tab-ba" role="tabpanel">
				<div id="skeleton-loading-ba"></div>
				<div id="helpdesk-content-ba" style="display:none;"></div>
			</div>

		</div>
	</div>
</div>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
	// SORTABLE (drag & drop priority)
	function initSortable() {
		document.querySelectorAll('.sortable-list').forEach(function(el) {
			if (el.dataset.sortableInit) return;
			el.dataset.sortableInit = '1';

			Sortable.create(el, {
				handle: '.drag-handle',
				animation: 150,
				ghostClass: 'bg-info',
				onEnd: function(evt) {
					var type = el.dataset.type;
					var orders = [];

					updateRowNumbers(el);

					el.querySelectorAll('.ticket-row').forEach(function(row, index) {
						orders.push({
							id: row.dataset.id,
							order: index + 1
						});
					});

					saveOrder(type, orders);
				}
			});
		});
	}

	function updateRowNumbers(container) {
		container.querySelectorAll('.ticket-row').forEach(function(row, index) {
			var numberDiv = row.querySelector('.priority-number');
			if (numberDiv) numberDiv.innerText = index + 1;
		});
	}

	function saveOrder(type, orders) {
		$.ajax({
			url: siteurl + active_controller + 'update_order',
			type: 'POST',
			data: {
				type: type,
				orders: orders
			},
			success: function(res) {
				var result = JSON.parse(res);
				if (result.success) {
					toastr.success('Urutan prioritas berhasil disimpan.');
				} else {
					toastr.error('Gagal menyimpan urutan.');
				}
			},
			error: function() {
				toastr.error('Terjadi kesalahan saat menyimpan urutan.');
			}
		});
	}

	// LOAD LIST
	function getSkeletonHTML() {
		var skeletonRows = '';
		for (var i = 0; i < 5; i++) {
			skeletonRows += `
                <tr>
                    <td width="5%"><div class="skeleton skeleton-line short"></div></td>
                    <td><div class="skeleton skeleton-line medium"></div></td>
                    <td width="15%"><div class="skeleton skeleton-line short"></div></td>
                    <td width="15%"><div class="skeleton skeleton-line short"></div></td>
                    <td width="15%"><div class="skeleton skeleton-line medium"></div></td>
                    <td width="15%"><div class="skeleton skeleton-line medium"></div></td>
                    <td width="20%"><div class="skeleton skeleton-line short"></div></td>
                </tr>`;
		}
		return `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>${skeletonRows}</tbody>
                </table>
            </div>`;
	}

	function loadProgrammerList() {
		$.ajax({
			url: siteurl + active_controller + 'get_list_programmer',
			type: 'GET',
			beforeSend: function() {
				$('#skeleton-loading-programmer').html(getSkeletonHTML()).show();
				$('#helpdesk-content-programmer').hide();
			},
			success: function(response) {
				$('#skeleton-loading-programmer').hide();
				$('#helpdesk-content-programmer').html(response).fadeIn();
				initSortable();
			},
			error: function() {
				$('#skeleton-loading-programmer').hide();
				$('#helpdesk-content-programmer')
					.html('<div class="alert alert-danger">Gagal memuat data helpdesk programmer.</div>')
					.show();
			}
		});
	}

	function loadBAList() {
		$.ajax({
			url: siteurl + active_controller + 'get_list_ba',
			type: 'GET',
			beforeSend: function() {
				$('#skeleton-loading-ba').html(getSkeletonHTML()).show();
				$('#helpdesk-content-ba').hide();
			},
			success: function(response) {
				$('#skeleton-loading-ba').hide();
				$('#helpdesk-content-ba').html(response).fadeIn();
				initSortable();
			},
			error: function() {
				$('#skeleton-loading-ba').hide();
				$('#helpdesk-content-ba')
					.html('<div class="alert alert-danger">Gagal memuat data helpdesk BA.</div>')
					.show();
			}
		});
	}

	function _executeSortByDueDate(btn) {
		var sortableId = btn.data('sortable-id');
		var type = btn.data('type'); // 'programmer' atau 'ba'
		var $list = $('#' + sortableId);

		$list.closest('.table-responsive').find('.sort-per-pic').removeClass('active');
		btn.addClass('active');

		var $rows = $list.find('.ticket-row');
		var nonDone = $rows.filter(function() {
			return $(this).data('status') != 4;
		}).toArray();

		var done = $rows.filter(function() {
			return $(this).data('status') == 4;
		}).toArray();

		// Sort non-done by due_date ASC, kosong ke paling bawah
		nonDone.sort(function(a, b) {
			var dateA = $(a).data('due-date');
			var dateB = $(b).data('due-date');

			if (!dateA && !dateB) return 0;
			if (!dateA) return 1; // kosong ke bawah
			if (!dateB) return -1;
			return dateA.localeCompare(dateB);
		});

		// Gabung: non-done terurut + done tetap di bawah
		var finalOrder = nonDone.concat(done);

		$.each(finalOrder, function(i, el) {
			$list.append(el);
		});
		updateRowNumbers($list[0]);

		var orders = [];
		$.each(finalOrder, function(i, el) {
			orders.push({
				id: $(el).data('id'),
				order: i + 1
			});
		});

		_saveOrderAfterSort(type, orders, btn);
	}

	function _saveOrderAfterSort(type, orders, btn) {
		btn.prop('disabled', true)
			.html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyimpan...');

		$.ajax({
			url: siteurl + active_controller + 'update_order',
			type: 'POST',
			data: {
				type: type,
				orders: orders
			},
			success: function(res) {
				var result = JSON.parse(res);
				if (result.success) {
					toastr.success('Urutan berhasil disimpan berdasarkan due date.');
				} else {
					toastr.error('Gagal menyimpan urutan.');
				}
			},
			error: function() {
				toastr.error('Terjadi kesalahan saat menyimpan urutan.');
			},
			complete: function() {
				btn.prop('disabled', false)
					.html('<i class="fa-solid fa-calendar-days me-1"></i> Due Date');
			}
		});
	}

	$(document).ready(function() {
		loadProgrammerList();

		var baLoaded = false;
		$('#ba-tab').on('shown.bs.tab', function() {
			if (!baLoaded) {
				loadBAList();
				baLoaded = true;
			}
		});

		$(document).on('click', '.sort-per-pic', function() {
			var btn = $(this);
			var sortType = btn.data('sort');

			if (sortType === 'due_date') {
				Swal.fire({
					title: 'Sort by Due Date?',
					html: `Urutan ticket akan disesuaikan berdasarkan <b>due date terdekat</b>`,
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: '<i class="fa-solid fa-check me-1"></i> Ya, Simpan',
					cancelButtonText: '<i class="fa-solid fa-xmark me-1"></i> Batal',
					confirmButtonColor: '#0dcaf0',
					cancelButtonColor: '#6c757d',
					reverseButtons: true,
				}).then(function(result) {
					if (result.isConfirmed) {
						_executeSortByDueDate(btn);
					}
				});
			}
		});
	});
</script>