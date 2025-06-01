@extends('layouts.app')

@section('title', 'داشبورد مدیریتی')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.css" />
<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-top: 1.5rem;
}
.dashboard-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px 0 rgba(37,99,235,0.07);
    padding: 2.2rem 1.5rem 1.5rem 1.5rem;
    position: relative;
    overflow: hidden;
    min-height: 200px;
}
.dashboard-card h3 {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 1.1rem;
    color: #2563eb;
    letter-spacing: .01em;
    display: flex;
    align-items: center;
    gap: .7em;
}
.dashboard-card .card-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: .8rem;
    direction: ltr;
}
.dashboard-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: .8rem;
}
.dashboard-table th, .dashboard-table td {
    padding: 0.5em 0.7em;
    text-align: right;
    border-bottom: 1px solid #eef1f8;
    font-size: 1em;
}
.dashboard-table th {
    background: #f4f6fa;
    color: #1e293b;
    font-weight: bold;
}
.dashboard-table tr:last-child td {
    border-bottom: none;
}
.dashboard-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.dashboard-list li {
    border-bottom: 1px solid #eef1f8;
    padding: .6em 0;
    display: flex;
    align-items: center;
    gap: 1em;
}
.dashboard-list li:last-child {border-bottom: none;}
.dashboard-list .person-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #eee;
    object-fit: cover;
    border: 1px solid #e0e7ef;
}
.dashboard-list .person-info {
    flex: 1;
}
.dashboard-list .person-name {
    font-weight: bold;
    color: #1976d2;
}
.dashboard-list .person-meta {
    color: #64748b;
    font-size: .92em;
}
.dashboard-list .person-phone {
    color: #0ea5e9;
    font-family: monospace;
    letter-spacing: .03em;
}
@media (max-width: 1000px) {
    .dashboard-grid {grid-template-columns: 1fr;}
}
@media (max-width: 700px) {
    .dashboard-card {padding: 1.1rem;}
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="dashboard-grid">

        <!-- بیشترین فروش و نمودار فروش -->
        <div class="dashboard-card">
            <h3><i class="fas fa-trophy text-yellow-600"></i>بیشترین فروش</h3>
            <div class="card-value">ریال ۹۱۶٬۶۶۹٬۰۰۰</div>
            <div class="mb-2" style="font-size:.95em;color:#64748b;">۱۴۰۳/۱/۱ - ۱۴۰۳/۱۲/۳۰</div>
            <div id="chart-top-sales"></div>
        </div>
        <div class="dashboard-card">
            <h3><i class="fas fa-chart-bar text-blue-600"></i>نمودار فروش ماهانه</h3>
            <div id="chart-sales"></div>
        </div>

        <!-- سود یا زیان و سود خالص -->
        <div class="dashboard-card">
            <h3><i class="fas fa-balance-scale text-green-600"></i>سود یا زیان</h3>
            <div class="card-value">ریال ۶٬۳۲۷٬۴۲۶٬۶۰۸</div>
            <div class="mb-2" style="font-size:.97em;color:#64748b;">سود خالص (۱۴۰۳/۱/۱ - ۱۴۰۳/۱۲/۳۰): <span style="color:#0ea5e9;font-weight:bold;">ریال ۸۴۳٬۳۱۴٬۰۰۰</span></div>
            <div id="chart-profit"></div>
        </div>
        <div class="dashboard-card">
            <h3><i class="fas fa-receipt text-orange-600"></i>آمار فروش</h3>
            <div class="card-value">ریال ۵۴۲٬۷۲۰٬۰۰۰</div>
            <div class="mb-2" style="font-size:.98em;color:#64748b;">بهای تمام شده کالای فروخته شده: <b style="color:#ef4444;">ریال ۷٬۶۰۴٬۵۴۱٬۹۲۸</b></div>
            <div id="chart-sales-stats"></div>
        </div>

        <!-- درآمدها و هزینه‌ها -->
        <div class="dashboard-card">
            <h3><i class="fas fa-wallet text-teal-600"></i>درآمدها</h3>
            <div class="card-value">ریال ۱٬۵۷۷٬۷۰۹٬۳۲۰</div>
            <div id="chart-incomes"></div>
        </div>
        <div class="dashboard-card">
            <h3><i class="fas fa-money-bill-wave text-red-600"></i>هزینه‌ها</h3>
            <div class="card-value">ریال ۷۲٬۳۵۰٬۰۰۰</div>
            <div id="chart-expenses"></div>
        </div>

        <!-- چک‌های دریافتی و بدهکاران -->
        <div class="dashboard-card">
            <h3><i class="fas fa-money-check-alt text-green-600"></i>چک‌های دریافتی</h3>
            <div class="card-value">ریال ۷۲٬۳۵۰٬۰۰۰ <span style="font-size:.8em; color:#64748b;">(تعداد: ۳)</span></div>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>بانک</th>
                        <th>عنوان</th>
                        <th>مبلغ</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ملی</td>
                        <td>آقای جمالی<br><span style="color:#888;">بعثت(۴۷۰۴۱)</span></td>
                        <td style="color:#2563eb;">۵۰,۰۰۰,۰۰۰</td>
                        <td>۱۳۹۸/۱۰/۳۰</td>
                    </tr>
                    <tr>
                        <td>سپه</td>
                        <td>مغازه گل ها<br><span style="color:#888;">ستارخان(۴۵۳۲۶۵)</span></td>
                        <td style="color:#2563eb;">۷,۳۵۰,۰۰۰</td>
                        <td>۱۳۹۸/۱۱/۲۴</td>
                    </tr>
                    <tr>
                        <td>صادرات</td>
                        <td>مغازه هوشمند - آقای هوشمند<br><span style="color:#888;">پاسداران(۵۴۱۳)</span></td>
                        <td style="color:#2563eb;">۱۵,۰۰۰,۰۰۰</td>
                        <td>۱۳۹۸/۱۲/۲۰</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dashboard-card">
            <h3><i class="fas fa-user-clock text-red-600"></i>بدهکاران</h3>
            <div class="card-value">ریال ۷۴۹٬۳۴۲٬۸۹۷٬۸۷۵ <span style="font-size:.8em; color:#64748b;">(تعداد: ۱۴)</span></div>
            <ul class="dashboard-list">
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">خانم محمدزادگان</span>
                        <span class="person-meta">۰۰۰۰۰۴</span>
                    </div>
                    <span class="person-phone">۰۹۱۹۸۶۹۷۸۹۵</span>
                </li>
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">آقای جمالی</span>
                        <span class="person-meta">۰۰۰۰۰۷</span>
                    </div>
                    <span class="person-phone">۰۹۱۹۲۵۸۷۸۹۹</span>
                </li>
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">دکتر مریم صباغی</span>
                        <span class="person-meta">۰۰۰۰۰۵</span>
                    </div>
                    <span class="person-phone">۰۹۱۲۱۲۳۴۵۶۷۸</span>
                </li>
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">مهندس جمیلی</span>
                        <span class="person-meta">۰۰۰۰۰۹</span>
                    </div>
                    <span class="person-phone">۰۹۱۳۵۲۲۴۵۶۵</span>
                </li>
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">یگانه کشت چمن</span>
                        <span class="person-meta">۰۰۰۰۳۱</span>
                    </div>
                    <span class="person-phone">۹۱۷۱۱۱۱۱۱۱</span>
                </li>
            </ul>
        </div>

        <!-- آخرین فروش ها و آخرین اشخاص ثبت شده -->
        <div class="dashboard-card">
            <h3><i class="fas fa-cash-register text-teal-600"></i>آخرین فروش‌ها</h3>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>شماره فاکتور</th>
                        <th>مشتری</th>
                        <th>مبلغ</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>F-1403-0123</td>
                        <td>شرکت دانش بنیان - دکتر محمد عباسی</td>
                        <td style="color:#10b981;">۳۹۰,۰۰۰,۰۰۰</td>
                        <td>۱۴۰۳/۰۲/۲۸</td>
                    </tr>
                    <tr>
                        <td>F-1403-0122</td>
                        <td>مغازه گل ها</td>
                        <td style="color:#10b981;">۱۷۰,۰۰۰,۰۰۰</td>
                        <td>۱۴۰۳/۰۲/۲۷</td>
                    </tr>
                    <tr>
                        <td>F-1403-0121</td>
                        <td>شرکت بصیرت - مهندس حسین بصیرت</td>
                        <td style="color:#10b981;">۱۱۰,۰۰۰,۰۰۰</td>
                        <td>۱۴۰۳/۰۲/۲۶</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dashboard-card">
            <h3><i class="fas fa-user-plus text-cyan-600"></i>آخرین اشخاص ثبت شده</h3>
            <ul class="dashboard-list">
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">یگانه کشت چمن</span>
                        <span class="person-meta">۰۰۰۰۳۱</span>
                    </div>
                    <span class="person-phone">۹۱۷۱۱۱۱۱۱۱</span>
                </li>
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">شرکت دانش بنیان - دکتر محمد عباسی</span>
                        <span class="person-meta">۰۰۰۰۰۲</span>
                    </div>
                    <span class="person-phone">۰۹۱۲۱۲۳۴۵۶۷۸</span>
                </li>
                <li>
                    <span class="person-avatar"><img src="{{ asset('img/user.png') }}" class="person-avatar"></span>
                    <div class="person-info">
                        <span class="person-name">شرکت بصیرت - مهندس حسین بصیرت</span>
                        <span class="person-meta">۰۰۰۰۰۳</span>
                    </div>
                    <span class="person-phone">۰۹۱۲۱۲۳۴۵۶۷۸</span>
                </li>
            </ul>
        </div>

        <!-- فروش امروز و نرخ ارز -->
        <div class="dashboard-card">
            <h3><i class="fas fa-calendar-day text-green-600"></i>فروش امروز</h3>
            <div class="card-value">ریال ۲۹٬۰۰۰٬۰۰۰</div>
            <div class="mt-2" style="color:#64748b;">آخرین فاکتور: F-1403-0123 (شرکت دانش بنیان - دکتر محمد عباسی)</div>
        </div>
        <div class="dashboard-card">
            <h3><i class="fas fa-coins text-yellow-600"></i>نرخ ارز و طلا</h3>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>قیمت</th>
                        <th>زمان</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>طلای ۱۸ عیار / ۷۵۰</td>
                        <td style="color:#ef4444;">۶۴٬۱۶۰٬۰۰۰</td>
                        <td>۱۹:۵۹:۳۸</td>
                    </tr>
                    <tr>
                        <td>طلای ۱۸ عیار / ۷۴۰</td>
                        <td style="color:#ef4444;">۶۳٬۳۰۴٬۰۰۰</td>
                        <td>۱۹:۵۹:۳۸</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-size:.9em; margin-top:.6em;"><a href="https://www.tgju.org/" target="_blank">منبع: tgju.org</a></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // نمودارها - اعداد نمونه
    new ApexCharts(document.querySelector("#chart-top-sales"), {
        chart: { type: 'bar', height: 120, fontFamily: 'IRANSans, Tahoma, sans-serif', sparkline: { enabled: true } },
        series: [{ name: 'فروش', data: [390, 170, 110, 80, 50] }],
        xaxis: { categories: ['شرکت دانش بنیان','مغازه گل ها','شرکت بصیرت','مغازه هوشمند','مغازه گل ها2'] },
        colors: ['#f59e42'],
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-sales"), {
        chart: { type: 'area', height: 140, fontFamily: 'IRANSans, Tahoma, sans-serif', toolbar: { show: false } },
        series: [{ name: 'فروش', data: [20, 23, 27, 26, 36, 38, 40, 41, 38, 39, 44, 47] }],
        xaxis: { categories: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'] },
        colors: ['#2563eb'],
        stroke: { curve: 'smooth', width:3 },
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-profit"), {
        chart: { type: 'line', height: 120, fontFamily: 'IRANSans, Tahoma, sans-serif', sparkline: { enabled: true } },
        series: [{ name: 'سود', data: [800, 950, 1020, 1200, 1300, 1100, 1150, 1200, 1250, 1400, 1440, 1500] }],
        xaxis: { categories: ['فرو','ارد','خرد','تیر','مر','شه','مهر','آبان','آذر','دی','بهم','اسف'] },
        colors: ['#0ea5e9'],
        stroke: { width: 3, curve: 'smooth' },
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-sales-stats"), {
        chart: { type: 'bar', height: 120, fontFamily: 'IRANSans, Tahoma, sans-serif', sparkline: { enabled: true } },
        series: [
            { name: 'فروش', data: [542, 410, 390, 460, 510, 430] },
            { name: 'بهای تمام شده', data: [760, 690, 710, 740, 800, 810] }
        ],
        xaxis: { categories: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور'] },
        colors: ['#10b981','#ef4444'],
        dataLabels: { enabled: false },
        legend: { show: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-incomes"), {
        chart: { type: 'area', height: 120, fontFamily: 'IRANSans, Tahoma, sans-serif', sparkline: { enabled: true } },
        series: [{ name: 'درآمد', data: [100, 140, 170, 160, 150, 155, 170, 180, 177, 190, 200, 209] }],
        xaxis: { categories: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'] },
        colors: ['#10b981'],
        stroke: { curve: 'smooth', width:3 },
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#chart-expenses"), {
        chart: { type: 'area', height: 120, fontFamily: 'IRANSans, Tahoma, sans-serif', sparkline: { enabled: true } },
        series: [{ name: 'هزینه', data: [72, 64, 80, 50, 56, 70, 65, 78, 80, 85, 88, 90] }],
        xaxis: { categories: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'] },
        colors: ['#ef4444'],
        stroke: { curve: 'smooth', width:3 },
        dataLabels: { enabled: false }
    }).render();
});
</script>
@endpush
