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
</head>
<body>
    <div class="auth-wrapper py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center g-4">
                <div class="col-12 col-lg-6">
                    <div class="p-4 p-md-5 bg-white auth-panel h-100">
                        <span class="badge text-bg-light border mb-3">Human Resource Information System</span>
                        <h1 class="display-6 fw-bold mb-3">Employee Management System</h1>
                        <p class="text-secondary mb-4">Manage employees, departments, attendance, and leave requests from one secure and responsive HR workspace.</p>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
                                Register
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="p-4 p-md-5 bg-white auth-panel">
                        <h2 class="h5 fw-bold mb-3">Core Modules</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                <i class="bi bi-shield-check text-primary" aria-hidden="true"></i>
                                <span>Role-based access for admin, HR, manager, and employee accounts.</span>
                            </li>
                            <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                <i class="bi bi-calendar2-check text-primary" aria-hidden="true"></i>
                                <span>Unified attendance and leave workflows with clear status tracking.</span>
                            </li>
                            <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                <i class="bi bi-graph-up-arrow text-primary" aria-hidden="true"></i>
                                <span>Operational dashboards and reports for workforce visibility.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
