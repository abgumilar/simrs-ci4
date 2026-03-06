<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - SIMRS Premium</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Premium Custom CSS -->
    <link href="<?= base_url('css/premium.css') ?>" rel="stylesheet">
</head>
<body>

<?php
    $currentEnv = session()->get('active_env') ?? 'Pelayanan';
    $menus = get_menus($currentEnv);
?>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <div class="bg-primary p-2 rounded me-2">
            <i class="fas fa-hospital-alt text-white"></i>
        </div>
        <h5 class="mb-0 text-white fw-bold">SIMRS <span class="text-primary">PRO</span></h5>
    </div>
    
    <div class="mt-4">
        <div class="text-muted small fw-bold text-uppercase px-4 mb-2"><?= $currentEnv ?></div>
        <nav>
            <?php foreach ($menus as $m): ?>
                <a href="<?= base_url($m['url']) ?>" class="nav-link <?= (isset($activeMenu) && $activeMenu == $m['url'] ? 'active' : '') ?>">
                    <i class="<?= $m['icon'] ?>"></i>
                    <span><?= $m['title'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="position-absolute bottom-0 w-100 p-3 border-top border-secondary">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-secondary rounded-circle p-2 me-2">
                <i class="fas fa-user text-white"></i>
            </div>
            <div class="text-white small">
                <div class="fw-bold"><?= session()->get('fullname') ?? 'Petugas SIMRS' ?></div>
                <div class="text-muted" style="font-size: 10px;"><?= session()->get('role') ?? 'Guest' ?></div>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm w-100">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
    </div>
</aside>

<!-- Topbar -->
<header class="topbar d-flex align-items-center justify-content-between">
    <div class="env-switcher">
        <?php foreach (['Pelayanan', 'Penunjang', 'Administrasi', 'Sistem'] as $env): ?>
            <a href="<?= base_url('switch-env/' . $env) ?>" class="env-btn <?= ($currentEnv == $env ? 'active' : '') ?>">
                <?= $env ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="search-badge d-none d-md-block">
            <i class="fas fa-search me-1"></i> Search <span class="ms-2">Ctrl+K</span>
        </div>
        
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle p-0 text-dark" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">No new notifications</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Main Container -->
<main class="main-content">
    <!-- Dynamic Tabs (Concept) -->
    <div class="tab-bar">
        <div class="tab-item active">
            <i class="fas fa-home"></i> Dashboard
        </div>
        <!-- Future tabs will go here -->
    </div>

    <div class="container-fluid p-0">
        <?= $this->renderSection('content') ?>
    </div>
<!-- Command Palette Modal -->
<div class="modal fade" id="commandPalette" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-body p-0">
                <div class="p-3 border-bottom d-flex align-items-center">
                    <i class="fas fa-search text-muted me-3"></i>
                    <input type="text" id="paletteSearch" class="form-control border-0 shadow-none p-0" placeholder="Ketik untuk mencari menu atau data pasien..." autoFocus>
                    <span class="badge bg-light text-muted border ms-2">ESC</span>
                </div>
                <div class="p-2" id="paletteResults" style="max-height: 400px; overflow-y: auto;">
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-keyboard fa-2x mb-2 opacity-25"></i>
                        <p class="small mb-0">Mulai mengetik untuk mencari...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tabbed Interface Logic
    let activeTabs = JSON.parse(localStorage.getItem('simrs_tabs')) || [
        { title: 'Dashboard', url: '<?= base_url('dashboard') ?>', active: true, icon: 'fas fa-home' }
    ];

    function renderTabs() {
        const tabBar = document.querySelector('.tab-bar');
        if (!tabBar) return;
        
        tabBar.innerHTML = activeTabs.map((tab, index) => `
            <div class="tab-item ${tab.active ? 'active' : ''}" onclick="window.location.href='${tab.url}'">
                <i class="${tab.icon}"></i>
                <span>${tab.title}</span>
                ${index > 0 ? `<i class="fas fa-times close-tab ms-2" onclick="event.stopPropagation(); closeTab(${index})"></i>` : ''}
            </div>
        `).join('');
    }

    function addTab(title, url, icon) {
        activeTabs.forEach(t => t.active = false);
        const exists = activeTabs.find(t => t.url === url);
        if (!exists) {
            activeTabs.push({ title, url, icon, active: true });
        } else {
            exists.active = true;
        }
        localStorage.setItem('simrs_tabs', JSON.stringify(activeTabs));
    }

    function closeTab(index) {
        if (activeTabs[index].active) {
            window.location.href = activeTabs[0].url;
        }
        activeTabs.splice(index, 1);
        localStorage.setItem('simrs_tabs', JSON.stringify(activeTabs));
        renderTabs();
    }

    // Command Palette Logic
    const paletteModal = new bootstrap.Modal(document.getElementById('commandPalette'));
    
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            paletteModal.show();
        }
    });

    document.getElementById('commandPalette').addEventListener('shown.bs.modal', function () {
        document.getElementById('paletteSearch').focus();
    });

    // Auto-tab for current page
    const currentTitle = "<?= $title ?? 'Menu' ?>";
    const currentUrl = window.location.href;
    const currentIcon = "<?= $activeIcon ?? 'fas fa-circle-notch' ?>";
    
    if (window.location.pathname !== '/simrs/' && window.location.pathname !== '/simrs/dashboard') {
        addTab(currentTitle, currentUrl, currentIcon);
    }
    renderTabs();
</script>
</body>
</html>
