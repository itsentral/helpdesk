<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body.email-template {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container-template {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .header-template {
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header-template h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header-template p {
            margin: 5px 0 0 0;
            opacity: 0.8;
            font-size: 14px;
        }

        .content-template {
            padding: 40px 30px;
            line-height: 1.6;
        }

        .content-template h3 {
            color: #1a237e;
            margin-top: 0;
        }

        .footer-template {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eeeeee;
        }

        .btn-template {
            display: inline-block;
            padding: 10px 25px;
            background-color: #1a237e;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }

        .note-template {
            background-color: #fff9c4;
            padding: 15px;
            border-left: 4px solid #fbc02d;
            margin: 20px 0;
            font-style: italic;
        }
    </style>
</head>

<body class="email-template">
    <div class="container-template">
        <div class="header-template">
            <?php if (isset($company_logo) && !empty($company_logo)): ?>
                <img src="{{company_logo}}" alt="Logo" style="max-height: 50px; margin-bottom: 10px;">
            <?php endif; ?>
            <h1>{{company_name}}</h1>
            <p>Helpdesk System</p>
        </div>
        <div class="content-template">
            {{content}}

            <div style="text-align: center; margin-top: 30px;">
                <a href="<?= base_url(); ?>" class="btn-template">Login to System</a>
            </div>
        </div>
        <div class="footer-template">
            &copy; <?= date('Y'); ?> {{company_name}}. All rights reserved.<br>
            {{company_address}}<br>
            <span style="font-size: 10px; opacity: 0.7;">This is an automated system notification. Please do not reply.</span>
        </div>
    </div>
</body>

</html>