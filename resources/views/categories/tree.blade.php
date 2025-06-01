<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>درخت دسته‌بندی</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- فونت ایران سنس یا Vazirmatn -->
    <link href="https://cdn.jsdelivr.net/npm/vazirmatn@33.003.0/dist/font-face.css" rel="stylesheet">
    <!-- Bootstrap (در صورت نیاز) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- استایل Treejs (در public/css) -->
    <link rel="stylesheet" href="/css/treejs.min.css">
    <style>
        body { font-family: Vazirmatn, Tahoma, Arial, sans-serif; background: #f9f9f9; }
        .treejs { direction: ltr; margin-bottom: 40px; }
        .treejs li { font-size: 16px; }
        .tree-container { background: #fff; border-radius: 10px; box-shadow: 0 4px 16px #0001; padding: 32px; max-width: 600px; margin: 42px auto; }
        h1 { color: #2d3748; }
    </style>
</head>
<body>
    <div class="tree-container">
        <h1 class="mb-4">درخت دسته‌بندی‌ها</h1>
        <div id="tree" class="mb-3"></div>
        <div id="selected-category" class="alert alert-info d-none"></div>
    </div>
    <script src="/js/tree.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            axios.get('/api/categories/tree')
                .then(function(res) {
                    let treeData = res.data;
                    let tree = new TreeJS(document.getElementById('tree'), {
                        data: treeData,
                        autoOpen: true,
                        dragAndDrop: false
                    });

                    tree.on('select', function(node) {
                        let div = document.getElementById('selected-category');
                        div.textContent = 'دسته‌بندی انتخاب‌شده: ' + node.text;
                        div.classList.remove('d-none');
                    });
                })
                .catch(function(err) {
                    document.getElementById('tree').innerHTML =
                        '<div class="alert alert-danger">خطا در دریافت داده دسته‌بندی!</div>';
                });
        });
    </script>
</body>
</html>
