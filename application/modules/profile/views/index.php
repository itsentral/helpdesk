<div class="profile-page">

	<style>
		.profile-page .profile-hero-card {
			border: none;
			box-shadow: 0 2px 16px rgba(0, 0, 0, .06);
		}

		.profile-page .profile-banner {
			height: 110px;
			background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), .95) 0%, rgba(var(--bs-primary-rgb), .55) 100%);
			position: relative;
		}

		.profile-page .profile-banner::after {
			content: "";
			position: absolute;
			inset: 0;
			background-image: radial-gradient(circle at 85% 20%, rgba(255, 255, 255, .18) 0, transparent 45%),
				radial-gradient(circle at 15% 90%, rgba(255, 255, 255, .12) 0, transparent 40%);
		}

		.profile-page .profile-hero-top {
			margin-top: -56px;
		}

		.profile-page .profile-avatar-wrapper {
			position: relative;
			display: inline-flex;
			flex-shrink: 0;
		}

		.profile-page .profile-avatar {
			width: 104px;
			height: 104px;
			object-fit: cover;
			border: 4px solid var(--bs-card-bg, #fff);
			box-shadow: 0 4px 14px rgba(0, 0, 0, .12);
		}

		.profile-page .profile-action {
			position: absolute;
			bottom: -2px;
			right: -2px;
			display: flex;
			gap: 4px;
		}

		.profile-page .profile-action .btn {
			width: 30px;
			height: 30px;
			padding: 0;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
		}

		.profile-page .quick-info-chip {
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 10px 14px;
			border-radius: 10px;
			background: var(--bs-tertiary-bg, #f5f6f8);
			height: 100%;
		}

		.profile-page .quick-info-chip .chip-icon {
			width: 34px;
			height: 34px;
			border-radius: 8px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: rgba(var(--bs-primary-rgb), .12);
			color: var(--bs-primary);
			flex-shrink: 0;
		}

		.profile-page .quick-info-chip .chip-label {
			font-size: .72rem;
			text-transform: uppercase;
			letter-spacing: .04em;
			color: var(--bs-secondary-color, #6c757d);
			margin-bottom: 1px;
		}

		.profile-page .quick-info-chip .chip-value {
			font-weight: 600;
			font-size: .92rem;
			word-break: break-word;
		}

		.profile-page .quick-info-chip .chip-value-list {
			margin: 0;
			padding: 0;
			list-style: none;
			columns: 2;
			/* fixed 2 kolom, tidak nambah otomatis */
			column-gap: 10px;
			width: 100%;
			max-width: 180px;
			/* batasi lebar biar gak dorong keluar card */
		}

		.profile-page .quick-info-chip .chip-value-list li {
			font-weight: 600;
			font-size: .85rem;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			break-inside: avoid;
			position: relative;
			padding-left: 12px;
			max-width: 85px;
		}

		.profile-page .quick-info-chip .chip-value-list li::before {
			content: "•";
			position: absolute;
			left: 0;
			color: var(--bs-primary);
		}

		/* View / Edit mode toggle for personal info */
		.profile-page .info-row {
			display: flex;
			justify-content: space-between;
			gap: 16px;
			padding: 12px 0;
			border-bottom: 1px dashed var(--bs-border-color);
		}

		.profile-page .info-row:last-child {
			border-bottom: none;
		}

		.profile-page .info-row .info-label {
			color: var(--bs-secondary-color, #6c757d);
			display: flex;
			align-items: center;
			gap: 8px;
			white-space: nowrap;
		}

		.profile-page .info-row .info-value {
			font-weight: 500;
			text-align: right;
		}

		.profile-page .section-fade {
			animation: profileFadeIn .25s ease;
		}

		.profile-page .quick-info-chip .chip-more-badge {
			display: inline-block;
			margin-top: 4px;
			font-size: .72rem;
			font-weight: 600;
			color: var(--bs-primary);
			cursor: pointer;
			text-decoration: underline dotted;
		}

		.profile-page .quick-info-chip .chip-more-badge:hover {
			color: var(--bs-primary);
			opacity: .8;
		}

		@keyframes profileFadeIn {
			from {
				opacity: 0;
				transform: translateY(4px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.profile-page .accordion-button:not(.collapsed) {
			background-color: rgba(var(--bs-primary-rgb), .08);
			box-shadow: none;
		}

		.profile-page .accordion-button:focus {
			box-shadow: none;
		}
	</style>

	<!-- HERO / HEADER -->
	<div class="col-md-12">
		<div class="card profile-hero-card overflow-hidden mb-3">
			<div class="profile-banner"></div>
			<div class="card-body pt-0">
				<div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-3 profile-hero-top">
					<div class="profile-avatar-wrapper">
						<img
							id="profilePhoto"
							class="rounded-circle profile-avatar"
							src="<?= (isset($user->photo) && file_exists('assets/images/users/' . $user->photo))
										? base_url('assets/images/users/' . $user->photo)
										: base_url('assets/images/male-def.png'); ?>"
							alt="User image">

						<div class="profile-action">
							<button type="button" class="btn btn-sm btn-icon btn-light-secondary rounded-circle change-photo-btn" title="Ganti Foto">
								<i class="ti ti-camera"></i>
							</button>
							<?php if (isset($user->photo) && !empty($user->photo)): ?>
								<button type="button" class="btn btn-sm btn-icon btn-light-danger rounded-circle delete-photo-btn" title="Hapus Foto">
									<i class="ti ti-trash"></i>
								</button>
							<?php endif; ?>
						</div>
						<input type="file" id="photoInput" style="display:none;" accept="image/*">
					</div>

					<div class="flex-grow-1 text-center text-md-start pb-1">
						<h4 class="mb-1 fw-bold"><?= $user->nm_lengkap; ?></h4>
						<p class="text-muted mb-2"><i class="ti ti-at me-1"></i><?= $user->username; ?></p>
						<div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
							<span class="badge bg-light-<?= $user->st_aktif == 1 ? 'success' : 'danger'; ?>">
								<i class="ti ti-circle-<?= $user->st_aktif == 1 ? 'check' : 'x'; ?> me-1"></i>
								<?= $user->st_aktif == 1 ? 'Aktif' : 'Non-Aktif'; ?>
							</span>
							<span class="badge bg-light-info">
								<i class="ti ti-building me-1"></i><?= $user->status == 1 ? 'External' : 'Internal'; ?>
							</span>
							<?php if ($user->is_ba == 1): ?>
								<span class="badge bg-light-primary"><i class="ti ti-briefcase me-1"></i>Business Analyst</span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<hr class="my-4">

				<div class="row g-3">
					<div class="col-6 col-lg-3">
						<div class="quick-info-chip">
							<span class="chip-icon"><i class="ti ti-phone"></i></span>
							<div>
								<div class="chip-label">No. HP</div>
								<div class="chip-value"><?= $user->hp ?: '-'; ?></div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<div class="quick-info-chip">
							<span class="chip-icon"><i class="ti ti-mail"></i></span>
							<div>
								<div class="chip-label d-flex align-items-center gap-1">
									Email
									<?php if (isset($user->is_email_verified) && $user->is_email_verified == 1): ?>
										<i class="ti ti-circle-check text-success" title="Terverifikasi"></i>
									<?php else: ?>
										<i class="ti ti-alert-triangle text-warning" title="Belum Diverifikasi"></i>
									<?php endif; ?>
								</div>
								<div class="chip-value text-truncate" style="max-width:160px;" title="<?= $user->email; ?>"><?= $user->email ?: '-'; ?></div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<div class="quick-info-chip">
							<span class="chip-icon"><i class="ti ti-map-pin"></i></span>
							<div>
								<div class="chip-label">Kota</div>
								<div class="chip-value"><?= $user->kota ?: '-'; ?></div>
							</div>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<div class="quick-info-chip">
							<span class="chip-icon"><i class="ti ti-apps"></i></span>
							<div>
								<div class="chip-label">Client Apps</div>
								<?php
								$apps = !empty($client_apps) ? array_map('trim', explode(',', $client_apps)) : [];
								$maxVisible = 4; // 2 kolom x 2 baris, sesuaikan sesuai selera
								$visibleApps = array_slice($apps, 0, $maxVisible);
								$remaining = count($apps) - count($visibleApps);
								?>
								<?php if (!empty($visibleApps)): ?>
									<ul class="chip-value-list">
										<?php foreach ($visibleApps as $app): ?>
											<li title="<?= $app; ?>"><?= $app; ?></li>
										<?php endforeach; ?>
									</ul>
									<?php if ($remaining > 0): ?>
										<span class="chip-more-badge btn-show-all-apps"
											data-apps='<?= htmlspecialchars(json_encode($apps), ENT_QUOTES, 'UTF-8'); ?>'>
											+<?= $remaining; ?> lainnya
										</span>
									<?php endif; ?>
								<?php else: ?>
									<div class="chip-value">-</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="row g-3">
		<!-- INFORMASI PERSONAL : VIEW / EDIT MODE -->
		<div class="col-lg-7">
			<div class="card h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="mb-0">Informasi Personal</h5>
						<button type="button" class="btn btn-sm btn-primary" id="btnEditProfil">
							<i class="ti ti-pencil me-1"></i>Edit Profil
						</button>
					</div>

					<!-- ===== VIEW MODE ===== -->
					<div id="profilViewMode" class="section-fade">
						<div class="info-row">
							<span class="info-label"><i class="ti ti-user"></i>Nama Lengkap</span>
							<span class="info-value" id="viewNama"><?= $user->nm_lengkap; ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><i class="ti ti-mail"></i>Email</span>
							<span class="info-value" id="viewEmail">
								<?= $user->email ?: '-'; ?>
								<?php if (isset($user->is_email_verified) && $user->is_email_verified == 1): ?>
									<span class="badge bg-light-success text-success ms-1"><i class="ti ti-circle-check me-1"></i>Terverifikasi</span>
								<?php else: ?>
									<span class="badge bg-light-warning text-warning ms-1"><i class="ti ti-alert-triangle me-1"></i>Belum Diverifikasi</span>
								<?php endif; ?>
								<a href="javascript:void(0)" class="ms-1 text-decoration-none jump-to-email-security" title="Kelola email di Akun & Keamanan">
									<i class="ti ti-external-link"></i>
								</a>
							</span>
						</div>
						<div class="info-row">
							<span class="info-label"><i class="ti ti-phone"></i>No. HP</span>
							<span class="info-value" id="viewHp"><?= $user->hp ?: '-'; ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><i class="ti ti-map-pin"></i>Kota</span>
							<span class="info-value" id="viewKota"><?= $user->kota ?: '-'; ?></span>
						</div>
						<div class="info-row">
							<span class="info-label"><i class="ti ti-home"></i>Alamat</span>
							<span class="info-value" id="viewAlamat"><?= $user->alamat ?: '-'; ?></span>
						</div>
					</div>

					<!-- ===== EDIT MODE ===== -->
					<div id="profilEditMode" class="section-fade d-none">
						<form id="formPersonalInfo">
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="nm_lengkap" value="<?= $user->nm_lengkap; ?>" required>
								</div>

								<div class="col-md-6">
									<label class="form-label">No. HP <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="hp" value="<?= $user->hp; ?>" required>
								</div>

								<div class="col-md-6">
									<label class="form-label">Kota <span class="text-danger">*</span></label>
									<input type="text" class="form-control" name="kota" value="<?= $user->kota; ?>" required>
								</div>

								<div class="col-12">
									<label class="form-label">Alamat <span class="text-danger">*</span></label>
									<textarea class="form-control" name="alamat" rows="3" required><?= $user->alamat; ?></textarea>
								</div>

								<div class="col-12 d-flex gap-2">
									<button type="submit" class="btn btn-sm btn-primary">
										<i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
									</button>
									<button type="button" class="btn btn-sm btn-outline-secondary" id="btnCancelEditProfil">
										Batal
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- AKUN & KEAMANAN -->
		<div class="col-lg-5">
			<div class="card h-100">
				<div class="card-body">
					<h5 class="mb-3">Akun &amp; Keamanan</h5>

					<div class="accordion" id="accountSecurityAccordion">
						<!-- Email -->
						<div class="accordion-item">
							<h2 class="accordion-header">
								<?php $is_verified = (isset($user->is_email_verified) && $user->is_email_verified == 1); ?>
								<button class="accordion-button<?= $is_verified ? ' collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmail">
									<i class="ti ti-mail me-2"></i>Verifikasi &amp; Ganti Email
									<?php if ($is_verified): ?>
										<span class="badge bg-light-success text-success ms-2"><i class="ti ti-circle-check me-1"></i>Terverifikasi</span>
									<?php else: ?>
										<span class="badge bg-light-warning text-warning ms-2"><i class="ti ti-alert-triangle me-1"></i>Belum Verifikasi</span>
									<?php endif; ?>
								</button>
							</h2>
							<div id="collapseEmail" class="accordion-collapse collapse<?= $is_verified ? '' : ' show'; ?>" data-bs-parent="#accountSecurityAccordion">
								<div class="accordion-body">
									<label class="form-label">Alamat Email</label>
									<input type="email" class="form-control mb-2" name="email" id="emailInput" value="<?= $user->email; ?>" <?= $is_verified ? 'disabled' : ''; ?> required>

									<div class="d-flex flex-wrap gap-2">
										<?php if ($is_verified): ?>
											<button class="btn btn-outline-warning btn-sm flex-fill" type="button" id="btnChangeEmail" title="Ganti Email">
												<i class="ti ti-edit me-1"></i>Ganti Email
											</button>
											<button class="btn btn-outline-primary btn-sm flex-fill d-none" type="button" id="btnRequestOtp" title="Kirim Kode OTP Verifikasi Email Baru">
												<i class="ti ti-shield-check me-1"></i>Verifikasi / OTP
											</button>
											<button class="btn btn-outline-secondary btn-sm flex-fill d-none" type="button" id="btnCancelChangeEmail" title="Batal Ganti Email">
												<i class="ti ti-x me-1"></i>Batal
											</button>
										<?php else: ?>
											<button class="btn btn-outline-primary btn-sm flex-fill" type="button" id="btnRequestOtp" title="Kirim Kode OTP Verifikasi">
												<i class="ti ti-shield-check me-1"></i>Verifikasi / OTP
											</button>
										<?php endif; ?>
									</div>

									<?php if ($is_verified): ?>
										<hr class="my-3">
										<h6 class="mb-2 fw-semibold text-dark"><i class="ti ti-bell me-1 text-primary"></i>Pengaturan Notifikasi Email</h6>
										<p class="text-muted text-xs mb-3">Pilih notifikasi yang ingin Anda terima melalui email:</p>

										<div class="form-check form-switch mb-2">
											<input class="form-check-input notif-toggle-switch" type="checkbox" id="switchNotifTicket" data-field="notif_email_ticket" <?= (isset($user->notif_email_ticket) && $user->notif_email_ticket == 1) ? 'checked' : ''; ?>>
											<label class="form-check-label fw-medium text-sm" for="switchNotifTicket">
												Notification Ticket
											</label>
											<div class="text-muted text-xs">Terima pembaruan status dan komentar ticket via email.</div>
										</div>

										<div class="form-check form-switch">
											<input class="form-check-input notif-toggle-switch" type="checkbox" id="switchNotifPm" data-field="notif_email_pm" <?= (isset($user->notif_email_pm) && $user->notif_email_pm == 1) ? 'checked' : ''; ?>>
											<label class="form-check-label fw-medium text-sm" for="switchNotifPm">
												Notification Project Management
											</label>
											<div class="text-muted text-xs">Terima pembaruan tugas dan aktivitas project management via email.</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<!-- Username -->
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsername">
									<i class="fas fa-user-circle me-2"></i>Ubah Username
								</button>
							</h2>
							<div id="collapseUsername" class="accordion-collapse collapse" data-bs-parent="#accountSecurityAccordion">
								<div class="accordion-body">
									<form id="formUsername">
										<div class="row g-3">
											<div class="col-12">
												<label class="form-label">Username Baru</label>
												<input type="text" class="form-control" name="username" value="<?= $user->username; ?>" required>
											</div>
											<div class="col-12">
												<button type="submit" class="btn btn-sm btn-primary w-100">
													<i class="ti ti-edit me-2"></i>Update Username
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>

						<!-- Password -->
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePassword">
									<i class="ti ti-lock me-2"></i>Ubah Password
								</button>
							</h2>
							<div id="collapsePassword" class="accordion-collapse collapse" data-bs-parent="#accountSecurityAccordion">
								<div class="accordion-body">
									<form id="formPassword">
										<div class="row g-3">
											<div class="col-12">
												<label class="form-label">Password Lama <span class="text-danger">*</span></label>
												<div class="input-group">
													<input type="password" class="form-control" name="current_password" id="currentPassword" required>
													<button class="btn btn-sm btn-outline-secondary toggle-password-btn" type="button" data-target="currentPassword">
														<i class="ti ti-eye"></i>
													</button>
												</div>
											</div>

											<div class="col-12">
												<label class="form-label">Password Baru <span class="text-danger">*</span></label>
												<div class="input-group">
													<input type="password" class="form-control" name="new_password" id="newPassword" required>
													<button class="btn btn-sm btn-outline-secondary toggle-password-btn" type="button" data-target="newPassword">
														<i class="ti ti-eye"></i>
													</button>
												</div>
												<small class="text-muted">Min. 5 karakter</small>
											</div>

											<div class="col-12">
												<label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
												<div class="input-group">
													<input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
													<button class="btn btn-sm btn-outline-secondary toggle-password-btn" type="button" data-target="confirmPassword">
														<i class="ti ti-eye"></i>
													</button>
												</div>
											</div>

											<div class="col-12">
												<button type="submit" class="btn btn-sm btn-primary w-100">
													<i class="ti ti-lock me-2"></i>Update Password
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL VERIFIKASI OTP EMAIL -->
	<div class="modal fade" id="modalOtp" tabindex="-1" aria-labelledby="modalOtpLabel" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalOtpLabel"><i class="ti ti-shield-lock me-2 text-primary"></i>Verifikasi Kode OTP Email</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body text-center py-4">
					<p class="text-muted mb-2">Kode OTP 6-digit telah dikirimkan ke alamat email:</p>
					<h6 class="fw-bold text-primary mb-3" id="otpTargetEmail">-</h6>

					<div class="mb-4 col-md-8 mx-auto">
						<label class="form-label text-muted text-sm mb-2">Masukkan Kode OTP</label>
						<input type="text" class="form-control text-center fw-bold fs-3" id="otpCodeInput" maxlength="6" placeholder="000000" autocomplete="off" style="letter-spacing: 6px;">
					</div>

					<div class="mb-3">
						<p class="text-muted text-sm mb-1">Kode berlaku selama: <strong id="otpTimerDisplay" class="text-danger">05:00</strong></p>
						<button type="button" class="btn btn-link btn-sm text-decoration-none" id="btnResendOtp" disabled>
							<i class="ti ti-refresh me-1"></i>Kirim Ulang Kode OTP
						</button>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-sm btn-primary px-4" id="btnVerifyOtp">
						<i class="ti ti-check me-1"></i>Verifikasi OTP
					</button>
				</div>
			</div>
		</div>
	</div>

</div><!-- /.profile-page -->

<script>
	$(document).ready(function() {

		// ===== Toggle View / Edit Mode =====
		$('#btnEditProfil').on('click', function() {
			$('#profilViewMode').addClass('d-none');
			$('#profilEditMode').removeClass('d-none');
			$(this).addClass('d-none');
		});

		$('#btnCancelEditProfil').on('click', function() {
			// reset form fields back to original values
			$('#formPersonalInfo')[0].reset();

			$('#profilEditMode').addClass('d-none');
			$('#profilViewMode').removeClass('d-none');
			$('#btnEditProfil').removeClass('d-none');
		});

		// Jump from view-mode email row to the Email accordion in Akun & Keamanan
		$(document).on('click', '.jump-to-email-security', function() {
			const $collapseEmail = $('#collapseEmail');
			if (!$collapseEmail.hasClass('show')) {
				$collapseEmail.collapse('show');
			}
			const target = document.getElementById('collapseEmail');
			target.scrollIntoView({
				behavior: 'smooth',
				block: 'center'
			});
		});

		// Toggle password visibility
		$(document).on('click', '.toggle-password-btn', function() {
			const fieldId = $(this).data('target');
			const $field = $('#' + fieldId);
			const $icon = $(this).find('i');

			if ($field.attr('type') === 'password') {
				$field.attr('type', 'text');
				$icon.removeClass('ti-eye').addClass('ti-eye-off');
			} else {
				$field.attr('type', 'password');
				$icon.removeClass('ti-eye-off').addClass('ti-eye');
			}
		});

		// Change Photo Button
		$(document).on('click', '.change-photo-btn', function() {
			$('#photoInput').click();
		});

		// Delete Photo
		$(document).on('click', '.delete-photo-btn', function() {
			Swal.fire({
				title: 'Hapus Foto Profile?',
				text: 'Foto profile akan dihapus',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + 'profile/delete_photo',
						type: 'POST',
						dataType: 'json',
						beforeSend: function() {
							Swal.fire({
								title: 'Menghapus...',
								allowOutsideClick: false,
								didOpen: () => {
									Swal.showLoading();
								}
							});
						},
						success: function(response) {
							if (response.status == 1) {
								$('#profilePhoto').attr('src', response.default_photo + '?' + new Date().getTime());

								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: response.message,
									timer: 2000,
									showConfirmButton: false
								}).then(() => {
									location.reload();
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
								text: 'Terjadi kesalahan saat menghapus foto'
							});
						}
					});
				}
			});
		});

		// Upload Photo
		$('#photoInput').on('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				if (!file.type.match('image.*')) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'File harus berupa gambar'
					});
					return;
				}

				if (file.size > 2048000) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Ukuran file maksimal 2MB'
					});
					return;
				}

				const formData = new FormData();
				formData.append('photo', file);

				Swal.fire({
					title: 'Uploading...',
					text: 'Sedang mengupload foto',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: siteurl + 'profile/update_photo',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(response) {
						if (response.status == 1) {
							$('#profilePhoto').attr('src', response.photo_url + '?' + new Date().getTime());
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: response.message,
								timer: 2000,
								showConfirmButton: false
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
							text: 'Terjadi kesalahan saat upload'
						});
					}
				});
			}
		});

		// Update Personal Info
		$('#formPersonalInfo').on('submit', function(e) {
			e.preventDefault();

			// 1. Tampilkan konfirmasi terlebih dahulu
			Swal.fire({
				title: 'Apakah Anda yakin?',
				text: 'Data informasi pribadi akan diperbarui.',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, simpan!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				// 2. Jalankan AJAX hanya jika pengguna menekan tombol "Ya, simpan!"
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + 'profile/update_info',
						type: 'POST',
						data: $(this).serialize(),
						dataType: 'json',
						beforeSend: function() {
							Swal.fire({
								title: 'Menyimpan...',
								allowOutsideClick: false,
								didOpen: () => {
									Swal.showLoading();
								}
							});
						},
						success: function(response) {
							if (response.status == 1) {
								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: response.message,
									timer: 2000,
									showConfirmButton: false
								}).then(() => {
									location.reload();
								});
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Gagal',
									html: response.message
								});
							}
						},
						error: function() {
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: 'Terjadi kesalahan pada server'
							});
						}
					});
				}
			});
		});

		// Update Username
		$('#formUsername').on('submit', function(e) {
			e.preventDefault();

			$.ajax({
				url: siteurl + 'profile/update_username',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Menyimpan...',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						}).then(() => {
							location.reload();
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							html: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan'
					});
				}
			});
		});

		// Update Password
		$('#formPassword').on('submit', function(e) {
			e.preventDefault();

			const newPass = $('input[name="new_password"]').val();
			const confirmPass = $('input[name="confirm_password"]').val();

			if (newPass !== confirmPass) {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Konfirmasi password tidak sesuai'
				});
				return;
			}

			$.ajax({
				url: siteurl + 'profile/update_password',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Menyimpan...',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						}).then(() => {
							$('#formPassword')[0].reset();
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							html: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan'
					});
				}
			});
		});

		// Logic Verifikasi OTP Email
		let otpTimerInterval = null;

		// Tombol Ganti Email
		$(document).on('click', '#btnChangeEmail', function() {
			// Warning saat user ingin ganti email yang sudah terverifikasi
			Swal.fire({
				icon: 'info',
				title: 'Ganti Email',
				html: `<div class="text-start">
					<p class="mb-2">Email Anda saat ini sudah <strong class="text-success">terverifikasi</strong>.</p>
					<p class="mb-2">Jika Anda mengganti email, Anda perlu melakukan verifikasi ulang dengan email baru.</p>
					<div class="alert alert-warning py-2 px-3 mb-0">
						<small><i class="ti ti-alert-triangle me-1"></i>Pastikan email baru yang Anda masukkan <strong>berbeda</strong> dari email saat ini dan dapat Anda akses.</small>
					</div>
				</div>`,
				showCancelButton: true,
				confirmButtonText: '<i class="ti ti-edit me-1"></i>Ya, Ganti Email',
				cancelButtonText: 'Batal',
				confirmButtonColor: '#e67e22',
				cancelButtonColor: '#6c757d',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					const $input = $('#emailInput');
					if (!$input.data('original-email')) {
						$input.data('original-email', $input.val());
					}
					$input.prop('disabled', false).focus();
					$('#btnChangeEmail').addClass('d-none');
					$('#btnRequestOtp').removeClass('d-none');
					$('#btnCancelChangeEmail').removeClass('d-none');
				}
			});
		});

		// Tombol Batal Ganti Email
		$(document).on('click', '#btnCancelChangeEmail', function() {
			const $input = $('#emailInput');
			const originalEmail = $input.data('original-email');
			if (originalEmail) {
				$input.val(originalEmail);
			}
			if ($('#btnChangeEmail').length) {
				$input.prop('disabled', true);
			}
			$('#btnChangeEmail').removeClass('d-none');
			$('#btnRequestOtp').addClass('d-none');
			$('#btnCancelChangeEmail').addClass('d-none');
		});

		function startOtpTimer(durationSeconds) {
			clearInterval(otpTimerInterval);
			let timer = durationSeconds;
			$('#btnResendOtp').prop('disabled', true).addClass('text-muted');

			function updateDisplay() {
				let minutes = parseInt(timer / 60, 10);
				let seconds = parseInt(timer % 60, 10);

				minutes = minutes < 10 ? "0" + minutes : minutes;
				seconds = seconds < 10 ? "0" + seconds : seconds;

				$('#otpTimerDisplay').text(minutes + ":" + seconds).removeClass('text-secondary').addClass('text-danger');

				if (--timer < 0) {
					clearInterval(otpTimerInterval);
					$('#otpTimerDisplay').text("Kadaluarsa").removeClass('text-danger').addClass('text-secondary');
					$('#btnResendOtp').prop('disabled', false).removeClass('text-muted');
				}
			}

			updateDisplay();
			otpTimerInterval = setInterval(updateDisplay, 1000);
		}

		// Click Request / Resend OTP
		$(document).on('click', '#btnRequestOtp, #btnResendOtp', function() {
			const targetEmail = $('#emailInput').val().trim();
			if (!targetEmail) {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Harap isi alamat email terlebih dahulu'
				});
				return;
			}

			// Validasi format email sederhana di frontend
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailRegex.test(targetEmail)) {
				Swal.fire({
					icon: 'warning',
					title: 'Email Tidak Valid',
					text: 'Format alamat email yang Anda masukkan tidak valid. Silakan periksa kembali.'
				});
				return;
			}

			// Warning konfirmasi sebelum kirim OTP
			const isResend = $(this).attr('id') === 'btnResendOtp';
			if (!isResend) {
				Swal.fire({
					icon: 'question',
					title: 'Konfirmasi Email',
					html: `<div class="text-center">
						<p class="mb-2">Kode OTP akan dikirimkan ke alamat email:</p>
						<div class="alert alert-info py-2 px-3 d-inline-block">
							<strong class="fs-6"><i class="ti ti-mail me-1"></i>${$('<span>').text(targetEmail).html()}</strong>
						</div>
						<p class="text-muted mt-2 mb-0 text-sm">Pastikan alamat email tersebut benar dan dapat Anda akses.</p>
					</div>`,
					showCancelButton: true,
					confirmButtonText: '<i class="ti ti-send me-1"></i>Ya, Kirim OTP',
					cancelButtonText: 'Periksa Kembali',
					confirmButtonColor: '#2563eb',
					cancelButtonColor: '#6c757d',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						sendOtpRequest(targetEmail);
					}
				});
			} else {
				sendOtpRequest(targetEmail);
			}
		});

		// Function kirim OTP setelah konfirmasi
		function sendOtpRequest(targetEmail) {
			$.ajax({
				url: siteurl + 'profile/send_email_otp',
				type: 'POST',
				data: {
					email: targetEmail
				},
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Mengirim OTP...',
						text: 'Sedang mengirimkan kode OTP ke email Anda',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'OTP Terkirim!',
							html: response.message,
							timer: 2000,
							showConfirmButton: false
						});

						$('#otpTargetEmail').text(targetEmail);
						$('#otpCodeInput').val('');
						$('#modalOtp').modal('show');
						startOtpTimer(response.expires_in || 300);
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							html: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan sistem saat mengirim kode OTP'
					});
				}
			});
		}

		// Submit Verification Code OTP
		$(document).on('click', '#btnVerifyOtp', function() {
			const targetEmail = $('#otpTargetEmail').text().trim();
			const otpCode = $('#otpCodeInput').val().trim();

			if (!otpCode || otpCode.length < 6) {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Harap masukkan 6 digit kode OTP dengan benar'
				});
				return;
			}

			$.ajax({
				url: siteurl + 'profile/verify_email_otp',
				type: 'POST',
				data: {
					email: targetEmail,
					otp_code: otpCode
				},
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Memverifikasi...',
						text: 'Sedang memeriksa kode OTP',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
				},
				success: function(response) {
					if (response.status == 1) {
						$('#modalOtp').modal('hide');
						clearInterval(otpTimerInterval);

						// Animasi sukses yang menarik
						Swal.fire({
							icon: 'success',
							title: 'Verifikasi Berhasil! 🎉',
							html: `<div class="text-center">
								<div class="mb-3">
									<div class="success-checkmark-animation">
										<svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="width:72px;height:72px;">
											<circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" stroke="#4caf50" stroke-width="2"/>
											<path class="checkmark-check" fill="none" stroke="#4caf50" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
										</svg>
									</div>
								</div>
								<p class="mb-2 fw-semibold text-success">Email berhasil diverifikasi!</p>
								<p class="text-muted text-sm mb-1">Email <strong>${$('<span>').text(targetEmail).html()}</strong> telah terhubung dengan akun Anda.</p>
								<p class="text-muted text-xs">Anda sekarang dapat menerima notifikasi melalui email ini.</p>
							</div>`,
							showConfirmButton: true,
							confirmButtonText: '<i class="ti ti-check me-1"></i>Selesai',
							confirmButtonColor: '#4caf50',
							allowOutsideClick: false,
							customClass: {
								popup: 'swal-success-verified'
							},
							didOpen: () => {
								// Tambah animasi CSS untuk checkmark
								const style = document.createElement('style');
								style.textContent = `
									.swal-success-verified {
										border-top: 4px solid #4caf50 !important;
									}
									.success-checkmark-animation .checkmark-svg {
										animation: scaleIn 0.5s ease-in-out;
									}
									.success-checkmark-animation .checkmark-circle {
										stroke-dasharray: 166;
										stroke-dashoffset: 166;
										animation: strokeCircle 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
									}
									.success-checkmark-animation .checkmark-check {
										stroke-dasharray: 48;
										stroke-dashoffset: 48;
										animation: strokeCheck 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.5s forwards;
									}
									@keyframes scaleIn {
										0% { transform: scale(0); opacity: 0; }
										50% { transform: scale(1.15); }
										100% { transform: scale(1); opacity: 1; }
									}
									@keyframes strokeCircle {
										100% { stroke-dashoffset: 0; }
									}
									@keyframes strokeCheck {
										100% { stroke-dashoffset: 0; }
									}
								`;
								document.head.appendChild(style);

								// Confetti particles dengan animasi JS
								const popup = Swal.getPopup();
								const colors = ['#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#f44336', '#00bcd4', '#ffeb3b'];
								
								for (let i = 0; i < 40; i++) {
									const particle = document.createElement('div');
									const size = Math.random() * 8 + 4;
									const angle = (Math.PI * 2 * i) / 40;
									const velocity = Math.random() * 150 + 80;
									const tx = Math.cos(angle) * velocity;
									const ty = Math.sin(angle) * velocity - 50;
									const rotation = Math.random() * 720;
									const delay = Math.random() * 200;
									const duration = Math.random() * 600 + 800;

									particle.style.cssText = `
										position: absolute;
										width: ${size}px;
										height: ${size}px;
										background: ${colors[Math.floor(Math.random() * colors.length)]};
										border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
										top: 40%;
										left: 50%;
										pointer-events: none;
										opacity: 1;
										transform: translate(0, 0) rotate(0deg);
										transition: none;
									`;
									popup.style.overflow = 'hidden';
									popup.appendChild(particle);

									setTimeout(() => {
										particle.style.transition = `all ${duration}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)`;
										particle.style.transform = `translate(${tx}px, ${ty}px) rotate(${rotation}deg)`;
										particle.style.opacity = '0';
									}, delay);

									setTimeout(() => {
										particle.remove();
									}, delay + duration + 100);
								}
							}
						}).then(() => {
							location.reload();
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Verifikasi Gagal',
							text: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan sistem saat verifikasi OTP'
					});
				}
			});
		});

		// Toggle Notification Switch Handler
		$(document).on('change', '.notif-toggle-switch', function() {
			const $switch = $(this);
			const fieldName = $switch.data('field');
			const isChecked = $switch.is(':checked') ? 1 : 0;

			$.ajax({
				url: siteurl + 'profile/update_notification_setting',
				type: 'POST',
				data: {
					field: fieldName,
					value: isChecked
				},
				dataType: 'json',
				beforeSend: function() {
					$switch.prop('disabled', true);
				},
				success: function(response) {
					$switch.prop('disabled', false);
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Pengaturan Diperbarui',
							text: response.message,
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000
						});
					} else {
						$switch.prop('checked', !isChecked);
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: response.message
						});
					}
				},
				error: function() {
					$switch.prop('disabled', false);
					$switch.prop('checked', !isChecked);
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan saat menyimpan pengaturan'
					});
				}
			});
		});

		// Show full Client Apps list in modal
		$(document).on('click', '.btn-show-all-apps', function() {
			let apps = [];
			try {
				apps = JSON.parse($(this).attr('data-apps'));
			} catch (e) {
				apps = [];
			}

			const listHtml = apps.map(app => `
				<div class="d-flex align-items-center gap-2 py-1 border-bottom text-start">
					<i class="ti ti-apps text-primary"></i>
					<span>${$('<div>').text(app).html()}</span>
				</div>
			`).join('');

			Swal.fire({
				title: '<i class="ti ti-apps me-2 text-primary"></i>Daftar Client Apps',
				html: `<div class="text-start" style="max-height:300px; overflow-y:auto;">${listHtml}</div>`,
				confirmButtonText: 'Tutup',
				confirmButtonColor: '#3085d6',
				width: 400
			});
		});
	});
</script>