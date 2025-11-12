<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 25px;
            color: #333;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .highlight {
            background: #fff3cd;
            padding: 12px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
            font-family: monospace;
        }
        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Import Failed</h1>
        </div>

        <div class="content">
            <p><strong>Dear,</strong></p>

            <p>An import process has <strong>failed</strong>. Here are the details:</p>

            <div class="highlight">
                <strong>Import ID:</strong> #{{ $import->id }}<br>
                <strong>Type:</strong> {{ config("imports.types.{$import->type}.label") ?? $import->type }}<br>
                <strong>User:</strong> {{ $import->user->username }}<br>
                <strong>Started at:</strong> {{ $import->created_at->format('M d, Y H:i:s') }}<br>
                <strong>File(s):</strong> {{ $import->file_name }}
            </div>

            <p>Please check the logs in the application to see the exact validation errors.</p>

            <a href="{{ url('/imports-history') }}" class="btn">View Import History</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Import Data App. All rights reserved.<br>
            This is an automated message — please do not reply.
        </div>
    </div>
</body>
</html>