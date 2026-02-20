<?php
$ENABLE_ADD     = has_permission('Ticket.Add');
$ENABLE_MANAGE  = has_permission('Ticket.Manage');
$ENABLE_VIEW    = has_permission('Ticket.View');
$ENABLE_DELETE  = has_permission('Ticket.Delete');
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

	#modalHistoryTicket .modal-dialog {
		max-width: 650px;
	}

	/* Timeline */
	.timeline-history {
		max-height: 600px;
		overflow-y: auto;
	}

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

	/* Scrollbar */
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

	.timeline-history::-webkit-scrollbar-thumb:hover {
		background: #a0aec0;
	}

	/* Chat Styles */
	/* Chat Styles */
	.chat-message {
		margin-bottom: 15px;
		display: flex;
		flex-direction: column;
	}

	.chat-message.sent {
		align-items: flex-end;
	}

	.chat-message.received {
		align-items: flex-start;
	}

	.chat-bubble {
		max-width: 70%;
		padding: 10px 15px;
		border-radius: 15px;
		word-wrap: break-word;
		position: relative;
	}

	.chat-message.sent .chat-bubble {
		background: #007bff;
		color: white;
		border-bottom-right-radius: 5px;
	}

	.chat-message.received .chat-bubble {
		background: white;
		border: 1px solid #dee2e6;
		border-bottom-left-radius: 5px;
	}

	.chat-sender {
		font-size: 14px;
		font-weight: bold;
		margin-bottom: 5px;
		color: #007bff;
	}

	.chat-message.sent .chat-sender {
		color: rgba(255, 255, 255, 0.9);
	}

	.message-content {
		font-size: 14px;
		line-height: 1.4;
	}

	.chat-time {
		font-size: 10px;
		color: #6c757d;
		margin-top: 3px;
		padding: 0 5px;
	}

	.chat-file {
		margin-top: 5px;
		padding: 8px 12px;
		background: rgba(0, 0, 0, 0.1);
		border-radius: 8px;
	}

	.chat-file a {
		color: inherit;
		text-decoration: none;
	}

	.chat-file a:hover {
		text-decoration: underline;
	}

	.chat-message.sent .chat-file {
		background: rgba(255, 255, 255, 0.2);
	}

	#chatMessages::-webkit-scrollbar {
		width: 6px;
	}

	#chatMessages::-webkit-scrollbar-track {
		background: #f1f1f1;
	}

	#chatMessages::-webkit-scrollbar-thumb {
		background: #cbd5e0;
		border-radius: 10px;
	}

	/* READERS POPUP */
	.readers-popup {
		position: absolute;
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 8px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		z-index: 9999;
		min-width: 250px;
		max-width: 300px;
		font-size: 13px;
		animation: fadeIn 0.2s ease;
	}

	.readers-header {
		padding: 10px 15px;
		border-bottom: 1px solid #eee;
		background: #f8f9fa;
		border-radius: 8px 8px 0 0;
	}

	.readers-list {
		max-height: 200px;
		overflow-y: auto;
		padding: 8px 0;
	}

	.reader-item {
		padding: 6px 15px;
	}

	.reader-item:nth-child(even) {
		background: #fafafa;
	}

	.reader-item:not(:last-child) {
		border-bottom: 1px solid #f0f0f0;
	}

	.readers-arrow {
		position: absolute;
		top: 100%;
		left: 50%;
		transform: translateX(-50%);
		width: 0;
		height: 0;
		border-left: 8px solid transparent;
		border-right: 8px solid transparent;
		border-top: 8px solid #fff;
	}

	/* ===== TOAST ===== */
	.simple-toast {
		position: fixed;
		top: 20px;
		right: 20px;
		color: #fff;
		padding: 10px 15px;
		border-radius: 5px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		z-index: 99999999;
		animation: slideIn 0.3s ease;
		max-width: 300px;
		font-size: 13px;
	}

	.simple-toast.error {
		background: #dc3545;
	}

	.simple-toast.success {
		background: #198754;
	}

	.simple-toast.warning {
		background: #ffc107;
		color: #000;
	}

	.simple-toast.info {
		background: #0dcaf0;
	}

	/* ===== ANIMATION ===== */
	@keyframes slideIn {
		from {
			transform: translateX(100%);
			opacity: 0;
		}

		to {
			transform: translateX(0);
			opacity: 1;
		}
	}

	@keyframes fadeIn {
		from {
			opacity: 0;
			transform: translateY(-5px);
		}

		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.readers-list::-webkit-scrollbar {
		width: 6px;
	}

	.readers-list::-webkit-scrollbar-thumb {
		background: #ccc;
		border-radius: 3px;
	}

	.readers-popup.popup-top .readers-arrow {
		top: 100%;
		border-top: 8px solid #fff;
	}

	.readers-popup.popup-bottom .readers-arrow {
		top: -8px;
		border-top: none;
		border-bottom: 8px solid #fff;
	}

	#scrollToBottomBtn {
		opacity: 0.9;
		transition: opacity 0.3s;
	}

	#scrollToBottomBtn:hover {
		opacity: 1;
		transform: scale(1.05);
	}

	#scrollToBottomBtn i {
		font-size: 16px;
	}

	.swal2-container {
		z-index: 3000 !important;
	}

	#chatMessages {
		background-color: #e5f2ff;
		background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%2347a8ff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
	}

	.chat-image {
		margin-top: 8px;
	}

	.chat-image-preview {
		display: block;
		object-fit: cover;
		transition: transform 0.2s ease;
	}

	.chat-image-preview:hover {
		transform: scale(1.02);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
	}

	.chat-file a {
		word-break: break-all;
	}
</style>

<!-- Berry Card -->
<div class="card">
	<div class="card-body">
		<!-- Tab Navigation -->
		<div class="mb-3">
			<ul class="nav nav-tabs" id="helpdeskTabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="open-tab" data-bs-toggle="tab" data-bs-target="#tab-open" type="button" role="tab">
						<i class="fa-solid fa-circle-dot"></i> Open
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#tab-approved" type="button" role="tab">
						<i class="fa-solid fa-check-double"></i> Approved
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="cancel-tab" data-bs-toggle="tab" data-bs-target="#tab-cancel" type="button" role="tab">
						<i class="fa-solid fa-ban"></i> Cancel
					</button>
				</li>
			</ul>
		</div>

		<!-- Tab Content -->
		<div class="tab-content" id="helpdeskTabContent">

			<!-- OPEN TAB -->
			<div class="tab-pane fade show active" id="tab-open" role="tabpanel">
				<div class="mb-3">
					<div class="d-flex justify-content-end gap-2">
						<select class="form-select form-select-sm" id="filterClient" style="width: 200px;">
							<option value="">All Client</option>
							<!-- Options via AJAX -->
						</select>

						<select class="form-select form-select-sm" id="filterStatus" style="width: 220px;">
							<option value="">Filter By Status</option>
							<option value="0">Open</option>
							<option value="1">Process</option>
							<option value="2">Pending</option>
							<option value="6">Revisi</option>
							<option value="4">Done</option>
							<option value="waiting_approval">Menunggu Approval</option>
							<option value="waiting_creator">Menunggu Konfirmasi Pembuat</option>
							<option value="no_pic">PIC Belum Ditunjuk</option>
						</select>

						<button class="btn btn-primary btn-sm refresh-list-helpdesk">
							<i class="fa-solid fa-arrows-rotate"></i> Refresh
						</button>

						<?php if (has_permission('Ticket.Add')): ?>
							<a href="<?= site_url('ticket/add_ticket') ?>" class="btn btn-success btn-sm">
								<i class="fa-solid fa-plus"></i> Add New Ticket
							</a>
						<?php endif; ?>
					</div>
				</div>

				<!-- OPEN CONTENT -->
				<div id="skeleton-loading-open"></div>
				<div id="helpdesk-content-open" style="display:none;"></div>

			</div>

			<!-- APPROVED TAB -->
			<div class="tab-pane fade" id="tab-approved" role="tabpanel">
				<div class="d-flex justify-content-end gap-2">
					<select class="form-select form-select-sm" id="filterClientApproved" style="width: 200px;">
						<option value="">All Client</option>
						<!-- Options via AJAX -->
					</select>
					<button class="btn btn-primary btn-sm refresh-list-approve">
						<i class="fa-solid fa-arrows-rotate"></i> Refresh
					</button>
				</div>
				<div id="skeleton-loading-approved"></div>
				<div id="helpdesk-content-approved" style="display:none;"></div>
			</div>

			<!-- CANCEL TAB -->
			<div class="tab-pane fade" id="tab-cancel" role="tabpanel">
				<div class="d-flex justify-content-end gap-2">
					<select class="form-select form-select-sm" id="filterClientCancel" style="width: 200px;">
						<option value="">All Client</option>
						<!-- Options via AJAX -->
					</select>
					<button class="btn btn-primary btn-sm refresh-list-cancel">
						<i class="fa-solid fa-arrows-rotate"></i> Refresh
					</button>
				</div>
				<div id="skeleton-loading-cancel"></div>
				<div id="helpdesk-content-cancel" style="display:none;"></div>
			</div>

		</div>
	</div>
</div>

<!-- Modal History Ticket -->
<div class="modal fade" id="modalHistoryTicket" tabindex="-1" aria-labelledby="modalHistoryLabel" aria-hidden="true" data-bs-backdrop="static"
	data-bs-keyboard="false">
	<div class="modal-dialog modal-dialog-scrollable">
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
				<div id="historyTimeline" class="timeline-history p-3"></div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Chat Room -->
<div class="modal fade" id="modalChatRoom" tabindex="-1" aria-labelledby="modalChatLabel" aria-hidden="true" data-bs-backdrop="static">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content d-flex flex-column" style="height: 90vh; max-height: 700px;">
			<div class="modal-header bg-primary text-white flex-shrink-0">
				<h5 class="modal-title text-white" id="modalChatLabel">
					<i class="fa-solid fa-comments"></i> Chat Room - Ticket: <span id="chatTicketNo"></span>
				</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
			</div>

			<div class="modal-body p-0 d-flex flex-column flex-grow-1">
				<!-- Chat Messages Container -->
				<div id="chatMessages" class="flex-grow-1 p-3" style="overflow-y: auto;">
					<div id="chatLoading" class="text-center py-5">
						<i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
						<p class="mt-2">Loading chat...</p>
					</div>

					<div id="chatMessagesContent"></div>
				</div>

				<!-- Chat Input - Sticky Bottom -->
				<div class="border-top p-3 bg-white flex-shrink-0" style="position: sticky; bottom: 0; z-index: 10;">
					<form id="chatForm" enctype="multipart/form-data">
						<input type="hidden" id="chatHelpdeskId" name="helpdesk_id">

						<!-- File Preview -->
						<div id="filePreview" class="mb-2" style="display: none;">
							<div class="alert alert-info d-flex align-items-center justify-content-between mb-0">
								<div>
									<i class="fa-solid fa-file"></i>
									<span id="fileName"></span>
									<small class="text-muted">(<span id="fileSize"></span>)</small>
								</div>
								<button type="button" class="btn btn-sm btn-danger" id="removeFile">
									<i class="fa-solid fa-times"></i>
								</button>
							</div>
						</div>

						<div class="input-group">
							<input type="file" id="chatFile" name="chat_file" class="d-none" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">

							<button type="button" class="btn btn-outline-secondary" id="btnAttachFile">
								<i class="fa-solid fa-paperclip"></i>
							</button>

							<input type="text" class="form-control" id="chatMessage" name="message"
								placeholder="Type your message..." autocomplete="off">

							<button type="submit" class="btn btn-primary" id="btnSendChat">
								<i class="fa-solid fa-paper-plane"></i> Send
							</button>
						</div>
						<small class="text-muted d-block mt-1">
							<i class="fa-solid fa-info-circle"></i> Maximum file size: 2MB
						</small>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<!-- Viewer.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

<!-- Viewer.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<script>
	let currentHelpdeskId = null;
	let chatRefreshInterval = null;
	let unreadCountInterval = null;
	let shouldAutoScroll = true;
	let userHasScrolledUp = false;
	let lastRenderedMessages = [];
	let viewerInstance = null;
	let selectedClientId = '';
	let selectedClientIdApproved = '';
	let selectedClientIdCancel = '';
	let selectedStatusId = '';


	function loadClientList() {
		$.ajax({
			url: siteurl + active_controller + 'get_client_list',
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				console.log(response)
				if (response.status == 1) {
					let options = '<option value="">All Client</option>';

					response.data.forEach(function(client) {
						options += `<option value="${client.id}">${client.name_app}</option>`;
					});

					$('#filterClient').html(options);
					$('#filterClientApproved').html(options);
					$('#filterClientCancel').html(options);
				}
			}
		});
	}

	function checkIfUserScrolledUp() {
		const chatMessages = $('#chatMessages')[0];
		if (!chatMessages) return false;

		const threshold = 100;
		const distanceFromBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight;

		return distanceFromBottom > threshold;
	}

	function showScrollToBottomButton() {
		$('#scrollToBottomBtn').remove();

		const buttonHtml = `
				<button id="scrollToBottomBtn" class="btn btn-primary btn-sm shadow" 
						style="position: absolute; bottom: 70px; right: 20px; z-index: 1000;
							border-radius: 50%; width: 40px; height: 40px; padding: 0;">
					<i class="fa-solid fa-chevron-down"></i>
				</button>
			`;

		$('#chatMessages').parent().css('position', 'relative').append(buttonHtml);
	}

	function hideScrollToBottomButton() {
		$('#scrollToBottomBtn').fadeOut();
	}

	$('#modalChatRoom').on('hidden.bs.modal', function(e) {
		// CEK: Jangan jalankan cleanup jika viewer sedang aktif
		if ($(this).data('viewer-active') === true) {
			console.log('Viewer is active, skip modal cleanup');
			return;
		}

		// CEK: Jangan jalankan jika masih ada viewer container
		if ($('.viewer-container').length > 0) {
			console.log('Viewer container exists, skip modal cleanup');
			return;
		}

		stopChatRefresh();
		currentHelpdeskId = null;
		shouldAutoScroll = true;
		userHasScrolledUp = false;
		lastRenderedMessages = [];

		$('#chatMessagesContent').html('');
		$('#chatMessage').val('');
		$('#chatFile').val('');
		$('#filePreview').hide();
		$('#scrollToBottomBtn').remove();
		$('#chatMessages').off('scroll');
	});

	function loadHelpdeskList(clientId = '', statusFilter = '') {
		$.ajax({
			url: siteurl + active_controller + 'get_list_ticket',
			type: 'GET',
			data: {
				client_id: clientId,
				status_filter: statusFilter
			},
			beforeSend: function() {
				$('#skeleton-loading-open').html(getSkeletonHTML()).show();
				$('#helpdesk-content-open').hide();
			},
			success: function(response) {
				$('#skeleton-loading-open').hide();
				$('#helpdesk-content-open').html(response).fadeIn();

				if ($.fn.DataTable.isDataTable('#table_helpdesk')) {
					$('#table_helpdesk').DataTable().destroy();
				}

				$('#table_helpdesk').DataTable({
					paging: true,
					searching: true,
					order: [],
					info: true,
					responsive: true,
				});

				setTimeout(loadUnreadCounts, 500);
			},
			error: function() {
				$('#skeleton-loading-open').hide();
				$('#helpdesk-content-open')
					.html('<div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data helpdesk.</div>')
					.show();
			}
		});
	}

	// Update function loadApprovedList
	function loadApprovedList(clientId = '') {
		$.ajax({
			url: siteurl + active_controller + 'get_list_approved',
			type: 'GET',
			data: {
				client_id: clientId
			},
			beforeSend: function() {
				$('#skeleton-loading-approved').html(getSkeletonHTML()).show();
				$('#helpdesk-content-approved').hide();
			},
			success: function(response) {
				$('#skeleton-loading-approved').hide();
				$('#helpdesk-content-approved').html(response).fadeIn();

				if ($.fn.DataTable.isDataTable('#table_approved')) {
					$('#table_approved').DataTable().destroy();
				}

				$('#table_approved').DataTable({
					paging: true,
					searching: true,
					order: [],
					info: true,
					responsive: true,
				});
			},
			error: function() {
				$('#skeleton-loading-approved').hide();
				$('#helpdesk-content-approved')
					.html('<div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data approved.</div>')
					.show();
			}
		});
	}

	// Update function loadCancelList
	function loadCancelList(clientId = '') {
		$.ajax({
			url: siteurl + active_controller + 'get_list_cancel',
			type: 'GET',
			data: {
				client_id: clientId
			},
			beforeSend: function() {
				$('#skeleton-loading-cancel').html(getSkeletonHTML()).show();
				$('#helpdesk-content-cancel').hide();
			},
			success: function(response) {
				$('#skeleton-loading-cancel').hide();
				$('#helpdesk-content-cancel').html(response).fadeIn();

				if ($.fn.DataTable.isDataTable('#table_cancel')) {
					$('#table_cancel').DataTable().destroy();
				}

				$('#table_cancel').DataTable({
					paging: true,
					searching: true,
					order: [],
					info: true,
					responsive: true,
				});
			},
			error: function() {
				$('#skeleton-loading-cancel').hide();
				$('#helpdesk-content-cancel')
					.html('<div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data cancel.</div>')
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

	function updateTicketApproval(ticketId, action) {

		var actionUpper = action.charAt(0).toUpperCase() + action.slice(1);

		var modalContent = `
        <div class="mb-3">
            <p>
                ${action === 'approve'
                    ? 'Approval akan diproses.'
                    : 'Ticket akan dikembalikan ke revisi.'}
            </p>
            <div class="form-group mt-3">
                <label>
                    ${action === 'approve' ? 'Catatan Approval' : 'Alasan Penolakan'}
                </label>
                <textarea class="form-control" id="approvalReason" rows="3"></textarea>
            </div>
        </div>
    	`;

		Swal.fire({
			title: 'Konfirmasi ' + actionUpper,
			html: modalContent,
			icon: action === 'approve' ? 'question' : 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, ' + actionUpper,
			preConfirm: () => {
				let reason = $('#approvalReason').val();
				if (!reason.trim()) {
					Swal.showValidationMessage('Wajib diisi');
					return false;
				}
				return {
					reason: reason.trim()
				};
			}
		}).then((result) => {
			if (!result.isConfirmed) return;

			$.ajax({
				url: siteurl + active_controller + 'update_approval',
				type: 'POST',
				dataType: 'json',
				data: {
					id: ticketId,
					action: action, // approve / reject
					approval_reason: result.value.reason
				},
				success: function(res) {
					Swal.fire({
						icon: res.status ? 'success' : 'error',
						title: res.status ? 'Berhasil' : 'Gagal',
						text: res.message,
						timer: 1500,
						showConfirmButton: false
					});
					if (res.status) loadHelpdeskList();
				}
			});
		});
	}


	function changeTicketStatus(ticketId, status, statusText, currentStatus = null, manHourPlan = null) {
		var modalContent = '';
		var showReasonInput = false;
		var showManHourInput = false;
		var showManHourPlanInput = false;

		switch (status) {
			case 1: // Process
				// CEK apakah man_hour_plan sudah diisi
				if (!manHourPlan || manHourPlan == 0 || manHourPlan == null) {
					modalContent = `
                    <div class="mb-3">
                        <h2>Man Hour Plan belum diisi!</h2>
                        <div class="form-group mt-3">
                            <label for="manHourPlan" class="form-label">
                                <i class="fa-solid fa-clock"></i> Man Hour Plan <small class="text-danger">*</small>
                            </label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    class="form-control"
                                    id="manHourPlan"
                                    placeholder="Contoh: 4"
                                    min="0.5"
                                    step="0.5"
                                    required
                                >
                                <span class="input-group-text">jam</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="fa-solid fa-info-circle"></i> Masukkan estimasi waktu yang dibutuhkan untuk menyelesaikan ticket ini
                            </small>
                        </div>
                    </div>
                `;
					showManHourPlanInput = true;
				} else {
					modalContent = '<h2>Ubah status ticket menjadi Process?</h2>';
				}
				break;

			case 2: // Pending
				modalContent = '<h2>Ubah status ticket menjadi Pending?</h2>';
				break;

			case 3: // Cancel
				modalContent = `
                <div class="mb-3">
                    <h2>Ubah status ticket menjadi Cancel?</h2>
                    <div class="form-group mt-3">
                        <label for="cancelReason" class="form-label">
                            Alasan Pembatalan <small class="text-danger">*</small>
                        </label>
                        <textarea
                            class="form-control"
                            id="cancelReason"
                            rows="3"
                            placeholder="Masukkan alasan pembatalan..."
                        ></textarea>
                    </div>
                </div>
            `;
				showReasonInput = true;
				break;

			case 4: // Done
				// Cek apakah dari status Process (1)
				if (currentStatus == 1) {
					modalContent = `
                    <div class="mb-3">
                        <h2>Ubah status ticket menjadi Done?</h2>
                        <div class="form-group mt-3">
                            <label for="manHourActual" class="form-label">
                                <i class="fa-solid fa-clock"></i> Man Hour Actual <small class="text-danger">*</small>
                            </label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    class="form-control"
                                    id="manHourActual"
                                    placeholder="Contoh: 2.5"
                                    min="0"
                                    step="0.5"
                                    required
                                >
                                <span class="input-group-text">jam</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="fa-solid fa-info-circle"></i> Masukkan waktu aktual yang dihabiskan untuk menyelesaikan ticket ini
                            </small>
                        </div>
                    </div>
                `;
					showManHourInput = true;
				} else {
					modalContent = '<h2>Ubah status ticket menjadi Done?</h2>';
				}
				break;

			default:
				modalContent = '<h2>Konfirmasi perubahan status?</h2>';
		}

		Swal.fire({
			html: modalContent,
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Ubah Status',
			cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#6c757d',
			showLoaderOnConfirm: true,
			preConfirm: () => {
				var cancelReason = '';
				var manHourActual = '';
				var manHourPlanInput = '';

				// Validasi Cancel Reason
				if (showReasonInput) {
					cancelReason = $('#cancelReason').val();
					if (!cancelReason || !cancelReason.trim()) {
						Swal.showValidationMessage('Alasan pembatalan wajib diisi');
						return false;
					}
				}

				// Validasi Man Hour Plan
				if (showManHourPlanInput) {
					manHourPlanInput = $('#manHourPlan').val();
					if (!manHourPlanInput || manHourPlanInput <= 0) {
						Swal.showValidationMessage('Man Hour Plan wajib diisi');
						return false;
					}
				}

				// Validasi Man Hour Actual
				if (showManHourInput) {
					manHourActual = $('#manHourActual').val();
					if (!manHourActual || manHourActual <= 0) {
						Swal.showValidationMessage('Man Hour Actual wajib diisi');
						return false;
					}
				}

				return new Promise((resolve, reject) => {
					$.ajax({
						url: siteurl + active_controller + 'update_status',
						type: 'POST',
						data: {
							id: ticketId,
							status: status,
							current_status: currentStatus,
							cancel_reason: cancelReason ? cancelReason.trim() : '',
							man_hour_actual: manHourActual ? parseFloat(manHourActual) : '',
							man_hour_plan: manHourPlanInput ? parseFloat(manHourPlanInput) : ''
						},
						dataType: 'json',
						success: function(response) {
							if (response.status == 1) {
								resolve(response);
							} else {
								reject(new Error(response.message || 'Terjadi kesalahan'));
							}
						},
						error: function(xhr, status, error) {
							reject(new Error(
								xhr.responseJSON?.message || error || 'Request gagal'
							));
						}
					});
				});
			},
			allowOutsideClick: () => !Swal.isLoading()
		}).then((result) => {
			if (result.isConfirmed && result.value) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil!',
					text: result.value.message,
					timer: 2000,
					showConfirmButton: false
				});
				loadHelpdeskList();
			}
		});
	}

	function viewTicketHistory(ticketId, ticketNo) {
		$('#historyTicketNo').text(ticketNo);
		$('#modalHistoryTicket').modal('show');
		$('#historyLoading').show();
		$('#historyEmpty').hide();
		$('#historyTimeline').hide().html('');

		$.ajax({
			url: siteurl + active_controller + 'get_ticket_history/' + ticketId,
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				$('#historyLoading').hide();

				if (response.status == 1 && response.data.length > 0) {
					var historyHTML = buildHistoryTimeline(response.data);
					$('#historyTimeline').html(historyHTML).show();
				} else {
					$('#historyEmpty').show();
				}
			},
			error: function() {
				$('#historyLoading').hide();
				$('#historyTimeline').html(
					'<div class="alert alert-danger m-3">' +
					'<i class="fa-solid fa-exclamation-triangle"></i> Gagal memuat history' +
					'</div>'
				).show();
			}
		});
	}

	function buildHistoryTimeline(historyData) {
		var timeline = '';

		// ACTION TYPE CONFIG
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


		// STATUS LABEL
		var statusLabels = {
			0: 'Open',
			1: 'Process',
			2: 'Pending',
			3: 'Cancel',
			4: 'Done',
			5: 'Close',
			6: 'Revisi'
		};


		// LOOP HISTORY
		historyData.forEach(function(item) {
			var actionInfo = actionTypeLabels[item.action_type] || {
				icon: 'fa-circle',
				text: 'Unknown',
				color: '#6c757d'
			};

			var description = item.description || '';

			// STATUS CHANGE INFO
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

			// APPROVAL LEVEL INFO
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

			// REJECT INFO
			if (item.action_type == 5) {
				description += `
				<br>
				<small class="text-warning">
					<i class="fa-solid fa-rotate-left"></i>
					Tiket dikembalikan ke revisi
				</small>`;
			}

			// REMARK / NOTE
			if (item.cause_pic && item.cause_pic.trim() !== '') {
				description += `
				<br>
				<small class="text-info">
					<i class="fa-solid fa-comment-dots"></i>
					<strong>Remark:</strong> ${item.cause_pic}
				</small>`;
			}

			// BUILD HTML
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

	function loadUnreadCounts() {
		$.ajax({
			url: siteurl + active_controller + 'get_unread_chat_counts',
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				if (response.status == 1) {
					updateUnreadBadges(response.data);
				}
			}
		});
	}

	function updateUnreadBadges(unreadCounts) {
		$('[class*="chat-unread-badge-"]').hide().text('0');

		$.each(unreadCounts, function(helpdeskId, count) {
			const badge = $('.chat-unread-badge-' + helpdeskId);
			if (count > 0) {
				badge.text(count > 99 ? '99+' : count).show();
			} else {
				badge.hide();
			}
		});
	}

	function markChatAsRead(helpdeskId) {
		$.ajax({
			url: siteurl + active_controller + 'mark_chat_read',
			type: 'POST',
			data: {
				helpdesk_id: helpdeskId
			},
			dataType: 'json',
			success: function(response) {
				if (response.status == 1) {
					$('.chat-unread-badge-' + helpdeskId).hide().text('0');
				}
			}
		});
	}

	function openChatRoom(helpdeskId, ticketNo) {
		currentHelpdeskId = helpdeskId;
		$('#chatTicketNo').text(ticketNo);
		$('#chatHelpdeskId').val(helpdeskId);

		shouldAutoScroll = true;
		userHasScrolledUp = false;
		lastRenderedMessages = [];

		markChatAsRead(helpdeskId);
		$('.chat-unread-badge-' + helpdeskId).hide().text('0');
		$('#modalChatRoom').modal('show');
		loadChatMessages(helpdeskId);
		stopChatRefresh();

		chatRefreshInterval = setInterval(() => {
			loadChatMessages(helpdeskId, true);
		}, 5000);
	}

	function stopChatRefresh() {
		if (chatRefreshInterval) {
			clearInterval(chatRefreshInterval);
			chatRefreshInterval = null;
			console.log('Chat auto-refresh distop');
		}
	}

	function startUnreadCountPolling() {
		loadUnreadCounts();

		if (unreadCountInterval) {
			clearInterval(unreadCountInterval);
		}

		unreadCountInterval = setInterval(() => {
			if (!$('#modalChatRoom').hasClass('show')) {
				loadUnreadCounts();
			}
		}, 10000);
	}

	function stopUnreadCountPolling() {
		if (unreadCountInterval) {
			clearInterval(unreadCountInterval);
			unreadCountInterval = null;
		}
	}

	function loadChatMessages(helpdeskId, silent = false) {
		if (!silent) {
			$('#chatLoading').show();
			$('#chatMessagesContent').hide();
		}

		const chatMessages = $('#chatMessages')[0];
		const previousScrollTop = chatMessages ? chatMessages.scrollTop : 0;
		const previousHeight = chatMessages ? chatMessages.scrollHeight : 0;

		$.ajax({
			url: siteurl + active_controller + 'get_chat_messages/' + helpdeskId,
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				$('#chatLoading').hide();

				if (response.status == 1 && response.data && response.data.length > 0) {
					const isFirstLoad = $('#chatMessagesContent').children().length === 0;
					const hasNewMessages = response.data.length > $('#chatMessagesContent').children().length;

					renderChatMessages(response.data, isFirstLoad || hasNewMessages);
					$('#chatMessagesContent').show();

					if ($('#modalChatRoom').hasClass('show') && hasNewMessages) {
						markChatAsRead(helpdeskId);
					}

					if (silent && !userHasScrolledUp) {
						const newHeight = chatMessages.scrollHeight;
						const heightDiff = newHeight - previousHeight;

						if (heightDiff > 0 && previousScrollTop > 0) {
							chatMessages.scrollTop = previousScrollTop + heightDiff;
						}
					}
				} else {
					$('#chatMessagesContent').html(
						'<div class="d-flex align-items-center justify-content-center" style="height: 100%; min-height: 400px;">' +
						'<div class="text-center text-muted">' +
						'<i class="fa-solid fa-comments fa-3x mb-3"></i>' +
						'<p>Belum ada pesan. Mulai percakapan!</p>' +
						'</div>' +
						'</div>'
					).show();
				}
			},
			error: function() {
				$('#chatLoading').hide();
				$('#chatMessagesContent').html(
					'<div class="alert alert-danger">Gagal memuat chat</div>'
				).show();
			}
		});
	}

	function renderChatMessages(messages, isNewMessage = false) {
		const currentUserId = '<?= $this->auth->user_id() ?>';

		userHasScrolledUp = checkIfUserScrolledUp();
		shouldAutoScroll = isNewMessage && !userHasScrolledUp;

		const messagesChanged = JSON.stringify(messages) !== JSON.stringify(lastRenderedMessages);

		if (!messagesChanged && $('#chatMessagesContent').children().length > 0) {
			return;
		}

		lastRenderedMessages = [...messages];

		let html = '';

		messages.forEach(function(msg) {
			const isSent = msg.sender_id == currentUserId;
			const messageClass = isSent ? 'sent' : 'received';

			html += `
				<div class="chat-message ${messageClass}" data-message-id="${msg.id}">
					<div class="chat-bubble">
						${!isSent ? `<div class="chat-sender">${msg.sender_name}</div>` : ''}
						<div class="message-content">${msg.message}</div>
						${msg.file_name ? renderChatFile(msg) : ''}
						${isSent ? `
							<div class="chat-read-status ${msg.read_count > 0 ? 'has-readers' : ''}" 
								data-chat-id="${msg.id}"
								data-read-count="${msg.read_count}"
								style="cursor: ${msg.read_count > 0 ? 'pointer' : 'default'};">
								<small>
									${msg.read_count > 0 
										? `<i class="fa-solid fa-check-double text-primary"></i> 
										<span class="read-count-text">Dilihat (${msg.read_count})</span>`
										: `<i class="fa-solid fa-check text-muted"></i> Terkirim`
									}
								</small>
							</div>
						` : ''}
					</div>
					<div class="chat-time">${formatChatTime(msg.create_date)}</div>
				</div>
			`;
		});

		$('#chatMessagesContent').html(html);

		$('.chat-read-status.has-readers').on('click', function(e) {
			e.stopPropagation();
			const chatId = $(this).data('chat-id');
			const $this = $(this);

			$('.readers-popup').remove();
			$this.append('<span class="ms-1"><i class="fa-solid fa-spinner fa-spin fa-xs"></i></span>');
			loadAndShowReaders(chatId, $this);
		});

		if (shouldAutoScroll) {
			setTimeout(scrollToBottom, 100);
		}
	}

	function loadAndShowReaders(chatId, $clickedElement) {
		$.ajax({
			url: siteurl + active_controller + 'get_chat_readers/' + chatId,
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				$clickedElement.find('.fa-spinner').parent().remove();

				if (response.status == 1 && response.data.length > 0) {
					showReadersPopup(response.data, $clickedElement);
				}
			},
			error: function() {
				$clickedElement.find('.fa-spinner').parent().remove();
			}
		});
	}

	function showReadersPopup(readers, $clickedElement) {
		let popupHtml = `
				<div class="readers-popup">
					<div class="readers-header">
						<div class="d-flex justify-content-between align-items-center">
							<strong>
								<i class="fa-solid fa-users me-1"></i> Dilihat oleh
							</strong>
							<small class="text-muted">${readers.length} orang</small>
						</div>
					</div>

					<div class="readers-list">
			`;

		readers.forEach(reader => {
			popupHtml += `
            <div class="reader-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fa-solid fa-user-circle text-primary me-2"></i>
                        <strong>${reader.name}</strong>
                    </div>
                    <small class="text-muted">
                        ${reader.read_time_formatted}
                    </small>
                </div>
            </div>
        `;
		});

		popupHtml += `
            </div>

            <div class="readers-arrow"></div>
        </div>
    	`;

		$('body').append(popupHtml);

		const $popup = $('.readers-popup');
		const pos = $clickedElement.offset();

		// ===== POSITION POPUP =====
		const popupHeight = $popup.outerHeight();
		const popupWidth = $popup.outerWidth();

		const elementOffset = $clickedElement.offset();
		const elementHeight = $clickedElement.outerHeight();

		const windowTop = $(window).scrollTop();
		const windowHeight = $(window).height();
		const spaceAbove = elementOffset.top - windowTop;
		const spaceBelow = (windowTop + windowHeight) - (elementOffset.top + elementHeight);

		let top, left;
		left = elementOffset.left +
			($clickedElement.outerWidth() / 2) -
			(popupWidth / 2);

		// batasi kiri kanan
		left = Math.max(10, Math.min(left, $(window).width() - popupWidth - 10));
		if (spaceAbove >= popupHeight + 10) {
			// tampil di ATAS
			top = elementOffset.top - popupHeight - 10;
			$popup.removeClass('popup-bottom').addClass('popup-top');
		} else {
			// tampil di BAWAH
			top = elementOffset.top + elementHeight + 10;
			$popup.removeClass('popup-top').addClass('popup-bottom');
		}

		$popup.css({
			top,
			left
		});


		setTimeout(() => {
			$(document).on('click.closeReaders', function(e) {
				if (!$(e.target).closest('.readers-popup, .chat-read-status').length) {
					$('.readers-popup').remove();
					$(document).off('click.closeReaders');
				}
			});
		}, 100);
	}

	function formatTime(date) {
		return date.toLocaleTimeString('id-ID', {
			hour: '2-digit',
			minute: '2-digit'
		});
	}

	function renderChatFile(msg) {
		const downloadUrl = siteurl + active_controller + 'download_chat_file/' + msg.id;

		// Cek apakah file adalah image
		if (msg.file_type && msg.file_type.includes('image')) {
			return `
            <div class="chat-file chat-image">
                <img src="${downloadUrl}" 
                     alt="${msg.original_name || msg.file_name}"
                     class="chat-image-preview"
                     data-viewer-src="${downloadUrl}"
                     style="max-width: 200px; max-height: 200px; border-radius: 8px; cursor: pointer;">
                <div class="mt-1">
                    <small>
                        <a href="${downloadUrl}" download>
                            <i class="fa-solid fa-download"></i> ${msg.original_name || msg.file_name}
                        </a>
                        <small class="text-muted">(${formatFileSize(msg.file_size)})</small>
                    </small>
                </div>
            </div>
        `;
		}

		// Untuk file non-image
		const icon = getFileIcon(msg.file_type);
		return `
        <div class="chat-file">
            <a href="${downloadUrl}" download>
                <i class="fa-solid ${icon}"></i> ${msg.original_name || msg.file_name}
                <small>(${formatFileSize(msg.file_size)})</small>
            </a>
        </div>
    `;
	}

	function getFileIcon(fileType) {
		if (!fileType) return 'fa-file';

		if (fileType.includes('image')) return 'fa-file-image';
		if (fileType.includes('pdf')) return 'fa-file-pdf';
		if (fileType.includes('word')) return 'fa-file-word';
		if (fileType.includes('excel') || fileType.includes('spreadsheet')) return 'fa-file-excel';
		if (fileType.includes('zip') || fileType.includes('rar')) return 'fa-file-zipper';

		return 'fa-file';
	}

	function formatFileSize(bytes) {
		if (!bytes) return '0 Bytes';
		const k = 1024;
		const sizes = ['Bytes', 'KB', 'MB', 'GB'];
		const i = Math.floor(Math.log(bytes) / Math.log(k));
		return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
	}

	function formatChatTime(dateString) {
		const date = new Date(dateString);
		const today = new Date();
		const yesterday = new Date(today);
		yesterday.setDate(yesterday.getDate() - 1);

		const timeStr = date.toLocaleTimeString('id-ID', {
			hour: '2-digit',
			minute: '2-digit'
		});

		if (date.toDateString() === today.toDateString()) {
			return 'Hari ini ' + timeStr;
		} else if (date.toDateString() === yesterday.toDateString()) {
			return 'Kemarin ' + timeStr;
		} else {
			return date.toLocaleDateString('id-ID', {
				day: '2-digit',
				month: 'short'
			}) + ' ' + timeStr;
		}
	}

	function scrollToBottom(force = false) {
		const chatMessages = $('#chatMessages');
		const container = chatMessages[0];

		if (!container) return;

		if (!force && !shouldAutoScroll) return;

		container.scrollTo({
			top: container.scrollHeight,
			behavior: 'smooth'
		});

		userHasScrolledUp = false;
		hideScrollToBottomButton();
	}

	$(document).ready(function() {
		loadClientList();
		loadHelpdeskList();
		startUnreadCountPolling();

		$('#filterStatus').on('change', function() {
			selectedStatusId = $(this).val();
			loadHelpdeskList(selectedClientId, selectedStatusId);
		});

		$('#filterClient').on('change', function() {
			selectedClientId = $(this).val();
			loadHelpdeskList(selectedClientId, selectedStatusId);
		});

		$('#filterClientApproved').on('change', function() {
			selectedClientIdApproved = $(this).val();
			loadApprovedList(selectedClientIdApproved);
		});

		$('#filterClientCancel').on('change', function() {
			selectedClientIdCancel = $(this).val();
			loadCancelList(selectedClientIdCancel);
		});

		$('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
			var target = $(e.target).data('bs-target');
			if (target === '#tab-open') {
				loadHelpdeskList(selectedClientId, selectedStatusId);
			} else if (target === '#tab-approved') {
				loadApprovedList(selectedClientIdApproved);
			} else if (target === '#tab-cancel') {
				loadCancelList(selectedClientIdCancel);
			}
		});

		$(document).on('click', '.refresh-list-helpdesk', function(e) {
			e.preventDefault();
			loadHelpdeskList(selectedClientId, selectedStatusId);
		});

		$(document).on('click', '.refresh-list-approve', function(e) {
			e.preventDefault();
			loadApprovedList(selectedClientIdApproved);
		});

		$(document).on('click', '.refresh-list-cancel', function(e) {
			e.preventDefault();
			loadCancelList(selectedClientIdCancel);
		});

		// Event untuk view detail ticket
		$(document).on('click', '.view-ticket', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			window.location.href = siteurl + active_controller + 'view_ticket/' + ticketId;
		});

		// Event untuk edit ticket
		$(document).on('click', '.edit-ticket', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			window.location.href = siteurl + active_controller + 'edit_ticket/' + ticketId;
		});

		// Event handlers untuk button status
		$(document).on('click', '.process-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			var currentStatus = $(this).data('current-status');
			var manHourPlan = $(this).data('man-hour-plan');
			changeTicketStatus(ticketId, 1, 'Process', currentStatus, manHourPlan);
		});

		$(document).on('click', '.pending-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			changeTicketStatus(ticketId, 2, 'Pending');
		});

		$(document).on('click', '.cancel-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			changeTicketStatus(ticketId, 3, 'Cancel');
		});

		$(document).on('click', '.done-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			var currentStatus = $(this).data('current-status');
			changeTicketStatus(ticketId, 4, 'Done', currentStatus);
		});

		$(document).on('click', '.approve-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');

			updateTicketApproval(ticketId, 'approve');
		});

		$(document).on('click', '.reject-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');

			updateTicketApproval(ticketId, 'reject');
		});

		$(document).on('click', '.view-history', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			var ticketNo = $(this).data('ticket');

			viewTicketHistory(ticketId, ticketNo);
		});

		$(document).on('click', '.open-chat', function(e) {
			e.preventDefault();
			const ticketId = $(this).data('id');
			const ticketNo = $(this).data('ticket');
			openChatRoom(ticketId, ticketNo);
		});

		// Attach File
		$('#btnAttachFile').on('click', function() {
			$('#chatFile').click();
		});

		// File Selected
		$('#chatFile').on('change', function() {
			const file = this.files[0];
			if (file) {
				if (file.size > 5 * 1024 * 1024) {
					Swal.fire({
						icon: 'error',
						title: 'File terlalu besar',
						text: 'Ukuran file maksimal 5MB'
					});
					$(this).val('');
					return;
				}

				$('#fileName').text(file.name);
				$('#fileSize').text(formatFileSize(file.size));
				$('#filePreview').show();
			}
		});

		// Remove File
		$('#removeFile').on('click', function() {
			$('#chatFile').val('');
			$('#filePreview').hide();
		});

		$('#chatForm').on('submit', function(e) {
			e.preventDefault();

			const message = $('#chatMessage').val().trim();
			const hasFile = $('#chatFile')[0].files.length > 0;

			if (!message && !hasFile) {
				Swal.fire({
					icon: 'warning',
					title: 'Pesan kosong',
					text: 'Silakan ketik pesan atau lampirkan file',
				});
				return;
			}

			if (hasFile) {
				const file = $('#chatFile')[0].files[0];
				const maxSize = 2 * 1024 * 1024;

				if (file.size > maxSize) {
					Swal.fire({
						icon: 'error',
						title: 'File terlalu besar',
						text: 'Ukuran maksimal file adalah 2 MB', 
						confirmButtonText: 'OK'
					});
					return;
				}
			}

			const formData = new FormData(this);

			$.ajax({
				url: siteurl + active_controller + 'send_chat_message',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				beforeSend: function() {
					$('#btnSendChat').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
				},
				success: function(response) {
					if (response.status == 1) {
						$('#chatMessage').val('');
						$('#chatFile').val('');
						$('#filePreview').hide();

						shouldAutoScroll = true;
						userHasScrolledUp = false;

						loadChatMessages(currentHelpdeskId, true);
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: response.message,
							confirmButtonText: 'OK'
						});
					}
				},
				error: function(xhr, status, error) {
					let errorMessage = 'Terjadi kesalahan saat mengirim pesan';

					if (xhr.responseJSON && xhr.responseJSON.message) {
						errorMessage = xhr.responseJSON.message;
					}

					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: errorMessage,
						confirmButtonText: 'OK'
					});
				},
				complete: function() {
					$('#btnSendChat').prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Send');
				}
			});
		});

		let scrollTimeout;
		$('#chatMessages').on('scroll', function() {
			clearTimeout(scrollTimeout);

			scrollTimeout = setTimeout(() => {
				userHasScrolledUp = checkIfUserScrolledUp();

				const chatMessages = this;
				const distanceFromBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight;

				if (distanceFromBottom > 200) {
					showScrollToBottomButton();
				} else {
					hideScrollToBottomButton();
				}
			}, 100);
		});

		$(document).on('click', '#scrollToBottomBtn', function(e) {
			e.preventDefault();
			e.stopPropagation();

			scrollToBottom(true);
			userHasScrolledUp = false;
			shouldAutoScroll = true;
		});

		$(window).on('beforeunload', function() {
			stopUnreadCountPolling();
		});

		$(document).on('visibilitychange', function() {
			if (document.hidden) {
				stopUnreadCountPolling();
				if (chatRefreshInterval) {
					console.log('Tab hidden - pausing chat refresh');
					stopChatRefresh();
				}
			} else {
				startUnreadCountPolling();
				if (currentHelpdeskId && $('#modalChatRoom').hasClass('show')) {
					console.log('Tab visible - resuming chat refresh');
					loadChatMessages(currentHelpdeskId, true);
					chatRefreshInterval = setInterval(function() {
						loadChatMessages(currentHelpdeskId, true);
					}, 5000);
				}
			}

		});

		$(document).on('click', '.chat-image-preview', function(e) {
			e.preventDefault();
			e.stopPropagation();

			const $allImages = $('.chat-image-preview');
			const currentIndex = $allImages.index(this);

			// Buat container di BODY, bukan di dalam modal
			const $tempContainer = $('<div id="tempViewerContainer" style="display:none;"></div>');

			// Clone semua images
			$allImages.each(function() {
				const $clone = $(this).clone();
				$tempContainer.append($clone);
			});

			// Append ke body (di luar modal)
			$('body').append($tempContainer);

			// Buat viewer
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
				viewed: () => {
					console.log('Image viewed');
				},
				hidden: () => {
					console.log('Viewer hidden, cleaning up temp container');
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

	});
</script>