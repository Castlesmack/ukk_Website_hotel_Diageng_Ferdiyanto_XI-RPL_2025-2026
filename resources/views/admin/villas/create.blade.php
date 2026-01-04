<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create Villa - Admin</title>
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
        <h2>Add Villa</h2>
        <form method="POST" action="{{ route('admin.villas.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="display:flex;gap:12px">
                <div style="flex:1">
                    <label>Thumbnail / Pictures</label>
                    <input type="file" name="thumbnail" style="width:100%;padding:8px;margin-bottom:8px">
                    <input type="file" name="images[]" multiple style="width:100%;padding:8px;margin-bottom:8px">
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
                    <input type="number" name="base_price" step="0.01" style="width:100%;padding:8px;margin-bottom:8px"
                        required>
                    <label>Bedrooms</label><br>
                    <input type="number" name="rooms_total" style="width:100%;padding:8px;margin-bottom:8px" required>
                    <label>Name</label><br>
                    <input type="text" name="name" style="width:100%;padding:8px;margin-bottom:8px" required>
                    <label>Capacity</label><br>
                    <input type="number" name="capacity" style="width:100%;padding:8px;margin-bottom:8px" required>
                    <label>Description</label><br>
                    <textarea name="description" style="width:100%;padding:8px;margin-bottom:8px"></textarea>
                    <label>Status</label><br>
                    <select name="status" style="width:100%;padding:8px">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
            <div style="margin-top:12px;text-align:right">
                <button style="padding:10px 14px;background:#000;color:#fff;border:none">Create</button>
            </div>
        </form>
    </div>
</body>

</html>