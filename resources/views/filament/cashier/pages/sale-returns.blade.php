<x-filament-panels::page heading="">

    <style>
        /* ============================================================
           MEDICARE PHARMACY
           CASHIER — SALE RETURNS
           COMPLETE UI
           Theme: Teal / White / Soft Gray
        ============================================================ */

        .pms-sr-page {
            --sr-primary: #00838f;
            --sr-primary-dark: #006b73;
            --sr-primary-light: #e7f8f8;
            --sr-primary-lighter: #f2fbfb;

            --sr-text: #183638;
            --sr-heading: #102f31;
            --sr-muted: #718486;
            --sr-soft: #94a4a6;

            --sr-border: #dce8e9;
            --sr-border-light: #eaf0f1;

            --sr-bg: #f5fafa;
            --sr-white: #ffffff;

            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0 0 30px;
            color: var(--sr-text);
        }

        .pms-sr-page *,
        .pms-sr-page *::before,
        .pms-sr-page *::after {
            box-sizing: border-box;
        }

        .pms-sr-page button,
        .pms-sr-page input,
        .pms-sr-page select {
            font-family: inherit;
        }

       
      /* ============================================================
   FILAMENT NATIVE HEADER - HIDE COMPLETELY
   Custom header will be used instead
============================================================ */

.fi-page-header,
.fi-page-header-ctn,
.fi-header,
.fi-header-heading,
.fi-header-main-ctn {
    display: none !important;
}

/* Remove unwanted top spacing created by native header */
.fi-page-content {
    padding-top: 0 !important;
}

.fi-page {
    padding-top: 0 !important;
}


/* ============================================================
   SALE RETURNS PAGE SHELL
============================================================ */

.pms-sr-page {
    --sr-primary: #00838f;
    --sr-primary-dark: #006b73;
    --sr-primary-light: #e7f8f8;
    --sr-primary-lighter: #f2fbfb;

    --sr-text: #183638;
    --sr-heading: #102f31;
    --sr-muted: #718486;
    --sr-soft: #94a4a6;

    --sr-border: #dce8e9;
    --sr-border-light: #eaf0f1;

    --sr-bg: #f5fafa;
    --sr-white: #ffffff;

    width: 100%;
    max-width: none;
    margin: 0;
    padding: 0 0 30px;
    color: var(--sr-text);
}


/* ============================================================
   CUSTOM PAGE HEADER
============================================================ */

.pms-sr-page-header {
    width: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 24px;

    margin: 0 0 18px;
    padding: 0;
}

.pms-sr-page-header-left {
    min-width: 0;
}

.pms-sr-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;

    margin: 0 0 7px;

    color: #91a1a3;
    font-size: 11px;
    line-height: 1;
    font-weight: 700;
}

.pms-sr-breadcrumb .active {
    color: var(--sr-primary);
}

.pms-sr-page-title {
    margin: 0;

    color: var(--sr-heading);

    font-size: 27px;
    line-height: 1.15;
    font-weight: 800;

    letter-spacing: -0.025em;
}

.pms-sr-page-description {
    margin: 6px 0 0;

    color: var(--sr-muted);

    font-size: 12px;
    line-height: 1.45;
    font-weight: 500;
}


/* ============================================================
   HEADER RIGHT STATUS
============================================================ */

.pms-sr-page-header-right {
    display: flex;
    align-items: center;
    gap: 9px;

    flex-shrink: 0;
}

.pms-sr-header-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    min-height: 34px;
    padding: 0 11px;

    border: 1px solid #dce9ea;
    border-radius: 9px;

    background: #ffffff;

    color: #587072;

    font-size: 10px;
    font-weight: 700;
}

.pms-sr-header-status-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: #20a36a;
    box-shadow: 0 0 0 3px #e5f7ef;
}


/* ============================================================
   WORKFLOW CARD
============================================================ */





/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 1200px) {

    .pms-sr-workflow-inner {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .pms-sr-workflow-steps {
        margin-top: 14px;
    }
}

@media (max-width: 760px) {

    .pms-sr-page-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }

    .pms-sr-page-header-right {
        width: 100%;
    }

    .pms-sr-select-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 560px) {

    .pms-sr-page-title {
        font-size: 23px;
    }

    .pms-sr-page-description {
        font-size: 11px;
    }

    .pms-sr-workflow-inner {
        padding: 17px;
    }

    .pms-sr-workflow-steps {
        display: none;
    }
}

        /* ============================================================
           STATISTICS
        ============================================================ */

        .pms-sr-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 13px;
            margin-top: 14px;
        }

        .pms-sr-stat-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 13px;
            min-height: 82px;
            padding: 15px 17px;
            overflow: hidden;
            border: 1px solid var(--sr-border);
            border-radius: 13px;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0,96,100,.035);
        }

        .pms-sr-stat-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
        }

        .pms-sr-stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .pms-sr-stat-value {
            margin: 0;
            color: var(--sr-heading);
            font-size: 20px;
            line-height: 1.1;
            font-weight: 800;
        }

        .pms-sr-stat-label {
            margin: 5px 0 0;
            color: var(--sr-muted);
            font-size: 11px;
            line-height: 1.2;
            font-weight: 600;
        }

        /* ============================================================
           INFORMATION / WORKFLOW GUIDE
        ============================================================ */

        .pms-sr-guide {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 13px;
            margin-top: 14px;
            padding: 14px 16px;
            border: 1px solid #d7ebec;
            border-radius: 12px;
            background: linear-gradient(90deg, #f1fbfb, #fbfefe);
        }

        .pms-sr-guide-icon {
            width: 34px;
            height: 34px;
            min-width: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #dff5f6;
            color: var(--sr-primary);
        }

        .pms-sr-guide-icon svg {
            width: 18px;
            height: 18px;
        }

        .pms-sr-guide-title {
            margin: 0;
            color: #29494b;
            font-size: 12px;
            font-weight: 800;
        }

        .pms-sr-guide-text {
            margin: 3px 0 0;
            color: #809294;
            font-size: 11px;
            line-height: 1.35;
        }

        .pms-sr-steps {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pms-sr-step {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 7px;
            background: #edf8f8;
            color: #4f7072;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .pms-sr-step-number {
            width: 17px;
            height: 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #d4eff0;
            color: var(--sr-primary);
            font-size: 9px;
            font-weight: 800;
        }

        /* ============================================================
           RETURN HISTORY CARD
        ============================================================ */

        .pms-sr-history-card {
            margin-top: 14px;
            overflow: hidden;
            border: 1px solid var(--sr-border);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 4px 18px rgba(0,96,100,.035);
        }

        .pms-sr-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            min-height: 67px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--sr-border-light);
        }

        .pms-sr-card-title-wrap {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .pms-sr-card-icon {
            width: 34px;
            height: 34px;
            min-width: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: var(--sr-primary-light);
            color: var(--sr-primary);
        }

        .pms-sr-card-icon svg {
            width: 17px;
            height: 17px;
        }

        .pms-sr-card-title {
            margin: 0;
            color: var(--sr-heading);
            font-size: 14px;
            font-weight: 800;
        }

        .pms-sr-card-subtitle {
            margin: 3px 0 0;
            color: var(--sr-muted);
            font-size: 10px;
        }

        .pms-sr-history-search {
            position: relative;
            width: 210px;
        }

        .pms-sr-history-search svg {
            position: absolute;
            left: 10px;
            top: 50%;
            width: 14px;
            height: 14px;
            transform: translateY(-50%);
            color: var(--sr-primary);
            pointer-events: none;
        }

        .pms-sr-history-input {
            width: 100%;
            height: 34px;
            padding: 0 10px 0 32px;
            border: 1px solid #dbe6e7 !important;
            border-radius: 8px !important;
            background: #fff !important;
            color: #405557 !important;
            font-size: 11px !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .pms-sr-history-input:focus {
            border-color: var(--sr-primary) !important;
            box-shadow: 0 0 0 3px rgba(0,131,143,.07) !important;
        }

        /* ============================================================
           HISTORY TABLE
        ============================================================ */

        .pms-sr-history-scroll {
            width: 100%;
            overflow-x: auto;
        }

        .pms-sr-history-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .pms-sr-history-table th {
            height: 43px;
            padding: 0 17px;
            border-bottom: 1px solid #e3eaeb;
            background: #fafcfc;
            color: #687b7d;
            text-align: left;
            white-space: nowrap;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .025em;
        }

        .pms-sr-history-table td {
            height: 58px;
            padding: 9px 17px;
            border-bottom: 1px solid #edf2f2;
            color: #536769;
            font-size: 11px;
            vertical-align: middle;
        }

        .pms-sr-history-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .pms-sr-history-table tbody tr:hover {
            background: #fafdfe;
        }

        .pms-sr-history-table th:nth-child(1),
        .pms-sr-history-table td:nth-child(1) {
            width: 13%;
        }

        .pms-sr-history-table th:nth-child(2),
        .pms-sr-history-table td:nth-child(2) {
            width: 20%;
        }

        .pms-sr-history-table th:nth-child(3),
        .pms-sr-history-table td:nth-child(3) {
            width: 10%;
        }

        .pms-sr-history-table th:nth-child(4),
        .pms-sr-history-table td:nth-child(4) {
            width: 15%;
        }

        .pms-sr-history-table th:nth-child(5),
        .pms-sr-history-table td:nth-child(5) {
            width: 16%;
        }

        .pms-sr-history-table th:nth-child(6),
        .pms-sr-history-table td:nth-child(6) {
            width: 13%;
        }

        .pms-sr-history-table th:nth-child(7),
        .pms-sr-history-table td:nth-child(7) {
            width: 13%;
        }

        .pms-sr-invoice {
            color: #00717a;
            font-weight: 800;
        }

        .pms-sr-medicine {
            color: #263f41;
            font-weight: 700;
        }

        .pms-sr-amount {
            color: #006b73;
            font-weight: 800;
        }

        .pms-sr-reason {
            display: inline-flex;
            align-items: center;
            min-height: 25px;
            padding: 4px 8px;
            border-radius: 6px;
            background: #f4f8f8;
            color: #607577;
            font-size: 10px;
            font-weight: 700;
        }

        .pms-sr-date {
            color: #667b7d;
            font-weight: 600;
        }

        .pms-sr-user {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #53686a;
            font-weight: 600;
        }

        .pms-sr-user-dot {
            width: 27px;
            height: 27px;
            min-width: 27px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e5f7f7;
            color: var(--sr-primary);
            font-size: 10px;
            font-weight: 800;
        }

        /* ============================================================
           EMPTY STATE
        ============================================================ */

        .pms-sr-empty {
            min-height: 210px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            text-align: center;
        }

        .pms-sr-empty-icon {
            width: 52px;
            height: 52px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e3eaeb;
            border-radius: 14px;
            background: #f7f9f9;
            color: #839294;
        }

        .pms-sr-empty-icon svg {
            width: 23px;
            height: 23px;
        }

        .pms-sr-empty-title {
            margin: 0;
            color: #263d3f;
            font-size: 13px;
            font-weight: 800;
        }

        .pms-sr-empty-text {
            max-width: 360px;
            margin: 6px auto 0;
            color: #91a0a2;
            font-size: 11px;
            line-height: 1.4;
        }

        /* ============================================================
           SELECTED SALE / RETURN PANEL
        ============================================================ */

        .pms-sr-return-panel {
            margin-top: 16px;
            overflow: hidden;
            border: 1px solid #cfe5e6;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 8px 26px rgba(0,96,100,.06);
        }

        .pms-sr-return-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 17px 19px;
            border-bottom: 1px solid var(--sr-border-light);
            background: linear-gradient(90deg, #fbfefe, #fff);
        }

        .pms-sr-return-panel-title-row {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .pms-sr-return-panel-icon {
            width: 39px;
            height: 39px;
            min-width: 39px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--sr-primary-light);
            color: var(--sr-primary);
        }

        .pms-sr-return-panel-icon svg {
            width: 19px;
            height: 19px;
        }

        .pms-sr-return-panel-title {
            margin: 0;
            color: var(--sr-heading);
            font-size: 16px;
            font-weight: 800;
        }

        .pms-sr-return-panel-subtitle {
            margin: 3px 0 0;
            color: var(--sr-muted);
            font-size: 11px;
        }

        .pms-sr-cancel-button {
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 12px;
            border: 1px solid #dce6e7;
            border-radius: 8px;
            background: #fff;
            color: #647678;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s ease;
        }

        .pms-sr-cancel-button:hover {
            border-color: #cbdadb;
            background: #f8fbfb;
            color: #43585a;
        }

        .pms-sr-cancel-button svg {
            width: 14px;
            height: 14px;
        }

        /* ============================================================
           SALE SUMMARY
        ============================================================ */

        .pms-sr-sale-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            padding: 15px 18px;
            background: #fff;
        }

        .pms-sr-sale-box {
            min-height: 65px;
            padding: 11px 12px;
            border: 1px solid #e7eeee;
            border-radius: 9px;
            background: #f9fbfb;
        }

        .pms-sr-sale-box.total {
            border-color: #cfe9ea;
            background: #effafa;
        }

        .pms-sr-sale-label {
            margin: 0;
            color: #8b9b9d;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .045em;
        }

        .pms-sr-sale-value {
            margin: 5px 0 0;
            overflow: hidden;
            color: #20383a;
            font-size: 13px;
            font-weight: 800;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pms-sr-sale-box.total .pms-sr-sale-label {
            color: #00727b;
        }

        .pms-sr-sale-box.total .pms-sr-sale-value {
            color: #006b73;
        }

        /* ============================================================
           RETURN ITEMS TABLE
        ============================================================ */

        .pms-sr-items-section {
            padding: 0 18px;
        }

        .pms-sr-items-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 13px 0 10px;
            border-top: 1px solid #edf2f2;
        }

        .pms-sr-items-title h3 {
            margin: 0;
            color: #2c4547;
            font-size: 12px;
            font-weight: 800;
        }

        .pms-sr-items-title span {
            color: #8a9b9d;
            font-size: 10px;
            font-weight: 600;
        }

        .pms-sr-items-scroll {
            width: 100%;
            overflow-x: auto;
        }

        .pms-sr-items-table {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
        }

        .pms-sr-items-table th {
            height: 40px;
            padding: 0 10px;
            border-bottom: 1px solid #e4ebec;
            background: #fafcfc;
            color: #738486;
            text-align: left;
            white-space: nowrap;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .035em;
        }

        .pms-sr-items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #edf2f2;
            color: #526668;
            font-size: 11px;
            vertical-align: middle;
        }

        .pms-sr-items-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .pms-sr-items-table tbody tr:hover {
            background: #fcfefe;
        }

        .pms-sr-medicine-name {
            margin: 0;
            color: #203a3c;
            font-size: 11px;
            font-weight: 800;
        }

        .pms-sr-item-meta {
            margin: 4px 0 0;
            color: #8c9b9d;
            font-size: 9px;
        }

        .pms-sr-batch-number {
            margin: 0;
            color: #40595b;
            font-size: 10px;
            font-weight: 800;
        }

        .pms-sr-expiry {
            margin: 3px 0 0;
            color: #98a5a7;
            font-size: 9px;
        }

        .pms-sr-number-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            min-height: 25px;
            padding: 3px 7px;
            border-radius: 6px;
            background: #f3f7f7;
            color: #4c6466;
            font-size: 10px;
            font-weight: 800;
        }

        .pms-sr-available-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            min-height: 26px;
            padding: 3px 8px;
            border-radius: 7px;
            background: #eaf8f1;
            color: #168252;
            font-size: 10px;
            font-weight: 800;
        }

        .pms-sr-fully-returned {
            color: #a0acad;
            font-size: 9px;
            font-weight: 700;
        }

        .pms-sr-qty-input {
            width: 82px;
            height: 34px;
            padding: 0 8px;
            border: 1px solid #d9e5e6 !important;
            border-radius: 8px !important;
            background: #fff !important;
            color: #294446 !important;
            text-align: center;
            font-size: 11px !important;
            font-weight: 800 !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .pms-sr-qty-input:focus {
            border-color: var(--sr-primary) !important;
            box-shadow: 0 0 0 3px rgba(0,131,143,.07) !important;
        }

        .pms-sr-reason-select {
            width: 205px;
            height: 34px;
            padding: 0 9px;
            border: 1px solid #d9e5e6 !important;
            border-radius: 8px !important;
            background: #fff !important;
            color: #526668 !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .pms-sr-reason-select:focus {
            border-color: var(--sr-primary) !important;
            box-shadow: 0 0 0 3px rgba(0,131,143,.07) !important;
        }

        /* ============================================================
           RETURN SUMMARY / ACTION
        ============================================================ */

        .pms-sr-action-area {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 15px 18px 18px;
            padding: 15px 16px;
            border: 1px solid #d8ebec;
            border-radius: 11px;
            background: linear-gradient(90deg, #f2fbfb, #fbfefe);
        }

        .pms-sr-action-info {
            min-width: 0;
        }

        .pms-sr-action-title {
            margin: 0;
            color: #294446;
            font-size: 12px;
            font-weight: 800;
        }

        .pms-sr-action-description {
            margin: 4px 0 0;
            color: #87989a;
            font-size: 10px;
        }

        .pms-sr-action-right {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
        }

        .pms-sr-refund-box {
            text-align: right;
        }

        .pms-sr-refund-label {
            margin: 0;
            color: #8d9d9f;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .045em;
        }

        .pms-sr-refund-value {
            margin: 3px 0 0;
            color: #006b73;
            font-size: 21px;
            line-height: 1.1;
            font-weight: 800;
        }

        .pms-sr-process-button {
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-width: 145px;
            padding: 0 17px;
            border: 0;
            border-radius: 9px;
            background: var(--sr-primary);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 5px 14px rgba(0,131,143,.18);
            transition: all .18s ease;
        }

        .pms-sr-process-button:hover {
            background: var(--sr-primary-dark);
            transform: translateY(-1px);
        }

        .pms-sr-process-button:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none;
        }

        .pms-sr-process-button svg {
            width: 16px;
            height: 16px;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */

        @media (max-width: 1150px) {
            .pms-sr-workflow-inner {
                grid-template-columns: 1fr;
            }

            .pms-sr-search-area {
                min-width: 0;
                width: 100%;
            }

            .pms-sr-steps {
                display: none;
            }
        }

        @media (max-width: 900px) {
            .pms-sr-stats {
                grid-template-columns: 1fr;
            }

            .pms-sr-guide {
                grid-template-columns: auto minmax(0, 1fr);
            }

            .pms-sr-sale-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .pms-sr-action-area {
                flex-direction: column;
                align-items: stretch;
            }

            .pms-sr-action-right {
                justify-content: space-between;
            }

            .pms-sr-refund-box {
                text-align: left;
            }
        }

        @media (max-width: 650px) {
            .pms-sr-page-header {
                align-items: flex-start;
            }

            .pms-sr-page-title {
                font-size: 22px;
            }

            .pms-sr-workflow-inner {
                padding: 17px;
            }

            .pms-sr-search-row {
                flex-direction: column;
                align-items: stretch;
            }

            .pms-sr-search-button {
                width: 100%;
            }

            .pms-sr-sale-summary {
                grid-template-columns: 1fr;
                padding: 13px;
            }

            .pms-sr-card-header {
                flex-direction: column;
                align-items: stretch;
            }

            .pms-sr-history-search {
                width: 100%;
            }

            .pms-sr-return-panel-header {
                align-items: flex-start;
            }

            .pms-sr-action-right {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .pms-sr-process-button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .pms-sr-guide {
                grid-template-columns: auto minmax(0, 1fr);
            }

            .pms-sr-page-description {
                font-size: 11px;
            }

            .pms-sr-workflow-title {
                font-size: 15px;
            }

            .pms-sr-workflow-text {
                font-size: 10px;
            }

            .pms-sr-return-panel-header {
                flex-direction: column;
            }

            .pms-sr-cancel-button {
                width: 100%;
            }
        }
/* ============================================================
   SALE RETURN WORKFLOW
   FINAL PROFESSIONAL RIGHT ALIGNMENT
   PMS TEAL THEME

   LEFT  = TITLE / DESCRIPTION
   RIGHT = SEARCH / INVOICE / MEDICINE
   BOTTOM = WORKFLOW STEPS
============================================================ */

.pms-sr-workflow {
    width: 100% !important;
    box-sizing: border-box !important;

    background: #00838F !important;
    color: #FFFFFF !important;

    border-radius: 14px !important;

    overflow: hidden !important;
}


/* ============================================================
   MAIN OUTER CONTAINER
============================================================ */

.pms-sr-workflow-inner {
    width: 100% !important;

    display: block !important;

    margin: 0 !important;
    padding: 24px 28px 20px !important;

    box-sizing: border-box !important;
}


/* ============================================================
   TOP AREA
   LEFT = TITLE
   RIGHT = CONTROLS
============================================================ */

.pms-sr-workflow-main {
    width: 100% !important;

    display: grid !important;

    grid-template-columns:
        minmax(260px, 0.72fr)
        minmax(560px, 1.28fr) !important;

    column-gap: 55px !important;

    align-items: start !important;

    margin: 0 !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   LEFT CONTENT
============================================================ */

.pms-sr-workflow-content {
    width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   TITLE ROW
============================================================ */

.pms-sr-workflow-top {
    width: 100% !important;

    display: flex !important;

    align-items: flex-start !important;

    gap: 14px !important;

    margin: 0 !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   BACK BUTTON
============================================================ */

.pms-sr-return-icon {
    width: 48px !important;
    height: 48px !important;

    min-width: 48px !important;
    min-height: 48px !important;

    flex: 0 0 48px !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    border-radius: 12px !important;

    background: rgba(255, 255, 255, 0.12) !important;

    border: 1px solid rgba(255, 255, 255, 0.22) !important;

    color: #FFFFFF !important;

    box-sizing: border-box !important;
}

.pms-sr-return-icon svg {
    width: 24px !important;
    height: 24px !important;

    color: #FFFFFF !important;
}


/* ============================================================
   TITLE
============================================================ */

.pms-sr-workflow-title {
    margin: 0 !important;
    padding: 0 !important;

    color: #FFFFFF !important;

    font-size: 18px !important;
    font-weight: 800 !important;

    line-height: 1.25 !important;
}

.pms-sr-workflow-text {
    margin: 6px 0 0 !important;
    padding: 0 !important;

    max-width: 260px !important;

    color: rgba(255, 255, 255, 0.82) !important;

    font-size: 11px !important;
    line-height: 1.5 !important;
}


/* ============================================================
   RIGHT CONTROL AREA
============================================================ */

.pms-sr-search-area {
    width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   CONTROL GRID

   INVOICE = LEFT
   MEDICINE = RIGHT
============================================================ */

.pms-sr-select-grid {
    width: 100% !important;

    display: grid !important;

    grid-template-columns:
        minmax(250px, 1fr)
        minmax(250px, 1fr) !important;

    column-gap: 18px !important;
    row-gap: 0 !important;

    align-items: start !important;

    margin: 0 !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   FIELD
============================================================ */

.pms-sr-field {
    width: 100% !important;

    min-width: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   LABEL
============================================================ */

.pms-sr-search-label {
    display: block !important;

    width: 100% !important;

    height: 14px !important;

    margin: 0 0 7px !important;
    padding: 0 !important;

    color: #FFFFFF !important;

    font-size: 10px !important;
    font-weight: 800 !important;

    line-height: 14px !important;

    text-transform: uppercase !important;

    letter-spacing: 0.1px !important;

    box-sizing: border-box !important;
}


/* ============================================================
   SEARCH INPUT WRAPPER
============================================================ */

.pms-sr-search-input-wrap {
    position: relative !important;

    width: 100% !important;

    height: 46px !important;

    margin: 0 0 10px !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   SEARCH INPUT
============================================================ */

.pms-sr-search-input {
    display: block !important;

    width: 100% !important;

    height: 46px !important;

    margin: 0 !important;

    padding: 0 40px 0 42px !important;

    border: 1px solid rgba(255, 255, 255, 0.35) !important;

    border-radius: 10px !important;

    background: #FFFFFF !important;

    color: #17374A !important;

    font-size: 12px !important;
    font-weight: 600 !important;

    line-height: 46px !important;

    outline: none !important;

    box-sizing: border-box !important;

    box-shadow: none !important;
}

.pms-sr-search-input::placeholder {
    color: #87969C !important;
}


/* ============================================================
   SEARCH ICON
============================================================ */

.pms-sr-search-input-icon {
    position: absolute !important;

    left: 14px !important;
    top: 50% !important;

    width: 17px !important;
    height: 17px !important;

    margin: 0 !important;
    padding: 0 !important;

    transform: translateY(-50%) !important;

    color: #00838F !important;

    pointer-events: none !important;

    z-index: 5 !important;
}

.pms-sr-search-input-icon svg {
    width: 17px !important;
    height: 17px !important;
}


/* ============================================================
   SEARCH CLEAR
============================================================ */

.pms-sr-search-clear {
    position: absolute !important;

    right: 9px !important;
    top: 50% !important;

    width: 23px !important;
    height: 23px !important;

    margin: 0 !important;
    padding: 0 !important;

    transform: translateY(-50%) !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    border: 0 !important;

    border-radius: 6px !important;

    background: #EAF7F7 !important;

    color: #00838F !important;

    font-size: 16px !important;
    line-height: 1 !important;

    cursor: pointer !important;
}


/* ============================================================
   SELECT WRAPPER
============================================================ */

.pms-sr-select-wrap {
    position: relative !important;

    width: 100% !important;

    height: 53px !important;

    min-height: 53px !important;

    margin: 0 !important;
    padding: 0 !important;

    box-sizing: border-box !important;
}


/* ============================================================
   SELECT
============================================================ */

.pms-sr-select {
    display: block !important;

    width: 100% !important;

    height: 53px !important;

    margin: 0 !important;

    padding: 0 42px 0 44px !important;

    border: 0 !important;

    border-radius: 11px !important;

    background: #FFFFFF !important;

    color: #17374A !important;

    font-size: 12px !important;
    font-weight: 700 !important;

    line-height: normal !important;

    appearance: none !important;

    outline: none !important;

    cursor: pointer !important;

    box-sizing: border-box !important;

    box-shadow: none !important;
}


/* ============================================================
   SELECT ICON
============================================================ */

.pms-sr-select-icon {
    position: absolute !important;

    left: 14px !important;
    top: 50% !important;

    width: 19px !important;
    height: 19px !important;

    margin: 0 !important;
    padding: 0 !important;

    transform: translateY(-50%) !important;

    color: #00838F !important;

    pointer-events: none !important;

    z-index: 3 !important;
}

.pms-sr-select-icon svg {
    width: 19px !important;
    height: 19px !important;
}


/* ============================================================
   SELECT ARROW
============================================================ */

.pms-sr-select-arrow {
    position: absolute !important;

    right: 13px !important;
    top: 50% !important;

    width: 17px !important;
    height: 17px !important;

    margin: 0 !important;
    padding: 0 !important;

    transform: translateY(-50%) !important;

    color: #829398 !important;

    pointer-events: none !important;

    z-index: 3 !important;
}

.pms-sr-select-arrow svg {
    width: 17px !important;
    height: 17px !important;
}


/* ============================================================
   DISABLED MEDICINE
============================================================ */

.pms-sr-select-wrap.is-disabled {
    opacity: 0.72 !important;
}


/* ============================================================
   SELECTION STATUS
   STAYS UNDER RIGHT SIDE CONTROLS
============================================================ */

.pms-sr-selection-status {
    width: 100% !important;

    margin: 12px 0 0 !important;

    padding: 10px 12px !important;

    display: flex !important;

    align-items: center !important;

    gap: 9px !important;

    border: 1px solid rgba(255, 255, 255, 0.18) !important;

    border-radius: 9px !important;

    background: rgba(255, 255, 255, 0.08) !important;

    box-sizing: border-box !important;
}


/* ============================================================
   SELECTION ICON
============================================================ */

.pms-sr-selection-icon {
    width: 30px !important;
    height: 30px !important;

    min-width: 30px !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    border-radius: 7px !important;

    background: rgba(255, 255, 255, 0.14) !important;

    color: #FFFFFF !important;
}

.pms-sr-selection-icon svg {
    width: 17px !important;
    height: 17px !important;
}


/* ============================================================
   SELECTION TEXT
============================================================ */

.pms-sr-selection-title {
    margin: 0 !important;

    color: #FFFFFF !important;

    font-size: 11px !important;
    font-weight: 800 !important;
}

.pms-sr-selection-text {
    margin: 3px 0 0 !important;

    color: rgba(255, 255, 255, 0.78) !important;

    font-size: 10px !important;
}


/* ============================================================
   WORKFLOW STEPS
   FULL WIDTH BELOW TOP AREA
============================================================ */

.pms-sr-workflow-steps {
    width: 100% !important;

    margin: 18px 0 0 !important;
    padding: 0 !important;

    display: flex !important;

    align-items: center !important;

    gap: 10px !important;

    box-sizing: border-box !important;
}


/* ============================================================
   STEP
============================================================ */

.pms-sr-step {
    min-height: 34px !important;
    height: 34px !important;

    padding: 0 13px !important;

    display: inline-flex !important;

    align-items: center !important;
    justify-content: center !important;

    gap: 7px !important;

    flex: 0 0 auto !important;

    border-radius: 8px !important;

    background: rgba(255, 255, 255, 0.12) !important;

    color: rgba(255, 255, 255, 0.82) !important;

    font-size: 11px !important;
    font-weight: 700 !important;

    white-space: nowrap !important;

    box-sizing: border-box !important;
}


/* ============================================================
   ACTIVE STEP
============================================================ */

.pms-sr-step.is-active {
    background: #FFFFFF !important;

    color: #00838F !important;
}


/* ============================================================
   STEP NUMBER
============================================================ */

.pms-sr-step-number {
    width: 19px !important;
    height: 19px !important;

    min-width: 19px !important;

    display: inline-flex !important;

    align-items: center !important;
    justify-content: center !important;

    border-radius: 50% !important;

    background: rgba(255, 255, 255, 0.18) !important;

    color: #FFFFFF !important;

    font-size: 9px !important;
    font-weight: 800 !important;
}


/* ============================================================
   ACTIVE NUMBER
============================================================ */

.pms-sr-step.is-active .pms-sr-step-number {
    background: #DFF4F4 !important;

    color: #00838F !important;
}


/* ============================================================
   STEP LINE
============================================================ */

.pms-sr-step-line {
    flex: 1 1 auto !important;

    width: auto !important;

    height: 1px !important;

    min-width: 30px !important;

    background: rgba(255, 255, 255, 0.30) !important;
}


/* ============================================================
   LARGE DESKTOP
============================================================ */

@media (min-width: 1250px) {

    .pms-sr-workflow-inner {
        padding: 24px 30px 20px !important;
    }

    .pms-sr-workflow-main {
        grid-template-columns:
            minmax(280px, 0.72fr)
            minmax(620px, 1.28fr) !important;

        column-gap: 60px !important;
    }

}


/* ============================================================
   MEDIUM DESKTOP
============================================================ */

@media (max-width: 1200px) {

    .pms-sr-workflow-main {
        grid-template-columns:
            minmax(250px, 0.7fr)
            minmax(520px, 1.3fr) !important;

        column-gap: 35px !important;
    }

    .pms-sr-workflow-inner {
        padding: 22px 24px 20px !important;
    }

}


/* ============================================================
   TABLET
============================================================ */

@media (max-width: 950px) {

    .pms-sr-workflow-main {
        grid-template-columns: 1fr !important;

        row-gap: 22px !important;
    }

    .pms-sr-workflow-content {
        width: 100% !important;
    }

    .pms-sr-search-area {
        width: 100% !important;
    }

}


/* ============================================================
   SMALL TABLET
============================================================ */

@media (max-width: 750px) {

    .pms-sr-select-grid {
        grid-template-columns: 1fr !important;

        row-gap: 12px !important;
    }

    .pms-sr-search-input-wrap {
        margin-bottom: 10px !important;
    }

    .pms-sr-workflow-steps {
        flex-wrap: wrap !important;
    }

    .pms-sr-step-line {
        flex: 1 1 30px !important;
    }

}


/* ============================================================
   MOBILE
============================================================ */

@media (max-width: 500px) {

    .pms-sr-workflow-inner {
        padding: 18px !important;
    }

    .pms-sr-workflow-main {
        row-gap: 20px !important;
    }

    .pms-sr-workflow-top {
        gap: 10px !important;
    }

    .pms-sr-return-icon {
        width: 42px !important;
        height: 42px !important;

        min-width: 42px !important;
        min-height: 42px !important;

        flex-basis: 42px !important;
    }

    .pms-sr-workflow-title {
        font-size: 16px !important;
    }

    .pms-sr-workflow-text {
        font-size: 10px !important;
    }

    .pms-sr-workflow-steps {
        display: grid !important;

        grid-template-columns: 1fr !important;

        gap: 7px !important;
    }

    .pms-sr-step {
        width: 100% !important;
    }

    .pms-sr-step-line {
        width: 1px !important;

        height: 10px !important;

        min-width: 1px !important;

        margin: 0 auto !important;
    }

}

    </style>


    {{-- ================================================================
         MAIN PAGE
    ================================================================= --}}

    <div class="pms-sr-page">


        {{-- ============================================================
             PAGE HEADER
        ============================================================= --}}

        <!-- <header class="pms-sr-page-header">

            <div>
                <div class="pms-sr-breadcrumb">
                    <span class="active">Sales</span>
                    <span>/</span>
                    <span>Sale Returns</span>
                </div>

                <h1 class="pms-sr-page-title">
                    Sale Returns
                </h1>

                <p class="pms-sr-page-description">
                    Search a completed sale and safely process customer item returns.
                </p>
            </div>

        </header> -->


      
    {{-- ============================================================
     SALE RETURN WORKFLOW
============================================================= --}}
<section class="pms-sr-workflow">

    <div class="pms-sr-workflow-inner">

        {{-- ====================================================
             TOP CONTENT
        ===================================================== --}}

        <div class="pms-sr-workflow-main">

            {{-- =================================================
                 LEFT - TITLE
            ================================================== --}}

            <div class="pms-sr-workflow-content">

                <div class="pms-sr-workflow-top">

                    <div class="pms-sr-return-icon">
                        <x-heroicon-o-arrow-uturn-left />
                    </div>

                    <div class="min-w-0">

                        <h2 class="pms-sr-workflow-title">
                            Sales Returns Management
                        </h2>

                        <p class="pms-sr-workflow-text">
                            Select an invoice, choose the medicine,
                            and process the customer return.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT - SELECTORS
            ================================================== --}}

            <div class="pms-sr-search-area">

                <div class="pms-sr-select-grid">


                    {{-- =================================================
                         ORIGINAL INVOICE
                    ================================================== --}}

                    <div class="pms-sr-field">

                        <label
                            for="sale-return-invoice-search"
                            class="pms-sr-search-label"
                        >
                            Original Invoice
                        </label>


                        {{-- SEARCH INPUT --}}

                        <div class="pms-sr-search-input-wrap">

                            <x-heroicon-o-magnifying-glass
                                class="pms-sr-search-input-icon"
                            />

                            <input
                                id="sale-return-invoice-search"
                                type="text"
                                wire:model.live.debounce.300ms="invoiceSearch"
                                placeholder="Search invoice or customer..."
                                class="pms-sr-search-input"
                            />

                            @if($invoiceSearch)

                                <button
                                    type="button"
                                    wire:click="$set('invoiceSearch', '')"
                                    class="pms-sr-search-clear"
                                    title="Clear search"
                                >
                                    ×
                                </button>

                            @endif

                        </div>


                        {{-- INVOICE SELECT --}}

                        <div class="pms-sr-select-wrap">

                            <x-heroicon-o-document-text
                                class="pms-sr-select-icon"
                            />

                            <select
                                id="sale-return-invoice"
                                wire:model.live="selectedSaleId"
                                class="pms-sr-select"
                            >

                                <option value="">
                                    Select invoice...
                                </option>


                                @foreach(
                                    $this->filteredInvoiceOptions
                                    as $saleId => $invoiceLabel
                                )

                                    <option value="{{ $saleId }}">
                                        {{ $invoiceLabel }}
                                    </option>

                                @endforeach

                            </select>

                            <x-heroicon-o-chevron-down
                                class="pms-sr-select-arrow"
                            />

                        </div>

                    </div>


                    {{-- =================================================
                         MEDICINE
                    ================================================== --}}

                    <div class="pms-sr-field">

                        <label
                            for="sale-return-medicine"
                            class="pms-sr-search-label"
                        >
                            Medicine
                        </label>

                        <div
                            class="pms-sr-select-wrap {{ ! $selectedSaleId ? 'is-disabled' : '' }}"
                        >

                            <x-heroicon-o-beaker
                                class="pms-sr-select-icon"
                            />

                            <select
                                id="sale-return-medicine"
                                wire:model.live="selectedSaleItemId"
                                @disabled(! $selectedSaleId)
                                class="pms-sr-select"
                            >

                                <option value="">
                                    {{
                                        $selectedSaleId
                                            ? 'Select medicine...'
                                            : 'Select invoice first...'
                                    }}
                                </option>


                                @foreach(
                                    ($saleData['items'] ?? []) as $item
                                )

                                    <option
                                        value="{{ $item['id'] }}"
                                        @disabled(
                                            ($item['returnable_quantity'] ?? 0) <= 0
                                        )
                                    >

                                        {{ $item['medicine_name'] }}

                                        — Available:

                                        {{ $item['returnable_quantity'] ?? 0 }}

                                    </option>

                                @endforeach

                            </select>

                            <x-heroicon-o-chevron-down
                                class="pms-sr-select-arrow"
                            />

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SELECTION STATUS
                ================================================== --}}

                @if($selectedSaleId)

                    <div class="pms-sr-selection-status">

                        <div class="pms-sr-selection-icon">

                            <x-heroicon-o-check-circle />

                        </div>


                        <div class="min-w-0 flex-1">

                            @if($selectedSaleItemId)

                                @php
                                    $selectedItem = collect(
                                        $saleData['items'] ?? []
                                    )->firstWhere(
                                        'id',
                                        (int) $selectedSaleItemId
                                    );
                                @endphp


                                <p class="pms-sr-selection-title">
                                    Medicine selected
                                </p>

                                <p class="pms-sr-selection-text">

                                    {{ $saleData['invoice_number'] ?? 'N/A' }}

                                    <span class="mx-1">
                                        •
                                    </span>

                                    {{ $selectedItem['medicine_name'] ?? 'Medicine selected' }}

                                </p>

                            @else

                                <p class="pms-sr-selection-title">
                                    Invoice selected
                                </p>

                                <p class="pms-sr-selection-text">

                                    {{ $saleData['invoice_number'] ?? 'N/A' }}

                                    <span class="mx-1">
                                        •
                                    </span>

                                    Select a medicine to continue

                                </p>

                            @endif

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- ====================================================
             WORKFLOW STEPS
             FULL WIDTH - IMPORTANT
        ===================================================== --}}

        <div class="pms-sr-workflow-steps">

            {{-- STEP 1 --}}

            <div class="pms-sr-step is-active">

                <span class="pms-sr-step-number">
                    1
                </span>

                <span>
                    Select Invoice
                </span>

            </div>


            <span class="pms-sr-step-line"></span>


            {{-- STEP 2 --}}

            <div
                class="pms-sr-step"
                @class([
                    'is-active' => $selectedSaleId,
                ])
            >

                <span class="pms-sr-step-number">
                    2
                </span>

                <span>
                    Select Medicine
                </span>

            </div>


            <span class="pms-sr-step-line"></span>


            {{-- STEP 3 --}}

            <div
                class="pms-sr-step"
                @class([
                    'is-active' =>
                        $selectedSaleId &&
                        $selectedSaleItemId,
                ])
            >

                <span class="pms-sr-step-number">
                    3
                </span>

                <span>
                    Process Return
                </span>

            </div>

        </div>

    </div>

</section>


        {{-- ============================================================
             STATISTICS
        ============================================================= --}}

        <section class="pms-sr-stats">

            {{-- TOTAL RETURNS --}}
            <div class="pms-sr-stat-card">

                <div
                    class="pms-sr-stat-icon"
                    style="background:#e7f8f8;color:#00838f;"
                >
                    <x-heroicon-o-arrow-uturn-left />
                </div>

                <div>
                    <p class="pms-sr-stat-value">
                        {{ $this->totalReturns ?? 0 }}
                    </p>

                    <p class="pms-sr-stat-label">
                        Total Returns
                    </p>
                </div>

            </div>


            {{-- MONTHLY RETURNS --}}
            <div class="pms-sr-stat-card">

                <div
                    class="pms-sr-stat-icon"
                    style="background:#fff3e8;color:#e68a00;"
                >
                    <x-heroicon-o-calendar-days />
                </div>

                <div>
                    <p class="pms-sr-stat-value">
                        {{ $this->monthlyReturns ?? 0 }}
                    </p>

                    <p class="pms-sr-stat-label">
                        Returns This Month
                    </p>
                </div>

            </div>


            {{-- REFUND --}}
            <div class="pms-sr-stat-card">

                <div
                    class="pms-sr-stat-icon"
                    style="background:#edf5ff;color:#4a90e2;"
                >
                    <x-heroicon-o-banknotes />
                </div>

                <div>
                    <p class="pms-sr-stat-value">
                        {{ currency_symbol() }}{{ number_format($this->totalRefundAmount ?? 0, 2) }}
                    </p>

                    <p class="pms-sr-stat-label">
                        Total Refund Amount
                    </p>
                </div>

            </div>

        </section>


        {{-- ============================================================
             WORKFLOW GUIDE
        ============================================================= --}}

        <section class="pms-sr-guide">

            <div class="pms-sr-guide-icon">
                <x-heroicon-o-information-circle />
            </div>

            <div>

                <p class="pms-sr-guide-title">
                    Return workflow
                </p>

                <p class="pms-sr-guide-text">
                    Search the invoice first. Only items sold on that invoice
                    can be selected for return.
                </p>

            </div>

            <div class="pms-sr-steps">

                <span class="pms-sr-step">
                    <span class="pms-sr-step-number">1</span>
                    Search
                </span>

                <span class="pms-sr-step">
                    <span class="pms-sr-step-number">2</span>
                    Select
                </span>

                <span class="pms-sr-step">
                    <span class="pms-sr-step-number">3</span>
                    Refund
                </span>

            </div>

        </section>
 {{-- ============================================================
             SELECTED SALE / RETURN PROCESSING
        ============================================================= --}}

        @if ($selectedSaleId)

            <section class="pms-sr-return-panel">


                {{-- ====================================================
                     PANEL HEADER
                ===================================================== --}}

                <div class="pms-sr-return-panel-header">

                    <div class="pms-sr-return-panel-title-row">

                        <div class="pms-sr-return-panel-icon">
                            <x-heroicon-o-arrow-path />
                        </div>

                        <div>

                            <h2 class="pms-sr-return-panel-title">
                                Create Sale Return
                            </h2>

                            <p class="pms-sr-return-panel-subtitle">
                                Select the medicine quantity being returned.
                            </p>

                        </div>

                    </div>


                    <button
                        type="button"
                        wire:click="clearSearch"
                        class="pms-sr-cancel-button"
                    >
                        <x-heroicon-o-x-mark />
                        Cancel
                    </button>

                </div>


                {{-- ====================================================
                     SALE INFORMATION
                ===================================================== --}}

                <div class="pms-sr-sale-summary">


                    {{-- INVOICE --}}
                    <div class="pms-sr-sale-box">

                        <p class="pms-sr-sale-label">
                            Invoice
                        </p>

                        <p class="pms-sr-sale-value">
                            {{ $saleData['invoice_number'] ?? '-' }}
                        </p>

                    </div>


                    {{-- CUSTOMER --}}
                    <div class="pms-sr-sale-box">

                        <p class="pms-sr-sale-label">
                            Customer
                        </p>

                        <p class="pms-sr-sale-value">
                            {{ $saleData['customer'] ?? '-' }}
                        </p>

                    </div>


                    {{-- SALE DATE --}}
                    <div class="pms-sr-sale-box">

                        <p class="pms-sr-sale-label">
                            Sale Date
                        </p>

                        <p class="pms-sr-sale-value">
                            {{ $saleData['date'] ?? '-' }}
                        </p>

                    </div>


                    {{-- ORIGINAL TOTAL --}}
                    <div class="pms-sr-sale-box total">

                        <p class="pms-sr-sale-label">
                            Original Total
                        </p>

                        <p class="pms-sr-sale-value">
                            {{ currency_symbol() }}{{ number_format($saleData['grand_total'] ?? 0, 2) }}
                        </p>

                    </div>

                </div>


                {{-- ====================================================
                     RETURN ITEMS
                ===================================================== --}}

                <div class="pms-sr-items-section">

                    <div class="pms-sr-items-title">

                        <h3>
                            Sale Items
                        </h3>

                        <span>
                            Enter return quantity for the required item(s)
                        </span>

                    </div>


                    <div class="pms-sr-items-scroll">

                        <table class="pms-sr-items-table">

                            <thead>

                                <tr>

                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th>Sold</th>
                                    <th>Returned</th>
                                    <th>Available</th>
                                    <th>Return Qty</th>
                                    <th>Reason</th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($saleData['items'] as $item)

                                    <tr wire:key="return-item-{{ $item['id'] }}">


                                        {{-- MEDICINE --}}
                                        <td>

                                            <p class="pms-sr-medicine-name">
                                                {{ $item['medicine_name'] }}
                                            </p>

                                            <p class="pms-sr-item-meta">
                                                {{ currency_symbol() }}{{ number_format($item['unit_price'], 2) }}
                                                / unit
                                            </p>

                                        </td>


                                        {{-- BATCH --}}
                                        <td>

                                            <p class="pms-sr-batch-number">
                                                {{ $item['batch_number'] }}
                                            </p>

                                            @if ($item['expiry_date'])

                                                <p class="pms-sr-expiry">
                                                    Exp: {{ $item['expiry_date'] }}
                                                </p>

                                            @endif

                                        </td>


                                        {{-- SOLD --}}
                                        <td>

                                            <span class="pms-sr-number-pill">
                                                {{ $item['sold_quantity'] }}
                                            </span>

                                        </td>


                                        {{-- RETURNED --}}
                                        <td>

                                            <span class="pms-sr-number-pill">
                                                {{ $item['returned_quantity'] }}
                                            </span>

                                        </td>


                                        {{-- AVAILABLE --}}
                                        <td>

                                            @if ($item['returnable_quantity'] > 0)

                                                <span class="pms-sr-available-pill">
                                                    {{ $item['returnable_quantity'] }}
                                                </span>

                                            @else

                                                <span class="pms-sr-fully-returned">
                                                    Fully Returned
                                                </span>

                                            @endif

                                        </td>


                                        {{-- RETURN QUANTITY --}}
                                        <td>

                                            <input
                                                type="number"
                                                min="0"
                                                max="{{ $item['returnable_quantity'] }}"
                                                step="1"
                                                wire:model.live="returnQuantities.{{ $item['id'] }}"
                                                @disabled($item['returnable_quantity'] <= 0)
                                                class="pms-sr-qty-input"
                                            >

                                        </td>


                                        {{-- REASON --}}
                                        <td>

                                            <select
                                                wire:model="reasons.{{ $item['id'] }}"
                                                @disabled($item['returnable_quantity'] <= 0)
                                                class="pms-sr-reason-select"
                                            >

                                                <option value="defective">
                                                    Defective / Damaged
                                                </option>

                                                <option value="wrong_item">
                                                    Wrong Item Sold
                                                </option>

                                                <option value="customer_request">
                                                    Customer Changed Mind
                                                </option>

                                                <option value="expired">
                                                    Expired
                                                </option>

                                                <option value="other">
                                                    Other
                                                </option>

                                            </select>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- ====================================================
                     RETURN ACTION
                ===================================================== --}}

                <div class="pms-sr-action-area">


                    <div class="pms-sr-action-info">

                        <p class="pms-sr-action-title">
                            Return Summary
                        </p>

                        <p class="pms-sr-action-description">
                            {{ $this->selectedReturnCount }} item line(s) selected
                            for return.
                        </p>

                    </div>


                    <div class="pms-sr-action-right">


                        {{-- REFUND --}}
                        <div class="pms-sr-refund-box">

                            <p class="pms-sr-refund-label">
                                Refund Total
                            </p>

                            <p class="pms-sr-refund-value">
                                {{ currency_symbol() }}{{ number_format($this->returnTotal, 2) }}
                            </p>

                        </div>


                        {{-- PROCESS --}}
                        <button
                            type="button"
                            wire:click="processReturn"
                            wire:loading.attr="disabled"
                            wire:target="processReturn"
                            @disabled($this->selectedReturnCount === 0 || $processing)
                            class="pms-sr-process-button"
                        >

                            <span
                                wire:loading.remove
                                wire:target="processReturn"
                                style="display:inline-flex;align-items:center;gap:7px;"
                            >
                                <x-heroicon-o-arrow-uturn-left />
                                Process Return
                            </span>

                            <span
                                wire:loading
                                wire:target="processReturn"
                            >
                                Processing...
                            </span>

                        </button>

                    </div>

                </div>

            </section>

        @endif


        {{-- ============================================================
             RETURN HISTORY
        ============================================================= --}}

        <section class="pms-sr-history-card">

            <div class="pms-sr-card-header">

                <div class="pms-sr-card-title-wrap">

                    <div class="pms-sr-card-icon">
                        <x-heroicon-o-clipboard-document-list />
                    </div>

                    <div>

                        <h2 class="pms-sr-card-title">
                            Return History
                        </h2>

                        <p class="pms-sr-card-subtitle">
                            Previously processed customer returns
                        </p>

                    </div>

                </div>


                <div class="pms-sr-history-search">

                    <x-heroicon-o-magnifying-glass />

                    <input
                        type="text"
                        wire:model.live.debounce.300ms="returnSearch"
                        placeholder="Search invoice, medicine..."
                        class="pms-sr-history-input"
                    >

                </div>

            </div>


            <div class="pms-sr-history-scroll">

                <table class="pms-sr-history-table">

                    <thead>

                        <tr>

                            <th>Invoice #</th>

                            <th>Medicine</th>

                            <th>Quantity</th>

                            <th>Refund Amount</th>

                            <th>Reason</th>

                            <th>Return Date</th>

                            <th>Processed By</th>

                        </tr>

                    </thead>


                    <tbody>

                        @if (!empty($this->returnRows) && count($this->returnRows))

                            @foreach ($this->returnRows as $return)

                                <tr wire:key="sale-return-row-{{ $return['id'] }}">

                                    {{-- INVOICE --}}
                                    <td>
                                        <span class="pms-sr-invoice">
                                            {{ $return['invoice_number'] ?? '-' }}
                                        </span>
                                    </td>


                                    {{-- MEDICINE --}}
                                    <td>
                                        <span class="pms-sr-medicine">
                                            {{ $return['medicine_name'] ?? '-' }}
                                        </span>
                                    </td>


                                    {{-- QUANTITY --}}
                                    <td>
                                        {{ $return['quantity'] ?? 0 }}
                                    </td>


                                    {{-- REFUND --}}
                                    <td>
                                        <span class="pms-sr-amount">
                                            {{ currency_symbol() }}{{ number_format($return['refund_amount'] ?? 0, 2) }}
                                        </span>
                                    </td>


                                    {{-- REASON --}}
                                    <td>

                                        <span class="pms-sr-reason">
                                            {{ $return['reason'] ?? '-' }}
                                        </span>

                                    </td>


                                    {{-- DATE --}}
                                    <td>
                                        <span class="pms-sr-date">
                                            {{ $return['return_date'] ?? '-' }}
                                        </span>
                                    </td>


                                    {{-- USER --}}
                                    <td>

                                        <span class="pms-sr-user">

                                            <span class="pms-sr-user-dot">
                                                {{ strtoupper(substr($return['processed_by'] ?? 'A', 0, 1)) }}
                                            </span>

                                            {{ $return['processed_by'] ?? '-' }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        @else

                            <tr>

                                <td
                                    colspan="7"
                                    style="padding:0;"
                                >

                                    <div class="pms-sr-empty">

                                        <div class="pms-sr-empty-icon">
                                            <x-heroicon-o-arrow-uturn-left />
                                        </div>

                                        <p class="pms-sr-empty-title">
                                            No sale returns found
                                        </p>

                                        <p class="pms-sr-empty-text">
                                            Process your first sale return by searching
                                            an invoice above.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>

        </section>


       

    </div>

</x-filament-panels::page>