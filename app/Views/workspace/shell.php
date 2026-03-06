<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - RSUD Ciamis</title>
    
    <!-- Google Fonts: Roboto & Inter -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --fortress-blue: #00a651; /* RSUD Ciamis Green */
            --fortress-violet: #8b5cf6; /* RSUD Ciamis Violet */
            --fortress-bg: #f8fafc;
            --sidebar-width: 260px;
            --header-height: 64px;
            --tab-height: 48px;
        }

        body {
            font-family: 'Inter', 'Roboto', sans-serif;
            background-color: var(--fortress-bg);
            overflow: hidden;
            height: 100vh;
            margin: 0;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #1e293b; /* Dark theme sidebar */
            border-right: 1px solid rgba(255,255,255,0.05);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            height: var(--header-height);
            background-color: #0f172a;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 15px 0;
        }

        /* Launcher Button */
        .btn-launcher {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            transition: all 0.2s;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-launcher:hover {
            background: var(--fortress-blue);
            border-color: var(--fortress-blue);
            color: white;
        }

        .nav-link {
            padding: 12px 25px;
            color: #94a3b8;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-radius: 0;
            transition: all 0.2s;
            cursor: pointer;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
        }

        .nav-link.active {
            background-color: rgba(0, 166, 81, 0.1);
            color: var(--fortress-blue);
            font-weight: 600;
            border-right: 3px solid var(--fortress-blue);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
            opacity: 0.7;
        }

        .nav-section-title {
            padding: 20px 25px 10px;
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Main Content Styling */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            z-index: 1001;
            position: sticky;
            top: 0;
        }

        /* Tab Bar Styling */
        #tab-bar-wrapper {
            height: var(--tab-height);
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 5;
            padding: 0 5px;
        }

        #tab-bar {
            flex: 1;
            height: 100%;
            display: flex;
            align-items: center;
            overflow-x: auto;
            scroll-behavior: smooth;
            white-space: nowrap;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        #tab-bar::-webkit-scrollbar { display: none; }

        .tab-scroll-btn {
            width: 30px;
            height: 70%;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            display: none;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            z-index: 10;
            margin: 0 5px;
        }

        .fortress-tab {
            height: 38px;
            display: inline-flex;
            align-items: center;
            padding: 0 16px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-right: 8px;
            transition: all 0.2s;
            position: relative;
        }

        .fortress-tab:hover {
            border-color: var(--fortress-blue);
            color: var(--fortress-blue);
        }

        .fortress-tab.active {
            background-color: var(--fortress-blue);
            color: white;
            border-color: var(--fortress-blue);
            box-shadow: 0 4px 6px -1px rgba(0, 166, 81, 0.2);
        }

        .close-tab {
            margin-left: 10px;
            font-size: 12px;
            opacity: 0.5;
            transition: all 0.2s;
        }

        .fortress-tab.active .close-tab { opacity: 0.8; }
        .close-tab:hover { color: #ef4444; opacity: 1 !important; transform: scale(1.2); }

        /* Content Area */
        #content-area {
            flex: 1;
            overflow-y: auto;
            background: var(--fortress-bg);
        }

        .tab-pane { display: none; height: 100%; }
        .tab-pane.active { display: block; }

        /* Module Launcher Modal */
        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .module-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .module-item:hover {
            border-color: var(--fortress-blue);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            transform: translateY(-5px);
        }

        .module-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-size: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .module-name {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            color: #1e293b;
            letter-spacing: 0.5px;
        }

        /* Sidebar Toggle Logic */
        body.collapsed #sidebar { width: 80px; }
        body.collapsed #main-wrapper { margin-left: 80px; }
        body.collapsed .sidebar-header span,
        body.collapsed .nav-link span,
        body.collapsed .nav-section-title,
        body.collapsed .nav-link .toggle-icon,
        body.collapsed .launcher-text,
        body.collapsed .profile-info { display: none !important; }
        
        body.collapsed .sidebar-header { justify-content: center; padding: 0; }
        body.collapsed .nav-link { justify-content: center; padding: 12px 0; }
        body.collapsed .nav-link i { margin: 0 !important; }

        /* DateTime Styling */
        .live-datetime { text-align: center; line-height: 1.2; }
        .live-time { font-size: 18px; display: block; font-weight: 700; color: var(--fortress-blue); font-variant-numeric: tabular-nums; }
        .live-date { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Animations */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); width: 260px !important; }
            #main-wrapper { margin-left: 0 !important; }
            body.sidebar-open #sidebar { transform: translateX(0); }
            #sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; }
            body.sidebar-open #sidebar-overlay { display: block; }
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body>
    <div id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-hospital-user me-3 fs-4 text-white"></i>
            <span>RSUD Ciamis</span>
        </div>
        
        <div class="px-3 py-4">
            <button class="btn btn-launcher w-100 py-3 d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#moduleLauncher">
                <i class="fa-solid fa-table-cells-large"></i>
                <span class="launcher-text">Ganti Modul</span>
            </button>
        </div>

        <div class="sidebar-content">
            <div class="nav-section-title" id="active-env-label">Dashboard</div>
            <div id="sidebar-menu">
                <a href="javascript:void(0)" class="nav-link active" data-id="dashboard" data-title="Dashboard">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <div class="p-4 border-top border-secondary border-opacity-10 mt-auto">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success shadow-sm d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                    <?= strtoupper(substr($user['fullname'], 0, 1)) ?>
                </div>
                <div class="profile-info">
                    <div class="small fw-bold text-white"><?= $user['fullname'] ?></div>
                    <div class="text-muted" style="font-size: 10px; text-transform: uppercase;"><?= $user['role'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <header>
            <div class="d-flex align-items-center gap-4">
                <button id="sidebarToggle" class="btn btn-link text-dark p-0"><i class="fa-solid fa-bars-staggered fs-5"></i></button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small text-uppercase fw-bold text-muted" style="letter-spacing: 0.5px;">
                        <li class="breadcrumb-item">SIMRS</li>
                        <li class="breadcrumb-item active text-dark" id="breadcrumb-active">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <div class="live-datetime d-none d-md-block">
                <span class="live-time" id="live-time">00:00:00</span>
                <span class="live-date" id="live-date">Memuat Tanggal...</span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <div class="text-end d-none d-md-block">
                            <div class="small fw-bold lh-1"><?= explode(' ', $user['fullname'])[0] ?></div>
                            <div class="text-success" style="font-size: 10px; text-transform: uppercase; font-weight: 700;">Online</div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fa-solid fa-user-circle fs-4 text-muted"></i>
                        </div>
                        <i class="fa-solid fa-chevron-down small text-muted opacity-50"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 py-2" style="min-width: 220px;">
                        <li class="px-3 py-3 border-bottom mb-2 bg-light bg-opacity-50">
                            <div class="fw-bold text-dark small"><?= $user['fullname'] ?></div>
                            <div class="text-muted small" style="font-size: 0.7rem;"><?= $user['role'] ?></div>
                        </li>
                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openTab('profile', 'Profil Saya')"><i class="fa-solid fa-user-circle me-2 opacity-50"></i> Profil Saya</a></li>
                        <li><a class="dropdown-item py-2" href="javascript:void(0)"><i class="fa-solid fa-history me-2 opacity-50"></i> Log Aktivitas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="<?= base_url('logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout Sesi</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Tab Bar -->
        <div id="tab-bar-wrapper">
            <button class="tab-scroll-btn left" id="tab-scroll-left"><i class="fa-solid fa-chevron-left"></i></button>
            <div id="tab-bar">
                <div class="fortress-tab active" id="tab-dashboard" onclick="activateTab('dashboard')">
                    <i class="fa-solid fa-home me-2"></i> Dashboard
                </div>
            </div>
            <button class="tab-scroll-btn right" id="tab-scroll-right"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <!-- Content Area -->
        <div id="content-area">
            <!-- Dashboard Pane -->
            <div class="tab-pane active" id="pane-dashboard">
                <div class="h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center p-5" style="max-width: 600px;">
                        <div class="mb-4 animate-fade-in" style="animation-delay: 0.1s;">
                            <i class="fas fa-hospital-symbol fa-5x text-success opacity-25"></i>
                        </div>
                        <h2 class="fw-bold text-dark animate-fade-in" style="animation-delay: 0.2s;">Selamat Datang di <span class="text-success">SIMRS</span></h2>
                        <h4 class="text-muted fw-light mb-4 animate-fade-in" style="animation-delay: 0.3s;">RSUD CIAMIS (Encounter-Based System)</h4>
                        
                        <div class="p-4 rounded-4 bg-white shadow-sm border border-light mb-4 animate-fade-in" style="animation-delay: 0.4s;">
                            <p class="mb-0 text-muted small">Sistem informasi manajemen rumah sakit terintegrasi, mendukung bridging SATUSEHAT, BPJS V-Claim, dan MJKN.</p>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3 animate-fade-in" style="animation-delay: 0.5s;">
                            <button onclick="$('#moduleLauncher').modal('show')" class="btn btn-success rounded-pill px-4 py-2 shadow-sm">
                                <i class="fas fa-rocket me-2"></i> Mulai Pekerjaan
                            </button>
                            <a href="javascript:void(0)" onclick="openTab('pendaftaran/rajal', 'Pendaftaran Baru')" class="btn btn-outline-success shadow-sm rounded-pill px-4">
                                <i class="fas fa-plus me-2"></i> Pendaftaran Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Module Launcher Modal -->
    <div class="modal fade" id="moduleLauncher" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content border-0">
                <div class="modal-header border-0 p-5 pb-0">
                    <div>
                        <h2 class="fw-bold mb-1">Module Launcher</h2>
                        <p class="text-muted small text-uppercase fw-bold tracking-wider">Pilih lingkungan kerja untuk melayani pasien.</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="module-grid">
                        <?php foreach($modules as $mod): ?>
                        <div class="module-item" onclick="loadModule('<?= $mod['environment'] ?>', '<?= $mod['icon'] ?>')">
                            <div class="module-icon" style="background-color: var(--fortress-blue)">
                                <i class="<?= $mod['icon'] ?>"></i>
                            </div>
                            <div class="module-name"><?= $mod['environment'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const baseUrl = '<?= base_url() ?>';
        let activeTabId = 'dashboard';

        $(document).ready(function() {
            // Live DateTime Logic
            function updateDateTime() {
                const now = new Date();
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' + 
                              now.getMinutes().toString().padStart(2, '0') + ':' + 
                              now.getSeconds().toString().padStart(2, '0');
                
                const dateStr = days[now.getDay()] + ', ' + 
                              now.getDate().toString().padStart(2, '0') + ' ' + 
                              months[now.getMonth()] + ' ' + 
                              now.getFullYear();
                
                $('#live-time').text(timeStr);
                $('#live-date').text(dateStr);
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();

            // Sidebar Toggle
            $('#sidebarToggle').on('click', function() {
                if ($(window).width() < 992) {
                    $('body').toggleClass('sidebar-open');
                } else {
                    $('body').toggleClass('collapsed');
                }
            });

            $('#sidebar-overlay').on('click', function() {
                $('body').removeClass('sidebar-open');
            });

            initTheme(); // Keep for legacy if needed
            restoreState();
        });

        // --- THEME ENGINE ---
        function initTheme() {
            const savedTheme = localStorage.getItem('simrs-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        // --- PERSISTENCE KEYS ---
        const STORAGE_KEY_MODULE = 'simrs_active_module';
        const STORAGE_KEY_TABS   = 'simrs_open_tabs';
        const STORAGE_KEY_ACTIVE = 'simrs_active_tab';

        function saveTabState() {
            const tabs = [];
            $('#tab-bar .fortress-tab').each(function() {
                const tabId = $(this).attr('id')?.replace('tab-', '');
                const title = $(this).find('span').text() || $(this).text().trim();
                const origUrl = $(this).data('orig-url') || tabId;
                if (tabId && tabId !== 'dashboard') {
                    tabs.push({ tabId, title, url: origUrl });
                }
            });
            localStorage.setItem(STORAGE_KEY_TABS, JSON.stringify(tabs));
            localStorage.setItem(STORAGE_KEY_ACTIVE, activeTabId);
        }

        function loadModule(env, icon) {
            $('#moduleLauncher').modal('hide');
            $('#active-env-label').text(env);
            localStorage.setItem(STORAGE_KEY_MODULE, JSON.stringify({ env, icon }));
            
            $.get(`${baseUrl}/workspace/get_sidebar/${env}`, function(html) {
                $('#sidebar-menu').html(`
                    <a href="javascript:void(0)" class="nav-link ${activeTabId === 'dashboard' ? 'active' : ''}" data-id="dashboard" data-title="Dashboard" onclick="activateTab('dashboard')">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                    ${html}
                `);
            });
        }

        $(document).on('click', '.sidebar-item', function() {
            const url = $(this).data('url');
            const title = $(this).data('title');
            if (!url) return;
            openTab(url, title);
        });

        function openTab(url, title, targetTabId = null) {
            const tabId = targetTabId || url.replace(/[^a-zA-Z0-9]/g, '-');
            const paneId = 'pane-' + tabId;

            // If targetTabId is provided OR it already exists, handle accordingly
            if ($(`#tab-${tabId}`).length) {
                activateTab(tabId);
                if (targetTabId) {
                    // Force reload if it's a targeted update (e.g. from search)
                    $(`#tab-${tabId} span`).text(title);
                    $(`#pane-${tabId}`).html('<div class="h-100 d-flex align-items-center justify-content-center"><div class="spinner-border text-success"></div></div>');
                    $.get(`${baseUrl}/${url}`, function(html) {
                        $(`#pane-${tabId}`).html(html);
                    });
                }
                return;
            }

            const tabHtml = `
                <div class="fortress-tab animate-fade-in" id="tab-${tabId}" data-orig-url="${url}" onclick="activateTab('${tabId}')">
                    <span>${title}</span>
                    <i class="fa-solid fa-xmark close-tab" onclick="event.stopPropagation(); closeTab('${tabId}')"></i>
                </div>
            `;
            $('#tab-bar').append(tabHtml);

            const paneHtml = `<div class="tab-pane animate-fade-in" id="pane-${tabId}"><div class="h-100 d-flex align-items-center justify-content-center"><div class="spinner-border text-success"></div></div></div>`;
            $('#content-area').append(paneHtml);

            activateTab(tabId);
            checkTabOverflow();

            $.get(`${baseUrl}/${url}`, function(html) {
                $(`#pane-${tabId}`).html(html);
            }).fail(function() {
                $.get(`${baseUrl}/workspace/render_menu/${url}`, function(html) {
                    $(`#pane-${tabId}`).html(html);
                }).fail(function() {
                    $(`#pane-${tabId}`).html('<div class="p-5 text-center text-danger">Gagal memuat konten.</div>');
                });
            });
        }

        function activateTab(tabId) {
            $('.fortress-tab').removeClass('active');
            $(`#tab-${tabId}`).addClass('active');

            $('.tab-pane').removeClass('active');
            $(`#pane-${tabId}`).addClass('active');

            $('.nav-link').removeClass('active');
            $(`.nav-link[data-id="${tabId}"]`).addClass('active');
            $(`.sidebar-item[data-url="${$(`#tab-${tabId}`).data('orig-url')}"]`).addClass('active');

            activeTabId = tabId;
            const title = $(`#tab-${tabId} span`).text() || $(`#tab-${tabId}`).text().replace('Dashboard', '').trim();
            $('#breadcrumb-active').text(title || 'Dashboard');

            scrollToActiveTab(tabId);
            saveTabState();
        }

        function closeTab(tabId) {
            if (tabId === 'dashboard') return;
            $(`#tab-${tabId}`).remove();
            $(`#pane-${tabId}`).remove();
            if (activeTabId === tabId) activateTab('dashboard');
            checkTabOverflow();
            saveTabState();
        }

        function scrollToActiveTab(id) {
            const container = $('#tab-bar');
            const activeTab = $(`#tab-${id}`);
            if (activeTab.length) {
                const scrollLeft = activeTab.position().left + container.scrollLeft() - (container.width() / 2) + (activeTab.width() / 2);
                container.animate({ scrollLeft: scrollLeft }, 200);
            }
        }

        function checkTabOverflow() {
            const container = document.getElementById('tab-bar');
            if (container.scrollWidth > container.clientWidth) {
                $('#tab-scroll-left, #tab-scroll-right').css('display', 'flex');
            } else {
                $('#tab-scroll-left, #tab-scroll-right').hide();
            }
        }

        $('#tab-scroll-left').click(() => $('#tab-bar').animate({ scrollLeft: $('#tab-bar').scrollLeft() - 200 }, 200));
        $('#tab-scroll-right').click(() => $('#tab-bar').animate({ scrollLeft: $('#tab-bar').scrollLeft() + 200 }, 200));

        function restoreState() {
            const savedModule = JSON.parse(localStorage.getItem(STORAGE_KEY_MODULE));
            if (savedModule && savedModule.env) {
                loadModule(savedModule.env, savedModule.icon);
            }

            const savedTabs = JSON.parse(localStorage.getItem(STORAGE_KEY_TABS));
            const savedActive = localStorage.getItem(STORAGE_KEY_ACTIVE) || 'dashboard';

            if (savedTabs && savedTabs.length > 0) {
                savedTabs.forEach(tab => openTab(tab.url, tab.title));
                setTimeout(() => activateTab(savedActive), 300);
            }
        }
    </script>
</body>
</html>
