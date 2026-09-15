<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>لائحة العمل</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Tahoma, sans-serif;
            background: #fff;
            color: #222;
            padding: 20px;
            direction: rtl;
            font-size: 13px;
        }

        .doc-header {
            background: #2c3e50;
            color: #fff;
            padding: 12px 16px;
            margin-bottom: 16px;
        }

        .doc-header h1 {
            font-size: 16px;
            margin-bottom: 3px;
        }

        .doc-header .sub {
            font-size: 11px;
        }

        .content {
            line-height: 1.8;
        }
    </style>
</head>

<body>
    <div class="doc-header">
        <h1>لائحة العمل</h1>
        @if ($branch)
            <div class="sub">{{ $branch->name }}</div>
        @endif
    </div>

    <div class="content">
        @if ($branch && $branch->work_regulations)
            {!! $branch->work_regulations !!}
        @else
            <p>لا توجد لائحة عمل مضافة لهذا الفرع حالياً</p>
        @endif
    </div>
</body>

</html>
