<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Villa - Admin</title>
    <style>
        body {
            font-family: Instrument Sans, Arial;
            padding: 20px;
            background: #f6f6f6
        }
    </style>
</head>

<body>
    <div style="max-width:900px;margin:20px auto;background:#fff;padding:18px;border:1px solid #e6e6e6">
        <h2>Edit Villa</h2>
        <form method="POST" action="#" enctype="multipart/form-data">
            <div style="display:flex;gap:12px">
                <div style="flex:1">
                    <label>Thumbnail / Pictures</label>
                    <div style="display:flex;gap:8px;margin-top:8px">
                        <div
                            style="width:160px;height:120px;border:1px dashed #ccc;display:flex;align-items:center;justify-content:center">
                            thumbnail</div>
                        <div
                            style="width:160px;height:120px;border:1px dashed #ccc;display:flex;align-items:center;justify-content:center">
                            pictures</div>
                    </div>
                </div>
                <div style="width:300px">
                    <label>Price</label><br>
                    <input type="text" style="width:100%;padding:8px;margin-bottom:8px">
                    <label>Kamar Mandi / Kamar Tidur</label><br>
                    <input type="text" style="width:100%;padding:8px;margin-bottom:8px">
                    <label>Nama Villa</label><br>
                    <input type="text" style="width:100%;padding:8px;margin-bottom:8px">
                    <label>Kapasitas</label><br>
                    <input type="text" style="width:100%;padding:8px">
                </div>
            </div>
            <div style="margin-top:12px;text-align:right">
                <button style="padding:10px 14px;background:#000;color:#fff;border:none">Simpan</button>
            </div>
        </form>
    </div>
</body>

</html>