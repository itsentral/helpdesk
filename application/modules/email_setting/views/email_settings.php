<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!-- Header Card -->
            <div class="card card-custom shadow mb-6">
                <div class="card-header border-0 pt-5 pb-5 d-flex align-items-center justify-content-between flex-wrap">
                    <div class="card-title">
                        <h3 class="card-label font-weight-bolder text-dark">
                            <i class="<?= $icon; ?> text-primary mr-2"></i> Master Konfigurasi Email Sender / Server
                            <span class="text-muted mt-1 d-block font-size-sm">Kelola master list server SMTP, pengirim email, dan uji koneksi pengiriman email</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary font-weight-bolder mr-2" id="btn-add-config">
                            <i class="fa fa-plus-circle mr-1"></i> Tambah Server Email
                        </button>
                        <a href="<?= site_url('setting/email_settings/template'); ?>" class="btn btn-light-warning font-weight-bold mr-2">
                            <i class="fa fa-edit mr-1"></i> Edit Template Email
                        </a>
                        <a href="<?= site_url('setting/email_settings/queue'); ?>" class="btn btn-light-info font-weight-bold">
                            <i class="fa fa-list-alt mr-1"></i> Lihat Queue Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- Master List Table Card -->
            <div class="card card-custom shadow">
                <div class="card-body">
                    <div class="alert alert-custom alert-light-info fade show mb-6" role="alert">
                        <div class="alert-icon"><i class="fa fa-info-circle text-info"></i></div>
                        <div class="alert-text">
                            Email yang ditandai sebagai <strong><span class="badge badge-success">AKTIF</span></strong> akan digunakan secara otomatis oleh sistem untuk pengiriman pesan antrean (notifikasi approval, review dokumen, dll).
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-head-custom table-vertical-center table-hover border-bottom" id="table-email-configs">
                            <thead>
                                <tr class="text-uppercase bg-light">
                                    <th class="text-center" width="50">#</th>
                                    <th>Status</th>
                                    <th>Provider & Identity</th>
                                    <th>SMTP Server</th>
                                    <th>Sender & Reply-To</th>
                                    <th>Status SMTP & Log</th>
                                    <th class="text-right" style="min-width: 180px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($configs)): ?>
                                    <?php $no = 1; foreach ($configs as $cfg): 
                                        $p_info = isset($providers[$cfg->provider]) ? $providers[$cfg->provider] : $providers['custom'];
                                    ?>
                                        <tr>
                                            <td class="text-center font-weight-bold"><?= $no++; ?></td>
                                            <td>
                                                <?php if ($cfg->is_active == 1): ?>
                                                    <span class="badge badge-success px-3 py-2 font-weight-bolder">
                                                        <i class="fa fa-check-circle text-white mr-1"></i> AKTIF
                                                    </span>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-set-active" data-id="<?= $cfg->id; ?>" data-title="<?= htmlspecialchars($cfg->title); ?>">
                                                        <i class="fa fa-power-off text-muted mr-1"></i> Set Aktif
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40 symbol-light-primary mr-3 flex-shrink-0">
                                                        <span class="symbol-label">
                                                            <i class="<?= $p_info['icon']; ?> font-size-h4"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0)" class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg btn-edit" data-id="<?= $cfg->id; ?>">
                                                            <?= htmlspecialchars($cfg->title); ?>
                                                        </a>
                                                        <span class="text-muted font-size-sm d-block">
                                                            <span class="badge <?= $p_info['badge']; ?> font-weight-bold mr-1"><?= $p_info['name']; ?></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark"><?= htmlspecialchars($cfg->smtp_host); ?></div>
                                                <div class="text-muted font-size-sm">
                                                    Port: <span class="font-weight-bold text-dark"><?= $cfg->smtp_port; ?></span> | 
                                                    Crypto: <span class="badge badge-light-primary font-weight-bold"><?= strtoupper($cfg->smtp_crypto); ?></span>
                                                </div>
                                                <div class="text-muted font-size-xs">User: <?= htmlspecialchars($cfg->smtp_user); ?></div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark"><i class="fa fa-user-circle text-primary mr-1"></i> <?= htmlspecialchars($cfg->sender_name); ?></div>
                                                <div class="text-muted font-size-sm"><i class="fa fa-envelope text-muted mr-1"></i> <?= htmlspecialchars($cfg->sender_email); ?></div>
                                                <?php if (!empty($cfg->reply_to_email)): ?>
                                                    <div class="text-muted font-size-xs mt-1">
                                                        <i class="fa fa-reply text-info mr-1"></i> Reply-To: <?= htmlspecialchars($cfg->reply_to_name ? $cfg->reply_to_name . ' <'.$cfg->reply_to_email.'>' : $cfg->reply_to_email); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <!-- Status SMTP (Last Test, Last Success, Last Error) -->
                                                <div class="font-size-xs">
                                                    <div>
                                                        <strong>Last Test:</strong> 
                                                        <?php if ($cfg->last_test_at): ?>
                                                            <?= date('d/m/Y H:i', strtotime($cfg->last_test_at)); ?>
                                                            <?php if ($cfg->last_test_status == 'success'): ?>
                                                                <span class="badge badge-xs badge-success">OK</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-xs badge-danger">FAILED</span>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">Belum dites</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <strong>Last Success:</strong> 
                                                        <?= $cfg->last_success_at ? '<span class="text-success font-weight-bold">' . date('d/m/Y H:i', strtotime($cfg->last_success_at)) . '</span>' : '<span class="text-muted">-</span>'; ?>
                                                    </div>
                                                    <?php if ($cfg->last_error_at): ?>
                                                        <div>
                                                            <strong class="text-danger">Last Error:</strong> 
                                                            <span class="text-danger font-weight-bold"><?= date('d/m/Y H:i', strtotime($cfg->last_error_at)); ?></span>
                                                            <?php if (!empty($cfg->last_error_msg)): ?>
                                                                <button type="button" class="btn btn-xs btn-link p-0 text-danger" data-toggle="popover" title="Detail Error" data-content="<?= htmlspecialchars($cfg->last_error_msg); ?>">
                                                                    <i class="fa fa-info-circle text-danger"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-sm btn-light-success font-weight-bold btn-test-modal mr-1" data-id="<?= $cfg->id; ?>" data-title="<?= htmlspecialchars($cfg->title); ?>" data-email="<?= htmlspecialchars($cfg->sender_email); ?>">
                                                    <i class="fa fa-paper-plane"></i> Test
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary btn-icon btn-edit mr-1" data-id="<?= $cfg->id; ?>" title="Edit Konfigurasi">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <?php if ($cfg->is_active != 1): ?>
                                                    <button type="button" class="btn btn-sm btn-light-danger btn-icon btn-delete" data-id="<?= $cfg->id; ?>" data-title="<?= htmlspecialchars($cfg->title); ?>" title="Hapus Server">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">Belum ada konfigurasi email server. Silakan tambah konfigurasi baru.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Tambah / Edit Konfigurasi -->
<div class="modal fade" id="modal-config" tabindex="-1" role="dialog" aria-labelledby="modalConfigLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bolder text-white" id="modalConfigLabel"><i class="fa fa-server mr-2"></i> Konfigurasi Email Server</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-config">
                <input type="hidden" id="config_id" name="id" value="">
                <div class="modal-body p-6">
                    
                    <!-- Quick Provider Preset Selector -->
                    <div class="form-group mb-5">
                        <label class="font-weight-bolder text-dark">Pilih Provider Preset <span class="text-danger">*</span></label>
                        <select class="form-control form-control-lg font-weight-bold border-primary" id="provider_select" name="provider">
                            <?php foreach ($providers as $key => $p): ?>
                                <option value="<?= $key; ?>"><?= $p['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted mt-2" id="provider_note">
                            Select a provider to auto-fill recommended default settings.
                        </small>
                    </div>

                    <hr class="mb-5">

                    <div class="row">
                        <!-- Judul Identitas Konfigurasi -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title" class="font-weight-bold">Nama Identitas Konfigurasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: Gmail Utama Perusahaan / Brevo Marketing" required>
                            </div>
                        </div>
                    </div>

                    <h6 class="font-weight-bolder text-primary mb-3"><i class="fa fa-network-wired mr-1"></i> Pengaturan SMTP Server</h6>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="smtp_host" class="font-weight-bold">SMTP Host <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="smtp_host" name="smtp_host" placeholder="Contoh: smtp.gmail.com" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="smtp_port" class="font-weight-bold">SMTP Port <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="smtp_port" name="smtp_port" value="465" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="smtp_user" class="font-weight-bold">SMTP Username / Email Login <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="smtp_user" name="smtp_user" placeholder="email@domain.com / Username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="smtp_pass" class="font-weight-bold">SMTP Password / App Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" placeholder="Password SMTP / App Key">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" style="cursor: pointer;">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <small class="form-text text-muted" id="pass_help_text">Tersimpan dengan enkripsi aman.</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="smtp_crypto" class="font-weight-bold">Enkripsi (Encryption) <span class="text-danger">*</span></label>
                                <select class="form-control" id="smtp_crypto" name="smtp_crypto">
                                    <option value="ssl">SSL (Port 465)</option>
                                    <option value="tls">TLS (Port 587 / 2525)</option>
                                    <option value="none">None / Non-Encrypted</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h6 class="font-weight-bolder text-primary mb-3 mt-3"><i class="fa fa-paper-plane mr-1"></i> Pengaturan Sender & Reply-To</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sender_name" class="font-weight-bold">Sender Name (Nama Pengirim) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="Contoh: E-Library Notification System" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sender_email" class="font-weight-bold">Sender Email (Email Pengirim) <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="sender_email" name="sender_email" placeholder="noreply@domain.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reply_to_name" class="font-weight-bold">Reply-To Name <span class="text-muted">(Opsional)</span></label>
                                <input type="text" class="form-control" id="reply_to_name" name="reply_to_name" placeholder="Nama penerima balasan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reply_to_email" class="font-weight-bold">Reply-To Email <span class="text-muted">(Opsional)</span></label>
                                <input type="email" class="form-control" id="reply_to_email" name="reply_to_email" placeholder="support@domain.com">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox custom-control-lg">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1">
                            <label class="custom-control-label font-weight-bold text-dark" for="is_active">
                                Setel sebagai Konfigurasi Aktif Utama
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-save-config">
                        <i class="fa fa-save mr-1"></i> Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Test Email -->
<div class="modal fade" id="modal-test" tabindex="-1" role="dialog" aria-labelledby="modalTestLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bolder text-white" id="modalTestLabel"><i class="fa fa-paper-plane mr-2"></i> Uji Coba Kirim Email (Test SMTP)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-test-email">
                <input type="hidden" id="test_config_id" name="config_id" value="">
                <div class="modal-body p-6">
                    <div class="alert alert-custom alert-light-success fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="fa fa-paper-plane text-success"></i></div>
                        <div class="alert-text">
                            Sistem akan mencoba mengirimkan pesan email pengujian secara real-time ke alamat email tujuan di bawah ini menggunakan server: <strong id="test-server-title">-</strong>.
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="target_email" class="font-weight-bolder text-dark">Email Tujuan Test <span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-lg" id="target_email" name="target_email" placeholder="masukkan_email_anda@gmail.com" required>
                        <small class="form-text text-muted">Hasil pengujian akan otomatis memperbarui Status SMTP (Last Test, Last Success, atau Last Error).</small>
                    </div>

                    <!-- Container Indikator Status Pengujian -->
                    <div id="test-result-box" style="display: none;" class="mt-4">
                        <!-- AJAX Result Alert injected here -->
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success font-weight-bolder" id="btn-submit-test">
                        <i class="fa fa-paper-plane mr-1"></i> Kirim Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var providerPresets = <?= json_encode($providers); ?>;

    $(document).ready(function() {
        // Initialize Popovers for error logs
        $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            placement: 'top'
        });

        // Toggle Password visibility
        $('.toggle-password').click(function() {
            var input = $('#smtp_pass');
            var icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Provider Select Change Handler
        $('#provider_select').change(function() {
            var pKey = $(this).val();
            if (providerPresets[pKey]) {
                var p = providerPresets[pKey];
                $('#provider_note').html(p.note);

                // Auto fill if not editing or custom selected
                if (pKey !== 'custom') {
                    $('#smtp_host').val(p.smtp_host);
                    $('#smtp_port').val(p.smtp_port);
                    $('#smtp_crypto').val(p.smtp_crypto);
                }
            }
        });

        // Auto sync sender_email when smtp_user changes (if sender_email empty)
        $('#smtp_user').on('keyup change', function() {
            var val = $(this).val();
            if (val.indexOf('@') !== -1 && !$('#sender_email').val()) {
                $('#sender_email').val(val);
            }
        });

        // Open Modal Add
        $('#btn-add-config').click(function() {
            $('#form-config')[0].reset();
            $('#config_id').val('');
            $('#modalConfigLabel').html('<i class="fa fa-plus-circle mr-2"></i> Tambah Server Email Baru');
            $('#provider_select').val('gmail').trigger('change');
            $('#pass_help_text').text('Tersimpan dengan enkripsi aman.');
            $('#smtp_pass').attr('required', true);
            $('#modal-config').modal('show');
        });

        // Open Modal Edit
        $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            $.ajax({
                url: siteurl + 'setting/email_settings/get_config/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status == 1) {
                        var d = res.data;
                        $('#config_id').val(d.id);
                        $('#title').val(d.title);
                        $('#provider_select').val(d.provider);
                        if (providerPresets[d.provider]) {
                            $('#provider_note').html(providerPresets[d.provider].note);
                        }
                        $('#smtp_host').val(d.smtp_host);
                        $('#smtp_port').val(d.smtp_port);
                        $('#smtp_user').val(d.smtp_user);
                        $('#smtp_pass').val(d.smtp_pass);
                        $('#smtp_crypto').val(d.smtp_crypto);
                        $('#sender_name').val(d.sender_name);
                        $('#sender_email').val(d.sender_email);
                        $('#reply_to_name').val(d.reply_to_name);
                        $('#reply_to_email').val(d.reply_to_email);
                        $('#is_active').prop('checked', d.is_active == 1);

                        $('#pass_help_text').text('Biarkan kosong jika tidak ingin mengubah password.');
                        $('#smtp_pass').removeAttr('required');
                        $('#modalConfigLabel').html('<i class="fa fa-edit mr-2"></i> Edit Konfigurasi Email');
                        $('#modal-config').modal('show');
                    } else {
                        Swal.fire('Gagal!', res.msg, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal mengambil data dari server.', 'error');
                }
            });
        });

        // Save Form Config (Submit)
        $('#form-config').submit(function(e) {
            e.preventDefault();
            var btn = $('#btn-save-config');
            var formdata = $(this).serialize();

            btn.attr('disabled', true).html('<i class="spinner spinner-border-sm mr-1"></i> Menyimpan...');

            $.ajax({
                url: siteurl + 'setting/email_settings/save_config',
                type: 'POST',
                dataType: 'json',
                data: formdata,
                success: function(res) {
                    btn.attr('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Konfigurasi');
                    if (res.status == 1) {
                        $('#modal-config').modal('hide');
                        Swal.fire({
                            title: 'Sukses!',
                            text: res.msg,
                            icon: 'success'
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', res.msg, 'error');
                    }
                },
                error: function() {
                    btn.attr('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Konfigurasi');
                    Swal.fire('Error!', 'Terjadi kesalahan pada koneksi server.', 'error');
                }
            });
        });

        // Set Active Configuration
        $(document).on('click', '.btn-set-active', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');

            Swal.fire({
                title: 'Aktifkan Konfigurasi?',
                html: 'Apakah Anda yakin ingin menjadikan <strong>' + title + '</strong> sebagai server email aktif utama untuk seluruh pengiriman notifikasi sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: siteurl + 'setting/email_settings/set_active',
                        type: 'POST',
                        dataType: 'json',
                        data: { id: id },
                        success: function(res) {
                            if (res.status == 1) {
                                Swal.fire('Berhasil!', res.msg, 'success').then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.msg, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                        }
                    });
                }
            });
        });

        // Delete Configuration
        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');

            Swal.fire({
                title: 'Hapus Server Email?',
                html: 'Hapus konfigurasi email <strong>' + title + '</strong>?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: siteurl + 'setting/email_settings/delete_config',
                        type: 'POST',
                        dataType: 'json',
                        data: { id: id },
                        success: function(res) {
                            if (res.status == 1) {
                                Swal.fire('Terhapus!', res.msg, 'success').then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.msg, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                        }
                    });
                }
            });
        });

        // Open Test Email Modal
        $(document).on('click', '.btn-test-modal', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var email = $(this).data('email');

            $('#test_config_id').val(id);
            $('#test-server-title').text(title);
            $('#target_email').val(email);
            $('#test-result-box').hide().html('');
            $('#modal-test').modal('show');
        });

        // Submit Test Email
        $('#form-test-email').submit(function(e) {
            e.preventDefault();
            var btn = $('#btn-submit-test');
            var resultBox = $('#test-result-box');
            var formdata = $(this).serialize();

            btn.attr('disabled', true).html('<i class="spinner spinner-border-sm mr-1"></i> Menguji koneksi SMTP...');
            resultBox.html('<div class="alert alert-info font-weight-bold"><i class="spinner spinner-border-sm mr-2"></i> Sedang menghubungkan ke server SMTP dan mengirim pesan test...</div>').slideDown();

            $.ajax({
                url: siteurl + 'setting/email_settings/test_config',
                type: 'POST',
                dataType: 'json',
                data: formdata,
                success: function(res) {
                    btn.attr('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Kirim Test Email');
                    if (res.status == 1) {
                        resultBox.html('<div class="alert alert-success"><i class="fa fa-check-circle mr-2"></i> ' + res.msg + '</div>');
                    } else {
                        resultBox.html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle mr-2"></i> ' + res.msg + '</div>');
                    }
                },
                error: function() {
                    btn.attr('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Kirim Test Email');
                    resultBox.html('<div class="alert alert-danger"><i class="fa fa-times-circle mr-2"></i> Terjadi kesalahan komunikasi dengan server.</div>');
                }
            });
        });
    });
</script>
