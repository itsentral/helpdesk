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
</style>

<!-- Berry Card -->
<div class="card">
	<div class="card-body">
		<!-- Tab Navigation -->
		<div class="mb-3">
			<ul class="nav nav-tabs" id="helpdeskTabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="programmer-tab" data-bs-toggle="tab" data-bs-target="#tab-programmer" type="button" role="tab">
						<i class="fa-solid fa-code"></i> Programmer
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="ba-tab" data-bs-toggle="tab" data-bs-target="#tab-ba" type="button" role="tab">
						<i class="fa-solid fa-business-time"></i> Business Analyst
					</button>
				</li>
			</ul>
		</div>

		<!-- Tab Content -->
		<div class="tab-content" id="helpdeskTabContent">

			<!-- programmer TAB -->
			<div class="tab-pane fade show active" id="tab-programmer" role="tabpanel">

				<!-- programmer CONTENT -->
				<div id="skeleton-loading-programmer"></div>
				<div id="helpdesk-content-programmer" style="display:none;"></div>

			</div>

			<!-- ba TAB -->
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
<!-- Viewer.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

<!-- Viewer.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
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
			if (numberDiv) {
				numberDiv.innerText = index + 1;
			}
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
				initSortable(); // <-- init setelah konten masuk

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
				initSortable(); // <-- init setelah konten masuk

			},
			error: function() {
				$('#skeleton-loading-ba').hide();
				$('#helpdesk-content-ba')
					.html('<div class="alert alert-danger">Gagal memuat data helpdesk BA.</div>')
					.show();
			}
		});
	}

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
                </tr>
            `;
		}

		return `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>${skeletonRows}</tbody>
                </table>
            </div>
        `;
	}

	$(document).ready(function() {
		// Load programmer tab saat pertama kali
		loadProgrammerList();

		// Load BA tab hanya saat pertama kali diklik (lazy load)
		var baLoaded = false;
		$('#ba-tab').on('shown.bs.tab', function() {
			if (!baLoaded) {
				loadBAList();
				baLoaded = true;
			}
		});

		// Refresh sesuai tab yang sedang aktif
		$(document).on('click', '.refresh-list-helpdesk', function(e) {
			e.preventDefault();
			var activeTab = $('.nav-tabs .nav-link.active').attr('id');
			if (activeTab === 'ba-tab') {
				baLoaded = false;
				loadBAList();
			} else {
				loadProgrammerList();
			}
		});
	});
</script>