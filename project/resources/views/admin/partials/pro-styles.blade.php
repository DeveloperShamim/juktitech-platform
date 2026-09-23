<style>
.adm-pro {
    --adm-bg: #f8fafc;
    --adm-card: #ffffff;
    --adm-text: #0f172a;
    --adm-muted: #64748b;
    --adm-border: #e2e8f0;
    --adm-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 8px 24px rgba(15, 23, 42, 0.04);
    --adm-primary: #ff5722;
    --adm-indigo: #ea580c;
}

.adm-pro.content-area {
    background: var(--adm-bg);
    padding: 24px;
    min-height: calc(100vh - 66px);
}

.adm-pro .adm-hero {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 16px;
    box-shadow: var(--adm-shadow);
    padding: 24px 28px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    position: relative;
    overflow: visible;
}

.adm-pro .adm-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 0.92rem;
    font-weight: 700;
    color: #fff !important;
    text-decoration: none !important;
    background: linear-gradient(135deg, #ff5722, #ea580c);
    box-shadow: 0 6px 16px rgba(255, 87, 34, 0.28);
    white-space: nowrap;
}

.adm-pro .adm-add-btn:hover {
    opacity: 0.92;
    transform: translateY(-1px);
    color: #fff !important;
}

.adm-pro .adm-hero::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #ff5722, #ea580c);
}

.adm-pro .adm-hero__left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.adm-pro .adm-hero__icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #fff7ed;
    color: #ea580c;
    border: 1px solid #fed7aa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

.adm-pro .adm-hero__text h1 {
    margin: 0 0 6px;
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--adm-text);
}

.adm-pro .adm-hero__text p {
    margin: 0;
    color: var(--adm-muted);
    font-size: 0.9rem;
    max-width: 560px;
}

.adm-pro .adm-hero__stats {
    display: none;
}

.adm-pro .adm-delete-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: #fef2f2;
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.adm-form__hint {
    margin: 0 0 18px;
    padding: 12px 14px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    font-size: 0.88rem;
    color: #1e40af;
    line-height: 1.55;
}

.adm-form__photo {
    margin-bottom: 18px;
}

.adm-form__photo label {
    display: block;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--adm-text);
    margin-bottom: 10px;
}

.adm-form .img-upload #image-preview {
    width: 160px;
    height: 160px;
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    overflow: hidden;
    position: relative;
}

.adm-form .img-upload #image-preview label {
    position: absolute;
    left: 50%;
    bottom: 12px;
    transform: translateX(-50%);
    width: auto;
    min-width: 120px;
    height: auto;
    padding: 8px 14px;
    line-height: 1.3;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.95);
    color: var(--adm-text);
    font-size: 0.78rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}

.adm-form .img-upload #image-preview label:hover {
    background: var(--adm-primary);
    color: #fff;
}

.adm-form .img-upload #image-preview input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.adm-pro .adm-stat {
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    padding: 10px 14px;
    min-width: 110px;
}

.adm-pro .adm-stat span {
    display: block;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--adm-muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.adm-pro .adm-stat strong {
    display: block;
    margin-top: 2px;
    font-size: 0.92rem;
    color: var(--adm-text);
}

.adm-pro .adm-panel {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: visible;
}

.adm-pro .adm-panel__head {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.adm-pro .adm-panel__title {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.adm-pro .adm-panel__title i {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #eef2ff;
    color: #4338ca;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}

.adm-pro .adm-panel__body {
    padding: 0;
}

.adm-pro .adm-panel__body .table-responsiv {
    padding: 0 16px 16px;
    overflow-x: auto;
}

.adm-pro .dataTables_wrapper {
    overflow: visible;
}

.adm-pro #geniustable {
    width: 100% !important;
}

.adm-pro #geniustable tbody td:last-child {
    width: 1%;
    min-width: 120px;
    white-space: nowrap !important;
    overflow: visible !important;
}

.adm-pro #geniustable td > .action-list,
.adm-pro .btn-area > .action-list {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    width: auto !important;
    max-width: none !important;
    overflow: visible !important;
}

.adm-pro #geniustable td > .action-list a,
.adm-pro .btn-area > .action-list a {
    display: inline-flex !important;
    align-items: center;
    gap: 6px;
    padding: 8px 14px !important;
    border-radius: 8px !important;
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    width: auto !important;
    min-width: 0 !important;
    max-width: none !important;
    line-height: 1.35 !important;
    height: auto !important;
    overflow: visible !important;
    box-sizing: border-box;
    transition: all 0.15s;
}

.adm-pro .btn-area .add-btn {
    background: linear-gradient(135deg, #4f46e5, #2563eb) !important;
    border: 0 !important;
    border-radius: 10px !important;
    padding: 10px 18px !important;
    font-weight: 700 !important;
    width: auto !important;
    white-space: nowrap !important;
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25) !important;
}

.adm-pro #geniustable thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 1px solid var(--adm-border) !important;
    padding: 14px 12px !important;
}

.adm-pro #geniustable tbody td {
    vertical-align: middle !important;
    padding: 14px 12px !important;
    font-size: 0.9rem;
    color: #334155;
    border-color: #f1f5f9 !important;
    overflow: visible !important;
}

.adm-pro #geniustable td > .action-list a.edit,
.adm-pro .btn-area > .action-list a.edit {
    background: #eff6ff;
    color: #2563eb !important;
    border: 1px solid #bfdbfe;
}

.adm-pro #geniustable td > .action-list a.edit:hover,
.adm-pro .btn-area > .action-list a.edit:hover {
    background: #2563eb;
    color: #fff !important;
}

.adm-pro #geniustable td > .action-list a.login-as,
.adm-pro .btn-area > .action-list a.login-as {
    background: #ecfdf5;
    color: #059669 !important;
    border: 1px solid #a7f3d0;
}

.adm-pro #geniustable td > .action-list a.login-as:hover,
.adm-pro .btn-area > .action-list a.login-as:hover {
    background: #059669;
    color: #fff !important;
}

.adm-pro #geniustable td > .action-list a.delete,
.adm-pro .btn-area > .action-list a.delete {
    background: #fef2f2;
    color: #dc2626 !important;
    border: 1px solid #fecaca;
}

.adm-pro #geniustable td > .action-list a.delete:hover,
.adm-pro .btn-area > .action-list a.delete:hover {
    background: #dc2626;
    color: #fff !important;
}

.adm-pro #modal1 .modal-content,
.adm-pro #confirm-delete .modal-content {
    border: 0;
    border-radius: 16px;
    overflow: visible;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
}

.adm-pro #modal1 .modal-dialog {
    overflow: visible;
}

.adm-pro #modal1 .modal-header,
.adm-pro #confirm-delete .modal-header {
    background: linear-gradient(180deg, #eef2ff 0%, #fff 100%);
    border-bottom: 1px solid #f1f5f9;
    padding: 18px 22px;
}

.adm-pro #modal1 .modal-title {
    font-weight: 800;
    color: var(--adm-text);
}

.adm-pro #modal1 .modal-body {
    padding: 0;
    overflow: visible;
}

.adm-pro #modal1 .modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 14px 22px;
    background: #fafbfc;
}

.adm-pro #confirm-delete .modal-body {
    padding: 24px 22px;
}

.adm-pro #confirm-delete .btn-danger {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    border: 0;
    border-radius: 8px;
    font-weight: 700;
}

/* Modal form (create/edit) */
.adm-form {
    padding: 22px 24px 16px;
}

.adm-form__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.adm-form__field {
    margin-bottom: 16px;
}

.adm-form__field--full {
    grid-column: 1 / -1;
}

.adm-form__field label {
    display: block;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--adm-text);
    margin-bottom: 6px;
}

.adm-form__field small {
    display: block;
    margin-top: 4px;
    font-size: 0.74rem;
    color: var(--adm-muted);
}

.adm-form__field .input-field {
    width: 100%;
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    padding: 11px 13px;
    font-size: 0.88rem;
    color: var(--adm-text);
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.adm-form__field .input-field:focus {
    outline: none;
    border-color: var(--adm-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.adm-form__field--select {
    position: relative;
    z-index: 2;
}

.adm-form__field select.adm-select,
.adm-form__field select.input-field {
    display: block;
    width: 100% !important;
    height: auto !important;
    min-height: 48px !important;
    line-height: 1.45 !important;
    padding: 12px 42px 12px 14px !important;
    margin-bottom: 0 !important;
    font-size: 0.92rem !important;
    font-weight: 600 !important;
    color: var(--adm-text) !important;
    background-color: #fff !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
    background-size: 16px !important;
    border: 1px solid var(--adm-border) !important;
    border-radius: 10px !important;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
    overflow: visible !important;
    text-overflow: ellipsis;
    white-space: normal;
}

.adm-form__field select.adm-select:focus,
.adm-form__field select.input-field:focus {
    border-color: var(--adm-primary) !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}

.adm-form__field select.adm-select option,
.adm-form__field select.input-field option {
    padding: 10px 12px;
    font-size: 0.92rem;
    line-height: 1.5;
    color: #0f172a;
    background: #fff;
}

/* Status toggles (Activated / Deactivated) — white text on colored bg */
.adm-pro .action-list .nice-select.drop-success,
.adm-pro .action-list .nice-select.drop-danger {
    color: #fff !important;
    border: 0 !important;
}

.adm-pro .action-list .nice-select.drop-success .current,
.adm-pro .action-list .nice-select.drop-danger .current {
    color: #fff !important;
}

.adm-pro .action-list .nice-select.drop-success::after,
.adm-pro .action-list .nice-select.drop-danger::after {
    border-bottom-color: #fff !important;
    border-right-color: #fff !important;
}

.adm-form #modal1 .modal-body,
.adm-pro #modal1 .modal-body {
    overflow: visible;
}

.adm-form__actions {
    margin-top: 8px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
}

.adm-form__submit {
    width: 100%;
    border: 0;
    border-radius: 10px;
    padding: 12px 18px;
    font-size: 0.92rem;
    font-weight: 700;
    color: #fff;
    cursor: pointer;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    transition: transform 0.15s;
    white-space: normal;
    line-height: 1.4;
    height: auto !important;
    min-height: 44px;
}

.adm-form__submit:hover {
    transform: translateY(-1px);
}

.adm-form__submit--edit {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    box-shadow: 0 6px 16px rgba(8, 145, 178, 0.25);
}

@media (max-width: 640px) {
    .adm-form__grid { grid-template-columns: 1fr; }
    .adm-pro .adm-hero { padding: 20px; }
}

.adm-breadcrumb {
    list-style: none;
    padding: 0;
    margin: 8px 0 0;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    align-items: center;
}
.adm-breadcrumb li {
    font-size: 0.78rem;
    color: var(--adm-muted);
}
.adm-breadcrumb li:not(:last-child)::after {
    content: '/';
    margin-left: 8px;
    color: #cbd5e1;
}
.adm-breadcrumb a {
    color: var(--adm-muted) !important;
    text-decoration: none;
    font-weight: 500;
}
.adm-breadcrumb a:hover { color: var(--adm-primary) !important; }
.adm-breadcrumb span { color: var(--adm-text); font-weight: 600; }

.adm-pro .product-description,
.adm-pro .add-product-content {
    background: transparent;
    padding: 0;
}
.adm-pro .product-description .body-area {
    padding: 22px 24px;
}
.adm-pro .product-description .body-area > .row {
    display: grid;
    grid-template-columns: minmax(140px, 220px) minmax(0, 1fr);
    gap: 8px 16px;
    align-items: center;
    margin-bottom: 16px;
}
.adm-pro .product-description .body-area > .row > .col-lg-4 {
    width: 100%;
    max-width: none;
    flex: none;
    text-align: right !important;
}
.adm-pro .product-description .body-area > .row > .col-lg-7,
.adm-pro .product-description .body-area > .row > .col-lg-8 {
    width: 100%;
    max-width: none;
    flex: none;
}
.adm-pro .product-description .body-area .heading {
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    margin: 0 !important;
    color: var(--adm-text) !important;
}
.adm-pro .addProductSubmit-btn,
.adm-pro .submit-btn {
    border: 0;
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    cursor: pointer;
}

.adm-thumb {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    padding: 4px;
}
.adm-thumb--logo img { height: 36px; width: auto; object-fit: contain; }
.adm-thumb--avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    padding: 0;
}
.adm-thumb--avatar img { width: 100%; height: 100%; object-fit: cover; }
.adm-link {
    color: var(--adm-primary) !important;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none !important;
}
.adm-link:hover { text-decoration: underline !important; }

@media (max-width: 768px) {
    .adm-pro .product-description .body-area > .row {
        grid-template-columns: 1fr;
    }
    .adm-pro .product-description .body-area > .row > .col-lg-4 {
        text-align: left !important;
    }
}

.adm-pro .adm-cell-truncate { max-width: 250px; }
.adm-pro .adm-toolbar-gap { gap: 12px; }
.adm-pro .adm-panel__tools { min-width: 220px; }
.adm-pro .adm-panel__tools .form-control { border-radius: 20px; padding: 8px 16px; }
.adm-pro .adm-message-box {
    white-space: pre-wrap;
    border-left: 4px solid #4f46e5;
    border-radius: 10px;
}

.role-perms {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px 24px;
    margin-bottom: 24px;
}

.role-perms__item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 14px;
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--adm-text);
}

@media (max-width: 768px) {
    .role-perms { grid-template-columns: 1fr; }
}
</style>
