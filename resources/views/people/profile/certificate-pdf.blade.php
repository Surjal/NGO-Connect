<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Volunteer Service</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #F8F4ED;
            color: #1A2A3A;
            line-height: 1.5;
        }

        .certificate-wrapper {
            width: 100%;
            min-height: 100vh;
            background: #F8F4ED;
            padding: 32px;
            box-sizing: border-box;
        }

        .certificate-border-outer {
            border: 3px solid #1A2A3A;
            padding: 18px;
            background: #FCF9F5;
        }

        .certificate-border-inner {
            border: 1.5px solid #C9A96E;
            padding: 0;
            background: #FCF9F5;
            position: relative;
        }

        .certificate-inner {
            padding: 50px 60px 40px;
        }

        .org-name {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: #C9A96E;
            margin-bottom: 28px;
            font-family: 'Georgia', 'Times New Roman', serif;
        }

        .separator-top {
            text-align: center;
            color: #C9A96E;
            font-size: 18px;
            letter-spacing: 12px;
            margin-bottom: 24px;
            font-family: 'Georgia', serif;
        }

        .cert-title {
            text-align: center;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #1A2A3A;
            margin-bottom: 6px;
        }

        .cert-subtitle {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1A2A3A;
            margin-bottom: 36px;
            font-family: 'Georgia', 'Times New Roman', serif;
        }

        .presented-line {
            text-align: center;
            font-size: 12px;
            font-style: italic;
            color: #5A6A7A;
            margin-bottom: 12px;
        }

        .volunteer-name {
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            color: #1A2A3A;
            margin-bottom: 8px;
            font-family: 'Georgia', 'Times New Roman', serif;
            letter-spacing: 1px;
            word-spacing: 4px;
        }

        .name-underline {
            width: 260px;
            height: 2px;
            background: #C9A96E;
            margin: 0 auto 24px;
        }

        .recognition-text {
            text-align: center;
            font-size: 13px;
            color: #3A4A5A;
            margin-bottom: 4px;
            font-style: italic;
            line-height: 1.8;
        }

        .recognition-text strong {
            font-style: normal;
            color: #1A2A3A;
        }

        .event-name {
            font-size: 16px;
            font-weight: 700;
            color: #1A2A3A;
            font-style: normal;
        }

        .org-by {
            text-align: center;
            font-size: 12px;
            color: #5A6A7A;
            margin-top: 20px;
            margin-bottom: 4px;
            font-style: italic;
        }

        .org-by-name {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            color: #1A2A3A;
            margin-bottom: 32px;
        }

        .separator-bottom {
            text-align: center;
            color: #C9A96E;
            font-size: 14px;
            letter-spacing: 8px;
            margin-bottom: 20px;
            font-family: 'Georgia', serif;
        }

        .issued-row {
            text-align: center;
            font-size: 11px;
            color: #5A6A7A;
            letter-spacing: 1px;
            margin-bottom: 24px;
        }

        .issued-row span {
            display: inline-block;
            margin: 0 20px;
        }

        .hash-section {
            text-align: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #E0D8CC;
        }

        .hash-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #8A7A6A;
        }

        .hash-value {
            font-family: 'Courier New', monospace;
            font-size: 9px;
            color: #8A7A6A;
            letter-spacing: 0.5px;
            word-break: break-all;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 120px;
            color: rgba(201, 169, 110, 0.04);
            font-family: 'Georgia', serif;
            font-weight: 700;
            letter-spacing: 20px;
            pointer-events: none;
            z-index: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate-border-outer">
            <div class="certificate-border-inner">
                <div class="watermark">NGO</div>
                <div class="certificate-inner content-wrapper">
                    <div class="org-name">NGO Connect</div>

                    <div class="separator-top">✦ — ✦ — ✦</div>

                    <div class="cert-title">Certificate of</div>
                    <div class="cert-subtitle">Volunteer Service</div>

                    <div class="presented-line">This is proudly presented to</div>

                    <div class="volunteer-name">{{ $certificate->user->name }}</div>
                    <div class="name-underline"></div>

                    <div class="recognition-text">
                        In recognition and appreciation of your dedicated volunteer service<br>
                        for the event <strong class="event-name">"{{ $certificate->event->title }}"</strong>
                    </div>

                    <div class="org-by">Organized by</div>
                    <div class="org-by-name">{{ $ngoName }}</div>

                    <div class="separator-bottom">✦ ✦ ✦</div>

                    <div class="issued-row">
                        <span>Issued: {{ $certificate->issued_at->format('F d, Y') }}</span>
                        <span>Event: {{ $certificate->event->start_date->format('M d, Y') }}</span>
                    </div>

                    <div class="hash-section">
                        <div class="hash-label">Verification Code</div>
                        <div class="hash-value">{{ $certificate->certificate_hash }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
