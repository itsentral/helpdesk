<!-- Summernote Lite CDN (Bootstrap 5 Compatible) -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom card-stretch shadow">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h2 class="mt-5"><i class="<?= isset($icon) ? $icon : 'fa fa-edit'; ?> mr-2 text-primary"></i><?= isset($title) ? $title : 'Edit Template Email'; ?></h2>
                    <div class="mt-3">
                        <a href="<?= site_url('email_setting/email_settings'); ?>" class="btn btn-light-danger font-weight-bold mr-2">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-primary font-weight-bold" id="btn-save-template">
                            <i class="fa fa-save"></i> Simpan Template
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-custom alert-light-info fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="fa fa-info-circle"></i></div>
                        <div class="alert-text">
                            <strong>Tips Editor:</strong> Gunakan editor visual di bawah untuk mengatur tata letak. Untuk menjaga tampilan tetap konsisten, gaya (CSS) dipisahkan ke tab <strong>Custom CSS</strong>.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_visual">
                                        <i class="ti ti-eye me-1"></i> Visual Editor
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_css">
                                        <i class="ti ti-code me-1"></i> Custom CSS
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_vars">
                                        <i class="ti ti-adjustments me-1"></i> Variabel Email
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_placeholders">
                                        <i class="ti ti-tag me-1"></i> Placeholders
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content mt-5">
                                <!-- Visual Editor -->
                                <div class="tab-pane fade show active" id="tab_visual" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <textarea id="template_editor" name="template_body"></textarea>
                                                <!-- Hidden buffer for initial load -->
                                                <textarea id="template_body_buffer" style="display:none;"><?= isset($template_body) ? $template_body : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <h4 class="font-weight-bold mb-3">Live Preview</h4>
                                            <div class="border rounded bg-light p-0" style="height: 500px; overflow-y: auto;">
                                                <iframe id="preview-frame" style="width: 100%; height: 100%; border: none; background: white;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CSS Editor -->
                                <div class="tab-pane fade" id="tab_css" role="tabpanel">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Custom CSS (Berlaku untuk seluruh template)</label>
                                        <textarea id="css_editor" name="template_css" class="form-control" style="height: 400px; font-family: monospace; background: #2c3e50; color: #ecf0f1;"><?= isset($template_css) ? htmlspecialchars($template_css) : ''; ?></textarea>
                                    </div>
                                </div>

                                <!-- Variabel Editor -->
                                <div class="tab-pane fade" id="tab_vars" role="tabpanel">
                                    <div class="card card-custom card-bordered">
                                        <div class="card-header">
                                            <h3 class="card-title">Kustomisasi Nilai Placeholder</h3>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-5">Nilai di bawah ini akan menggantikan (override) data Master Perusahaan khusus untuk pengiriman email.</p>
                                            <form id="form-vars">
                                                <div class="form-group row">
                                                    <label class="col-3 col-form-label font-weight-bold">Nama Perusahaan ({{company_name}})</label>
                                                    <div class="col-9">
                                                        <input type="text" name="email_vars[email_vars_company_name]" class="form-control var-input" value="<?= isset($email_vars['email_vars_company_name']) ? $email_vars['email_vars_company_name'] : ''; ?>" placeholder="Contoh: Helpdesk System">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3 col-form-label font-weight-bold">Alamat Perusahaan ({{company_address}})</label>
                                                    <div class="col-9">
                                                        <textarea name="email_vars[email_vars_company_address]" class="form-control var-input" rows="3" placeholder="Contoh: Jl. Sudirman No. 123..."><?= isset($email_vars['email_vars_company_address']) ? $email_vars['email_vars_company_address'] : ''; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3 col-form-label font-weight-bold">URL Logo ({{company_logo}})</label>
                                                    <div class="col-9">
                                                        <input type="text" name="email_vars[email_vars_company_logo]" class="form-control var-input" value="<?= isset($email_vars['email_vars_company_logo']) ? $email_vars['email_vars_company_logo'] : ''; ?>" placeholder="Contoh: https://perusahaan.com/logo.png">
                                                        <span class="form-text text-muted">Biarkan kosong untuk menggunakan logo default sistem.</span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Placeholders Help -->
                                <div class="tab-pane fade" id="tab_placeholders" role="tabpanel">
                                    <div class="card card-bordered p-5">
                                        <h5>Daftar Placeholder yang Tersedia:</h5>
                                        <p class="text-muted">Klik pada placeholder untuk menyalin ke clipboard.</p>
                                        <div class="d-flex flex-wrap">
                                            <div class="p-3 border rounded mr-2 mb-2 bg-light cursor-pointer copy-placeholder"><code>{{content}}</code> <br><small>Isi pesan notifikasi</small></div>
                                            <div class="p-3 border rounded mr-2 mb-2 bg-light cursor-pointer copy-placeholder"><code>{{subject}}</code> <br><small>Subjek email</small></div>
                                            <div class="p-3 border rounded mr-2 mb-2 bg-light cursor-pointer copy-placeholder"><code>{{company_name}}</code> <br><small>Nama Perusahaan</small></div>
                                            <div class="p-3 border rounded mr-2 mb-2 bg-light cursor-pointer copy-placeholder"><code>{{company_logo}}</code> <br><small>URL Logo Perusahaan</small></div>
                                            <div class="p-3 border rounded mr-2 mb-2 bg-light cursor-pointer copy-placeholder"><code>{{company_address}}</code> <br><small>Alamat Perusahaan</small></div>
                                            <div class="p-3 border rounded mr-2 mb-2 bg-light cursor-pointer copy-placeholder"><code>{{action_url}}</code> <br><small>Link Langsung Dokumen</small></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Inisialisasi Summernote
        $('#template_editor').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'italic']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ],
            callbacks: {
                onChange: function(contents) {
                    updatePreview();
                }
            }
        });

        // Load awal data ke Summernote dari buffer
        var initialBody = $('#template_body_buffer').val();
        $('#template_editor').summernote('code', initialBody);

        // Fungsi Update Preview
        function updatePreview() {
            var bodyHtml = $('#template_editor').summernote('code');
            var cssContent = $('#css_editor').val();
            var previewDoc = document.getElementById('preview-frame').contentWindow.document;

            // Ambil nilai dari variabel email (jika diisi)
            var cName = $('input[name="email_vars[email_vars_company_name]"]').val() || 'Helpdesk System';
            var cAddr = $('textarea[name="email_vars[email_vars_company_address]"]').val() || 'Jl. Contoh Alamat No. 123, Jakarta';
            var cLogo = $('input[name="email_vars[email_vars_company_logo]"]').val() || 'https://via.placeholder.com/150x50?text=Logo+Perusahaan';

            var sampleContent = '<div style="background:#e3f2fd; padding:15px; border-left:4px solid #2196f3;"><strong>Contoh Notifikasi:</strong><br>Dokumen Prosedur "SOP Pembelian" telah disetujui oleh Direktur.</div>';

            var fullHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>' + cssContent + '</style></head><body class="email-template">' + bodyHtml + '</body></html>';

            var renderedHtml = fullHtml.replace(/{{content}}/g, sampleContent);
            renderedHtml = renderedHtml.replace(/{{company_name}}/g, cName);
            renderedHtml = renderedHtml.replace(/{{company_logo}}/g, cLogo);
            renderedHtml = renderedHtml.replace(/{{company_address}}/g, cAddr);
            renderedHtml = renderedHtml.replace(/{{action_url}}/g, siteurl + 'monitoring/view/123');

            previewDoc.open();
            previewDoc.write(renderedHtml);
            previewDoc.close();
        }

        $('#css_editor, .var-input').on('input', updatePreview);
        updatePreview();

        // Copy placeholder
        $('.copy-placeholder').click(function() {
            var text = $(this).find('code').text();
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success('Copied: ' + text);
        });

        // Simpan
        $('#btn-save-template').click(function() {
            var btn = $(this);
            var bodyHtml = $('#template_editor').summernote('code');
            var cssContent = $('#css_editor').val();

            Swal.fire({
                title: 'Simpan Template & Variabel?',
                text: 'Perubahan akan segera diterapkan pada email notifikasi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: siteurl + 'email_setting/email_settings/save_template',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            template_body: bodyHtml,
                            template_css: cssContent,
                            email_vars: $('#form-vars').serializeArray().reduce(function(obj, item) {
                                var key = item.name.match(/\[(.*?)\]/)[1];
                                obj[key] = item.value;
                                return obj;
                            }, {})
                        },
                        beforeSend: function() {
                            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                        },
                        complete: function() {
                            btn.attr('disabled', false).html('<i class="fa fa-save"></i> Simpan Template');
                        },
                        success: function(res) {
                            if (res.status == 1) {
                                Swal.fire('Sukses!', res.msg, 'success');
                            } else {
                                Swal.fire('Gagal!', res.msg, 'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>