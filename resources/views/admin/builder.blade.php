<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Builder — {{ $page->title }}</title>

    <!-- Google Fonts & GrapesJS Core CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">

    <style>
        :root {
            --bg-canvas: #121214;
            --bg-panel: #18181b;
            --bg-card: #27272a;
            --bg-card-hover: #323238;
            --border-color: #2e2e33;
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
            --primary: #ff6b00;
            --primary-hover: #e05e00;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        * { box-sizing: border-box; }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: var(--font-family);
            background-color: var(--bg-canvas);
            color: var(--text-main);
        }

        /* 1. TOP NAVBAR */
        #builder-header {
            height: 52px;
            background: var(--bg-panel);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: relative;
            z-index: 100;
        }

        .header-left, .header-center, .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            margin-left: 12px;
        }
        .page-badge strong { color: var(--text-main); }
        .page-badge code {
            background: var(--bg-card);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            color: #fb923c;
        }

        .btn-ui {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid transparent;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .btn-ui:hover {
            background: var(--bg-card);
            color: var(--text-main);
        }
        .btn-ui.active {
            background: var(--bg-card);
            color: var(--primary);
            border-color: var(--border-color);
        }

        .btn-save {
            background: var(--primary);
            color: #fff;
            padding: 6px 18px;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .btn-save:hover { background: var(--primary-hover); }

        /* 2. THREE-PANEL LAYOUT */
        #builder-main {
            height: calc(100vh - 52px);
            display: flex;
            width: 100%;
            overflow: hidden;
        }

        .builder-sidebar {
            width: 300px;
            flex-shrink: 0;
            background: var(--bg-panel);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 10;
        }
        .builder-sidebar-right {
            border-right: none;
            border-left: 1px solid var(--border-color);
            width: 320px;
        }

        .sidebar-tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            background: #141416;
        }
        .tab-btn {
            flex: 1;
            padding: 10px 0;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            text-align: center;
            transition: all 0.15s;
        }
        .tab-btn:hover { color: var(--text-main); }
        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: var(--bg-panel);
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
        }
        .sidebar-content::-webkit-scrollbar { width: 4px; }
        .sidebar-content::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }

        /* 3. RESPONSIVE CANVAS & WORKING AREA */
        #canvas-wrap {
            flex: 1;
            height: 100%;
            position: relative;
            background: var(--bg-canvas);
            overflow: hidden;
        }

        #gjs {
            height: 100% !important;
            width: 100% !important;
        }

        .gjs-cv-canvas {
            width: 100% !important;
            height: 100% !important;
            top: 0 !important;
            background-color: var(--bg-canvas) !important;
        }

        .gjs-frames {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* Responsive Frame Wrapper */
        .gjs-frame-wrapper {
            margin: 0 auto;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), height 0.3s ease, box-shadow 0.3s ease;
        }

        body.mode-desktop .gjs-frame-wrapper {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        body.mode-tablet .gjs-frame-wrapper,
        body.mode-mobile .gjs-frame-wrapper {
            height: calc(100% - 48px) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6) !important;
            border-radius: 12px !important;
            border: 2px solid var(--border-color) !important;
            overflow: hidden !important;
        }

        .gjs-frame {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            background-color: #ffffff !important;
        }

        /* 4. BLOCKS PANEL */
        .gjs-blocks-c { padding: 4px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .gjs-block {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 6px !important;
            padding: 12px 6px !important;
            margin: 0 !important;
            width: 100% !important;
            height: auto !important;
            box-shadow: none !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            font-size: 11px !important;
            font-weight: 500 !important;
            text-align: center !important;
            transition: all 0.15s ease !important;
        }
        .gjs-block:hover {
            background: var(--bg-card-hover) !important;
            border-color: var(--primary) !important;
            transform: translateY(-2px);
            color: #fff !important;
        }
        .gjs-block svg { width: 20px; height: 20px; fill: var(--primary); }

        .gjs-block-category .gjs-title {
            background: transparent !important;
            color: var(--text-muted) !important;
            border: none !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 14px 4px 6px !important;
        }

        /* 5. STYLE MANAGER */
        .gjs-sm-sector .gjs-sm-sector-title {
            background: var(--bg-card) !important;
            color: var(--text-main) !important;
            border-radius: 6px !important;
            padding: 10px 12px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            margin-bottom: 6px !important;
        }
        .gjs-field {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            color: #fff !important;
            border-radius: 4px !important;
            font-size: 12px !important;
        }
        .gjs-field input, .gjs-field select { color: #fff !important; }
        .gjs-sm-property__name { color: var(--text-muted) !important; font-size: 11px !important; }

        .gjs-layer { background: transparent !important; color: var(--text-muted) !important; }
        .gjs-layer:hover, .gjs-layer.gjs-selected { background: var(--bg-card) !important; color: var(--text-main) !important; }
    </style>
</head>
<body class="mode-desktop">

    <!-- TOP CONTROL BAR -->
    <header id="builder-header">
        <div class="header-left">
            <a href="/admin/pages" class="btn-ui">← Exit</a>
            <div class="page-badge">
                <span>Editing: <strong>{{ $page->title }}</strong></span>
                <code>/{{ $page->slug }}</code>
            </div>
        </div>

        <div class="header-center">
            <button class="btn-ui active" data-device="desktop" title="Full Width Desktop">🖥️ Desktop</button>
            <button class="btn-ui" data-device="tablet" title="Tablet View (768px)">📱 Tablet</button>
            <button class="btn-ui" data-device="mobile" title="Mobile View (375px)">📱 Mobile</button>
        </div>

        <div class="header-right">
            <button id="btn-undo" class="btn-ui" title="Undo (Ctrl+Z)">↩️</button>
            <button id="btn-redo" class="btn-ui" title="Redo (Ctrl+Y)">↪️</button>
            <button id="btn-code" class="btn-ui" title="View Code">&lt;/&gt;</button>
            <button id="btn-preview" class="btn-ui" title="Preview Canvas">👁️</button>
            <button id="btn-clear" class="btn-ui" title="Clear Canvas">🗑️</button>
            <span id="save-status" style="font-size: 12px; margin-left: 8px;"></span>
            <button id="btn-save" class="btn-save">Save Page</button>
        </div>
    </header>

    <!-- MAIN STUDIO WORKSPACE -->
    <div id="builder-main">
        <!-- LEFT SIDEBAR: BLOCKS & LAYERS -->
        <aside class="builder-sidebar">
            <div class="sidebar-tabs">
                <button class="tab-btn active" id="tab-blocks" onclick="switchLeftTab('blocks')">🧩 Elements</button>
                <button class="tab-btn" id="tab-layers" onclick="switchLeftTab('layers')">🗂️ Layers</button>
            </div>
            <div id="panel-blocks-view" class="sidebar-content">
                <div id="blocks-container"></div>
            </div>
            <div id="panel-layers-view" class="sidebar-content" style="display: none;">
                <div id="layers-container"></div>
            </div>
        </aside>

        <!-- CENTER CANVAS -->
        <main id="canvas-wrap">
            <div id="gjs"></div>
        </main>

        <!-- RIGHT SIDEBAR: STYLES & PROPERTIES -->
        <aside class="builder-sidebar builder-sidebar-right">
            <div class="sidebar-tabs">
                <button class="tab-btn active" id="tab-styles" onclick="switchRightTab('styles')">🎨 Styles</button>
                <button class="tab-btn" id="tab-traits" onclick="switchRightTab('traits')">⚙️ Settings</button>
            </div>
            <div id="panel-styles-view" class="sidebar-content">
                <div id="styles-container"></div>
            </div>
            <div id="panel-traits-view" class="sidebar-content" style="display: none;">
                <div id="traits-container"></div>
            </div>
        </aside>
    </div>

    <!-- GrapesJS Scripts -->
    <script src="https://unpkg.com/grapesjs"></script>

    <script>
        const initialProjectData = @json($page->gjs_data ?? null);
        const initialHtml = @json($page->content ?? '');
        const initialCss = @json($page->css ?? '');

        const editor = grapesjs.init({
            container: '#gjs',
            fromElement: false,
            height: '100%',
            width: '100%',
            storageManager: false,
            panels: { defaults: [] },
            blockManager: {
                appendTo: '#blocks-container',
            },
            layerManager: {
                appendTo: '#layers-container',
            },
            traitManager: {
                appendTo: '#traits-container',
            },
            styleManager: {
                appendTo: '#styles-container',
                sectors: [
                    {
                        name: 'Layout & Display',
                        open: true,
                        buildProps: ['display', 'flex-direction', 'justify-content', 'align-items', 'gap', 'flex-wrap'],
                    },
                    {
                        name: 'Dimension & Sizing',
                        open: true,
                        buildProps: ['width', 'min-width', 'max-width', 'height', 'margin', 'padding'],
                        properties: [
                            {
                                name: 'Max Width',
                                property: 'max-width',
                                type: 'select',
                                defaults: 'none',
                                options: [
                                    { value: 'none', name: 'None (Full Width)' },
                                    { value: '1320px', name: '1320px (Desktop Large)' },
                                    { value: '1140px', name: '1140px (Standard Desktop)' },
                                    { value: '960px', name: '960px (Medium)' },
                                    { value: '720px', name: '720px (Narrow / Article)' },
                                    { value: '100%', name: '100% Fluid' }
                                ]
                            }
                        ]
                    },
                    {
                        name: 'Typography',
                        open: false,
                        buildProps: ['font-family', 'font-size', 'font-weight', 'color', 'line-height', 'text-align'],
                    },
                    {
                        name: 'Background & Borders',
                        open: false,
                        buildProps: ['background-color', 'border', 'border-radius', 'box-shadow', 'opacity'],
                    }
                ]
            },
            deviceManager: {
                devices: [
                    { id: 'desktop', name: 'Desktop', width: '' },
                    { id: 'tablet', name: 'Tablet', width: '768px' },
                    { id: 'mobile', name: 'Mobile', width: '375px' },
                ]
            },
            canvas: {
                styles: [
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
                    'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap'
                ],
                scripts: [
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'
                ]
            },
            projectData: initialProjectData || undefined,
            components: (!initialProjectData && initialHtml) ? initialHtml : undefined,
            style: (!initialProjectData && initialCss) ? initialCss : undefined,
        });

        // Canvas Styles: full-bleed working area with non-blocking placeholder
        editor.on('load', () => {
            const head = editor.Canvas.getDocument().head;
            const style = document.createElement('style');
            style.innerHTML = `
                html, body {
                    min-height: 100vh;
                    background-color: #ffffff;
                    margin: 0;
                    padding: 0;
                    color: #212529;
                    box-sizing: border-box;
                }
                body:empty {
                    outline: 2px dashed #cbd5e1;
                    outline-offset: -16px;
                }
                body:empty::before {
                    content: '➕ Drag structural layout blocks from the left sidebar to start building';
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 380px;
                    color: #94a3b8;
                    font-size: 16px;
                    font-weight: 500;
                    font-family: 'Inter', -apple-system, sans-serif;
                    pointer-events: none; /* Crucial: allows drop events through */
                }
            `;
            head.appendChild(style);
        });

        const bm = editor.BlockManager;

        /* ========================================================
           1. LAYOUT & STRUCTURE BLOCKS
           ======================================================== */
        bm.add('section-full', {
            label: '⬛ Full Section',
            category: '1. Layout',
            content: `
                <section class="py-5" style="background-color: #f8f9fa;">
                    <div class="container text-center py-4">
                        <h2>Full Width Section</h2>
                        <p class="text-muted">Place any content, grids, or banners inside here.</p>
                    </div>
                </section>
            `
        });

        bm.add('container-boxed', {
            label: '📦 Boxed Container',
            category: '1. Layout',
            content: `
                <div class="container py-4 my-2">
                    <p class="lead">Boxed container content (Max 1320px auto-centered).</p>
                </div>
            `
        });

        bm.add('container-fluid', {
            label: '↔️ Fluid Container',
            category: '1. Layout',
            content: `
                <div class="container-fluid py-4">
                    <p>Fluid edge-to-edge container content.</p>
                </div>
            `
        });

        bm.add('col-1', {
            label: '▭ 1 Column',
            category: '1. Layout',
            content: `
                <div class="row my-3">
                    <div class="col-12 p-3 bg-light border rounded">1 Column Row</div>
                </div>
            `
        });

        bm.add('col-2', {
            label: '▯▯ 2 Columns (50/50)',
            category: '1. Layout',
            content: `
                <div class="row g-4 my-3">
                    <div class="col-md-6"><div class="p-3 bg-light border rounded">Column 1</div></div>
                    <div class="col-md-6"><div class="p-3 bg-light border rounded">Column 2</div></div>
                </div>
            `
        });

        bm.add('col-2-uneven', {
            label: '▯▮ 2 Columns (33/66)',
            category: '1. Layout',
            content: `
                <div class="row g-4 my-3">
                    <div class="col-md-4"><div class="p-3 bg-light border rounded">Sidebar (4 cols)</div></div>
                    <div class="col-md-8"><div class="p-3 bg-light border rounded">Main Content (8 cols)</div></div>
                </div>
            `
        });

        bm.add('col-3', {
            label: '▯▯▯ 3 Columns',
            category: '1. Layout',
            content: `
                <div class="row g-4 my-3">
                    <div class="col-md-4"><div class="p-3 bg-light border rounded">Col 1</div></div>
                    <div class="col-md-4"><div class="p-3 bg-light border rounded">Col 2</div></div>
                    <div class="col-md-4"><div class="p-3 bg-light border rounded">Col 3</div></div>
                </div>
            `
        });

        bm.add('col-4', {
            label: '▯▯▯▯ 4 Columns',
            category: '1. Layout',
            content: `
                <div class="row g-3 my-3">
                    <div class="col-md-3"><div class="p-3 bg-light border rounded">Col 1</div></div>
                    <div class="col-md-3"><div class="p-3 bg-light border rounded">Col 2</div></div>
                    <div class="col-md-3"><div class="p-3 bg-light border rounded">Col 3</div></div>
                    <div class="col-md-3"><div class="p-3 bg-light border rounded">Col 4</div></div>
                </div>
            `
        });

        bm.add('flex-box', {
            label: '⇄ Flex Row',
            category: '1. Layout',
            content: `
                <div class="d-flex align-items-center justify-content-between p-3 my-2 border rounded">
                    <span>Flex Item Left</span>
                    <span>Flex Item Right</span>
                </div>
            `
        });

        bm.add('divider-space', {
            label: '➖ Divider / Line',
            category: '1. Layout',
            content: `<hr class="my-5 border-secondary-subtle">`
        });

        /* ========================================================
           2. BASIC CONTENT ELEMENTS
           ======================================================== */
        bm.add('heading', {
            label: 'H Heading',
            category: '2. Basic',
            content: '<h2 class="fw-bold mb-3">Heading Title</h2>'
        });

        bm.add('paragraph', {
            label: '¶ Paragraph',
            category: '2. Basic',
            content: '<p class="lead text-muted">Write your descriptive body paragraph text here.</p>'
        });

        bm.add('button-main', {
            label: '🔘 Button',
            category: '2. Basic',
            content: '<a href="#" class="btn btn-warning px-4 py-2 fw-semibold" style="background-color: #ff6b00; color: #fff;">Click Here</a>'
        });

        bm.add('image-block', {
            label: '🖼️ Image',
            category: '2. Basic',
            content: '<img src="https://placehold.co/800x450/e2e8f0/475569?text=Image" class="img-fluid rounded-3" alt="Placeholder">'
        });

        bm.add('card-simple', {
            label: '🎴 Card',
            category: '2. Basic',
            content: `
                <div class="card border shadow-sm rounded-3">
                    <img src="https://placehold.co/600x350/e2e8f0/475569?text=Card+Banner" class="card-img-top" alt="...">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold">Card Title</h5>
                        <p class="card-text text-muted">Short card description text goes here.</p>
                        <a href="#" class="btn btn-dark">Read More</a>
                    </div>
                </div>
            `
        });

        /* ========================================================
           3. PRE-BUILT SECTIONS
           ======================================================== */
        bm.add('hero-section', {
            label: '🌟 Hero Banner',
            category: '3. Sections',
            content: `
                <section class="py-5 text-center bg-light  p-5">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">WOOFLIX EXCLUSIVE</span>
                    <h1 class="display-4 fw-bold">Premium Treats For Your Paws</h1>
                    <p class="lead text-muted mx-auto col-lg-8">Nutritious, 100% natural, grain-free snacks prepared with human-grade meat and wholesome love.</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="/shop" class="btn btn-warning btn-lg fw-bold px-4 py-2" style="background-color: #ff6b00; color: #fff;">Explore Deals</a>
                        <a href="/about" class="btn btn-outline-secondary btn-lg px-4 py-2">Learn More</a>
                    </div>
                </section>
            `
        });

        /* ========================================================
        4. FORM ELEMENTS & CONTACT BLOCKS
        ======================================================== */

        // Complete Ready-Made Contact Page Section (2-Column Info + Form)
        bm.add('contact-section', {
            label: '📞 Contact Page (Full)',
            category: '3. Sections',
            content: `
                <section class="py-5" style="background-color: #f8fafc;">
                    <div class="container py-4">
                        <div class="text-center mb-5">
                            <span class="badge px-3 py-2 rounded-pill fw-bold mb-2" style="background-color: #fff3e0; color: #ff6b00;">GET IN TOUCH</span>
                            <h1 class="fw-bold">We’d Love to Hear From You</h1>
                            <p class="text-muted mx-auto col-lg-6">Have a question about our pet products, orders, or custom dietary advice? Reach out and our team will respond within 24 hours.</p>
                        </div>

                        <div class="row g-4 justify-content-center">
                            <!-- Left Column: Contact Details -->
                            <div class="col-lg-5">
                                <div class="p-4 p-md-5 bg-white border shadow-sm h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <h4 class="fw-bold mb-4">Contact Information</h4>
                                        
                                        <div class="d-flex align-items-start gap-3 mb-4">
                                            <div class="fs-4 text-warning">📍</div>
                                            <div>
                                                <h6 class="fw-bold mb-1">Our Store / HQ</h6>
                                                <p class="text-muted small mb-0">123 Pet Lane, Bandra West, Mumbai, Maharashtra 400050</p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mb-4">
                                            <div class="fs-4 text-warning">📞</div>
                                            <div>
                                                <h6 class="fw-bold mb-1">Phone & WhatsApp</h6>
                                                <p class="text-muted small mb-0">+91 98765 43210<br><span class="text-muted">Mon - Sat: 9:00 AM - 7:00 PM</span></p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mb-4">
                                            <div class="fs-4 text-warning">✉️</div>
                                            <div>
                                                <h6 class="fw-bold mb-1">Email Us</h6>
                                                <p class="text-muted small mb-0">support@wooflix.in<br>wholesale@wooflix.in</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-3 mt-4" style="background-color: #fff8f3; border: 1px dashed #ff6b00;">
                                        <p class="small text-muted mb-0">🐾 <strong>Emergency Pet Care?</strong> We offer express dispatch on prescription diet meals.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Interactive Form -->
                            <div class="col-lg-7">
                                <div class="p-4 p-md-5 bg-white border shadow-sm">
                                    <h4 class="fw-bold mb-3">Send Us a Message</h4>
                                    <p class="text-muted small mb-4">Fill out the form below and we will get back to your furry friend's query promptly.</p>

                                    <form class="ajax-contact-form" action="/contact/send" method="POST">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Your Name *</label>
                                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Email Address *</label>
                                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Phone Number</label>
                                                <input type="tel" name="phone" class="form-control" placeholder="+91 98765 00000">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Subject *</label>
                                                <select name="subject" class="form-select" required>
                                                    <option value="General Inquiry">General Inquiry</option>
                                                    <option value="Order Status">Order Status</option>
                                                    <option value="Wholesale & Bulk">Wholesale & Bulk</option>
                                                    <option value="Product Nutrition Question">Product Nutrition Question</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Your Message *</label>
                                                <textarea name="message" rows="4" class="form-control" placeholder="Tell us how we can help..." required></textarea>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-white" style="background-color: #ff6b00; border: none;">
                                                    Send Message ➔
                                                </button>
                                                <div class="form-feedback mt-3" style="display: none;"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

                    // Individual Form Primitives (so you can assemble custom forms from scratch)
                    bm.add('form-input', {
                        label: '📝 Input Field',
                        category: '4. Form Elements',
                        content: `
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Field Label</label>
                                <input type="text" name="custom_field" class="form-control" placeholder="Enter value...">
                            </div>
                        `
                    });

                    bm.add('form-textarea', {
                        label: '📋 Textarea',
                        category: '4. Form Elements',
                        content: `
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Message</label>
                                <textarea name="message" class="form-control" rows="3" placeholder="Enter message..."></textarea>
                            </div>
                        `
                    });

                    bm.add('form-button', {
                        label: '🔘 Submit Button',
                        category: '4. Form Elements',
                        content: `
                            <button type="submit" class="btn btn-warning px-4 py-2 fw-bold text-white" style="background-color: #ff6b00;">
                                Submit
                            </button>
                        `
                    });

                    bm.add('google-map-embed', {
                        label: '🗺️ Map Embed',
                        category: '3. Sections',
                        content: `
                            <div class="my-4 rounded-4 overflow-hidden border shadow-sm" style="height: 350px;">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d120615.86438722055!2d72.77583648602528!3d19.140733800620864!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b63aceef0c69%3A0x2aa8019ac096288!2sMumbai%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" 
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy">
                                </iframe>
                            </div>
                        `
                    });

        bm.add('features-grid', {
            label: '💎 3-Col Features',
            category: '3. Sections',
            content: `
                <div class="row g-4 py-5 text-center">  
                    <div class="col-md-4">
                        <div class="p-4 border bg-white shadow-sm h-100">
                            <div class="fs-1 mb-2">🚚</div>
                            <h4 class="fw-bold">Free Express Delivery</h4>
                            <p class="text-muted small mb-0">Instant delivery on all orders over ₹499 right to your doorstep.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 border rounded-3 bg-white shadow-sm h-100">
                            <div class="fs-1 mb-2">🥩</div>
                            <h4 class="fw-bold">100% Real Ingredients</h4>
                            <p class="text-muted small mb-0">No fillers, by-products, or artificial preservatives ever.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 border rounded-3 bg-white shadow-sm h-100">
                            <div class="fs-1 mb-2">🩺</div>
                            <h4 class="fw-bold">Vet Certified</h4>
                            <p class="text-muted small mb-0">Formulated alongside certified pet nutritionists.</p>
                        </div>
                    </div>
                </div>
            `
        });

        bm.add('cta-banner', {
            label: '📢 CTA Banner',
            category: '3. Sections',
            content: `
                <div class="p-5 text-white  d-flex flex-column flex-md-row align-items-center justify-content-between gap-4" style="background-color: #ff6b00;">
                    <div>
                        <h2 class="fw-bold mb-1">Get 20% Off Your First Order</h2>
                        <p class="mb-0 opacity-75">Sign up today and treat your furry companion to the finest diet.</p>
                    </div>
                    <a href="/register" class="btn btn-dark btn-lg px-4 py-2 fw-semibold text-nowrap">Claim Coupon</a>
                </div>
            `
        });

        bm.add('faq-accordion', {
            label: '❓ FAQ Accordion',
            category: '3. Sections',
            content: `
                <div class="my-5 py-3">
                    <h3 class="fw-bold text-center">Frequently Asked Questions</h3>
                    <div class="accordion col-lg-8 mx-auto" id="builderFaq">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How long does delivery take?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show">
                                <div class="accordion-body text-muted">
                                    Orders are dispatched within 24 hours and typically reach your doorstep in 2–4 business days.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Are your treats safe for puppies and kittens?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse">
                                <div class="accordion-body text-muted">
                                    Yes! All items undergo rigorous veterinary testing and are gentle on young digestive systems.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `
        });

        /* ========================================================
           UI INTERFACE & TAB CONTROLS
           ======================================================== */
        function switchLeftTab(tab) {
            document.getElementById('tab-blocks').classList.toggle('active', tab === 'blocks');
            document.getElementById('tab-layers').classList.toggle('active', tab === 'layers');
            document.getElementById('panel-blocks-view').style.display = tab === 'blocks' ? 'block' : 'none';
            document.getElementById('panel-layers-view').style.display = tab === 'layers' ? 'block' : 'none';
        }

        function switchRightTab(tab) {
            document.getElementById('tab-styles').classList.toggle('active', tab === 'styles');
            document.getElementById('tab-traits').classList.toggle('active', tab === 'traits');
            document.getElementById('panel-styles-view').style.display = tab === 'styles' ? 'block' : 'none';
            document.getElementById('panel-traits-view').style.display = tab === 'traits' ? 'block' : 'none';
        }

        editor.on('component:selected', () => {
            switchRightTab('styles');
        });

        // 🟢 Device Mode Switcher (Corrected & Placed in Global Scope)
        function setDeviceMode(deviceId) {
            document.querySelectorAll('[data-device]').forEach(b => {
                b.classList.toggle('active', b.getAttribute('data-device') === deviceId);
            });

            document.body.classList.remove('mode-desktop', 'mode-tablet', 'mode-mobile');
            document.body.classList.add('mode-' + deviceId);

            if (editor.Devices && typeof editor.Devices.select === 'function') {
                editor.Devices.select(deviceId);
            } else if (typeof editor.setDevice === 'function') {
                editor.setDevice(deviceId);
            }
        }

        document.querySelectorAll('[data-device]').forEach(btn => {
            btn.addEventListener('click', () => {
                const device = btn.getAttribute('data-device');
                setDeviceMode(device);
            });
        });

        // Toolbar Buttons
        document.getElementById('btn-undo').addEventListener('click', () => editor.runCommand('core:undo'));
        document.getElementById('btn-redo').addEventListener('click', () => editor.runCommand('core:redo'));
        document.getElementById('btn-code').addEventListener('click', () => editor.runCommand('core:open-code'));
        document.getElementById('btn-preview').addEventListener('click', () => editor.runCommand('core:preview'));
        document.getElementById('btn-clear').addEventListener('click', () => {
            if (confirm('Clear the entire canvas? This cannot be undone.')) {
                editor.DomComponents.clear();
                editor.CssComposer.clear();
            }
        });

        // Save Functionality
        const btnSave = document.getElementById('btn-save');
        const saveStatus = document.getElementById('save-status');

        btnSave.addEventListener('click', async () => {
            btnSave.disabled = true;
            btnSave.innerText = 'Saving...';
            saveStatus.innerText = '';

            const payload = {
                content: editor.getHtml(),
                css: editor.getCss(),
                gjs_data: editor.getProjectData(),
            };

            try {
                const response = await fetch("{{ route('admin.pages.builder.update', $page->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    saveStatus.style.color = '#34d399';
                    saveStatus.innerText = 'Saved ' + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                } else {
                    throw new Error(data.message || 'Failed to save');
                }
            } catch (err) {
                saveStatus.style.color = '#f87171';
                saveStatus.innerText = 'Error: ' + err.message;
            } finally {
                btnSave.disabled = false;
                btnSave.innerText = 'Save Page';
            }
        });
    </script>
</body>
</html>