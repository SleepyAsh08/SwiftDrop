<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Disapproval</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            width: 100%;
            max-width: 650px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }
        .header {
            background: linear-gradient(90deg, #FF0000, #a42929);
            color: #ffffff;
            padding: 25px;
            text-align: center;
        }
        .header img {
            width: 60px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
        }
        .content {
            padding: 30px;
            background-color: #f9f9f9;
            text-align: center;
        }
        .content p {
            font-size: 18px;
            color: #333333;
            margin-bottom: 15px;
        }
        .content .approved-date {
            font-size: 16px;
            color: #777;
            margin-top: 10px;
        }
        .footer {
            padding: 20px;
            background-color: #f1f1f1;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ asset('images/AgrishopLogo.png') }}" alt="Agrishop Logo">
            <h1>Account Deactivation</h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <h2>Hello {{ $user->name . " " . $user->lastname }}</h2>
            <p>Your account has been deactivated!</p>

            <p>Kindly email the support for activation of Account. Thank you.</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>For any inquiries, feel free to <a href="mailto:faithtimogan@gmail.com">contact support</a>.</p>
        </div>
    </div>
</body>
</html>

