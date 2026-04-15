<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System - Register</title>
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
            padding: 20px;
        }
        
        .register-dialog {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 20px;
            padding: 40px 35px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 100px rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(10px);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 38px;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }
        
        .header-text {
            text-align: center;
            margin-top: 15px;
        }
        
        .header-text h1 {
            color: #f8fafc;
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }
        
        .header-text p {
            color: #94a3b8;
            font-size: 13px;
            margin: 0;
            font-weight: 400;
        }
        
        form {
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 16px;
        }
        
        .form-group label {
            color: #f1f5f9;
            font-size: 13px;
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }
        
        .form-group input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(100, 116, 139, 0.3);
            border-radius: 8px;
            color: #cbd5e1;
            font-size: 14px;
            transition: all 0.3s ease;
            font-weight: 400;
        }
        
        .form-group input::placeholder {
            color: #64748b;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(30, 41, 59, 0.8);
            color: #f1f5f9;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .register-btn {
            width: 100%;
            padding: 11px 28px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
            margin-top: 10px;
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: -10px;
            margin-bottom: 16px;
        }

        .login-link {
            text-align: center;
            margin-top: 16px;
            color: #cbd5e1;
            font-size: 13px;
        }

        .login-link a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #93c5fd;
        }

        @media (max-height: 800px) {
            .register-dialog {
                max-height: 95vh;
            }
        }
    </style>
</head>
<body>
    <div class="register-dialog">
        <div class="logo-container">
            <div class="logo">📋</div>
        </div>
        
        <div class="header-text">
            <h1>Create your account</h1>
            <p>Register to get started</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Enter your full name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="••••••••"
                    required
                >
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="register-btn">Register</button>
        </form>

        <div class="login-link">
            Already registered? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</body>
</html>
