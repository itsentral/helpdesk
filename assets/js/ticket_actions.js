

function changeTicketStatus(ticketId, status, statusText, currentStatus = null, manHourPlan = null, causes = null, actionPlan = null) {
    var modalContent = '';
    var showReasonInput = false;
    var showManHourInput = false;
    var showManHourPlanInput = false;

    switch (status) {
        case 1: // Process
            var missingFields = [];

            if (!causes || causes.toString().trim() === '') {
                missingFields.push('Causes');
            }
            if (!actionPlan || actionPlan.toString().trim() === '') {
                missingFields.push('Action Plan');
            }

            if (!manHourPlan || manHourPlan == 0 || manHourPlan == null) {
                showManHourPlanInput = true;
            }

            if (missingFields.length > 0 || showManHourPlanInput) {
                modalContent = `<div class="mb-3">`;

                if (missingFields.length > 0) {
                    modalContent += `
                <h2>Field berikut belum diisi!</h2>
                <div class="alert alert-warning mt-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <strong>${missingFields.join(', ')}</strong> wajib diisi sebelum ticket diproses.
                </div>
            `;
                }

                if (missingFields.includes('Causes')) {
                    modalContent += `
                <div class="form-group mt-3">
                    <label for="causesInput" class="form-label">
                        <i class="fa-solid fa-magnifying-glass"></i> Causes <small class="text-danger">*</small>
                    </label>
                    <textarea class="form-control" id="causesInput" rows="3" placeholder="Masukkan penyebab masalah..."></textarea>
                </div>
            `;
                }

                if (missingFields.includes('Action Plan')) {
                    modalContent += `
                <div class="form-group mt-3">
                    <label for="actionPlanInput" class="form-label">
                        <i class="fa-solid fa-list-check"></i> Action Plan <small class="text-danger">*</small>
                    </label>
                    <textarea class="form-control" id="actionPlanInput" rows="3" placeholder="Masukkan rencana penanganan..."></textarea>
                </div>
            `;
                }

                if (showManHourPlanInput) {
                    modalContent += `
                <div class="form-group mt-3">
                    <label for="manHourPlan" class="form-label">
                        <i class="fa-solid fa-clock"></i> Man Hour Plan <small class="text-danger">*</small>
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="manHourPlan"
                            placeholder="Contoh: 4" min="0.5" step="0.5" required>
                        <span class="input-group-text">jam</span>
                    </div>
                    <small class="text-muted d-block mt-1">
                        <i class="fa-solid fa-info-circle"></i> Masukkan estimasi waktu yang dibutuhkan
                    </small>
                </div>
            `;
                }

                modalContent += `</div>`;
            } else {
                modalContent = '<h2>Ticket ini akan diproses. Lanjutkan?</h2>';
            }
            break;

        case 2: // Pending
            modalContent = '<h2>Tunda pengerjaan ticket ini?</h2>';
            break;

        case 3: // Cancel
            modalContent = `
                <div class="mb-3">
                    <h2>Ticket ini akan dibatalkan. Mohon konfirmasi.</h2>
                    <div class="form-group mt-3">
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
                        <h2>Tandai ticket ini sudah selesai?</h2>
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
                modalContent = '<h2>Tandai ticket ini sudah selesai?</h2>';
            }
            break;

        default:
            modalContent = '<h2>Yakin ingin melanjutkan perubahan ini?</h2>';
    }

    Swal.fire({
        html: modalContent,
        icon: 'question',
        didOpen: () => {
            const container = Swal.getHtmlContainer();
            container.style.maxHeight = "400px";
            container.style.overflowY = "auto";
        },
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Lanjutkan',
        cancelButtonText: '<i class="fa-solid fa-xmark"></i> Kembali',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            var cancelReason = '';
            var manHourActual = '';
            var manHourPlanInput = '';
            var causesInput = '';
            var actionPlanInput = '';

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

            if ($('#causesInput').length) {
                causesInput = $('#causesInput').val();
                if (!causesInput || !causesInput.trim()) {
                    Swal.showValidationMessage('Causes wajib diisi');
                    return false;
                }
            }

            // Validasi Action Plan
            if ($('#actionPlanInput').length) {
                actionPlanInput = $('#actionPlanInput').val();
                if (!actionPlanInput || !actionPlanInput.trim()) {
                    Swal.showValidationMessage('Action Plan wajib diisi');
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
                        man_hour_plan: manHourPlanInput ? parseFloat(manHourPlanInput) : '',
                        causes: causesInput ? causesInput.trim() : '',
                        action_plan: actionPlanInput ? actionPlanInput.trim() : ''
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 1) {
                            resolve(response);
                        } else {
                            reject(new Error(response.message || 'Terjadi kesalahan'));
                        }
                    },
                    error: function (xhr, status, error) {
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
            // loadHelpdeskList();
            // loadHelpdeskList(selectedClientId, selectedStatusId);
            loadHelpdeskList(
                typeof selectedClientId !== 'undefined' ? selectedClientId : '',
                typeof selectedStatusId !== 'undefined' ? selectedStatusId : ''
            );
        }
    });
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
            success: function (res) {
                Swal.fire({
                    icon: res.status ? 'success' : 'error',
                    title: res.status ? 'Berhasil' : 'Gagal',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                // if (res.status) loadHelpdeskList();
                // if (res.status) loadHelpdeskList(selectedClientId, selectedStatusId);
                if (res.status) loadHelpdeskList(
                    typeof selectedClientId !== 'undefined' ? selectedClientId : '',
                    typeof selectedStatusId !== 'undefined' ? selectedStatusId : ''
                );
            }
        });
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
        success: function (response) {
            $('#historyLoading').hide();

            if (response.status == 1 && response.data.length > 0) {
                var historyHTML = buildHistoryTimeline(response.data);
                $('#historyTimeline').html(historyHTML).show();
            } else {
                $('#historyEmpty').show();
            }
        },
        error: function () {
            $('#historyLoading').hide();
            $('#historyTimeline').html(
                '<div class="alert alert-danger m-3">' +
                '<i class="fa-solid fa-exclamation-triangle"></i> Gagal memuat history' +
                '</div>'
            ).show();
        }
    });
}