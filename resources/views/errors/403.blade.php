
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>403 Access Denied | Luxury Fashion Hub</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: #0b0b0b;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            width: 100%;
            max-width: 650px;
            text-align: center;
            padding: 55px 30px;
            background: #171717;
            border: 1px solid #403522;
            border-radius: 14px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.5);
        }

        .brand {
            color: #d4af72;
            font-size: 19px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 35px;
        }

        .error-code {
            color: #d4af72;
            font-size: clamp(85px, 18vw, 140px);
            font-weight: bold;
            line-height: 1;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .message {
            color: #aaa;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 13px 23px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-gold {
            background: #c9a24a;
            color: #111;
        }

        .btn-gold:hover {
            background: #e0bd78;
        }

        .btn-dark {
            background: #292929;
            color: #fff;
            border: 1px solid #50432e;
        }

        .btn-dark:hover {
            background: #383838;
        }

        .footer {
            color: #666;
            font-size: 12px;
            margin-top: 45px;
        }

        @media(max-width: 480px) {
            .error-container {
                padding: 40px 18px;
            }

            h1 {
                font-size: 23px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="error-container">

        <div class="brand">
            LUXURY FASHION HUB
        </div>

        <div class="error-code">
            403
        </div>

        <h1>ACCESS DENIED</h1>

        <p class="message">
            Sorry! You don't have permission to access this page.
            <br>
            Please contact your administrator if you believe this is a mistake.
        </p>

        <div class="buttons">

            <a href="{{ auth()->check() && auth()->user()->can('view-admin-dashboard') ? route('admin.dashboard') : url('/') }}"
               class="btn btn-gold">
                Return to Dashboard
            </a>

            <a href="{{ url('/') }}" class="btn btn-dark">
                Visit Store
            </a>

        </div>

        <div class="footer">
            © {{ date('Y') }} Luxury Fashion Hub. All Rights Reserved.
        </div>

    </div>

</body>
</html>
