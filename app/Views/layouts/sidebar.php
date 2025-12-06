<?php
// letakkan ini sebelum pemakaian $uri
$uri = service('uri');
$segment1 = $uri->getSegment(1);
$segment2 = $uri->getSegment(2);
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

                    <li class="sidebar-item <?= ($segment1 == 'user-management') ? 'active' : '' ?>">
                        <a href="<?= base_url('user-management'); ?>" class="sidebar-link">
                            <i class="bi bi-person-gear"></i>
                            <span>Management User</span>
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

                            <li class="submenu-item <?= ($segment2 == 'consignee') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/consignee') ?>"><i class="bi bi-person-vcard me-1"></i> Consignee</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'pelayaran') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/pelayaran') ?>"><i class="bi bi-building me-1"></i> Pelayaran</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'port') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/port') ?>"><i class="bi bi-geo me-1"></i> Pelabuhan</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'notify-party') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/notify-party') ?>"><i class="bi bi-people me-1"></i> Notify Party</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'vessel') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/vessel') ?>"><i class="bi bi-life-preserver me-1"></i> Vessel</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'lokasi-sandar') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lokasi-sandar') ?>"><i class="bi bi-map me-1"></i> Lokasi Sandar</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'kemasan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/kemasan') ?>"><i class="bi bi-boxes me-1"></i> Kemasan</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'lartas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lartas') ?>"><i class="bi bi-clipboard2-check me-1"></i> Lartas</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'fasilitas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/fasilitas') ?>"><i class="bi bi-file-medical me-1"></i> Fasilitas</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'info-tambahan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/info-tambahan') ?>"><i class="bi bi-clipboard-plus me-1"></i> Informasi Tambahan</a>
                            </li>

                        </ul>
                    </li>

                    <!-- ====================== Recycle Bin ====================== -->
                    <li class="sidebar-item has-sub 
                        <?= in_array($segment1, ['booking-job-trash', 'worksheet-import-trash', 'worksheet-export-trash']) ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-trash"></i>
                            <span>Recycle Bin</span>
                        </a>

                        <ul class="submenu" <?= in_array($segment1, ['booking-job-trash', 'worksheet-import-trash', 'worksheet-export-trash']) ? 'style="display:block;"' : '' ?>>
                            <li class="submenu-item <?= ($segment2 == 'booking-job-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('booking-job-trash') ?>"><i class="bi bi-calendar-check-fill"></i> Booking Job</a>
                            </li>
                            <li class="submenu-item <?= ($segment2 == 'worksheet-import-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('worksheet-import-trash') ?>"><i class="bi bi-journal-text"></i> WS Import</a>
                            </li>
                            <li class="submenu-item <?= ($segment2 == 'worksheet-export-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('worksheet-export-trash') ?>"><i class="bi bi-journal-text"></i> WS Export</a>
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

                <!-- Role exim -->
                <?php if (in_groups('exim')): ?>
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

                    <!-- ====================== MASTER DATA ====================== -->
                    <li class="sidebar-title">Database</li>

                    <li class="sidebar-item has-sub 
                        <?= ($segment1 == 'master-data') ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-folder2-open"></i>
                            <span>Master Data</span>
                        </a>

                        <ul class="submenu" <?= ($segment1 == 'master-data') ? 'style="display:block;"' : '' ?>>

                            <li class="submenu-item <?= ($segment2 == 'consignee') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/consignee') ?>"><i class="bi bi-person-vcard me-1"></i> Consignee</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'pelayaran') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/pelayaran') ?>"><i class="bi bi-building me-1"></i> Pelayaran</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'port') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/port') ?>"><i class="bi bi-geo me-1"></i> Pelabuhan</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'notify-party') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/notify-party') ?>"><i class="bi bi-people me-1"></i> Notify Party</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'vessel') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/vessel') ?>"><i class="bi bi-life-preserver me-1"></i> Vessel</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'lokasi-sandar') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lokasi-sandar') ?>"><i class="bi bi-map me-1"></i> Lokasi Sandar</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'kemasan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/kemasan') ?>"><i class="bi bi-boxes me-1"></i> Kemasan</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'lartas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lartas') ?>"><i class="bi bi-clipboard2-check me-1"></i> Lartas</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'fasilitas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/fasilitas') ?>"><i class="bi bi-file-medical me-1"></i> Fasilitas</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'info-tambahan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/info-tambahan') ?>"><i class="bi bi-clipboard-plus me-1"></i> Informasi Tambahan</a>
                            </li>

                        </ul>
                    </li>

                    <!-- ====================== Recycle Bin ====================== -->
                    <li class="sidebar-item has-sub 
                        <?= in_array($segment1, ['booking-job-trash', 'worksheet-import-trash', 'worksheet-export-trash']) ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-trash"></i>
                            <span>Recycle Bin</span>
                        </a>

                        <ul class="submenu" <?= in_array($segment1, ['booking-job-trash', 'worksheet-import-trash', 'worksheet-export-trash']) ? 'style="display:block;"' : '' ?>>
                            <li class="submenu-item <?= ($segment2 == 'booking-job-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('booking-job-trash') ?>"><i class="bi bi-calendar-check-fill"></i> Booking Job</a>
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


                <!-- Role document -->
                <?php if (in_groups('document')): ?>
                    <li class="sidebar-title">Menu Staff Document</li>

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

                    <!-- ====================== MASTER DATA ====================== -->
                    <li class="sidebar-title">Database</li>

                    <li class="sidebar-item has-sub 
                        <?= ($segment1 == 'master-data') ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-folder2-open"></i>
                            <span>Master Data</span>
                        </a>

                        <ul class="submenu" <?= ($segment1 == 'master-data') ? 'style="display:block;"' : '' ?>>

                            <li class="submenu-item <?= ($segment2 == 'consignee') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/consignee') ?>"><i class="bi bi-person-vcard me-1"></i> Consignee</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'pelayaran') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/pelayaran') ?>"><i class="bi bi-building me-1"></i> Pelayaran</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'port') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/port') ?>"><i class="bi bi-geo me-1"></i> Pelabuhan</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'notify-party') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/notify-party') ?>"><i class="bi bi-people me-1"></i> Notify Party</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'vessel') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/vessel') ?>"><i class="bi bi-life-preserver me-1"></i> Vessel</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'lokasi-sandar') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lokasi-sandar') ?>"><i class="bi bi-map me-1"></i> Lokasi Sandar</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'kemasan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/kemasan') ?>"><i class="bi bi-boxes me-1"></i> Kemasan</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'lartas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/lartas') ?>"><i class="bi bi-clipboard2-check me-1"></i> Lartas</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'fasilitas') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/fasilitas') ?>"><i class="bi bi-file-medical me-1"></i> Fasilitas</a>
                            </li>

                            <li class="submenu-item <?= ($segment2 == 'info-tambahan') ? 'active' : '' ?>">
                                <a href="<?= base_url('master-data/info-tambahan') ?>"><i class="bi bi-clipboard-plus me-1"></i> Informasi Tambahan</a>
                            </li>

                        </ul>
                    </li>

                    <!-- ====================== Recycle Bin ====================== -->
                    <li class="sidebar-item has-sub 
                        <?= in_array($segment1, ['booking-job-trash', 'worksheet-import-trash', 'worksheet-export-trash']) ? 'active' : '' ?>">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-trash"></i>
                            <span>Recycle Bin</span>
                        </a>

                        <ul class="submenu" <?= in_array($segment1, ['worksheet-import-trash', 'worksheet-export-trash']) ? 'style="display:block;"' : '' ?>>
                            <li class="submenu-item <?= ($segment2 == 'worksheet-import-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('worksheet-import-trash') ?>"><i class="bi bi-journal-text"></i> WS Import</a>
                            </li>
                            <li class="submenu-item <?= ($segment2 == 'worksheet-export-trash') ? 'active' : '' ?>">
                                <a href="<?= base_url('worksheet-export-trash') ?>"><i class="bi bi-journal-text"></i> WS Export</a>
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

            </ul>
        </div>

        <button class="sidebar-toggler btn x">
            <i data-feather="x"></i>
        </button>
    </div>
</div>