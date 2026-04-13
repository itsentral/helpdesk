<style>
	.select2-container--modern .select2-selection--multiple {
		min-height: 40px;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		background: #fafafa;
		transition: all 0.3s ease;
	}

	.select2-container--modern .select2-selection--multiple:focus-within {
		border-color: #4dabf7;
		box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.1);
		background: #fff;
	}

	.select2-container--modern .select2-selection--multiple .select2-selection__choice {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		border: none;
		border-radius: 16px;
		padding: 4px 10px;
		margin: 3px;
		font-size: 0.8rem;
		font-weight: 500;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}

	.select2-container--modern .select2-selection--multiple .select2-selection__choice__remove {
		color: rgba(255, 255, 255, 0.8);
		border-left: 1px solid rgba(255, 255, 255, 0.3);
		margin-left: 6px;
		padding-left: 6px;
	}

	.select2-container--modern .select2-selection--multiple .select2-selection__choice__remove:hover {
		color: white;
	}

	.select2-container--modern .select2-dropdown {
		border: none;
		border-radius: 8px;
		box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
		margin-top: 4px;
	}

	.select2-container--modern .select2-results__option {
		padding: 8px 12px;
	}

	.select2-container--modern .select2-results__option--selected {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
	}

	.select2-container--modern .select2-results__option--highlighted {
		background-color: #f8f9fa;
		color: #333;
	}

	/* Search input */
	.select2-container--modern .select2-search--inline .select2-search__field {
		background: transparent;
		border: none;
		padding: 8px;
		font-size: 0.9rem;
	}

	.select2-container--modern .select2-search--inline .select2-search__field:focus {
		outline: none;
		box-shadow: none;
	}
</style>

<div class="card">
	<div class="card-body">
		<?= form_open($this->uri->uri_string(), array(
			'id' => 'frm_users',
			'name' => 'frm_users',
			'role' => 'form'
		)) ?>

		<div class="row g-3">

			<!-- Username -->
			<div class="col-md-6">
				<label class="form-label fw-semibold"><?= lang('users_username') ?></label>
				<div class="input-group <?= form_error('username') ? 'is-invalid' : ''; ?>">
					<span class="input-group-text"><i class="ti ti-user"></i></span>
					<input type="text"
						class="form-control <?= form_error('username') ? 'is-invalid' : ''; ?>"
						id="username" name="username"
						maxlength="45"
						value="<?= set_value('username', isset($data->username) ? $data->username : ''); ?>"
						autofocus />
				</div>
				<?= form_error('username', '<div class="invalid-feedback d-block">', '</div>'); ?>
			</div>

			<!-- Email -->
			<div class="col-md-6">
				<label class="form-label fw-semibold"><?= lang('users_email') ?></label>
				<div class="input-group">
					<span class="input-group-text"><i class="ti ti-mail"></i></span>
					<input type="email"
						class="form-control <?= form_error('email') ? 'is-invalid' : ''; ?>"
						id="email" name="email"
						maxlength="100"
						value="<?= set_value('email', isset($data->email) ? $data->email : ''); ?>" />
				</div>
				<?= form_error('email', '<div class="invalid-feedback d-block">', '</div>'); ?>
			</div>

			<!-- Password -->
			<div class="col-md-6">
				<label class="form-label fw-semibold"><?= lang('users_password') ?></label>
				<div class="input-group">
					<span class="input-group-text"><i class="ti ti-lock"></i></span>
					<input type="password"
						class="form-control <?= form_error('password') ? 'is-invalid' : ''; ?>"
						id="password" name="password"
						maxlength="100"
						value="<?= set_value('password'); ?>" />
				</div>
				<?= form_error('password', '<div class="invalid-feedback d-block">', '</div>'); ?>
				<?php if (isset($data)): ?>
					<small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah password.</small>
				<?php endif; ?>
			</div>

			<!-- Re Password -->
			<div class="col-md-6">
				<label class="form-label fw-semibold"><?= lang('users_repassword') ?></label>
				<div class="input-group">
					<span class="input-group-text"><i class="ti ti-shield-lock"></i></span>
					<input type="password"
						class="form-control <?= form_error('re-password') ? 'is-invalid' : ''; ?>"
						id="re-password" name="re-password"
						maxlength="100"
						value="<?= set_value('re-password'); ?>" />
				</div>
				<?= form_error('re-password', '<div class="invalid-feedback d-block">', '</div>'); ?>
			</div>

			<!-- Nama Lengkap -->
			<div class="col-md-6">
				<label class="form-label fw-semibold"><?= lang('users_nm_lengkap') ?></label>
				<div class="input-group">
					<span class="input-group-text"><i class="ti ti-id"></i></span>
					<input type="text"
						class="form-control <?= form_error('nm_lengkap') ? 'is-invalid' : ''; ?>"
						id="nm_lengkap" name="nm_lengkap"
						maxlength="100"
						value="<?= set_value('nm_lengkap', isset($data->nm_lengkap) ? $data->nm_lengkap : ''); ?>" />
				</div>
				<?= form_error('nm_lengkap', '<div class="invalid-feedback d-block">', '</div>'); ?>
			</div>

			<!-- Kota -->
			<div class="col-md-3">
				<label class="form-label fw-semibold"><?= lang('users_kota') ?></label>
				<div class="input-group">
					<span class="input-group-text"><i class="ti ti-map-pin"></i></span>
					<input type="text"
						class="form-control <?= form_error('kota') ? 'is-invalid' : ''; ?>"
						id="kota" name="kota"
						maxlength="20"
						value="<?= set_value('kota', isset($data->kota) ? $data->kota : ''); ?>" />
				</div>
				<?= form_error('kota', '<div class="invalid-feedback d-block">', '</div>'); ?>
			</div>

			<!-- HP -->
			<div class="col-md-3">
				<label class="form-label fw-semibold"><?= lang('users_hp') ?></label>
				<div class="input-group">
					<span class="input-group-text"><i class="ti ti-phone"></i></span>
					<input type="text"
						class="form-control <?= form_error('hp') ? 'is-invalid' : ''; ?>"
						id="hp" name="hp"
						maxlength="20"
						value="<?= set_value('hp', isset($data->hp) ? $data->hp : ''); ?>" />
				</div>
				<?= form_error('hp', '<div class="invalid-feedback d-block">', '</div>'); ?>
			</div>

			<!-- Alamat -->
			<div class="col-12">
				<label class="form-label fw-semibold"><?= lang('users_alamat') ?></label>
				<div class="input-group">
					<span class="input-group-text"><i class="ti ti-home"></i></span>
					<textarea class="form-control <?= form_error('alamat') ? 'is-invalid' : ''; ?>"
						id="alamat" name="alamat"
						maxlength="255"
						rows="3"><?= set_value('alamat', isset($data->alamat) ? $data->alamat : ''); ?></textarea>
				</div>
				<?= form_error('alamat', '<div class="invalid-feedback d-block">', '</div>'); ?>
			</div>

			<div class="col-md-4">
				<label class="form-label fw-semibold">
					<i class="ti ti-user-check"></i> <?= lang('users_status') ?? 'Tipe User' ?> Pilih Tipe User
				</label>
				<select name="status" id="status" class="form-select <?= form_error('status') ? 'is-invalid' : ''; ?>">
					<option value="">-- Pilih Tipe User --</option>
					<option value="0" <?= set_select('status', '0', (isset($data->status) && $data->status == 0) ? TRUE : FALSE); ?>>
						Internal
					</option>
					<option value="1" <?= set_select('status', '1', (isset($data->status) && $data->status == 1) ? TRUE : FALSE); ?>>
						External (Client)
					</option>
				</select>
				<?= form_error('status', '<div class="invalid-feedback d-block">', '</div>'); ?>
				<small class="text-muted d-block mt-1">Internal: User dari organisasi. External: User dari client.</small>
			</div>

			<!-- Role User -->
			<div class="col-md-3">
				<label class="form-label fw-semibold">
					<i class="ti ti-user-check"></i> Pilih Role User
				</label>
				<select name="role_user" id="role_user" class="form-select <?= form_error('role_user') ? 'is-invalid' : ''; ?>">
					<option value="">-- Pilih Role --</option>
					<option value="ba" <?= set_select(
											'role_user',
											'ba',
											(isset($data->is_ba) && $data->is_ba == 1) ? TRUE : FALSE
										); ?>>
						Business Analyst
					</option>
					<option value="programmer" <?= set_select(
													'role_user',
													'programmer',
													(isset($data->is_programmer) && $data->is_programmer == 1) ? TRUE : FALSE
												); ?>>
						Programmer
					</option>
					<option value="user" <?= set_select(
												'role_user',
												'user',
												(isset($data->is_ba) && $data->is_ba == 0 && isset($data->is_programmer) && $data->is_programmer == 0) ? TRUE : FALSE
											); ?>>
						User
					</option>
				</select>
				<?= form_error('role_user', '<div class="invalid-feedback d-block">', '</div>'); ?>
				<small class="text-muted d-block mt-1">Pilih role untuk user ini.</small>
			</div>

			<!-- Hidden fields -->
			<!-- <input type="hidden" name="status" value="0"> -->
			<input type="hidden" name="is_ba" id="is_ba" value="0">
			<input type="hidden" name="is_programmer" id="is_programmer" value="0">

			<!-- client app  -->
			<div class="col-5">
				<label class="form-label fw-semibold">
					<i class="ti ti-building-store"></i> <?= lang('users_client') ?? 'Helpdesk Client(s)' ?> Pilih Client
				</label>
				<select name="client_ids[]" id="client_ids" class="form-control" multiple>
					<?php if (!empty($clients)): ?>
						<?php foreach ($clients as $client): ?>
							<?php
							$selected = '';
							if (isset($selected_clients) && in_array($client['id'], $selected_clients)) {
								$selected = 'selected';
							}
							$clientInfo = $client['name_app'];
							if (!empty($client['client_code'])) $clientInfo .= " (" . $client['client_code'] . ")";
							if (!empty($client['remark'])) $clientInfo .= " - " . $client['remark'];
							?>
							<option value="<?= $client['id'] ?>" <?= $selected ?> data-client-code="<?= !empty($client['client_code']) ? $client['client_code'] : '' ?>">
								<?= $clientInfo ?>
							</option>
						<?php endforeach; ?>
					<?php else: ?>
						<option value="" disabled>Tidak ada data client</option>
					<?php endif; ?>
				</select>
				<small class="text-muted d-block mb-2">Pilih satu atau lebih client untuk user ini</small>
			</div>


		</div>

		<hr class="my-4">

		<!-- Buttons -->
		<div class="d-flex justify-content-end gap-2">
			<button type="submit" name="save" class="btn btn-primary">
				<i class="ti ti-device-floppy"></i> <?= lang('users_btn_save') ?>
			</button>

			<?= anchor('users/setting', '<i class="ti ti-x"></i> ' . lang('users_btn_cancel'), array('class' => 'btn btn-dark')); ?>
		</div>

		<?= form_close() ?>
	</div>
</div>


<script>
	$(document).ready(function() {
		$('#role_user').on('change', function() {
			const role = $(this).val();
			$('#is_ba').val(role === 'ba' ? 1 : 0);
			$('#is_programmer').val(role === 'programmer' ? 1 : 0);
		});

		$('#role_user').trigger('change');

		// Select2
		$('#client_ids').select2({
			width: '100%',
			placeholder: 'Pilih client...',
			allowClear: true,
			closeOnSelect: false,
			dropdownParent: $('#client_ids').closest('.col-5'),
			containerCssClass: 'select2-container--modern',
			dropdownCssClass: 'select2-dropdown--modern'
		});
	});
</script>