<header class="mb-4">
    <nav class="navbar navbar-expand navbar-light navbar-top shadow-sm bg-white">
        <div class="container-fluid">
            <!-- Hamburger menu (sidebar toggle) -->
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <div class="d-flex align-items-center ms-auto">
                <!-- User Profile -->
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" class="d-flex align-items-center">
                        <img src="/assets/images/faces/1.jpg" class="rounded-circle me-2" style="width:35px;height:35px;">
                        <span class="fw-bold"><?= user()->username ?? 'User' ?></span>
                        <i id="profileIcon" class="bi bi-caret-up-square ms-2 mb-2"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.querySelector(".dropdown");
    const icon = document.getElementById("profileIcon");

    dropdown.addEventListener("show.bs.dropdown", function () {
        icon.classList.remove("bi-caret-up-square");
        icon.classList.add("bi-caret-down-square");
    });

    dropdown.addEventListener("hide.bs.dropdown", function () {
        icon.classList.remove("bi-caret-down-square");
        icon.classList.add("bi-caret-up-square");
    });
});
</script>
