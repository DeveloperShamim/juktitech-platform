<style>
.pay-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
    padding: 12px;
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
}

.pay-nav a {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--adm-muted) !important;
    text-decoration: none !important;
    border: 1px solid transparent;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}

.pay-nav a:hover {
    background: #f1f5f9;
    color: var(--adm-text) !important;
}

.pay-nav a.is-active {
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    color: #047857 !important;
    border-color: #a7f3d0;
}

.pay-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.pay-stat {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    padding: 16px 18px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.pay-stat__icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.pay-stat__icon--pending   { background: #fff7ed; color: #c2410c; }
.pay-stat__icon--completed { background: #dcfce7; color: #15803d; }
.pay-stat__icon--failed    { background: #fee2e2; color: #b91c1c; }
.pay-stat__icon--available { background: #dbeafe; color: #1d4ed8; }
.pay-stat__icon--matched   { background: #dcfce7; color: #15803d; }
.pay-stat__icon--ignored   { background: #f1f5f9; color: #64748b; }

.pay-stat__body span {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--adm-muted);
    margin-bottom: 3px;
}

.pay-stat__body strong {
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--adm-text);
    line-height: 1.1;
}

.pay-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.pay-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.pay-card__head h3 {
    margin: 0;
    font-size: 0.96rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.pay-card__head h3 i {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #ecfdf5;
    color: #059669;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}

.pay-card__body { padding: 0 14px 14px; }

.pay-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 14px 6px 0;
}

.pay-tab {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--adm-muted) !important;
    text-decoration: none !important;
    border: 1px solid var(--adm-border);
    background: #fff;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}

.pay-tab:hover {
    background: #f8fafc;
    color: var(--adm-text) !important;
}

.pay-tab.is-active {
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    color: #047857 !important;
    border-color: #a7f3d0;
}

.pay-tab__count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.08);
    font-size: 0.68rem;
    font-weight: 800;
}

.pay-tab.is-active .pay-tab__count {
    background: rgba(5, 150, 105, 0.15);
    color: #047857;
}

.pay-hint {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin: 14px 6px 0;
    padding: 11px 13px;
    border-radius: 10px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    font-size: 0.78rem;
    line-height: 1.5;
}

.pay-hint i { margin-top: 2px; flex-shrink: 0; }

.pay-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    white-space: nowrap;
}

.pay-pill--pending   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.pay-pill--completed { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.pay-pill--failed    { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.pay-pill--available { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.pay-pill--matched   { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.pay-pill--ignored   { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }

.pay-code {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #334155;
    font-size: 0.76rem;
    font-weight: 600;
    font-family: ui-monospace, monospace;
}

.pay-customer strong {
    display: block;
    font-size: 0.84rem;
    color: var(--adm-text);
}

.pay-customer small {
    display: block;
    font-size: 0.74rem;
    color: var(--adm-muted);
    margin-top: 2px;
}

.pay-amount {
    font-size: 0.88rem;
    font-weight: 800;
    color: #047857;
}

.pay-method {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 9px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    font-size: 0.74rem;
    font-weight: 600;
    color: var(--adm-text);
    text-transform: capitalize;
}

.pay-date {
    font-size: 0.78rem;
    color: var(--adm-text);
    font-weight: 600;
}

.pay-date small {
    display: block;
    font-size: 0.7rem;
    color: var(--adm-muted);
    font-weight: 400;
    margin-top: 2px;
}

.pay-actions {
    display: flex;
    justify-content: flex-end;
    gap: 6px;
    flex-wrap: wrap;
}

.pay-act {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 12px;
    border-radius: 8px;
    border: none;
    font-size: 0.74rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s, transform 0.1s;
}

.pay-act--approve { background: #dcfce7; color: #15803d; }
.pay-act--approve:hover { background: #22c55e; color: #fff; }
.pay-act--reject  { background: #fee2e2; color: #b91c1c; }
.pay-act--reject:hover  { background: #ef4444; color: #fff; }

.pay-note {
    display: block;
    margin-top: 5px;
    font-size: 0.7rem;
    color: #b91c1c;
    line-height: 1.35;
}

.pay-empty {
    text-align: center;
    padding: 48px 20px;
    color: var(--adm-muted);
}

.pay-empty i {
    font-size: 2.2rem;
    color: #cbd5e1;
    margin-bottom: 12px;
}

.pay-empty strong {
    display: block;
    font-size: 0.92rem;
    color: var(--adm-text);
    margin-bottom: 4px;
}

.pay-empty small { font-size: 0.78rem; }

.pay-msg {
    display: block;
    max-width: 280px;
    font-size: 0.74rem;
    color: var(--adm-muted);
    line-height: 1.45;
    word-break: break-word;
}

.pay-pagination {
    padding: 14px 16px;
    border-top: 1px solid #f1f5f9;
}

/* QR page */
.pay-qr-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 18px;
    align-items: start;
}

.pay-qr-box {
    text-align: center;
    padding: 20px;
    border-radius: 14px;
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
    border: 1px dashed #cbd5e1;
}

.pay-qr-generate {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px 18px;
    border: 0;
    border-radius: 11px;
    background: linear-gradient(135deg, #059669, #047857);
    color: #fff;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.25);
    transition: opacity 0.15s;
}

.pay-qr-generate:hover { opacity: 0.92; }
.pay-qr-generate:disabled { opacity: 0.6; cursor: not-allowed; }

.pay-qr-canvas {
    display: inline-block;
    padding: 14px;
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--adm-border);
    box-shadow: var(--adm-shadow);
    margin-bottom: 14px;
}

.pay-qr-timer {
    font-size: 1.1rem;
    font-weight: 800;
    color: #047857;
}

.pay-steps {
    margin: 0;
    padding: 0;
    list-style: none;
}

.pay-steps li {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.8rem;
    color: var(--adm-muted);
    line-height: 1.45;
}

.pay-steps li:last-child { border-bottom: 0; }

.pay-steps__num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.72rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.pay-warn {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
    font-size: 0.78rem;
    line-height: 1.45;
    margin-top: 14px;
}

@media (max-width: 900px) {
    .pay-qr-layout { grid-template-columns: 1fr; }
}

@media (max-width: 640px) {
    .pay-stats { grid-template-columns: 1fr; }
}
</style>
