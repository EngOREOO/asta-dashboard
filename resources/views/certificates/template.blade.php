<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .certificate-container {
            background: white;
            border-radius: 20px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 800px;
            width: 100%;
            border: 8px solid #gold;
        }
        
        .header {
            margin-bottom: 40px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 50px;
        }
        
        .main-content {
            margin: 40px 0;
        }
        
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .completion-text {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .course-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        
        .details {
            margin: 40px 0;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        
        .detail-item {
            margin: 20px;
            text-align: center;
        }
        
        .detail-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .signature-box {
            text-align: center;
            margin: 20px;
            flex: 1;
            min-width: 200px;
        }
        
        .signature-line {
            border-bottom: 2px solid #333;
            width: 200px;
            margin: 10px auto;
            height: 40px;
        }
        
        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .signature-title {
            font-size: 14px;
            color: #666;
        }
        
        .certificate-id {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
            color: #999;
        }
        
        .border-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 3px solid #gold;
            border-radius: 20px;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="border-pattern"></div>
        
        <div class="header">
            <div class="logo">ASTA Learning Platform</div>
            <div class="title">Certificate of Completion</div>
            <div class="subtitle">This is to certify that</div>
        </div>
        
        <div class="main-content">
            <div class="student-name">{{ $user->name }}</div>
            <div class="completion-text">
                has successfully completed the course
            </div>
            <div class="course-name">{{ $course->title }}</div>
        </div>
        
        <div class="details">
            <div class="detail-item">
                <div class="detail-label">Issued Date</div>
                <div class="detail-value">{{ $issued_date }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Instructor</div>
                <div class="detail-value">{{ $instructor->name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Category</div>
                <div class="detail-value">{{ $course->category->name ?? 'N/A' }}</div>
            </div>
        </div>
        
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">ASTA Platform</div>
                <div class="signature-title">Learning Management System</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $instructor->name ?? 'Course Instructor' }}</div>
                <div class="signature-title">Course Instructor</div>
            </div>
        </div>
        
        <div class="certificate-id">
            Certificate ID: {{ $certificate_id }}
        </div>
    </div>
</body>
</html> 