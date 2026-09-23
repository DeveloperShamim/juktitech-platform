<style>
.um-nav {
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

.um-nav a {
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

.um-nav a:hover {
    background: #f1f5f9;
    color: var(--adm-text) !important;
}

.um-nav a.is-active {
    background: linear-gradient(135deg, #eef2ff, #dbeafe);
    color: #4338ca !important;
    border-color: #c7d2fe;
}

.um-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 14px;
    box-shadow: var(--adm-shadow);
    overflow: hidden;
}

.um-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
}

.um-card__head h3 {
    margin: 0;
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.um-card__head h3 i {
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

.um-card__body { padding: 22px 24px; }

.um-section {
    margin-bottom: 22px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}

.um-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: 0;
}

.um-section__title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--adm-text);
}

.um-section__title i {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #f1f5f9;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.um-photo {
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    border-radius: 12px;
}

.um-photo__preview {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.12);
    background: #e2e8f0 center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.um-photo__preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.um-photo__preview.is-empty {
    color: #94a3b8;
    font-size: 2rem;
}

.um-photo__info strong {
    display: block;
    font-size: 0.88rem;
    color: var(--adm-text);
    margin-bottom: 4px;
}

.um-photo__info small {
    display: block;
    font-size: 0.76rem;
    color: var(--adm-muted);
    margin-bottom: 10px;
}

.um-photo__btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 14px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    color: #fff !important;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    margin-bottom: 0;
}

.um-photo input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.um-save {
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

.um-save--edit {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    box-shadow: 0 6px 16px rgba(8, 145, 178, 0.25);
}

.um-link {
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

.um-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 8px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
}

.um-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    background: #eef2ff;
    color: #4338ca;
    font-size: 0.76rem;
    font-weight: 700;
}

.um-hint {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin: 16px 16px 0;
    padding: 12px 14px;
    border-radius: 10px;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    color: #0369a1;
    font-size: 0.8rem;
    line-height: 1.5;
}

.um-hint i { margin-top: 2px; flex-shrink: 0; }

.um-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 14px 16px;
    background: #f8fafc;
    border: 1px solid var(--adm-border);
    border-radius: 12px;
}

.um-toggle-row small {
    display: block;
    margin-top: 4px;
    font-size: 0.74rem;
    color: var(--adm-muted);
    line-height: 1.45;
}

.adm-form--modal { padding: 4px 2px 0; }

.adm-form--modal .um-section { margin-bottom: 18px; padding-bottom: 16px; }

@media (max-width: 640px) {
    .um-photo { flex-direction: column; text-align: center; }
}
</style>
