<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Welcome to 24/7 Security</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .email-container {
            padding: 20px;
        }
        .email-header {
            /* background-color: #007bff; */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            background-color: white;
            padding: 20px;
        }
        .email-footer {
            text-align: center;
            color: #6c757d;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container email-container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header email-header">
                        <img src="https://smartairs.tax/assets/editedLogo-CDS-RWTE.jpeg" alt="" class="img-fluid">
                        {{-- <h2>Welcome to 24/7 Security</h2> --}}
                    </div>
                    <div class="card-body email-body">
                        <p>Hello {{ $data['first_name'] }} {{ $data['last_name'] }},</p>
                        <p>Welcome to 24/7 Security! We are thrilled to have you on board. </p>
                        <p>Follow the link below to reset your password and complete your profile</p>
                        <a href="www.247security.com/password/password-reset/{{ $data['token'] }}"></a>
                        <p>Thank you for joining us!</p>
                    </div>
                    <div class="card-footer email-footer">
                        <p>&copy; 2024 24/7 Security. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
