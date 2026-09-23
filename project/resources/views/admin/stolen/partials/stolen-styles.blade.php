<style>
.sr-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.sr-stat {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    padding: 16px 18px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.sr-stat__icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.sr-stat__icon--total    { background: #eef2ff; color: #4338ca; }
.sr-stat__icon--pending  { background: #fff7ed; color: #c2410c; }
.sr-stat__icon--blocked  { background: #fee2e2; color: #b91c1c; }
.sr-stat__icon--resolved { background: #dcfce7; color: #15803d; }

.sr-stat__body span {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--adm-muted);
    margin-bottom: 3px;
}

.sr-stat__body strong {
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--adm-text);
    line-height: 1.1;
}

.sr-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 18px;
    align-items: start;
}

.sr-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.sr-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.sr-card__head h3 {
    margin: 0;
    font-size: 0.96rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.sr-card__head h3 i {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #fee2e2;
    color: #dc2626;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}

.sr-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.sr-tool {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 9px;
    border: 1px solid var(--adm-border);
    background: #fff;
    color: var(--adm-text);
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none !important;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
}

.sr-tool:hover { background: #f8fafc; color: var(--adm-text) !important; }

.sr-tool--danger {
    background: #fef2f2;
    border-color: #fecaca;
    color: #b91c1c !important;
}

.sr-tool--danger:hover { background: #fee2e2; }

.sr-tool--danger:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

.sr-tool--primary {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8 !important;
}

.sr-tool--ghost {
    color: var(--adm-muted) !important;
}

.sr-card__body { padding: 0 14px 14px; }

.sr-hint {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin: 14px 6px 0;
    padding: 11px 13px;
    border-radius: 10px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
    font-size: 0.78rem;
    line-height: 1.5;
}

.sr-hint i { margin-top: 2px; flex-shrink: 0; }

.sr-aside {
    position: sticky;
    top: 84px;
}

.sr-guide__head {
    padding: 16px 18px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.sr-guide__head h4 {
    margin: 0;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.sr-guide__body { padding: 14px 16px 16px; }

.sr-guide__item {
    display: flex;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}

.sr-guide__item:last-child { border-bottom: 0; padding-bottom: 0; }

.sr-guide__icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.78rem;
    flex-shrink: 0;
}

.sr-guide__icon--cache  { background: #fef9c3; color: #a16207; }
.sr-guide__icon--kill   { background: #1e1b4b; color: #f87171; }
.sr-guide__icon--block  { background: #fee2e2; color: #dc2626; }
.sr-guide__icon--status { background: #f1f5f9; color: #64748b; }
.sr-guide__icon--delete { background: #ffe4e6; color: #e11d48; }

.sr-guide__text strong {
    display: block;
    font-size: 0.8rem;
    color: var(--adm-text);
    margin-bottom: 2px;
}

.sr-guide__text small {
    display: block;
    font-size: 0.73rem;
    color: var(--adm-muted);
    line-height: 1.45;
}

.sr-doc-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 14px;
    padding: 10px 12px;
    border-radius: 10px;
    background: linear-gradient(135deg, #eef2ff, #dbeafe);
    border: 1px solid #c7d2fe;
    color: #4338ca !important;
    font-size: 0.78rem;
    font-weight: 700;
    text-decoration: none !important;
}

.sr-doc-link:hover { background: #e0e7ff; }

/* Table cell content */
.sr-domain__name {
    display: block;
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--adm-text);
    margin-bottom: 6px;
    word-break: break-word;
}

.sr-domain__visit {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 999px;
    background: #eef2ff;
    color: #4338ca !important;
    font-size: 0.7rem;
    font-weight: 700;
    text-decoration: none !important;
    transition: background 0.15s;
}

.sr-domain__visit:hover { background: #dbeafe; }

.sr-details__row {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    font-size: 0.76rem;
    color: var(--adm-muted);
    margin-bottom: 4px;
}

.sr-details__row i {
    width: 14px;
    margin-top: 2px;
    color: #94a3b8;
    flex-shrink: 0;
}

.sr-details__url {
    word-break: break-all;
    line-height: 1.4;
}

.sr-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    white-space: nowrap;
}

.sr-pill--pending  { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.sr-pill--resolved { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.sr-pill--blocked  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

.sr-date__main {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--adm-text);
}

.sr-date__sub {
    display: block;
    font-size: 0.7rem;
    color: var(--adm-muted);
    margin-top: 2px;
}

.sr-cache-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 9px;
    border-radius: 999px;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.68rem;
    font-weight: 700;
    margin-right: 4px;
}

.sr-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
}

.adm-pro .stolen-btn-action,
.adm-pro .btn-action {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    border: none;
    transition: 0.18s;
    cursor: pointer;
    text-decoration: none !important;
}

.adm-pro .btn-clear-cache { background: #fef9c3; color: #a16207; }
.adm-pro .btn-clear-cache:hover { background: #fde047; color: #713f12; }
.adm-pro .btn-clear-cache.loading,
.adm-pro .btn-kill-switch-action.loading,
.adm-pro .btn-toggle-block-action.loading {
    background: #94a3b8 !important;
    color: #fff !important;
    pointer-events: none;
}
.adm-pro .btn-clear-cache-success { background: #dcfce7; color: #15803d; }

.adm-pro .btn-kill-switch-off { background: #1e1b4b; color: #f87171; }
.adm-pro .btn-kill-switch-off:hover { background: #991b1b; color: #fff; }
.adm-pro .btn-kill-switch-on { background: #fee2e2; color: #b91c1c; }
.adm-pro .btn-kill-switch-on:hover { background: #16a34a; color: #fff; }
.adm-pro .btn-kill-switch-missing { background: #f1f5f9; color: #64748b; }
.adm-pro .btn-kill-switch-missing:hover { background: #fde047; color: #713f12; }

.adm-pro .btn-block-domain { background: #fee2e2; color: #b91c1c; }
.adm-pro .btn-block-domain:hover { background: #ef4444; color: #fff; }
.adm-pro .btn-unblock-domain { background: #dcfce7; color: #15803d; }
.adm-pro .btn-unblock-domain:hover { background: #22c55e; color: #fff; }

.adm-pro .btn-status-change { background: #f1f5f9; color: #64748b; }
.adm-pro .btn-status-change:hover { background: #4338ca; color: #fff; }
.adm-pro .btn-delete-report { background: #ffe4e6; color: #e11d48; }
.adm-pro .btn-delete-report:hover { background: #e11d48; color: #fff; }

.adm-pro #select-all,
.adm-pro .row-checkbox {
    cursor: pointer;
    width: 17px;
    height: 17px;
    accent-color: #4338ca;
}

.adm-pro .stolen-checkbox-col { width: 42px; }

#cache-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 99999;
    min-width: 320px;
    max-width: 420px;
    display: none;
}

.cache-toast-alert {
    border-radius: 12px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.18);
    font-size: 0.84rem;
}

.sr-modal .modal-content {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
}

.sr-modal .modal-body { padding: 28px 24px 22px; text-align: center; }

.sr-modal .adm-delete-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: #fee2e2;
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

@media (max-width: 1100px) {
    .sr-layout { grid-template-columns: 1fr; }
    .sr-aside { position: static; }
}

@media (max-width: 768px) {
    .sr-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 480px) {
    .sr-stats { grid-template-columns: 1fr; }
}
</style>
