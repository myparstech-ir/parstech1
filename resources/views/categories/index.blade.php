@extends('layouts.app')

@section('title', 'لیست دسته‌بندی‌ها (جدولی درختی)')

@section('styles')
<style>
    /* CSS Variables & Base Styles */
    :root {
        /* Primary Colors */
        --primary-50: #eff6ff;
        --primary-100: #dbeafe;
        --primary-200: #bfdbfe;
        --primary-300: #93c5fd;
        --primary-400: #60a5fa;
        --primary-500: #2776d1;
        --primary-600: #2563eb;
        --primary-700: #1d4ed8;
        --primary-800: #1e40af;
        --primary-900: #1e3a8a;

        /* Success Colors */
        --success-50: #f0fdf4;
        --success-100: #dcfce7;
        --success-200: #bbf7d0;
        --success-300: #86efac;
        --success-400: #4ade80;
        --success-500: #1cb08e;
        --success-600: #16a34a;
        --success-700: #15803d;
        --success-800: #166534;
        --success-900: #14532d;

        /* Warning Colors */
        --warning-50: #fffbeb;
        --warning-100: #fef3c7;
        --warning-200: #fde68a;
        --warning-300: #fcd34d;
        --warning-400: #fbbf24;
        --warning-500: #c97e10;
        --warning-600: #d97706;
        --warning-700: #b45309;
        --warning-800: #92400e;
        --warning-900: #78350f;

        /* Danger Colors */
        --danger-50: #fef2f2;
        --danger-100: #fee2e2;
        --danger-200: #fecaca;
        --danger-300: #fca5a5;
        --danger-400: #f87171;
        --danger-500: #ef4444;
        --danger-600: #dc2626;
        --danger-700: #b91c1c;
        --danger-800: #991b1b;
        --danger-900: #7f1d1d;

        /* Gray Colors */
        --gray-50: #f9fafb;
        --gray-100: #f7fafd;
        --gray-200: #e2e6ee;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;

        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        --shadow-inner: inset 0 2px 4px 0 rgb(0 0 0 / 0.05);

        /* Border Radius */
        --radius-none: 0;
        --radius-sm: 0.125rem;
        --radius-default: 0.25rem;
        --radius-md: 0.375rem;
        --radius-lg: 0.5rem;
        --radius-xl: 0.75rem;
        --radius-2xl: 1rem;
        --radius-3xl: 1.5rem;
        --radius-full: 9999px;

        /* Transitions */
        --transition-all: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-colors: color 0.3s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-opacity: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-shadow: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-transform: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);

        /* Animation Timing */
        --duration-75: 75ms;
        --duration-100: 100ms;
        --duration-150: 150ms;
        --duration-200: 200ms;
        --duration-300: 300ms;
        --duration-500: 500ms;
        --duration-700: 700ms;
        --duration-1000: 1000ms;
    }

    /* Base Styles */
    body {
        background: var(--gray-100);
        font-family: 'IRANSans', 'Vazir', Tahoma, Arial;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Container & Layout */
    .categories-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Header Section */
    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }

    .categories-header::after {
        content: '';
        position: absolute;
        bottom: -1rem;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(to right,
            rgba(99, 179, 237, 0),
            rgba(99, 179, 237, 0.4),
            rgba(99, 179, 237, 0)
        );
    }

    .categories-title {
        font-size: 1.75rem;
        color: var(--primary-700);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
    }

    .categories-title i {
        font-size: 1.35em;
        opacity: 0.9;
        background: linear-gradient(135deg, var(--primary-500), var(--primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0px 1px 1px rgba(0, 0, 0, 0.1));
    }

    .categories-title::after {
        content: '';
        position: absolute;
        bottom: -0.5rem;
        left: 0;
        width: 50%;
        height: 3px;
        background: linear-gradient(to right, var(--primary-500), transparent);
        border-radius: var(--radius-full);
    }

    /* Add Category Button */
    .add-category-btn {
        background: linear-gradient(135deg, var(--success-500), var(--success-600));
        color: white;
        border: none;
        padding: 0.875rem 1.75rem;
        border-radius: var(--radius-xl);
        font-size: 1.05rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-all);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }

    .add-category-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
        transition: var(--transition-opacity);
        opacity: 0;
    }

    .add-category-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .add-category-btn:hover::before {
        opacity: 1;
    }

    .add-category-btn:active {
        transform: translateY(0);
    }

    .add-category-btn i {
        font-size: 1.1em;
        transition: var(--transition-transform);
    }

    .add-category-btn:hover i {
        transform: rotate(90deg);
    }

    /* Table Wrapper */
    .cat-tree-wrapper {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: var(--transition-shadow);
        position: relative;
    }

    .cat-tree-wrapper::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: var(--radius-2xl);
        padding: 2px;
        background: linear-gradient(135deg,
            var(--primary-300),
            var(--primary-500),
            var(--primary-700)
        );
        -webkit-mask: linear-gradient(#fff 0 0) content-box,
                      linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box,
              linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .cat-tree-wrapper:hover {
        box-shadow: var(--shadow-xl);
    }

    /* Table Styles */
    .cat-tree-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    /* Table Header */
    .cat-tree-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
        backdrop-filter: blur(8px);
    }

    .cat-tree-table th {
        background: linear-gradient(180deg,
            rgba(243, 247, 250, 0.95),
            rgba(237, 242, 247, 0.95)
        );
        color: var(--primary-700);
        font-weight: 800;
        font-size: 1.1rem;
        padding: 1.25rem 1.5rem;
        text-align: right;
        border-bottom: 2px solid var(--primary-100);
        position: relative;
        transition: var(--transition-colors);
    }

    .cat-tree-table th:hover {
        background: linear-gradient(180deg,
            rgba(235, 242, 249, 0.95),
            rgba(230, 238, 245, 0.95)
        );
        color: var(--primary-800);
    }

    .cat-tree-table th::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-500);
        transform: scaleX(0);
        transition: var(--transition-transform);
    }

    .cat-tree-table th:hover::after {
        transform: scaleX(1);
    }

    /* Table Body */
    .cat-tree-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        font-size: 1rem;
        background: white;
        transition: var(--transition-colors);
    }

    /* Table Row */
    .cat-tree-row {
        transition: var(--transition-all);
        position: relative;
    }

    .cat-tree-row:hover {
        background: var(--primary-50);
    }

    .cat-tree-row:hover td {
        background: transparent;
    }

    .cat-tree-row::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(90deg,
            var(--primary-100),
            transparent 10%
        );
        opacity: 0;
        transition: var(--transition-opacity);
        pointer-events: none;
    }

    .cat-tree-row:hover::before {
        opacity: 0.5;
    }

    /* Tree Structure */
    .cat-tree-indent {
        display: inline-block;
        width: 24px;
        height: 2px;
        vertical-align: middle;
        position: relative;
        background: var(--gray-200);
        margin-right: 0.5rem;
        border-radius: var(--radius-full);
        transition: var(--transition-all);
    }

    .cat-tree-indent::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 2px;
        height: 24px;
        background: var(--gray-200);
        transform: translateY(-50%);
        border-radius: var(--radius-full);
        transition: var(--transition-all);
    }

    .cat-tree-row:hover .cat-tree-indent,
    .cat-tree-row:hover .cat-tree-indent::before {
        background: var(--primary-300);
    }

    /* Toggle Button */
    .cat-tree-toggle {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: var(--radius-full);
        color: var(--primary-600);
        transition: var(--transition-all);
        position: relative;
        margin-left: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cat-tree-toggle::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--primary-100);
        border-radius: var(--radius-full);
        transform: scale(0.8);
        opacity: 0;
        transition: var(--transition-all);
    }

    .cat-tree-toggle:hover::before {
        transform: scale(1);
        opacity: 1;
    }

    .cat-tree-toggle i {
        font-size: 1.2rem;
        transition: var(--transition-transform);
        position: relative;
        z-index: 1;
    }

    .cat-tree-toggle[aria-expanded="true"] i {
        transform: rotate(90deg);
    }

    /* Category Types */
    .cat-type-prod,
    .cat-type-serv,
    .cat-type-pers {
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-lg);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .cat-type-prod {
        background: var(--success-50);
        color: var(--success-700);
    }

    .cat-type-serv {
        background: var(--primary-50);
        color: var(--primary-700);
    }

    .cat-type-pers {
        background: var(--warning-50);
        color: var(--warning-700);
    }

    .cat-type-prod i,
    .cat-type-serv i,
    .cat-type-pers i {
        font-size: 1.1em;
        opacity: 0.8;
    }

    /* Category Images */
    .cat-tree-img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: var(--radius-xl);
        border: 2px solid var(--primary-100);
        transition: var(--transition-all);
        background: var(--primary-50);
        position: relative;
    }

    .cat-tree-img::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: var(--radius-xl);
        border: 2px solid transparent;
        background: linear-gradient(135deg,
            var(--primary-300),
            var(--primary-500)
        ) border-box;
        -webkit-mask: linear-gradient(#fff 0 0) padding-box,
                      linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) padding-box,
              linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: var(--transition-opacity);
    }

    .cat-tree-img:hover {
        transform: scale(1.1) rotate(3deg);
        box-shadow: var(--shadow-lg);
    }

    .cat-tree-img:hover::after {
        opacity: 1;
    }

    /* Badges */
    .cat-tree-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.875rem;
        font-size: 0.95rem;
        border-radius: var(--radius-lg);
        background: var(--primary-50);
        color: var(--primary-700);
        font-weight: 600;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition-all);
    }

    .cat-tree-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg,
            transparent,
            rgba(99, 179, 237, 0.1),
            transparent
        );
        transform: translateX(-100%);
        transition: var(--transition-transform);
    }

    .cat-tree-badge:hover::before {
        transform: translateX(100%);
    }

    .cat-tree-badge i {
        font-size: 0.9em;
        opacity: 0.8;
    }

    /* Description Text */
    .cat-tree-desc {
        color: var(--gray-600);
        font-size: 0.95rem;
        max-width: 300px;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        position: relative;
        transition: var(--transition-colors);
    }

    .cat-tree-desc:hover {
        color: var(--gray-800);
    }

    .cat-tree-desc::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 50px;
        height: 100%;
        background: linear-gradient(to right, transparent, white);
        pointer-events: none;
    }

    /* Action Buttons */
    .cat-tree-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .cat-tree-action-btn {
        background: transparent;
        color: var(--primary-600);
        border: 2px solid var(--primary-100);
        border-radius: var(--radius-lg);
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-all);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .cat-tree-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--primary-600);
        transform: scaleX(0);
        transform-origin: left;
        transition: var(--transition-transform);
        z-index: 0;
    }

    .cat-tree-action-btn:hover {
        color: white;
        border-color: var(--primary-600);
        transform: translateY(-2px);
    }

    .cat-tree-action-btn:hover::before {
        transform: scaleX(1);
    }

    .cat-tree-action-btn i {
        position: relative;
        z-index: 1;
        transition: var(--transition-transform);
    }

    .cat-tree-action-btn span {
        position: relative;
        z-index: 1;
    }

    .cat-tree-action-btn:hover i {
        transform: scale(1.2);
    }

    .cat-tree-action-btn.delete-btn {
        color: var(--danger-600);
        border-color: var(--danger-100);
    }

    .cat-tree-action-btn.delete-btn::before {
        background: var(--danger-600);
    }

    .cat-tree-action-btn.delete-btn:hover {
        border-color: var(--danger-600);
    }

    /* Success Alert */
    .alert-success {
        background: linear-gradient(135deg,
            var(--success-500),
            var(--success-600)
        );
        color: white;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-xl);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.5s ease-out;
    }

    .alert-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg,
            transparent,
            rgba(255, 255, 255, 0.1),
            transparent
        );
        transform: translateX(-100%);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-1rem);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loading States */
    .cat-tree-row.loading {
        opacity: 0.7;
        pointer-events: none;
    }

    .cat-tree-row.loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.4),
            transparent
        );
        transform: translateX(-100%);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        100% {
            transform: translateX(100%);
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-0.5rem);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .cat-tree-row {
        animation: fadeIn 0.3s ease-out;
    }

    .cat-tree-row[data-level="0"] {
        animation-delay: calc(var(--duration-75) * var(--row-index, 0));
    }

    /* Hover Effects */
    .cat-tree-row:hover .cat-tree-badge {
        background: var(--primary-100);
        transform: translateY(-1px);
    }

    .cat-tree-row:hover .cat-type-prod {
        background: var(--success-100);
    }

    .cat-tree-row:hover .cat-type-serv {
        background: var(--primary-100);
    }

    .cat-tree-row:hover .cat-type-pers {
        background: var(--warning-100);
    }

    /* Focus States */
    .cat-tree-action-btn:focus-visible {
        outline: 2px solid var(--primary-500);
        outline-offset: 2px;
    }

    .cat-tree-toggle:focus-visible {
        outline: 2px solid var(--primary-500);
        outline-offset: 2px;
    }

    /* Print Styles */
    @media print {
        .cat-tree-wrapper {
            box-shadow: none;
        }

        .cat-tree-action-btn {
            display: none;
        }

        .categories-header {
            display: none;
        }
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .cat-tree-table th,
        .cat-tree-table td {
            padding: 1rem 1.25rem;
        }

        .cat-tree-img {
            width: 44px;
            height: 44px;
        }

        .cat-tree-desc {
            max-width: 250px;
        }
    }

    @media (max-width: 992px) {
        .categories-title {
            font-size: 1.5rem;
        }

        .cat-tree-table {
            font-size: 0.95rem;
        }

        .cat-tree-badge {
            font-size: 0.9rem;
            padding: 0.35rem 0.75rem;
        }

        .cat-tree-action-btn {
            padding: 0.4rem 0.875rem;
            font-size: 0.9rem;
        }

        .cat-tree-img {
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 768px) {
        .categories-container {
            padding: 1.5rem 1rem;
        }

        .categories-title {
            font-size: 1.35rem;
        }

        .add-category-btn {
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
        }

        .cat-tree-table th,
        .cat-tree-table td {
            padding: 0.875rem 1rem;
        }

        .cat-tree-table {
            font-size: 0.9rem;
        }

        .cat-tree-desc {
            max-width: 200px;
        }

        .cat-tree-img {
            width: 36px;
            height: 36px;
        }

        .cat-tree-actions {
            flex-direction: column;
            gap: 0.5rem;
        }

        .cat-tree-action-btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .categories-container {
            padding: 1rem 0.75rem;
        }

        .categories-title {
            font-size: 1.25rem;
        }

        .cat-tree-table th,
        .cat-tree-table td {
            padding: 0.75rem;
        }

        .cat-tree-table {
            font-size: 0.85rem;
        }

        .cat-tree-badge {
            font-size: 0.85rem;
            padding: 0.25rem 0.625rem;
        }

        .cat-tree-img {
            width: 32px;
            height: 32px;
        }

        .cat-tree-desc {
            max-width: 150px;
        }
    }

    /* RTL Specific Styles */
    [dir="rtl"] .cat-tree-toggle i {
        transform: rotate(180deg);
    }

    [dir="rtl"] .cat-tree-toggle[aria-expanded="true"] i {
        transform: rotate(90deg);
    }

    [dir="rtl"] .cat-tree-indent::before {
        right: 0;
        left: auto;
    }

    [dir="rtl"] .cat-tree-badge {
        margin-right: 0;
        margin-left: 0.5rem;
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        :root {
            --primary-50: #1a365d;
            --primary-100: #1e429f;
            --primary-200: #1a4bbd;
            --primary-300: #2563eb;
            --primary-400: #3b82f6;
            --primary-500: #60a5fa;
            --primary-600: #93c5fd;
            --primary-700: #bfdbfe;
            --primary-800: #dbeafe;
            --primary-900: #eff6ff;

            --success-50: #064e3b;
            --success-100: #065f46;
            --success-200: #047857;
            --success-300: #059669;
            --success-400: #10b981;
            --success-500: #34d399;
            --success-600: #6ee7b7;
            --success-700: #a7f3d0;
            --success-800: #d1fae5;
            --success-900: #ecfdf5;

            --warning-50: #451a03;
            --warning-100: #78350f;
            --warning-200: #92400e;
            --warning-300: #b45309;
            --warning-400: #d97706;
            --warning-500: #f59e0b;
            --warning-600: #fbbf24;
            --warning-700: #fcd34d;
            --warning-800: #fde68a;
            --warning-900: #fef3c7;

            --gray-50: #18181b;
            --gray-100: #27272a;
            --gray-200: #3f3f46;
            --gray-300: #52525b;
            --gray-400: #71717a;
            --gray-500: #a1a1aa;
            --gray-600: #d4d4d8;
            --gray-700: #e4e4e7;
            --gray-800: #f4f4f5;
            --gray-900: #fafafa;
        }

        body {
            background: var(--gray-100);
            color: var(--gray-200);
        }

        .cat-tree-wrapper {
            background: var(--gray-50);
        }

        .cat-tree-table th {
            background: linear-gradient(180deg,
                rgba(39, 39, 42, 0.95),
                rgba(63, 63, 70, 0.95)
            );
            color: var(--primary-400);
            border-bottom-color: var(--gray-700);
        }

        .cat-tree-table td {
            background: var(--gray-50);
            border-bottom-color: var(--gray-200);
            color: var(--gray-700);
        }

        .cat-tree-row:hover td {
            background: var(--gray-100);
        }

        .cat-tree-badge {
            background: var(--primary-900);
            color: var(--primary-200);
        }

        .cat-tree-desc {
            color: var(--gray-500);
        }

        .cat-tree-desc:hover {
            color: var(--gray-400);
        }

        .cat-tree-desc::after {
            background: linear-gradient(to right, transparent, var(--gray-50));
        }

        .cat-tree-action-btn {
            background: var(--gray-100);
            color: var(--primary-400);
            border-color: var(--gray-700);
        }

        .cat-tree-action-btn:hover {
            background: var(--primary-700);
            color: var(--gray-900);
        }

        .cat-tree-toggle {
            color: var(--primary-400);
        }

        .cat-tree-toggle:hover {
            background: var(--primary-900);
        }

        .cat-type-prod {
            background: var(--success-900);
            color: var(--success-300);
        }

        .cat-type-serv {
            background: var(--primary-900);
            color: var(--primary-300);
        }

        .cat-type-pers {
            background: var(--warning-900);
            color: var(--warning-300);
        }

        .alert-success {
            background: linear-gradient(135deg,
                var(--success-600),
                var(--success-500)
            );
        }
    }
</style>
@endsection

@section('content')
<div class="categories-container">
    <div class="categories-header">
        <h2 class="categories-title">
            <i class="fa fa-sitemap"></i>
            دسته‌بندی‌ها (جدولی درختی)
        </h2>
        <a href="{{ route('categories.create') }}" class="add-category-btn">
            <i class="fa fa-plus"></i>
            <span>افزودن دسته‌بندی جدید</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="cat-tree-wrapper">
        <div class="table-responsive">
            <table class="cat-tree-table">
                <thead>
                    <tr>
                        <th style="width:35px"></th>
                        <th>نام دسته</th>
                        <th>نوع</th>
                        <th>کد</th>
                        <th>توضیح</th>
                        <th>تصویر</th>
                        <th>تعداد محصول</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function renderCategoryRows($categories, $level = 0, $parentId = null) {
                            foreach($categories as $index => $category) {
                                $hasChildren = $category->children && $category->children->count() > 0;
                                $typeClass = $category->category_type == 'product' ? 'cat-type-prod' :
                                            ($category->category_type == 'service' ? 'cat-type-serv' : 'cat-type-pers');
                                $typeIcon = $category->category_type == 'product' ? 'box' :
                                           ($category->category_type == 'service' ? 'cogs' : 'user');
                                $typeText = $category->category_type == 'product' ? 'محصول' :
                                           ($category->category_type == 'service' ? 'خدمت' : 'شخص');

                                echo '<tr class="cat-tree-row" data-id="'.$category->id.'"
                                     data-parent="'.($category->parent_id ?: '').'"
                                     data-level="'.$level.'"
                                     style="--row-index:'.$index.'"
                                     '.($level > 0 ? ' hidden' : '').'>';

                                // Toggle Column
                                echo '<td>';
                                if($hasChildren) {
                                    echo '<button class="cat-tree-toggle" aria-expanded="false"
                                           data-toggle-id="'.$category->id.'"
                                           title="باز/بستن زیرشاخه‌ها">
                                           <i class="fa fa-caret-left"></i>
                                          </button>';
                                } else {
                                    echo str_repeat('<span class="cat-tree-indent"></span>', $level+1);
                                }
                                echo '</td>';

                                // Name Column
                                echo '<td>';
                                echo str_repeat('<span class="cat-tree-indent"></span>', $level);
                                echo '<span class="cat-name">'.e($category->name).'</span>';
                                echo '</td>';

                                // Type Column
                                echo '<td>';
                                echo '<span class="'.$typeClass.'">
                                        <i class="fa fa-'.$typeIcon.'"></i>
                                        <span>'.$typeText.'</span>
                                     </span>';
                                echo '</td>';

                                // Code Column
                                echo '<td>';
                                echo '<span class="cat-tree-badge">
                                        <i class="fa fa-hashtag"></i>
                                        <span>'.e($category->code).'</span>
                                     </span>';
                                echo '</td>';

                                // Description Column
                                echo '<td>';
                                echo '<span class="cat-tree-desc" title="'.e($category->description).'">'.
                                     e($category->description).'</span>';
                                echo '</td>';

                                // Image Column
                                echo '<td>';
                                if($category->image) {
                                    echo '<img src="/storage/'.e($category->image).'"
                                          class="cat-tree-img"
                                          alt="'.$category->name.'"
                                          loading="lazy">';
                                }
                                echo '</td>';

                                // Products Count Column
                                echo '<td>';
                                echo '<span class="cat-tree-badge">
                                        <i class="fa fa-cubes"></i>
                                        <span>'.($category->products ? $category->products->count() : 0).'</span>
                                     </span>';
                                echo '</td>';

                                // Actions Column
                                echo '<td>';
                                echo '<div class="cat-tree-actions">';
                                echo '<a href="'.route('categories.edit', $category->id).'"
                                      class="cat-tree-action-btn">
                                      <i class="fa fa-edit"></i>
                                      <span>ویرایش</span>
                                     </a>';
                                echo '<form action="'.route('categories.destroy', $category->id).'"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirmDelete(event)">';
                                echo csrf_field();
                                echo method_field('DELETE');
                                echo '<button type="submit" class="cat-tree-action-btn delete-btn">
                                        <i class="fa fa-trash"></i>
                                        <span>حذف</span>
                                      </button>';
                                echo '</form>';
                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';

                                if($hasChildren) {
                                    renderCategoryRows($category->children, $level + 1, $category->id);
                                }
                            }
                        }
                    @endphp

                    @php
                        renderCategoryRows($categories);
                    @endphp
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Cache DOM elements
    const table = document.querySelector('.cat-tree-table');
    const toggleButtons = document.querySelectorAll('.cat-tree-toggle');

    // Add event listeners to all toggle buttons
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', handleToggleClick);
    });

    // Handle toggle button click
    function handleToggleClick(event) {
        const btn = event.currentTarget;
        const id = btn.getAttribute('data-toggle-id');
        const expanded = btn.getAttribute('aria-expanded') === 'true';

        // Toggle button state with animation
        btn.setAttribute('aria-expanded', !expanded);

        // Toggle children with animation
        toggleChildren(id, !expanded);
    }

    // Toggle children rows
    function toggleChildren(parentId, show) {
        const childRows = document.querySelectorAll(`tr[data-parent="${parentId}"]`);

        childRows.forEach(row => {
            if(show) {
                // Show animation
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                row.removeAttribute('hidden');

                // Trigger reflow
                row.offsetHeight;

                // Apply animation
                row.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            } else {
                // Hide animation
                row.style.transition = 'opacity 0.2s ease-out, transform 0.2s ease-out';
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';

                // Hide after animation
                setTimeout(() => {
                    row.setAttribute('hidden', 'hidden');
                    row.style.opacity = '';
                    row.style.transform = '';
                }, 200);
            }

            // Recursively handle nested children
            if(!show) {
                const id = row.getAttribute('data-id');
                toggleChildren(id, false);

                const btn = row.querySelector('.cat-tree-toggle');
                if(btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Confirmation dialog for delete
    window.confirmDelete = function(event) {
        event.preventDefault();

        if(confirm('آیا از حذف این دسته‌بندی اطمینان دارید؟')) {
            const form = event.target;
            const row = form.closest('tr');

            // Add loading state
            row.classList.add('loading');

            // Submit the form
            form.submit();
        }

        return false;
    }

    // Optional: Add keyboard navigation
    table.addEventListener('keydown', function(e) {
        if(e.target.classList.contains('cat-tree-toggle')) {
            if(e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                e.target.click();
            }
        }
    });
});
</script>
@endsection
