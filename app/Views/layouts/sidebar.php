<?php
// letakkan ini sebelum pemakaian $uri
$uri = service('uri');
$segment1 = $uri->getSegment(1);
?>


<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="<?= base_url('dashboard'); ?>">
                        <img src="<?= base_url('assets/images/logo/logo.png'); ?>" alt="Logo">
                    </a>
                </div>
                <a href="#" class="sidebar-hide d-block">
                    <i class="bi bi-x bi-middle"></i>
                </a>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">

                <!-- ====================== ROLE ADMIN ====================== -->
                <?php if (in_groups('admin')): ?>
                    <li class="sidebar-title">Menu Admin</li>

                    <li class="sidebar-item <?= ($segment1 == 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard') ?>" class="sidebar-link">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'booking-job') ? 'active' : '' ?>">
                        <a href="<?= base_url('booking-job') ?>" class="sidebar-link">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Booking Job</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'worksheet') ? 'active' : '' ?>">
                        <a href="<?= base_url('worksheet'); ?>" class="sidebar-link">
                            <i class="bi bi-journal-text"></i>
                            <span>Worksheet</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="<?= base_url('debit-note'); ?>" class="sidebar-link">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span>Debit Note</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="<?= base_url('e-arsip'); ?>" class="sidebar-link">
                            <i class="bi bi-archive-fill"></i>
                            <span>E-Arsip</span>
                        </a>
                    </li>

                    <!-- ====================== MASTER DATA ====================== -->
                    <li class="sidebar-title">Database</li>

                    <li class="sidebar-item has-sub 
                        <?= ($segment1 == 'master-data') ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-folder2-open"></i>
                            <span>Master Data</span>
                        </a>

                        <ul class="submenu" <?= ($segment1 == 'master-data') ? 'style="display:block;"' : '' ?>>

                            <li class="submenu-item <?= ($segment1 == 'consignee') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/consignee') ?>">Consignee</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'pelayaran') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/pelayaran') ?>">Pelayaran</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'port') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/port') ?>">Port</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'notify-party') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/notify-party') ?>">Notify Party</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'vessel') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/vessel') ?>">Vessel</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'lokasi-sandar') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lokasi-sandar') ?>">Lokasi Sandar</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'kemasan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/kemasan') ?>">Kemasan</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'lartas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lartas') ?>">Lartas</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'fasilitas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/fasilitas') ?>">Fasilitas</a>
                            </li>

                            <li class="submenu-item <?= ($segment1 == 'informasi-tambahan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/informasi-tambahan') ?>">Informasi Tambahan</a>
                            </li>

                        </ul>
                    </li>

                    <!-- ====================== SAMPAH ====================== -->
                    <li class="sidebar-item has-sub 
                        <?= in_array($segment1, ['booking-job-trash','worksheet-import-trash','worksheet-export-trash']) ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-trash"></i>
                            <span>Sampah</span>
                        </a>

                        <ul class="submenu" <?= in_array($segment1, ['booking-job-trash','worksheet-import-trash','worksheet-export-trash']) ? 'style="display:block;"' : '' ?>>
                            <li class="submenu-item <?= ($segment1 == 'booking-job-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('booking-job-trash') ?>">Booking Job</a>
                            </li>
                            <li class="submenu-item <?= ($segment1 == 'worksheet-import-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('worksheet-import-trash') ?>">Worksheet Import</a>
                            </li>
                            <li class="submenu-item <?= ($segment1 == 'worksheet-export-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('worksheet-export-trash') ?>">Worksheet Export</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-title">Account</li>
                    <?php if (logged_in()): ?>
                        <li class="sidebar-item">
                            <a href="<?= base_url('logout'); ?>" class="sidebar-link">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>

                <!-- Role Staff -->
                <?php if (in_groups('staff')): ?>
                    <li class="sidebar-title">Menu Staff Exim</li>

                    <li class="sidebar-item <?= ($segment1 == 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard') ?>" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'booking-job') ? 'active' : '' ?>">
                        <a href="<?= base_url('booking-job'); ?>" class="sidebar-link">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Booking Job</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'worksheet') ? 'active' : '' ?>">
                        <a href="<?= base_url('worksheet'); ?>" class="sidebar-link">
                            <i class="bi bi-journal-text"></i>
                            <span>Worksheet</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'e-arsip') ? 'active' : '' ?>">
                        <a href="<?= base_url('e-arsip'); ?>" class="sidebar-link">
                            <i class="bi bi-archive-fill"></i>
                            <span>E-Arsip</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'master-data') ? 'active' : '' ?>">
                        <a href="<?= base_url('master-data'); ?>" class="sidebar-link">
                            <i class="bi bi-folder2-open"></i>
                            <span>Master Data</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Account</li>
                    <?php if (logged_in()): ?>
                        <li class="sidebar-item">
                            <a href="<?= base_url('logout'); ?>" class="sidebar-link">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>


                <!-- Role Accounting -->
                <?php if (in_groups('accounting')): ?>
                    <li class="sidebar-title">Menu Staff Accounting</li>

                    <li class="sidebar-item <?= ($segment1 == 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard') ?>" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'worksheet') ? 'active' : '' ?>">
                        <a href="<?= base_url('worksheet'); ?>" class="sidebar-link">
                            <i class="bi bi-journal-text"></i>
                            <span>Worksheet</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'debit-note') ? 'active' : '' ?>">
                        <a href="<?= base_url('debit-note'); ?>" class="sidebar-link">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span>Debit Note</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'e-arsip') ? 'active' : '' ?>">
                        <a href="<?= base_url('e-arsip'); ?>" class="sidebar-link">
                            <i class="bi bi-archive-fill"></i>
                            <span>E-Arsip</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($segment1 == 'master-data') ? 'active' : '' ?>">
                        <a href="<?= base_url('master-data'); ?>" class="sidebar-link">
                            <i class="bi bi-folder2-open"></i>
                            <span>Master Data</span>
                        </a>
                    </li>

                    <li class="sidebar-title">Account</li>
                    <?php if (logged_in()): ?>
                        <li class="sidebar-item">
                            <a href="<?= base_url('logout'); ?>" class="sidebar-link">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>




            </ul>
        </div>

        <button class="sidebar-toggler btn x">
            <i data-feather="x"></i>
        </button>
    </div>
</div>