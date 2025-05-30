<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="add-unit-form" method="POST" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="unitModalLabel">افزودن واحد جدید</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div id="unit-form-alert"></div>
                    <div class="mb-3">
                        <label for="unit-title" class="form-label">عنوان واحد <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="unit-title" name="title" required autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-success">ثبت واحد</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let unitForm = document.getElementById('add-unit-form');
    if(unitForm){
        unitForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var title = document.getElementById('unit-title').value.trim();
            document.getElementById('unit-form-alert').innerHTML = '';
            if (!title) {
                document.getElementById('unit-form-alert').innerHTML = '<div class="alert alert-danger">عنوان واحد را وارد کنید.</div>';
                return;
            }
            fetch("{{ route('units.store') }}", {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ title: title })
            })
            .then(async r => {
                let ok = r.ok;
                let data;
                try { data = await r.json(); } catch(e){ data = {}; }
                let unitTitle = data.title || title;
                if(ok){
                    let select = document.getElementById('selected-unit');
                    let option = document.createElement('option');
                    option.value = unitTitle;
                    option.text = unitTitle;
                    option.selected = true;
                    select.appendChild(option);
                    document.getElementById('unit-form-alert').innerHTML = '<div class="alert alert-success">واحد با موفقیت افزوده شد.</div>';
                    setTimeout(function(){
                        document.getElementById('unit-form-alert').innerHTML = '';
                        document.getElementById('unitModal').querySelector('.btn-close').click();
                    }, 800);
                    document.getElementById('unit-title').value = '';
                } else if(data && data.errors && data.errors.title) {
                    document.getElementById('unit-form-alert').innerHTML = '<div class="alert alert-danger">' + data.errors.title[0] + '</div>';
                } else {
                    document.getElementById('unit-form-alert').innerHTML = '<div class="alert alert-danger">خطا در ثبت واحد</div>';
                }
            })
            .catch(err => {
                document.getElementById('unit-form-alert').innerHTML = '<div class="alert alert-danger">خطا در ارتباط با سرور</div>';
            });
        });
    }
});
</script>
