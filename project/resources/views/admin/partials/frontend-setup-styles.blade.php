<style>
.fe-nav {
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

.fe-nav a {
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

.fe-nav a:hover {
    background: #f1f5f9;
    color: var(--adm-text) !important;
}

.fe-nav a.is-active {
    background: linear-gradient(135deg, #eef2ff, #dbeafe);
    color: #4338ca !important;
    border-color: #c7d2fe;
}

.fe-nav a i { font-size: 0.82rem; }

.fe-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 260px;
    gap: 22px;
    align-items: start;
}

.fe-layout--single { grid-template-columns: 1fr; }

.fe-aside {
    position: sticky;
    top: 84px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.fe-tip {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    padding: 16px 18px;
}

.fe-tip h5 {
    margin: 0 0 10px;
    font-size: 0.84rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.fe-tip h5 i { color: #f59e0b; }

.fe-tip p, .fe-tip li {
    font-size: 0.78rem;
    color: var(--adm-muted);
    line-height: 1.55;
    margin: 0 0 8px;
}

.fe-tip ul { margin: 0; padding: 0; list-style: none; }

.fe-tip li {
    position: relative;
    padding-left: 14px;
    margin-bottom: 8px;
}

.fe-tip li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 7px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #93c5fd;
}

.fe-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.fe-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.fe-card__head h3 {
    margin: 0;
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.fe-card__head h3 i {
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

.fe-card__body { padding: 22px 24px; }

.fe-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 14px 22px 0;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfc;
}

.fe-tab {
    border: 0;
    background: transparent;
    padding: 10px 16px;
    border-radius: 10px 10px 0 0;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--adm-muted);
    cursor: pointer;
    margin-bottom: -1px;
    border: 1px solid transparent;
    transition: color 0.15s, background 0.15s;
}

.fe-tab:hover { color: var(--adm-text); background: rgba(255,255,255,0.7); }

.fe-tab.is-active {
    color: #4338ca;
    background: #fff;
    border-color: #f1f5f9;
    border-bottom-color: #fff;
}

.fe-panel { display: none; padding: 22px 24px; }
.fe-panel.is-active { display: block; }

.fe-item {
    background: #fff;
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 12px;
}

.fe-item__label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--adm-text);
}

.fe-item__label i {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #f1f5f9;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.fe-hint {
    margin: 0 0 16px;
    padding: 12px 14px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    font-size: 0.82rem;
    color: #1e40af;
    line-height: 1.55;
}

.fe-hint code {
    background: rgba(255,255,255,0.7);
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 0.78rem;
}

.fe-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding-top: 8px;
    margin-top: 8px;
    border-top: 1px solid #f1f5f9;
}

.fe-save {
    border: 0;
    border-radius: 10px;
    padding: 12px 24px;
    font-size: 0.92rem;
    font-weight: 700;
    color: #fff;
    cursor: pointer;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.fe-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 10px;
    border: 1px solid var(--adm-border);
    background: #fff;
    color: var(--adm-text) !important;
    font-size: 0.86rem;
    font-weight: 700;
    text-decoration: none !important;
}

.fe-upload {
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    background: #f8fafc;
    padding: 16px;
    text-align: center;
}

.fe-upload-preview {
    width: 100%;
    max-width: 280px;
    height: 160px;
    margin: 0 auto 14px;
    border-radius: 12px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border: 1px solid var(--adm-border);
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.fe-upload-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.fe-upload label.upload-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    color: #fff;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    margin-bottom: 0;
}

.fe-upload input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.fe-badge-count {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    background: #eef2ff;
    color: #4338ca;
    font-size: 0.76rem;
    font-weight: 700;
}

.fe-pricing-layout {
    display: grid;
    grid-template-columns: 340px minmax(0, 1fr);
    gap: 22px;
    align-items: start;
}

.fe-empty {
    text-align: center;
    padding: 48px 24px;
    color: var(--adm-muted);
}

.fe-empty i {
    font-size: 2rem;
    color: #cbd5e1;
    margin-bottom: 12px;
}

.adm-pro .adm-form { padding: 0; }

.adm-thumb--feature img,
.adm-thumb--preview img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 10px;
}

.adm-icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4338ca;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.adm-badge-count {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 8px;
    background: #eef2ff;
    color: #4338ca;
    font-weight: 700;
    font-size: 0.88rem;
}

@media (max-width: 1100px) {
    .fe-layout, .fe-pricing-layout { grid-template-columns: 1fr; }
    .fe-aside { position: static; }
}
</style>
