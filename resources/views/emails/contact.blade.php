<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Us Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        h1 {
            font-size: 24px;
            color: #444;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 18px;
            color: #555;
        }
        .section p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Us Form Submission</h1>
        <div class="section">
            <h2>Submitter Details</h2>
            <p><strong>First Name:</strong> {{ $firstName }}</p>
            <p><strong>Last Name:</strong> {{ $lastName }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Country:</strong> {{ $country }}</p>
        </div>
        <div class="section">
            <h2>Topic</h2>
            <p>{{ $topic->name }}</p>
        </div>
        <div class="section">
            <h2>Message</h2>
            <p>{{ $contactus }}</p>
        </div>
        <div class="section">
            <p>Dear Admin,</p>
            <p>A new message from contact us form has been submitted through the form. Please review the details above and take appropriate action.</p>
            <p>Thank you for your attention to this matter.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Chandra Asri. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
