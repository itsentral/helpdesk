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
		line-height: 1.5;
		padding-top: 8px;
		padding-bottom: 8px;
		transition: height 0.1s ease;
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

	/* Chat action menu */
	.chat-actions {
		position: relative;
		display: inline-block;
	}

	.chat-actions-btn {
		background: none;
		border: none;
		color: rgba(255, 255, 255, 0.7);
		cursor: pointer;
		padding: 0 4px;
		font-size: 16px;
		line-height: 1;
		border-radius: 4px;
		transition: color 0.2s;
	}

	.chat-message.sent .chat-actions-btn {
		color: rgba(255, 255, 255, 0.8);
	}

	.chat-actions-btn:hover {
		color: #fff;
		background: rgba(255, 255, 255, 0.2);
	}

	.chat-actions-dropdown {
		position: absolute;
		right: 0;
		top: 100%;
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 8px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		z-index: 9999;
		min-width: 140px;
		overflow: hidden;
		animation: fadeIn 0.15s ease;
	}

	.chat-actions-dropdown .dropdown-item {
		padding: 8px 14px;
		font-size: 13px;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 8px;
		color: #333;
		border: none;
		background: none;
		width: 100%;
		text-align: left;
	}

	.chat-actions-dropdown .dropdown-item:hover {
		background: #f5f5f5;
	}

	.chat-actions-dropdown .dropdown-item.text-danger:hover {
		background: #fff5f5;
	}

	/* Pesan terhapus */
	.message-deleted {
		font-style: italic;
		color: rgba(255, 255, 255, 0.6) !important;
		font-size: 13px;
	}

	.chat-message.received .message-deleted {
		color: #999 !important;
	}

	/* Edit input inline */
	/* Mode edit: bubble melebar penuh */
	.chat-bubble.editing {
		max-width: 100% !important;
		width: 100%;
	}

	.chat-edit-form {
		display: flex;
		flex-direction: column;
		gap: 8px;
		width: 100%;
	}

	.chat-edit-input {
		width: 100%;
		border-radius: 8px;
		border: 2px solid rgba(255, 255, 255, 0.7);
		padding: 8px 10px;
		font-size: 13px;
		background: rgba(255, 255, 255, 0.15);
		color: white;
		outline: none;
		font-family: inherit;
		line-height: 1.6;
		resize: none;
		overflow: hidden;
		min-height: 60px;
		max-height: 200px;
		box-sizing: border-box;
	}

	.chat-edit-input::placeholder {
		color: rgba(255, 255, 255, 0.5);
	}

	.chat-edit-input:focus {
		border-color: rgba(255, 255, 255, 0.95);
		background: rgba(255, 255, 255, 0.2);
	}

	.chat-edit-actions {
		display: flex;
		gap: 6px;
		justify-content: flex-end;
	}

	.btn-edit-save {
		background: rgba(255, 255, 255, 0.95);
		color: #007bff;
		font-weight: 700;
		border: none;
		border-radius: 6px;
		padding: 5px 12px;
		font-size: 12px;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 4px;
	}

	.btn-edit-cancel {
		background: rgba(0, 0, 0, 0.2);
		color: white;
		border: none;
		border-radius: 6px;
		padding: 5px 12px;
		font-size: 12px;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 4px;
	}

	.btn-edit-save:hover {
		background: #fff;
	}

	.btn-edit-cancel:hover {
		background: rgba(0, 0, 0, 0.35);
	}

	.chat-edit-input::placeholder {
		color: rgba(255, 255, 255, 0.5);
	}

	.btn-edit-save,
	.btn-edit-cancel {
		border: none;
		border-radius: 5px;
		padding: 3px 8px;
		font-size: 12px;
		cursor: pointer;
	}

	.btn-edit-save {
		background: rgba(255, 255, 255, 0.9);
		color: #007bff;
		font-weight: bold;
	}

	.btn-edit-cancel {
		background: rgba(0, 0, 0, 0.2);
		color: white;
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
					<div class="d-flex justify-content-end gap-2 align-items-center flex-wrap">

						<!-- Filter Client -->
						<div class="d-flex align-items-center gap-1">
							<select class="form-select form-select-sm" id="filterClient" style="width: 200px;">
								<option value="">All Client</option>
							</select>
							<button type="button" id="clearFilterClient" class="btn btn-sm btn-outline-secondary"
								style="display:none;" title="Reset filter client">
								<i class="fa-solid fa-xmark"></i>
							</button>
						</div>

						<!-- Filter Status -->
						<div class="d-flex align-items-center gap-1">
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
							<button type="button" id="clearFilterStatus" class="btn btn-sm btn-outline-secondary"
								style="display:none;" title="Reset filter status">
								<i class="fa-solid fa-xmark"></i>
							</button>
						</div>

						<button class="btn btn-primary btn-sm refresh-list-helpdesk">
							<i class="fa-solid fa-arrows-rotate"></i> Refresh
						</button>

						<?php if (has_permission('Ticket.Add') && $this->session->userdata('app_session')['id_user'] != 7): ?>
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
				<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

					<!-- KIRI: Date Range Filter -->
					<div class="d-flex align-items-center gap-2">
						<div class="input-group input-group-sm" style="width: auto;">
							<span class="input-group-text bg-white">
								<i class="fa-solid fa-calendar-days text-primary"></i>
							</span>
							<input type="text" class="form-control form-control-sm" id="filterDateFromApproved"
								placeholder="Dari Tanggal" style="width: 130px;" readonly>
							<span class="input-group-text bg-white text-muted">s/d</span>
							<input type="text" class="form-control form-control-sm" id="filterDateToApproved"
								placeholder="Sampai Tanggal" style="width: 130px;" readonly>
						</div>
						<button type="button" id="btnFilterApproved" class="btn btn-sm btn-primary">
							<i class="fa-solid fa-magnifying-glass"></i> Filter
						</button>
						<button type="button" id="clearDateApproved" class="btn btn-sm btn-outline-secondary" title="Reset ke bulan ini">
							<i class="fa-solid fa-rotate-left"></i>
						</button>
					</div>

					<!-- KANAN: Client Filter + Refresh -->
					<div class="d-flex align-items-center gap-2">
						<select class="form-select form-select-sm" id="filterClientApproved" style="width: 200px;">
							<option value="">All Client</option>
						</select>
						<button class="btn btn-primary btn-sm refresh-list-approve">
							<i class="fa-solid fa-arrows-rotate"></i> Refresh
						</button>
					</div>

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
							<div class="alert alert-info d-flex align-items-center justify-content-between mb-0 gap-2">
								<!-- Preview image jika file adalah gambar -->
								<div id="imagePreviewWrapper" style="display:none;">
									<img id="imagePreviewThumb" src="" alt="preview"
										style="height: 60px; width: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #bee5eb;">
								</div>

								<!-- Info file -->
								<div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width:0;">
									<i class="fa-solid fa-file" id="fileIcon"></i>
									<div style="min-width:0;">
										<div id="fileName" class="text-truncate" style="max-width: 180px; font-size:13px;"></div>
										<small class="text-muted"><span id="fileSize"></span></small>
									</div>
								</div>

								<button type="button" class="btn btn-sm btn-danger flex-shrink-0" id="removeFile">
									<i class="fa-solid fa-times"></i>
								</button>
							</div>
						</div>

						<div class="d-flex align-items-end gap-2">
							<input type="file" id="chatFile" name="chat_file" class="d-none"
								accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">

							<!-- Tombol attach -->
							<button type="button" class="btn btn-outline-secondary flex-shrink-0 d-flex align-items-center justify-content-center"
								id="btnAttachFile" style="height: 38px; width: 38px; padding: 0;">
								<i class="fa-solid fa-paperclip"></i>
							</button>

							<!-- Textarea message -->
							<textarea class="form-control flex-grow-1" id="chatMessage" name="message"
								placeholder="Type your message..." autocomplete="off"
								rows="1" style="resize:none; overflow:hidden; max-height: 120px; min-height: 38px;"></textarea>

							<!-- Tombol send -->
							<button type="submit" class="btn btn-primary flex-shrink-0"
								id="btnSendChat" style="height: 38px; white-space: nowrap; padding: 0 12px;">
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<!-- Viewer.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

<!-- Viewer.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>
<script src="<?= base_url('assets/js/ticket_actions.js') ?>"></script>

<!-- Action Popover Toggle (single binding for all AJAX-loaded tabs) -->
<script>
$(function() {
    $(document).on('click', '.btn-action-toggle', function(e) {
        e.stopPropagation();
        var $wrapper = $(this).closest('.action-btn-wrapper');
        var $popover = $wrapper.find('.action-popover');
        var isOpen = $popover.hasClass('show');

        $('.action-popover.show').removeClass('show');
        $('.btn-action-toggle.active').removeClass('active');

        if (!isOpen) {
            $popover.addClass('show');
            $(this).addClass('active');
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.action-btn-wrapper').length) {
            $('.action-popover.show').removeClass('show');
            $('.btn-action-toggle.active').removeClass('active');
        }
    });

    $(document).on('click', '.action-popover-item', function() {
        $('.action-popover.show').removeClass('show');
        $('.btn-action-toggle.active').removeClass('active');
    });
});
</script>

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
				// console.log(response)
				if (response.status == 1) {
					let options = '<option value="">All Client</option>';

					response.data.forEach(function(client) {
						options += `<option value="${client.id}">${client.name_app}</option>`;
					});

					$('#filterClient').html(options);
					$('#filterClientApproved').html(options);
					$('#filterClientCancel').html(options);

					if (selectedClientId) {
						$('#filterClient').val(selectedClientId);
						$('#clearFilterClient').show();
					}
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

				var savedPageLength = parseInt(localStorage.getItem('helpdesk_pageLength')) || 10;

				$('#table_helpdesk').DataTable({
					paging: true,
					searching: true,
					order: [],
					info: true,
					responsive: true,
					pageLength: savedPageLength,
				});

				$('#table_helpdesk').on('length.dt', function(e, settings, len) {
					localStorage.setItem('helpdesk_pageLength', len);
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

	// function loadApprovedList
	function loadApprovedList(clientId = '') {
		const fpFromEl = document.querySelector('#filterDateFromApproved')._flatpickr;
		const fpToEl = document.querySelector('#filterDateToApproved')._flatpickr;

		const dateFrom = fpFromEl && fpFromEl.selectedDates[0] ?
			fpFromEl.formatDate(fpFromEl.selectedDates[0], 'Y-m-d') :
			'';
		const dateTo = fpToEl && fpToEl.selectedDates[0] ?
			fpToEl.formatDate(fpToEl.selectedDates[0], 'Y-m-d') :
			'';

		$.ajax({
			url: siteurl + active_controller + 'get_list_approved',
			type: 'GET',
			data: {
				client_id: clientId,
				date_from: dateFrom,
				date_to: dateTo
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

	// function loadCancelList
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

	function isImageFile(fileName) {
		if (!fileName) return false;
		const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
		const ext = fileName.split('.').pop().toLowerCase();
		return imageExtensions.includes(ext);
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

			// KETERANGAN PENYELESAIAN (saat status diubah ke Done)
			if (item.new_status == 4 && item.keterangan_penyelesaian && item.keterangan_penyelesaian.trim() !== '') {
				description += `
            <br>
            <small class="text-info">
                <i class="fa-solid fa-note-sticky"></i>
                <strong>Keterangan Penyelesaian:</strong> ${item.keterangan_penyelesaian}
            </small>`;
			}

			// ATTACHMENT BUKTI PENYELESAIAN
			if (item.new_status == 4 && item.file_done_hash_name) {
				const downloadUrl = siteurl + active_controller + 'download_done_file/' + item.helpdesk_id + '/' + item.file_done_hash_name;
				const displayName = item.file_done_original_name || item.file_done_hash_name;

				if (isImageFile(item.file_done_hash_name)) {
					// Tampilkan preview gambar (bisa diklik via Viewer.js)
					description += `
                	<br>
					<div class="history-file mt-2">
						<small class="d-block mb-1 text-primary">
							<i class="fa-solid fa-paperclip"></i> <strong>Bukti Penyelesaian:</strong>
						</small>
						<img src="${downloadUrl}"
							alt="${displayName}"
							class="history-image-preview"
							data-viewer-src="${downloadUrl}"
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
					// File non-image, langsung download saat diklik
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
			// console.log('Chat auto-refresh distop');
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
						'<div class="d-flex align-items-center justify-content-center">' +
						'<div class="text-center">' +
						// --- SVG Animasi Pesan Dalam Botol Mulai Sini ---
						'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="180" height="180" class="mb-3">' +
						'<defs>' +
						'  <clipPath id="waveClip">' +
						'    <rect x="0" y="0" width="200" height="200" rx="20" />' +
						'  </clipPath>' +
						'</defs>' +
						'<style>' +
						'  /* Animasi Botol Mengapung */' +
						'  @keyframes floatBottle {' +
						'    0%, 100% { transform: translateY(0px) rotate(-3deg); }' +
						'    50% { transform: translateY(-12px) rotate(4deg); }' +
						'  }' +
						'  /* Animasi Ombak Mengalir */' +
						'  @keyframes waveMove {' +
						'    0% { transform: translateX(0); }' +
						'    100% { transform: translateX(-80px); }' +
						'  }' +
						'  /* Animasi Burung Terbang */' +
						'  @keyframes birdFly {' +
						'    0%, 100% { transform: translateY(0); }' +
						'    50% { transform: translateY(-5px); }' +
						'  }' +
						'  .bottle-group { transform-origin: 100px 140px; animation: floatBottle 5s ease-in-out infinite; }' +
						'  .wave-bg { animation: waveMove 7s linear infinite; }' +
						'  .wave-fg { animation: waveMove 5s linear infinite reverse; }' +
						'  .bird { animation: birdFly 4s ease-in-out infinite; }' +
						'</style>' +

						'  ' +
						'  <circle cx="100" cy="100" r="90" fill="#f8fafc" />' +
						'  ' +
						'  <circle cx="150" cy="60" r="16" fill="#fde047" opacity="0.8" />' +

						'  ' +
						'  <g class="bird" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.6">' +
						'    <path d="M 35,50 Q 42,43 50,50 Q 58,43 65,50" />' +
						'    <path d="M 65,75 Q 70,70 75,75 Q 80,70 85,75" stroke-width="2" opacity="0.5" />' +
						'  </g>' +

						'  ' +
						'  <g class="bottle-group">' +
						'    ' +
						'    <path d="M 85,100 L 115,90 L 115,145 L 85,155 Z" fill="#ffffff" stroke="#94a3b8" stroke-width="2" stroke-linejoin="round"/>' +
						'    <line x1="92" y1="110" x2="108" y2="105" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>' +
						'    <line x1="92" y1="120" x2="108" y2="115" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>' +
						'    <line x1="92" y1="130" x2="100" y2="128" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>' +
						'    ' +
						'    <path d="M 92,40 L 108,40 L 105,50 L 95,50 Z" fill="#cbd5e1" stroke="#94a3b8" stroke-width="2" stroke-linejoin="round"/>' +
						'    ' +
						'    <path d="M 90,50 L 110,50 L 110,70 C 110,85 130,100 130,120 L 130,150 C 130,165 115,175 100,175 C 85,175 70,165 70,150 L 70,120 C 70,100 90,85 90,70 Z" fill="#e2e8f0" fill-opacity="0.3" stroke="#64748b" stroke-width="3" stroke-linejoin="round"/>' +
						'    ' +
						'    <path d="M 78,115 L 78,150" stroke="#ffffff" stroke-width="4" stroke-linecap="round" opacity="0.8" />' +
						'  </g>' +

						'  ' +
						'  <g clip-path="url(#waveClip)" stroke="#64748b" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.5">' +
						'    ' +
						'    <path class="wave-bg" d="M -80,160 Q -60,150 -40,160 T 0,160 T 40,160 T 80,160 T 120,160 T 160,160 T 200,160 T 240,160 T 280,160" />' +
						'    ' +
						'    <path class="wave-fg" d="M -80,175 Q -60,185 -40,175 T 0,175 T 40,175 T 80,175 T 120,175 T 160,175 T 200,175 T 240,175 T 280,175" stroke-width="4" opacity="0.7" />' +
						'  </g>' +
						'</svg>' +
						// --- SVG Animasi Pesan Dalam Botol Selesai Sini ---
						'<h6 class="fw-bold mb-1" style="color: #475569;">Belum Ada Pesan</h6>' +
						'<p class="small text-muted m-0">Silakan kirim pesan untuk memulai percakapan.</p>' +
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
			const isDeleted = msg.is_delete == 1;
			const sendDate = new Date(msg.create_date);
			const today = new Date();
			const isSameDay = sendDate.toDateString() === today.toDateString();
			const canModify = isSent && !isDeleted && isSameDay;

			// Konten pesan
			let messageContent = '';
			if (isDeleted) {
				messageContent = `<div class="message-content message-deleted">
                                <i class="fa-solid fa-ban fa-xs"></i> Pesan ini telah dihapus
                              </div>`;
			} else {
				const formattedMessage = msg.message
					.replace(/&/g, '&amp;')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;')
					.replace(/\n/g, '<br>');

				messageContent = `<div class="message-content" id="msg-content-${msg.id}">${formattedMessage}</div>`;
			}

			// Tombol titik 3 — hanya tampil jika bisa dimodifikasi
			const actionsBtn = canModify ? `
            <div class="chat-actions ms-1">
                <button class="chat-actions-btn" data-chat-id="${msg.id}" title="Opsi pesan">&#8942;</button>
                <div class="chat-actions-dropdown" id="dropdown-${msg.id}" style="display:none;">
                    <button class="dropdown-item text-primary chat-edit-btn"
                        data-chat-id="${msg.id}"
                        data-message="${msg.message.replace(/"/g, '&quot;')}">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <button class="dropdown-item text-danger chat-delete-btn"
                        data-chat-id="${msg.id}">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        ` : '';

			html += `
            <div class="chat-message ${messageClass}" data-message-id="${msg.id}">
                <div class="chat-bubble">
                    <div class="d-flex align-items-start justify-content-between gap-1">
                        <div style="flex:1; min-width:0;">
                            ${!isSent ? `<div class="chat-sender">${msg.sender_name}</div>` : ''}
                            ${messageContent}
                            ${!isDeleted && msg.file_name ? renderChatFile(msg) : ''}
                        </div>
                        ${actionsBtn}
                    </div>
                    ${isSent && !isDeleted ? `
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

		// Event: buka/tutup dropdown titik 3
		$(document).off('click.chatActions').on('click.chatActions', '.chat-actions-btn', function(e) {
			e.stopPropagation();
			const chatId = $(this).data('chat-id');
			const $dropdown = $('#dropdown-' + chatId);

			$('.chat-actions-dropdown').not($dropdown).hide();
			$dropdown.toggle();
		});

		$(document).off('click.closeDropdown').on('click.closeDropdown', function(e) {
			if (!$(e.target).closest('.chat-actions').length) {
				$('.chat-actions-dropdown').hide();
			}
		});

		$(document).off('click.deleteChat').on('click.deleteChat', '.chat-delete-btn', function(e) {
			e.stopPropagation();
			const chatId = $(this).data('chat-id');
			$('.chat-actions-dropdown').hide();

			Swal.fire({
				title: 'Hapus Pesan?',
				text: 'Pesan akan dihapus dan tidak dapat dikembalikan.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Hapus',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_chat_message',
						type: 'POST',
						data: {
							chat_id: chatId
						},
						dataType: 'json',
						success: function(response) {
							if (response.status == 1) {
								shouldAutoScroll = false;
								loadChatMessages(currentHelpdeskId, true);
							} else {
								Swal.fire('Gagal', response.message, 'error');
							}
						},
						error: function() {
							Swal.fire('Error', 'Terjadi kesalahan saat menghapus pesan', 'error');
						}
					});
				}
			});
		});

		// Edit pesan 
		$(document).off('click.editChat').on('click.editChat', '.chat-edit-btn', function(e) {
			e.stopPropagation();
			const chatId = $(this).data('chat-id');
			const originalMessage = $(this).data('message');
			$('.chat-actions-dropdown').hide();

			const $msgContent = $('#msg-content-' + chatId);

			if ($('#edit-form-' + chatId).length > 0) return;

			$msgContent.closest('.chat-bubble').find('.chat-actions-btn').hide();

			$msgContent.hide();
			$msgContent.after(`
				<div class="chat-edit-form" id="edit-form-${chatId}">
					<textarea class="chat-edit-input" 
							id="edit-input-${chatId}" 
							rows="3"
					>${originalMessage}</textarea>
					<div class="chat-edit-actions">
						<button class="btn-edit-cancel" data-chat-id="${chatId}">
							<i class="fa-solid fa-xmark"></i> Batal
						</button>
						<button class="btn-edit-save" data-chat-id="${chatId}">
							<i class="fa-solid fa-check"></i> Simpan
						</button>
					</div>
				</div>
			`);

			$('#edit-form-' + chatId).closest('.chat-bubble').addClass('editing');

			const $ta = $('#edit-input-' + chatId);
			$ta[0].style.height = 'auto';
			$ta[0].style.height = Math.min($ta[0].scrollHeight, 200) + 'px';

			const len = $ta.val().length;
			$ta[0].setSelectionRange(len, len);
			$ta.focus();

			$('#edit-input-' + chatId).focus().select();
		});

		$(document).off('click.cancelEdit').on('click.cancelEdit', '.btn-edit-cancel', function() {
			const chatId = $(this).data('chat-id');
			$('#edit-form-' + chatId).closest('.chat-bubble').removeClass('editing');
			$('#edit-form-' + chatId).remove();
			$('#msg-content-' + chatId).show();
			$('#msg-content-' + chatId).closest('.chat-bubble').find('.chat-actions-btn').show();
		});

		// Event: Simpan edit
		$(document).off('click.saveEdit').on('click.saveEdit', '.btn-edit-save', function() {
			const chatId = $(this).data('chat-id');
			const newMessage = $('#edit-input-' + chatId).val().trim();

			if (!newMessage) {
				Swal.fire('Peringatan', 'Pesan tidak boleh kosong', 'warning');
				return;
			}

			$.ajax({
				url: siteurl + active_controller + 'edit_chat_message',
				type: 'POST',
				data: {
					chat_id: chatId,
					message: newMessage
				},
				dataType: 'json',
				success: function(response) {
					if (response.status == 1) {
						shouldAutoScroll = false;
						loadChatMessages(currentHelpdeskId, true);
					} else {
						Swal.fire('Gagal', response.message, 'error');
					}
				},
				error: function() {
					Swal.fire('Error', 'Terjadi kesalahan saat mengedit pesan', 'error');
				}
			});
		});

		// Enter key di input edit
		$(document).off('keydown.editInput').on('keydown.editInput', '.chat-edit-input', function(e) {
			const chatId = $(this).attr('id').replace('edit-input-', '');

			if (e.key === 'Enter' && e.shiftKey) {
				setTimeout(() => {
					this.style.height = 'auto';
					this.style.height = Math.min(this.scrollHeight, 200) + 'px';
				}, 0);
				return;
			}

			if (e.key === 'Enter' && !e.shiftKey) {
				e.preventDefault();
				$('.btn-edit-save[data-chat-id="' + chatId + '"]').trigger('click');
				return;
			}

			if (e.key === 'Escape') {
				e.preventDefault();
				$('.btn-edit-cancel[data-chat-id="' + chatId + '"]').trigger('click');
			}
		});

		$(document).off('input.editInput').on('input.editInput', '.chat-edit-input', function() {
			this.style.height = 'auto';
			this.style.height = Math.min(this.scrollHeight, 200) + 'px';
		});

		// Event: read status click
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

	function getDefaultDateApproved() {
		const today = new Date();

		const pad = n => String(n).padStart(2, '0');
		const toYMD = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

		const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

		return {
			from: toYMD(firstDay),
			to: toYMD(today)
		};
	}

	function updateUrlParams() {
		const params = new URLSearchParams();
		if (selectedClientId) params.set('client_id', selectedClientId);
		if (selectedStatusId) params.set('status_id', selectedStatusId);

		const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
		history.replaceState(null, '', newUrl);
	}

	$(document).ready(function() {
		const urlParams = new URLSearchParams(window.location.search);
		const urlClientId = urlParams.get('client_id') || '';
		const urlStatusId = urlParams.get('status_id') || '';

		if (urlClientId) {
			selectedClientId = urlClientId;
			$('#clearFilterClient').show();
		}
		if (urlStatusId) {
			selectedStatusId = urlStatusId;
			$('#filterStatus').val(urlStatusId);
			$('#clearFilterStatus').show();
		}

		loadClientList();
		loadHelpdeskList(selectedClientId, selectedStatusId);
		startUnreadCountPolling();

		$('#filterStatus').on('change', function() {
			selectedStatusId = $(this).val();
			$('#clearFilterStatus').toggle(selectedStatusId !== '');
			updateUrlParams();
			loadHelpdeskList(selectedClientId, selectedStatusId);
		});

		$('#clearFilterStatus').on('click', function() {
			$('#filterStatus').val('');
			selectedStatusId = '';
			$(this).hide();
			updateUrlParams();
			loadHelpdeskList(selectedClientId, selectedStatusId);
		});

		$('#filterClient').on('change', function() {
			selectedClientId = $(this).val();
			$('#clearFilterClient').toggle(selectedClientId !== '');
			updateUrlParams();
			loadHelpdeskList(selectedClientId, selectedStatusId);
		});

		$('#clearFilterClient').on('click', function() {
			$('#filterClient').val('');
			selectedClientId = '';
			$(this).hide();
			updateUrlParams();
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

		$(document).on('click', '.view-ticket', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			var params = new URLSearchParams();
			if (selectedClientId) params.set('client_id', selectedClientId);
			if (selectedStatusId) params.set('status_id', selectedStatusId);
			var query = params.toString() ? '?' + params.toString() : '';
			window.location.href = siteurl + active_controller + 'view_ticket/' + ticketId + query;
		});

		$(document).on('click', '.edit-ticket', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			var params = new URLSearchParams();
			if (selectedClientId) params.set('client_id', selectedClientId);
			if (selectedStatusId) params.set('status_id', selectedStatusId);
			var query = params.toString() ? '?' + params.toString() : '';
			window.location.href = siteurl + active_controller + 'edit_ticket/' + ticketId + query;
		});

		$(document).on('click', '.process-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			var currentStatus = $(this).data('current-status');
			var manHourPlan = $(this).data('man-hour-plan');
			var causes = $(this).data('causes');
			var actionPlan = $(this).data('action-plan');
			changeTicketStatus(ticketId, 1, 'Process', currentStatus, manHourPlan, causes, actionPlan);
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
		// File Selected
		$('#chatFile').on('change', function() {
			const file = this.files[0];
			if (!file) return;

			if (file.size > 2 * 1024 * 1024) {
				Swal.fire({
					icon: 'error',
					title: 'File terlalu besar',
					text: 'Ukuran file maksimal 2MB'
				});
				$(this).val('');
				return;
			}

			$('#fileName').text(file.name);
			$('#fileSize').text(formatFileSize(file.size));

			// Cek apakah file adalah gambar
			if (file.type.startsWith('image/')) {
				const reader = new FileReader();
				reader.onload = function(e) {
					$('#imagePreviewThumb').attr('src', e.target.result);
					$('#imagePreviewWrapper').show();
					$('#fileIcon').hide();
				};
				reader.readAsDataURL(file);
			} else {
				// File non-image, tampilkan icon sesuai tipe
				$('#imagePreviewWrapper').hide();
				$('#fileIcon').show();

				const ext = file.name.split('.').pop().toLowerCase();
				const iconMap = {
					'pdf': 'fa-file-pdf text-danger',
					'doc': 'fa-file-word text-primary',
					'docx': 'fa-file-word text-primary',
					'xls': 'fa-file-excel text-success',
					'xlsx': 'fa-file-excel text-success',
					'zip': 'fa-file-zipper text-warning',
					'rar': 'fa-file-zipper text-warning',
				};
				const iconClass = iconMap[ext] || 'fa-file text-secondary';
				$('#fileIcon').attr('class', 'fa-solid ' + iconClass);
			}

			$('#filePreview').show();
		});

		// Remove File
		$('#removeFile').on('click', function() {
			$('#chatFile').val('');
			$('#filePreview').hide();
			$('#imagePreviewThumb').attr('src', '');
			$('#imagePreviewWrapper').hide();
			$('#fileIcon').show();
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
						$('#chatMessage')[0].style.height = 'auto';
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
					// console.log('Tab hidden - pausing chat refresh');
					stopChatRefresh();
				}
			} else {
				startUnreadCountPolling();
				if (currentHelpdeskId && $('#modalChatRoom').hasClass('show')) {
					// console.log('Tab visible - resuming chat refresh');
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
					// console.log('Viewer hidden, cleaning up temp container');
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

		// Init Flatpickr Approved
		const defaultDate = getDefaultDateApproved();
		const today = new Date();
		const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
		const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

		const fpFrom = flatpickr('#filterDateFromApproved', {
			locale: 'id',
			dateFormat: 'd M Y',
			defaultDate: firstDayOfMonth,
			onChange: function(selectedDates) {
				if (fpTo.selectedDates[0] && selectedDates[0] > fpTo.selectedDates[0]) {
					fpTo.setDate(selectedDates[0]);
				}
				fpTo.set('minDate', selectedDates[0] || null);
			}
		});

		const fpTo = flatpickr('#filterDateToApproved', {
			locale: 'id',
			dateFormat: 'd M Y',
			defaultDate: todayDate,
			minDate: firstDayOfMonth,
			onChange: function(selectedDates) {
				fpFrom.set('maxDate', selectedDates[0] || null);
			}
		});

		// Tombol Filter
		$('#btnFilterApproved').on('click', function() {
			loadApprovedList(selectedClientIdApproved);
		});

		// Tombol Reset
		$('#clearDateApproved').on('click', function() {
			const now = new Date();
			const resetFrom = new Date(now.getFullYear(), now.getMonth(), 1);
			const resetTo = new Date(now.getFullYear(), now.getMonth(), now.getDate());

			fpFrom.setDate(resetFrom);
			fpTo.setDate(resetTo);
			fpFrom.set('maxDate', null);
			fpTo.set('minDate', resetFrom);
			loadApprovedList(selectedClientIdApproved);
		});

		// Auto resize textarea
		$(document).on('input', '#chatMessage', function() {
			this.style.height = 'auto';
			this.style.height = Math.min(this.scrollHeight, 120) + 'px';
		});

		$(document).on('keydown', '#chatMessage', function(e) {
			if (e.key === 'Enter' && !e.shiftKey) {
				e.preventDefault();
				$('#chatForm').trigger('submit');
			}
		});

		$(document).on('paste', '#chatMessage', function(e) {
			const clipboardData = e.originalEvent.clipboardData;
			if (!clipboardData || !clipboardData.items) return;

			const items = Array.from(clipboardData.items);
			const fileItem = items.find(item => item.kind === 'file');

			if (!fileItem) return;

			e.preventDefault();

			const file = fileItem.getAsFile();
			if (!file) return;

			// Validasi
			if (file.size > 2 * 1024 * 1024) {
				Swal.fire({
					icon: 'error',
					title: 'File terlalu besar',
					text: 'Ukuran file maksimal 2MB'
				});
				return;
			}

			// Validasi tipe file
			const allowedTypes = [
				'image/jpeg', 'image/png', 'image/gif', 'image/webp',
				'application/pdf',
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/vnd.ms-excel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/zip',
				'application/x-rar-compressed'
			];

			if (!allowedTypes.includes(file.type)) {
				Swal.fire({
					icon: 'error',
					title: 'Tipe file tidak didukung',
					text: 'Hanya mendukung gambar, PDF, Word, Excel, ZIP, RAR'
				});
				return;
			}

			const dataTransfer = new DataTransfer();
			dataTransfer.items.add(file);
			$('#chatFile')[0].files = dataTransfer.files;

			$('#chatFile').trigger('change');
		});

		$(document).on('click', '.history-image-preview', function(e) {
			e.preventDefault();
			e.stopPropagation();

			const $allImages = $('#historyTimeline .history-image-preview');
			const currentIndex = $allImages.index(this);

			// Buat container di BODY, bukan di dalam modal
			const $tempContainer = $('<div id="tempHistoryViewerContainer" style="display:none;"></div>');

			// Clone semua images dalam timeline history
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
	});
</script>