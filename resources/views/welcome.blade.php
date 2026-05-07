<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System - HRIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top left, #eff6ff 0%, var(--app-bg) 40%, var(--app-bg-alt) 100%);
            color: var(--app-text);
        }

        .welcome-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .welcome-panel {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid var(--app-border);
            border-radius: 1rem;
            box-shadow: var(--app-shadow);
        }

        .welcome-panel .badge {
            letter-spacing: 0.08em;
        }

        .welcome-hero {
            border-left: 4px solid var(--app-primary);
            padding-left: 1rem;
        }

        .welcome-feature {
            border: 1px solid var(--app-border);
            border-radius: 0.9rem;
            background: #fff;
            min-height: 100%;
        }
    </style>
</head>
<body>
    <div class="welcome-shell py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center align-items-center g-4">
                <div class="col-12 col-xl-7">
                    <div class="welcome-panel p-4 p-md-5 h-100">
                        <span class="badge text-bg-light border mb-3">Human Resource Information System</span>
                        <div class="welcome-hero mb-4">
                            <h1 class="display-6 fw-bold mb-2">Employee Management System</h1>
                            <p class="text-secondary mb-0">Manage employees, departments, attendance, and leave requests from one secure workspace built for admin, HR, manager, and employee roles.</p>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
                                Register
                            </a>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="card card-soft welcome-feature h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-shield-check text-primary"></i>
                                            <strong>Role access</strong>
                                        </div>
                                        <div class="text-secondary small">Admin, HR, manager, and employee access in one flow.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card card-soft welcome-feature h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-calendar2-check text-primary"></i>
                                            <strong>Attendance</strong>
                                        </div>
                                        <div class="text-secondary small">Track time-in, time-out, and leave requests cleanly.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card card-soft welcome-feature h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-graph-up-arrow text-primary"></i>
                                            <strong>Reports</strong>
                                        </div>
                                        <div class="text-secondary small">See workforce activity and department summaries at a glance.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="welcome-panel p-4 p-md-5 h-100">
                        <h2 class="h5 fw-bold mb-3">What you can do</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                <i class="bi bi-people text-primary" aria-hidden="true"></i>
                                <span>Manage employee records and assignments.</span>
                            </li>
                            <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                <i class="bi bi-diagram-3 text-primary" aria-hidden="true"></i>
                                <span>Organize departments and reporting structures.</span>
                            </li>
                            <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                <i class="bi bi-clock-history text-primary" aria-hidden="true"></i>
                                <span>Review attendance and leave activity quickly.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
