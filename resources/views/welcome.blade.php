<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Employee Management System - HRIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .welcome-shell {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(30, 78, 216, 0.18), transparent 28%),
                radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.16), transparent 26%),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
            color: #0f172a;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(148, 163, 184, 0.25);
            border-radius: 1.5rem;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(16px);
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(30, 78, 216, 0.08);
            color: #1d4ed8;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .welcome-title {
            font-size: clamp(2.5rem, 5vw, 4.75rem);
            line-height: 0.95;
            letter-spacing: -0.05em;
            font-weight: 800;
            margin: 0;
        }

        .welcome-copy {
            color: #475569;
            font-size: 1.06rem;
            max-width: 42rem;
        }

        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.7rem 0.95rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.28);
            color: #334155;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        .stat-tile {
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        }

        .stat-value {
            font-weight: 800;
            font-size: 1.5rem;
            color: #0f172a;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.85rem;
        }
    </style>
</head>
<body class="welcome-shell">
    <main class="container py-4 py-md-5">
        <div class="row justify-content-center align-items-stretch g-4">
            <div class="col-12 col-xl-7">
                <section class="welcome-card p-4 p-md-5 h-100">
                    <div class="welcome-badge mb-4">
                        <i class="bi bi-building" aria-hidden="true"></i>
                        Human Resource Information System
                    </div>
                    <h1 class="welcome-title mb-4">Employee management that feels clear, fast, and alive.</h1>
                    <p class="welcome-copy mb-4">Track employees, departments, attendance, and leave from one structured workspace. This landing page is self-contained so it still renders clearly even if the app shell is not loading yet.</p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg px-4">
                            <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                            Register
                        </a>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="feature-pill"><i class="bi bi-shield-check"></i>Role-based access</span>
                        <span class="feature-pill"><i class="bi bi-calendar2-check"></i>Attendance and leave</span>
                        <span class="feature-pill"><i class="bi bi-graph-up-arrow"></i>HR reports</span>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-5">
                <section class="welcome-card p-4 p-md-5 h-100">
                    <h2 class="h4 fw-bold mb-3">At a glance</h2>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-tile">
                                <div class="stat-value">24/7</div>
                                <div class="stat-label">workspace access</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-tile">
                                <div class="stat-value">4</div>
                                <div class="stat-label">core roles</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-tile">
                                <div class="stat-value">1</div>
                                <div class="stat-label">central HR hub</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-tile">
                                <div class="stat-value">0</div>
                                <div class="stat-label">browser clutter</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-4" style="background: linear-gradient(135deg, #eff6ff, #f8fafc); border: 1px solid rgba(148, 163, 184, 0.24);">
                        <div class="small text-uppercase fw-bold text-primary mb-1">Next step</div>
                        <div class="fw-semibold">Open Sign In or Register to access the app shell.</div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
