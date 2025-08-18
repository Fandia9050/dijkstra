<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>AdminLTE v4 | Dashboard</title>
        <!--begin::Accessibility Meta Tags-->
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=yes"
        />
        <meta name="color-scheme" content="light dark" />
        <meta
            name="theme-color"
            content="#007bff"
            media="(prefers-color-scheme: light)"
        />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta
            name="theme-color"
            content="#1a1a1a"
            media="(prefers-color-scheme: dark)"
        />
        <!--end::Accessibility Meta Tags-->
        <!--begin::Primary Meta Tags-->
        <meta name="title" content="AdminLTE v4 | Dashboard" />
        <meta name="author" content="ColorlibHQ" />
        <meta
            name="description"
            content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
        />
        <meta
            name="keywords"
            content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
        />
        <!--end::Primary Meta Tags-->
        <!--begin::Accessibility Features-->
        <!-- Skip links will be dynamically added by accessibility.js -->
        <meta name="supported-color-schemes" content="light dark" />
        <link rel="preload" href="{{ asset('css/style.css') }}" as="style" />
        <!--end::Accessibility Features-->
        <!--begin::Fonts-->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
            integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
            crossorigin="anonymous"
            media="print"
            onload="this.media='all'"
        />
        <!--end::Fonts-->
        <!--begin::Third Party Plugin(OverlayScrollbars)-->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
            crossorigin="anonymous"
        />
        <!--end::Third Party Plugin(OverlayScrollbars)-->
        <!--begin::Third Party Plugin(Bootstrap Icons)-->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
            crossorigin="anonymous"
        />
        <!--end::Third Party Plugin(Bootstrap Icons)-->
        <!--begin::Required Plugin(AdminLTE)-->
        <link rel="stylesheet" href="{{ asset('./css/adminlte.css') }}" />
        <!--end::Required Plugin(AdminLTE)-->
        <!-- apexcharts -->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
            integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
            crossorigin="anonymous"
        />
        <!-- jsvectormap -->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
            integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
            crossorigin="anonymous"
        />
        @yield("css")
        <style>
            #map {
                height: 70vh;
                width: 100%;
                margin-top: 12px;
            }
            .marker-label {
                background: #1976d2;
                color: #fff;
                padding: 2px 6px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 12px;
            }
            .controls {
                display: flex;
                gap: 8px;
                align-items: center;
                flex-wrap: wrap;
            }
            .box {
                padding: 8px;
                background: #fff;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
                border-radius: 6px;
            }
        </style>
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    @php
        $permissions = auth()->user()->permissions_with_assign;
        $viewRole = $permissions->first(function ($permission) {
            return $permission['name'] === 'view-roles';
        });
        $viewUser = $permissions->first(function ($permission) {
            return $permission['name'] === 'view-users';
        });
    @endphp
    <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
        <!--begin::App Wrapper-->
        <div class="app-wrapper">
            <!--begin::Header-->
            <nav class="app-header navbar navbar-expand bg-body">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Start Navbar Links-->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                data-lte-toggle="sidebar"
                                href="#"
                                role="button"
                            >
                                <i class="bi bi-list"></i>
                            </a>
                        </li>
                    </ul>
                    <!--end::Start Navbar Links-->
                    <!--begin::End Navbar Links-->
                    <div class="dropdown">
                        <button
                            class="btn btn-light drodown-end"
                            type="button"
                            id="dropdownMenuButton1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            {{auth()->user()->name}}
                        </button>
                        <ul
                            class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="dropdownMenuButton1"
                        >
                            <li>
                                <a class="dropdown-item" href="/logout"
                                    >Sign Out</a
                                >
                            </li>
                        </ul>
                    </div>
                    <!--end::End Navbar Links-->
                </div>
                <!--end::Container-->
            </nav>
            <!--end::Header-->
            <!--begin::Sidebar-->
            <aside
                class="app-sidebar bg-body-secondary shadow"
                data-bs-theme="dark"
            >
                <!--begin::Sidebar Brand-->
                <div class="sidebar-brand">
                    <!--begin::Brand Link-->
                    <a href="./index.html" class="brand-link">
                        <!--begin::Brand Image-->
                        <!--end::Brand Image-->
                        <!--begin::Brand Text-->
                        <span class="brand-text fw-light">Dijkstra</span>
                        <!--end::Brand Text-->
                    </a>
                    <!--end::Brand Link-->
                </div>
                <!--end::Sidebar Brand-->
                <!--begin::Sidebar Wrapper-->
                <div class="sidebar-wrapper">
                    <nav class="mt-2">
                        <!--begin::Sidebar Menu-->
                        <ul
                            class="nav sidebar-menu flex-column"
                            data-lte-toggle="treeview"
                            role="navigation"
                            aria-label="Main navigation"
                            data-accordion="false"
                            id="navigation"
                        >
                            <li class="nav-item">
                                <a
                                    href="/home"
                                    class="nav-link {{request()->is('home') ? 'active' : ''}}"
                                >
                                    <svg
                                        class="nav-icon"
                                        style="fill: var(--lte-sidebar-color)"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 640 640"
                                    >
                                        <!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path
                                            d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3 315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3 576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4 289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256 432C256 405.5 277.5 384 304 384z"
                                        />
                                    </svg>
                                    <p>Home</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="/locations"
                                    class="nav-link {{request()->is('locations') ? 'active' : ''}}"
                                >
                                    <svg
                                        class="nav-icon"
                                        style="fill: var(--lte-sidebar-color)"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 640 640"
                                    >
                                        <!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path
                                            d="M128 252.6C128 148.4 214 64 320 64C426 64 512 148.4 512 252.6C512 371.9 391.8 514.9 341.6 569.4C329.8 582.2 310.1 582.2 298.3 569.4C248.1 514.9 127.9 371.9 127.9 252.6zM320 320C355.3 320 384 291.3 384 256C384 220.7 355.3 192 320 192C284.7 192 256 220.7 256 256C256 291.3 284.7 320 320 320z"
                                        />
                                    </svg>
                                    <p>Locations</p>
                                </a>
                            </li>
                            @if($viewRole['assigned'])
                            <li class="nav-item">
                                <a
                                    href="/roles"
                                    class="nav-link {{request()->is('roles') ? 'active' : ''}}"
                                >
                                    <svg
                                        class="nav-icon"
                                        style="fill: var(--lte-sidebar-color)"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 640 640"
                                    >
                                        <!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path
                                            d="M320 80C377.4 80 424 126.6 424 184C424 241.4 377.4 288 320 288C262.6 288 216 241.4 216 184C216 126.6 262.6 80 320 80zM96 152C135.8 152 168 184.2 168 224C168 263.8 135.8 296 96 296C56.2 296 24 263.8 24 224C24 184.2 56.2 152 96 152zM0 480C0 409.3 57.3 352 128 352C140.8 352 153.2 353.9 164.9 357.4C132 394.2 112 442.8 112 496L112 512C112 523.4 114.4 534.2 118.7 544L32 544C14.3 544 0 529.7 0 512L0 480zM521.3 544C525.6 534.2 528 523.4 528 512L528 496C528 442.8 508 394.2 475.1 357.4C486.8 353.9 499.2 352 512 352C582.7 352 640 409.3 640 480L640 512C640 529.7 625.7 544 608 544L521.3 544zM472 224C472 184.2 504.2 152 544 152C583.8 152 616 184.2 616 224C616 263.8 583.8 296 544 296C504.2 296 472 263.8 472 224zM160 496C160 407.6 231.6 336 320 336C408.4 336 480 407.6 480 496L480 512C480 529.7 465.7 544 448 544L192 544C174.3 544 160 529.7 160 512L160 496z"
                                        />
                                    </svg>
                                    <p>Roles</p>
                                </a>
                            </li>
                            @endif
                            @if($viewUser['assigned'])
                            <li class="nav-item">
                                <a
                                    href="/users"
                                    class="nav-link {{request()->is('users') ? 'active' : ''}}"
                                >
                                    <svg
                                        class="nav-icon"
                                        style="fill: var(--lte-sidebar-color)"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 640 640"
                                    >
                                        <!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path
                                            d="M320 80C377.4 80 424 126.6 424 184C424 241.4 377.4 288 320 288C262.6 288 216 241.4 216 184C216 126.6 262.6 80 320 80zM96 152C135.8 152 168 184.2 168 224C168 263.8 135.8 296 96 296C56.2 296 24 263.8 24 224C24 184.2 56.2 152 96 152zM0 480C0 409.3 57.3 352 128 352C140.8 352 153.2 353.9 164.9 357.4C132 394.2 112 442.8 112 496L112 512C112 523.4 114.4 534.2 118.7 544L32 544C14.3 544 0 529.7 0 512L0 480zM521.3 544C525.6 534.2 528 523.4 528 512L528 496C528 442.8 508 394.2 475.1 357.4C486.8 353.9 499.2 352 512 352C582.7 352 640 409.3 640 480L640 512C640 529.7 625.7 544 608 544L521.3 544zM472 224C472 184.2 504.2 152 544 152C583.8 152 616 184.2 616 224C616 263.8 583.8 296 544 296C504.2 296 472 263.8 472 224zM160 496C160 407.6 231.6 336 320 336C408.4 336 480 407.6 480 496L480 512C480 529.7 465.7 544 448 544L192 544C174.3 544 160 529.7 160 512L160 496z"
                                        />
                                    </svg>
                                    <p>Users</p>
                                </a>
                            </li>
                            @endif
                           
                        </ul>
                        <!--end::Sidebar Menu-->
                    </nav>
                </div>
                <!--end::Sidebar Wrapper-->
            </aside>
            <!--end::Sidebar-->
            <!--begin::App Main-->
            <main class="app-main">
                <!--begin::App Content Header-->
                <div class="app-content-header">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        <!--begin::Row-->
                        @yield('header')
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::App Content Header-->
                <!--begin::App Content-->
                <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">@yield('content')</div>
                    <!--end::Container-->
                </div>
                <!--end::App Content-->
            </main>
            <!--end::App Main-->
            <!--begin::Footer-->
            <footer class="app-footer">
                <!--begin::To the end-->
                <div class="float-end d-none d-sm-inline">
                    Anything you want
                </div>
                <!--end::To the end-->
                <!--begin::Copyright-->
                <strong>
                    Copyright &copy; 2014-2025&nbsp;
                    <a href="https://adminlte.io" class="text-decoration-none"
                        >AdminLTE.io</a
                    >.
                </strong>
                All rights reserved.
                <!--end::Copyright-->
            </footer>
            <!--end::Footer-->
        </div>
        <!--end::App Wrapper-->
        <!--begin::Script-->
        <!--begin::Third Party Plugin(OverlayScrollbars)-->
        <script
            src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
            crossorigin="anonymous"
        ></script>
        <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            crossorigin="anonymous"
        ></script>
        <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
            crossorigin="anonymous"
        ></script>
        <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
        <script src="{{ asset('js/adminlte.js') }}"></script>
        <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
        <script>
            const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
            const Default = {
                scrollbarTheme: 'os-theme-light',
                scrollbarAutoHide: 'leave',
                scrollbarClickScroll: true,
            };
            document.addEventListener('DOMContentLoaded', function () {
                const sidebarWrapper = document.querySelector(
                    SELECTOR_SIDEBAR_WRAPPER
                );
                if (
                    sidebarWrapper &&
                    OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined
                ) {
                    OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                        scrollbars: {
                            theme: Default.scrollbarTheme,
                            autoHide: Default.scrollbarAutoHide,
                            clickScroll: Default.scrollbarClickScroll,
                        },
                    });
                }
            });
        </script>
        <!--end::OverlayScrollbars Configure-->
        <!-- OPTIONAL SCRIPTS -->
        <!-- sortablejs -->
        <script
            src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
            crossorigin="anonymous"
        ></script>
        <!-- sortablejs -->
        <script>
            new Sortable(document.querySelector('.connectedSortable'), {
                group: 'shared',
                handle: '.card-header',
            });

            const cardHeaders = document.querySelectorAll(
                '.connectedSortable .card-header'
            );
            cardHeaders.forEach((cardHeader) => {
                cardHeader.style.cursor = 'move';
            });
        </script>
        <!-- apexcharts -->
        <script
            src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
            integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
            crossorigin="anonymous"
        ></script>
        <!-- ChartJS -->
        <script>
            // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
            // IT'S ALL JUST JUNK FOR DEMO
            // ++++++++++++++++++++++++++++++++++++++++++

            const sales_chart_options = {
                series: [
                    {
                        name: 'Digital Goods',
                        data: [28, 48, 40, 19, 86, 27, 90],
                    },
                    {
                        name: 'Electronics',
                        data: [65, 59, 80, 81, 56, 55, 40],
                    },
                ],
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                },
                legend: {
                    show: false,
                },
                colors: ['#0d6efd', '#20c997'],
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: 'smooth',
                },
                xaxis: {
                    type: 'datetime',
                    categories: [
                        '2023-01-01',
                        '2023-02-01',
                        '2023-03-01',
                        '2023-04-01',
                        '2023-05-01',
                        '2023-06-01',
                        '2023-07-01',
                    ],
                },
                tooltip: {
                    x: {
                        format: 'MMMM yyyy',
                    },
                },
            };

            const sales_chart = new ApexCharts(
                document.querySelector('#revenue-chart'),
                sales_chart_options
            );
            sales_chart.render();
        </script>
        <!-- jsvectormap -->
        <script
            src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
            integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
            crossorigin="anonymous"
        ></script>
        <script
            src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
            integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
            crossorigin="anonymous"
        ></script>
        <!-- jsvectormap -->
        <script>
            // World map by jsVectorMap
            new jsVectorMap({
                selector: '#world-map',
                map: 'world',
            });

            // Sparkline charts
            const option_sparkline1 = {
                series: [
                    {
                        data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
                    },
                ],
                chart: {
                    type: 'area',
                    height: 50,
                    sparkline: {
                        enabled: true,
                    },
                },
                stroke: {
                    curve: 'straight',
                },
                fill: {
                    opacity: 0.3,
                },
                yaxis: {
                    min: 0,
                },
                colors: ['#DCE6EC'],
            };

            const sparkline1 = new ApexCharts(
                document.querySelector('#sparkline-1'),
                option_sparkline1
            );
            sparkline1.render();

            const option_sparkline2 = {
                series: [
                    {
                        data: [
                            515, 519, 520, 522, 652, 810, 370, 627, 319, 630,
                            921,
                        ],
                    },
                ],
                chart: {
                    type: 'area',
                    height: 50,
                    sparkline: {
                        enabled: true,
                    },
                },
                stroke: {
                    curve: 'straight',
                },
                fill: {
                    opacity: 0.3,
                },
                yaxis: {
                    min: 0,
                },
                colors: ['#DCE6EC'],
            };

            const sparkline2 = new ApexCharts(
                document.querySelector('#sparkline-2'),
                option_sparkline2
            );
            sparkline2.render();

            const option_sparkline3 = {
                series: [
                    {
                        data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
                    },
                ],
                chart: {
                    type: 'area',
                    height: 50,
                    sparkline: {
                        enabled: true,
                    },
                },
                stroke: {
                    curve: 'straight',
                },
                fill: {
                    opacity: 0.3,
                },
                yaxis: {
                    min: 0,
                },
                colors: ['#DCE6EC'],
            };

            const sparkline3 = new ApexCharts(
                document.querySelector('#sparkline-3'),
                option_sparkline3
            );
            sparkline3.render();
        </script>

        @yield("js")
        <!--end::Script-->
    </body>
    <!--end::Body-->
</html>
