<x-filament-widgets::widget>
    
       <div class="category-overview-wrapper">

    {{-- HERO --}}
    <div class="category-hero">

        <div class="category-hero-left">

            <div class="category-hero-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M4 7h16M4 12h16M4 17h16"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                </svg>
            </div>

            <div>
                <h2>Category Management</h2>
                <p>Organize and manage your medicine categories</p>
            </div>

        </div>

        <button
    type="button"
    x-data
    x-on:click="$wire.$parent.mountAction('create')"
    class="category-hero-add-btn"
>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
    </svg>
    New Category
</button>
    </div>


    {{-- STATS --}}
    <div class="category-stats-grid">

        <div class="category-stat-card">

            <div class="category-stat-icon teal">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M4 7h16M4 12h16M4 17h16"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                </svg>
            </div>

            <div>
                <span class="category-stat-number">
                    {{ $totalCategories }}
                </span>

                <span class="category-stat-label">
                    Total Categories
                </span>
            </div>

        </div>


        <div class="category-stat-card">

            <div class="category-stat-icon green">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M5 12l4 4L19 6"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </div>

            <div>
                <span class="category-stat-number">
                    {{ $activeCategories }}
                </span>

                <span class="category-stat-label">
                    Active Categories
                </span>
            </div>

        </div>


        <div class="category-stat-card">

            <div class="category-stat-icon blue">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M7 3h10v18H7z"
                        stroke="currentColor"
                        stroke-width="1.7"
                    />

                    <path
                        d="M9.5 7h5M9.5 11h5M9.5 15h3"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                </svg>
            </div>

            <div>
                <span class="category-stat-number">
                    {{ $totalMedicines }}
                </span>

                <span class="category-stat-label">
                    Medicines Assigned
                </span>
            </div>

        </div>

    </div>


    {{-- INFO GLASS CARD --}}
    <div class="category-info-card">

        <div class="category-info-icon">

            <svg viewBox="0 0 24 24" fill="none">
                <circle
                    cx="12"
                    cy="12"
                    r="9"
                    stroke="currentColor"
                    stroke-width="1.6"
                />

                <path
                    d="M12 16v-4"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                />

                <circle
                    cx="12"
                    cy="8"
                    r="1"
                    fill="currentColor"
                />
            </svg>

        </div>

        <div class="category-info-content">
            <strong>Manage your medicine categories</strong>

            <span>
                Create organized categories to make medicine management
                faster and easier.
            </span>
        </div>

    </div>


    <style>

        .category-overview-wrapper {
            width: 100%;
            margin-bottom: 18px;
        }

        /* HERO */

        .category-hero {
            position: relative;

            min-height: 96px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 18px 24px;

            overflow: hidden;

            border-radius: 14px;

            background:
                linear-gradient(
                    135deg,
                    #00707A,
                    #00707A 55%,
                    #00707A
                );

            box-shadow:
                0 12px 30px rgba(0, 105, 100, .12);
        }

        .category-hero::after {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            right: -60px;
            top: -100px;

            border-radius: 50%;

            background:
                rgba(255,255,255,.07);
        }

        .category-hero-left {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;

            gap: 15px;
        }

        .category-hero-icon {
            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            color: white;

            background:
                rgba(255,255,255,.13);

            border:
                1px solid rgba(255,255,255,.16);

            backdrop-filter: blur(10px);
        }

        .category-hero-icon svg {
            width: 24px;
            height: 24px;
        }

        .category-hero h2 {
            margin: 0;

            color: white;

            font-size: 18px;
            font-weight: 700;
        }

        .category-hero p {
            margin: 4px 0 0;

            color:
                rgba(255,255,255,.76);

            font-size: 12px;
        }

        .category-hero-badge {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;

            gap: 7px;

            padding: 9px 13px;

            border-radius: 9px;

            color:
                rgba(255,255,255,.88);

            background:
                rgba(255,255,255,.10);

            border:
                1px solid rgba(255,255,255,.12);

            backdrop-filter: blur(10px);

            font-size: 11px;
            font-weight: 600;
        }

        .category-hero-badge span {
            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: #7ff0cb;

            box-shadow:
                0 0 0 3px rgba(127,240,203,.12);
        }


        /* STATS */

        .category-stats-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 15px;

            margin-top: 17px;
        }

        .category-stat-card {
            min-height: 88px;

            display: flex;
            align-items: center;

            gap: 13px;

            padding: 15px 17px;

            border-radius: 13px;

            background:
                rgba(255,255,255,.72);

            border:
                1px solid rgba(218,231,233,.9);

            box-shadow:
                0 5px 18px rgba(0,70,80,.035),
                inset 0 1px 0 rgba(255,255,255,.9);

            backdrop-filter: blur(14px);

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .category-stat-card:hover {
            transform: translateY(-2px);

            box-shadow:
                0 10px 25px rgba(0,80,90,.07),
                inset 0 1px 0 rgba(255,255,255,1);
        }

        .category-stat-icon {
            width: 42px;
            height: 42px;

            min-width: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;
        }

        .category-stat-icon svg {
            width: 21px;
            height: 21px;
        }

        .category-stat-icon.teal {
            color: #008b91;
            background: #e3f5f4;
        }

        .category-stat-icon.green {
            color: #18a45f;
            background: #e5f7ed;
        }

        .category-stat-icon.blue {
            color: #4389c8;
            background: #eaf3fb;
        }

        .category-stat-number {
            display: block;

            color: #17374a;

            font-size: 23px;
            font-weight: 750;

            line-height: 1;
        }

        .category-stat-label {
            display: block;

            margin-top: 5px;

            color: #78909c;

            font-size: 11px;
        }


        /* INFO CARD */

        .category-info-card {
            display: flex;
            align-items: center;

            gap: 12px;

            margin-top: 15px;

            padding: 12px 15px;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    rgba(235,250,250,.76),
                    rgba(255,255,255,.58)
                );

            border:
                1px solid rgba(211,231,233,.85);

            box-shadow:
                0 4px 15px rgba(0,80,90,.025);

            backdrop-filter: blur(16px);
        }

        .category-info-icon {
            width: 34px;
            height: 34px;

            min-width: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #00858c;

            background:
                rgba(0,140,145,.09);

            border-radius: 9px;
        }

        .category-info-icon svg {
            width: 17px;
            height: 17px;
        }

        .category-info-content strong {
            display: block;

            color: #365563;

            font-size: 11px;
            font-weight: 700;
        }

        .category-info-content span {
            display: block;

            margin-top: 2px;

            color: #8a9ba3;

            font-size: 10px;
        }


        /* RESPONSIVE */

        @media (max-width: 900px) {

            .category-stats-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 640px) {

            .category-hero-badge {
                display: none;
            }

        }

        .category-hero-add-btn {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;
    gap: 6px;

    padding: 9px 16px;

    border-radius: 9px;
    border: none;
    cursor: pointer;

    color: #00707A;
    background: white;

    font-size: 12px;
    font-weight: 700;

    box-shadow: 0 4px 12px rgba(0,0,0,.12);
    transition: transform .15s ease, box-shadow .15s ease;
}

.category-hero-add-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(0,0,0,.16);
}

.category-hero-add-btn svg {
    width: 15px;
    height: 15px;
}


.pms-hide-header-action {
    display: none !important;
}
    </style>

</div>
    
</x-filament-widgets::widget>
