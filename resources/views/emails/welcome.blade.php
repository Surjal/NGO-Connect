<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Platform</title>
    <style>
        /* Inline Tailwind CSS styles for email compatibility */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
        }

        .header {
            background-color: #dc2626;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #dc2626;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
        }

        .button:hover {
            background-color: #b91c1c;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            color: #374151;
        }

        .detail-label {
            font-weight: 500;
            color: #4b5563;
        }

        .detail-value {
            color: #1f2937;
        }

        .section {
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome, {{ $userName }}!</h1>
        </div>
        <div class="content">
            <p>Thank you for registering your NGO with our platform! We're excited to have you on board to make a
                difference.</p>
            <p>Your account and NGO have been successfully registered. Below are the details of your account and the NGO
                you created.</p>

            <div class="section">
                <h2>Your Account Details</h2>
                <table class="table">
                    <tr>
                        <td class="detail-label">Full Name:</td>
                        <td class="detail-value">{{ $userName }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Email:</td>
                        <td class="detail-value">{{ $userEmail }}</td>
                    </tr>
                </table>
                <p>You can log in to your account using the email address above and the password you set during
                    registration. If you need to reset your password, click the button below:</p>
                <p style="text-align: center; margin: 20px 0;">
                    <a href="{{ $passwordResetUrl }}" class="button">Reset Password</a>
                </p>
            </div>

            <div class="section">
                <h2>NGO Details</h2>
                <table class="table">
                    <tr>
                        <td class="detail-label">NGO Name:</td>
                        <td class="detail-value">{{ $ngo->ngo_name }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Registration Date:</td>
                        <td class="detail-value">{{ \Carbon\Carbon::parse($ngo->registration_date)->format('F j, Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Category:</td>
                        <td class="detail-value">{{ $ngo->category }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Subcategory:</td>
                        <td class="detail-value">{{ $ngo->subcategory ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Address:</td>
                        <td class="detail-value">{{ $ngo->address }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Phone:</td>
                        <td class="detail-value">{{ $ngo->phone }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Mission:</td>
                        <td class="detail-value">{{ $ngo->mission ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Registration Number:</td>
                        <td class="detail-value">{{ $ngo->registration_number }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Registration District:</td>
                        <td class="detail-value">{{ $ngo->registration_district }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Last Renewal Date:</td>
                        <td class="detail-value">{{ \Carbon\Carbon::parse($ngo->last_renewal_date)->format('F j, Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">PAN Number:</td>
                        <td class="detail-value">{{ $ngo->pan_number }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Contact Person Position:</td>
                        <td class="detail-value">{{ $ngo->contact_position }}</td>
                    </tr>
                </table>
            </div>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $loginUrl }}" class="button">Log In to Your Account</a>
            </p>
            <p>If you have any questions or need assistance, feel free to contact our support team.</p>
            <p>Warm regards,<br>The Platform Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Platform Name. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
