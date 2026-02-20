<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />

  <title>
    <?= isset($idt->nm_perusahaan) ? $idt->nm_perusahaan : 'not-set'; ?>
    <?= isset($template['title']) ? ' | ' . $template['title'] : ''; ?>
  </title>

  <!-- Favicon (pakai milikmu) -->
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo-topbar.png'); ?>" />

  <!-- =========================
       BERRY CSS (Bootstrap 5)   
       ========================= -->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" id="main-font-link" />

  <!-- Icons (Berry) -->
  <link rel="stylesheet" href="<?= base_url('assets/berry/fonts/tabler-icons.min.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/berry/fonts/feather.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/berry/fonts/fontawesome.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/berry/fonts/material.css'); ?>" />

  <!-- Customer Theme -->
  <link rel="stylesheet" href="<?= base_url('assets/berry/css/custom-theme.css'); ?>">
  <!-- <link rel="stylesheet" href="<?= base_url('assets/berry/css/custom-dark.css'); ?>"> -->


  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/berry/css/plugins/animate.min.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/berry/css/style.css'); ?>" id="main-style-link" />
  <link rel="stylesheet" href="<?= base_url('assets/berry/css/custom.css'); ?>" id="main-style-link" />
  <link rel="stylesheet" href="<?= base_url('assets/berry/css/style-preset.css'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-v7/css/all.min.css') ?>">


  <!-- =========================
  CSS PLUGIN LAMA (sementara dipertahankan)
  ========================= -->
  <link rel="stylesheet" href="<?= base_url('assets/dist/sweetalert.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/jquery-ui-1.12.1/jquery-ui-1.12.1/jquery-ui.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/timepicker/bootstrap-timepicker.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datetimepicker/bootstrap-datetimepicker.css'); ?>">
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.dataTables.min.css">

  <!-- =========================
  JS yang kamu pakai di banyak halaman (dipertahankan dulu)
  ========================= -->
  <script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>

  <!-- Date/Time libs (existing) -->
  <script src="<?= base_url('assets/plugins/daterangepicker/moment.min.js'); ?>"></script>
  <script src="<?= base_url('assets/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
  <script src="<?= base_url('assets/jquery-ui-1.12.1/jquery-ui-1.12.1/jquery-ui.min.js'); ?>"></script>
  <script src="<?= base_url('assets/plugins/timepicker/bootstrap-timepicker.min.js'); ?>"></script>
  <script src="<?= base_url('assets/plugins/datetimepicker/bootstrap-datetimepicker.js'); ?>"></script>
  <script src="<?= base_url('assets/plugins/datetimepicker/moment-with-locales.js'); ?>"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

  <!-- Sweet Alert -->
  <script src="<?= base_url('assets/dist/sweetalert.min.js'); ?>"></script>
  <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>

  <!-- Form Jquery -->
  <script src="<?= base_url('assets/plugins/jqueryform/jquery.form.js'); ?>"></script>

  <!-- (SlimScroll biasanya AdminLTE, tapi biarin dulu kalau masih kepakai di halaman tertentu) -->
  <script src="<?= base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>

  <script src="<?= base_url('assets/js/scripts.js'); ?>" type="text/javascript"></script>

  <style>
    .notif-item:hover {
      background: #d0e8ff !important;
      cursor: pointer;
    }

    /* Pulse ring effect */
    @keyframes pulseRing {
      0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5);
      }

      70% {
        box-shadow: 0 0 0 8px rgba(220, 53, 69, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
      }
    }

    @keyframes bellShake {
      0% {
        transform: rotate(0deg);
      }

      15% {
        transform: rotate(15deg);
      }

      30% {
        transform: rotate(-13deg);
      }

      45% {
        transform: rotate(10deg);
      }

      60% {
        transform: rotate(-8deg);
      }

      75% {
        transform: rotate(5deg);
      }

      90% {
        transform: rotate(-3deg);
      }

      100% {
        transform: rotate(0deg);
      }
    }

    .bell-shake {
      display: inline-block;
      animation: bellShake 0.6s ease;
      animation-iteration-count: 3;
      transform-origin: top center;
    }

    .bell-pulse {
      border-radius: 50%;
      animation: pulseRing 1.2s ease-out infinite;
    }

    @keyframes badgeBounce {

      0%,
      100% {
        transform: translateY(0) scale(1);
      }

      50% {
        transform: translateY(-3px) scale(1.15);
      }
    }

    .badge-bounce {
      animation: badgeBounce 0.8s ease infinite;
    }
  </style>

  <script type="text/javascript">
    var baseurl = "<?= base_url(); ?>";
    var siteurl = "<?= site_url(); ?>";
    var base_url = siteurl;
    var active_controller = "<?= $this->uri->segment(1); ?>/";
    var active_function = "<?= $this->uri->segment(2); ?>/";

    window.addEventListener("load", function() {
      const loader = document.getElementById("app-loader");
      if (!loader) return;

      loader.classList.add("hide");
      setTimeout(() => loader.remove(), 320);
    });

    // fail safe: kalau load stuck, loader hilang 6 detik
    setTimeout(() => {
      const loader = document.getElementById("app-loader");
      if (loader) loader.remove();
    }, 6000);
  </script>


  <script>
    const notifTypeIcon = {
      0: {
        icon: 'ti-ticket',
        color: 'text-primary',
        label: 'Ticket Baru'
      },
      1: {
        icon: 'ti-refresh',
        color: 'text-info',
        label: 'Status Update'
      },
      2: {
        icon: 'ti-user-check',
        color: 'text-success',
        label: 'Assigned'
      },
      3: {
        icon: 'ti-checkup-list',
        color: 'text-warning',
        label: 'Approval'
      },
      4: {
        icon: 'ti-circle-check',
        color: 'text-success',
        label: 'Approved'
      },
      5: {
        icon: 'ti-edit',
        color: 'text-secondary',
        label: 'Diupdate'
      },
    };

    function timeAgo(dateStr) {
      const now = new Date();
      const past = new Date(dateStr);
      const diff = Math.floor((now - past) / 1000);
      if (diff < 60) return diff + ' detik lalu';
      if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
      if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
      return Math.floor(diff / 86400) + ' hari lalu';
    }

    function loadNotifications() {
      $.ajax({
        url: siteurl + 'users/get_notifications',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
          if (!res || res.status !== 1) return;

          const count = res.unread_count;

          // Update badge
          if (count > 0) {
            $('#notif_badge').text(count > 99 ? '99+' : count).show();
            $('#notif_badge').addClass('badge-bounce');

            // Bell shake
            const $bell = $('#notif_bell i');
            $bell.removeClass('bell-shake');
            void $bell[0].offsetWidth;
            $bell.addClass('bell-shake');
            setTimeout(() => $bell.removeClass('bell-shake'), 1800);
            $('#notif_bell').addClass('bell-pulse');
          } else {
            $('#notif_badge').hide().removeClass('badge-bounce');
            $('#notif_bell i').removeClass('bell-shake');
            $('#notif_bell').removeClass('bell-pulse');
          }

          $('#notif_count_label').text(count);

          // Render list
          const list = res.notifications;
          if (!list || list.length === 0) {
            $('#notif_list').html(`
                    <div class="text-center py-4 text-muted">
                        <i class="ti ti-bell-off" style="font-size:32px;"></i>
                        <p class="mt-2 mb-0">Belum ada notifikasi</p>
                    </div>
                `);
            return;
          }

          let html = '';
          list.forEach(function(n) {
            const meta = notifTypeIcon[n.type] || notifTypeIcon[5];
            const bg = n.is_read == 0 ? 'style="background:#f0f7ff;"' : '';
            const bold = n.is_read == 0 ? 'fw-semibold' : '';

            html += `
                    <div class="list-group-item list-group-item-action notif-item"
                         data-id="${n.id}" data-ticket-id="${n.helpdesk_id}" ${bg}
                         style="cursor:pointer;">
                        <div class="d-flex align-items-start gap-2">
                            <div class="flex-shrink-0 mt-1">
                                <span class="avatar avatar-s rounded-circle bg-light-primary">
                                    <i class="ti ${meta.icon} ${meta.color}"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 ${bold}" style="font-size:13px;">${n.message}</p>
                                <small class="text-muted">${timeAgo(n.created_at)}</small>
                                ${n.is_read == 0 ? '<span class="badge bg-primary ms-1" style="font-size:9px;">Baru</span>' : ''}
                            </div>
                        </div>
                    </div>
                `;
          });

          $('#notif_list').html(html);
          $('#notif_loading').hide();
        }
      });
    }

    $(document).on('click', '.notif-item', function(e) {
      e.stopPropagation();
      e.preventDefault();

      const id = $(this).data('id');
      const ticketId = $(this).data('ticket-id');

      $.post(siteurl + 'users/mark_notification_read', {
        id: id
      }, function() {
        window.location.href = siteurl + 'ticket/view_ticket/' + ticketId;
      });
    });

    // Mark all read
    $(document).on('click', '#btn_mark_all_read', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $.post(siteurl + 'users/mark_notification_read', {
        id: 'all'
      }, function(res) {
        // console.log(res); 
        loadNotifications();
      });
    });

    // Load saat dropdown dibuka
    $('#notif_dropdown').on('show.bs.dropdown', function() {
      loadNotifications();
    });

    // Auto-refresh setiap 60 detik
    loadNotifications();
    setInterval(loadNotifications, 60000);
  </script>
</head>

<body>

  <!-- [ Pre-loader ] start -->
  <div id="app-loader" class="app-loader">
    <div class="app-loader__card">
      <div class="app-loader__spinner"></div>

      <div class="app-loader__text">
        Loading
        <span class="loader-dots">
          <span></span><span></span><span></span>
        </span>
      </div>
    </div>
  </div>

  <!-- [ Pre-loader ] End -->


  <!-- =========================
       SIDEBAR BERRY (ASLI)
       ========================= -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="<?= site_url(); ?>" class="b-brand text-decoration-none">
          <img src="<?= base_url('assets/images/logo-topbar.png'); ?>" alt="logo" style="height:40px; width:auto;">
          <span class="b-title ms-2"><?= isset($idt->nm_perusahaan) ? $idt->nm_perusahaan : 'not-set'; ?></span>
        </a>
      </div>

      <div class="navbar-content">
        <?= $this->menu_generator->build_menus(); ?>
      </div>
    </div>
  </nav>

  <!-- =========================
       HEADER TOPBAR BERRY
       ========================= -->
  <header class="pc-header">
    <div class="header-wrapper px-4">
      <!-- left -->
      <div class="me-auto">
        <ul class="list-unstyled mb-0 d-flex align-items-center">

          <!-- MOBILE: buka sidebar overlay -->
          <li class="pc-h-item d-lg-none">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i data-feather="menu"></i>
            </a>
          </li>

          <!-- DESKTOP: collapse/minify sidebar -->
          <li class="pc-h-item d-none d-lg-inline-flex">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
              <i data-feather="menu"></i>
            </a>
          </li>

        </ul>
      </div>

      <!-- right -->
      <div class="ms-auto">
        <ul class="list-unstyled">

          <!-- NOTIFICATION -->
          <li class="dropdown pc-h-item" id="notif_dropdown">
            <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none me-0"
              data-bs-toggle="dropdown"
              data-bs-auto-close="outside"
              href="#" role="button"
              aria-haspopup="false" aria-expanded="false"
              id="notif_bell">
              <i class="ti ti-bell"></i>
              <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                id="notif_badge"
                style="display:none; font-size:10px; padding: 2px 5px;">0</span>
            </a>

            <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown" style="width: 360px;">
              <div class="dropdown-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Notifikasi
                  <span class="badge bg-warning rounded-pill ms-1" id="notif_count_label">0</span>
                </h5>
                <a href="#!" class="link-primary text-decoration-underline small" id="btn_mark_all_read">
                  Tandai semua dibaca
                </a>
              </div>

              <div class="dropdown-header px-0 text-wrap position-relative"
                style="max-height: 400px; overflow-y: auto;" id="notif_list_wrapper">
                <div class="list-group list-group-flush w-100" id="notif_list">
                  <div class="text-center py-4 text-muted" id="notif_loading">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    Loading...
                  </div>
                </div>
              </div>

              <div class="dropdown-divider"></div>
              <div class="text-center py-2">
                <a href="<?= site_url('ticket') ?>" class="link-primary small">Lihat semua ticket</a>
              </div>
            </div>
          </li>

          <!-- USER PROFILE -->
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
              data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">

              <img
                src="<?= (isset($userData->photo) && file_exists('assets/images/users/' . $userData->photo))
                        ? base_url('assets/images/users/' . $userData->photo)
                        : base_url('assets/images/male-def.png'); ?>"
                alt="user-image"
                class="user-avtar" />

              <span>
                <i class="ti ti-settings"></i>
              </span>
            </a>

            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header">

                <h4 class="mb-0">
                  <span id="greetingText">Hello</span>,
                  <span class="small text-muted">
                    <?= isset($userData->nm_lengkap) ? ucwords($userData->nm_lengkap) : $userData->username; ?>
                  </span>
                </h4>

                <p class="text-muted mb-2 d-flex align-items-center gap-2">
                  <i class="ti ti-calendar"></i>
                  <span id="liveDateTime">--</span>
                </p>

                <p class="text-muted mb-2">
                  <?= isset($userData->groups) ? $userData->groups : '-'; ?>
                </p>

                <hr>

                <div class="profile-notification-scroll position-relative"
                  style="max-height: calc(100vh - 280px)" data-simplebar="init">

                  <a href="<?= site_url('profile'); ?>" class="dropdown-item">
                    <i class="ti ti-user"></i>
                    <span>Profile</span>
                  </a>

                  <!-- <a href="#" class="dropdown-item">
                    <i class="ti ti-settings"></i>
                    <span>Account Settings</span>
                  </a> -->

                  <a href="<?= site_url('users/logout'); ?>" class="dropdown-item text-danger">
                    <i class="ti ti-logout"></i>
                    <span>Logout</span>
                  </a>

                </div>
              </div>
            </div>
          </li>

        </ul>
      </div>

    </div>
  </header>

  <!-- =========================
       CONTENT AREA BERRY
       ========================= -->
  <div class="pc-container">
    <div class="pc-content">

      <!-- Page header -->
      <div class="card mb-3">
        <div class="card-body py-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

            <!-- LEFT: Title -->
            <div>
              <h3 class="mb-0"><?= isset($template['title']) ? $template['title'] : 'Dashboard'; ?></h3>
            </div>

            <!-- RIGHT: Breadcrumb -->
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 align-items-center">
                <li class="breadcrumb-item">
                  <a href="<?= site_url(); ?>" class="text-decoration-none d-flex align-items-center gap-1">
                    <i class="ti ti-home"></i> Home
                  </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  <?= isset($template['title']) ? $template['title'] : 'Dashboard'; ?>
                </li>
              </ol>
            </nav>

          </div>
        </div>
      </div>


      <!-- ✅ MAIN CONTENT START -->
      <div class="row">
        <div class="col-12">