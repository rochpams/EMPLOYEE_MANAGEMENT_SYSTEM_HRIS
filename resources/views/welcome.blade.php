<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System - HRIS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .container {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 20px;
            padding: 60px 40px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 100px rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.1);
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 40px;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .subtitle {
            color: #9ca3af;
            font-size: 16px;
            margin-bottom: 40px;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 30px;
        }

        .btn {
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #e5e7eb;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(59, 130, 246, 0.5);
        }


    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            
        </div>
        
        <h1>Employee Management System</h1>
        <p class="subtitle">Sign in to access your HRIS dashboard</p>
        
        <div class="buttons">
            <a href="{{ route('login') }}" class="btn btn-primary">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-secondary">Register Here</a>
        </div>
    </div>
</body>
</html>
