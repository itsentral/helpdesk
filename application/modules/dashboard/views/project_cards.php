<style>
    /* Container Grid */
    .pd-project-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 12px;
        padding: 16px;
    }

    /* Card Utama */
    .pd-project-card {
        position: relative;
        background: #fff;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }

    .pd-project-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
    }

    /* Accent bar di atas card */
    .pd-card-accent {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }

    /* Header Card */
    .pd-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin: 6px 0 10px;
    }

    .pd-client-name {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #212529;
        line-height: 1.3;
        flex: 1;
        padding-right: 8px;
    }

    .pd-ticket-count {
        font-size: 20px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* Badge area */
    .pd-badge-container {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 10px;
    }

    /* Progress Bar */
    .pd-progress-wrapper {
        height: 4px;
        background: #f1f3f5;
        border-radius: 4px;
        overflow: hidden;
    }

    .pd-progress-bar {
        height: 100%;
        background: #27AE60;
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    /* Footer Card */
    .pd-card-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
    }

    .pd-footer-text {
        font-size: 10px;
        color: #6c757d;
    }

    .pd-click-hint {
        font-size: 10px;
        color: #adb5bd;
    }
</style>

<?php if (empty($clients)): ?>
    <div class="pd-empty">
        <i class="ti ti-mood-smile"></i>
        <p>Tidak ada data project ditemukan.</p>
    </div>
<?php else: ?>
    <div class="pd-project-grid">
        <?php foreach ($clients as $i => $c):
            $total      = (int)$c['total'];
            $done_pct   = $total > 0 ? round($c['done'] / $total * 100) : 0;

            // Logic warna accent
            $accent_color = ($total === 0) ? '#dee2e6' : ($c['open'] > 0 ? '#E24B4A' : '#27AE60');
            $count_color  = ($total === 0) ? '#adb5bd' : '#534AB7';
            $card_opacity = ($total === 0) ? '0.6' : '1';
        ?>
            <div class="pd-project-card"
                style="opacity: <?= $card_opacity ?>;"
                onclick="pdOpenModal(<?= $i ?>)">

                <div class="pd-card-accent" style="background: <?= $accent_color ?>;"></div>

                <div class="pd-card-header">
                    <p class="pd-client-name">
                        <?= htmlspecialchars($c['client_name']) ?>
                    </p>
                    <span class="pd-ticket-count" style="color: <?= $count_color ?>;">
                        <?= $total ?>
                    </span>
                </div>

                <div class="pd-badge-container">
                    <?php if ($total === 0): ?>
                        <span class="pd-badge" style="background:#f1f3f5; color:#adb5bd;">Tidak ada tiket</span>
                    <?php endif; ?>

                    <?php if ($c['open']):    ?><span class="pd-badge" style="background:#FCEBEB; color:#A32D2D;">Open <?= $c['open'] ?></span><?php endif; ?>
                    <?php if ($c['process']): ?><span class="pd-badge" style="background:#E6F1FB; color:#185FA5;">Process <?= $c['process'] ?></span><?php endif; ?>
                    <?php if ($c['pending']): ?><span class="pd-badge" style="background:#FAEEDA; color:#854F0B;">Pending <?= $c['pending'] ?></span><?php endif; ?>
                    <?php if ($c['done']):    ?><span class="pd-badge" style="background:#E9F7EF; color:#196F3D;">Done <?= $c['done'] ?></span><?php endif; ?>
                    <?php if ($c['revisi']):  ?><span class="pd-badge" style="background:#F4C0D1; color:#72243E;">Revisi <?= $c['revisi'] ?></span><?php endif; ?>
                </div>

                <div class="pd-progress-wrapper">
                    <div class="pd-progress-bar" style="width: <?= $done_pct ?>%;"></div>
                </div>

                <div class="pd-card-footer">
                    <span class="pd-footer-text">
                        <?= ($total === 0) ? 'Belum ada tiket' : $done_pct . '% selesai' ?>
                    </span>
                    <span class="pd-click-hint">Klik detail →</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>