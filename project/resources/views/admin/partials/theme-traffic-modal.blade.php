<style>
.traffic-modal {
    --tm-bg: #f8fafc;
    --tm-card: #ffffff;
    --tm-text: #0f172a;
    --tm-muted: #64748b;
    --tm-border: #e2e8f0;
}
.traffic-modal .modal-dialog { max-width: 720px; }
.traffic-modal .modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(15, 23, 42, 0.18);
    background: var(--tm-bg);
}
.traffic-modal__hero {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #7c3aed 100%);
    padding: 24px 28px 22px;
    position: relative;
    overflow: hidden;
}
.traffic-modal__hero::before {
    content: '';
    position: absolute;
    right: -30px;
    top: -30px;
    width: 140px;
    height: 140px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    pointer-events: none;
}
.traffic-modal__hero-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    position: relative;
    z-index: 1;
}
.traffic-modal__hero-title {
    margin: 0 0 4px;
    font-size: 1.15rem;
    font-weight: 800;
    color: #fff !important;
}
.traffic-modal__hero-sub {
    margin: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.85) !important;
}
.traffic-modal__close {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff !important;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
    line-height: 1;
    flex-shrink: 0;
}
.traffic-modal__close:hover { background: rgba(255,255,255,0.25); }
.traffic-modal__summary {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    margin-top: 18px;
    position: relative;
    z-index: 1;
}
.traffic-modal__summary-item {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    padding: 12px 14px;
}
.traffic-modal__summary-label {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: rgba(255,255,255,0.75) !important;
    margin-bottom: 4px;
    font-weight: 600;
}
.traffic-modal__summary-value {
    font-size: 1.35rem;
    font-weight: 800;
    color: #fff !important;
}
.traffic-modal .modal-body { padding: 16px 20px 20px; }
.traffic-modal__layout {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 16px;
    align-items: start;
}
.traffic-modal__chart-box {
    background: var(--tm-card);
    border: 1px solid var(--tm-border);
    border-radius: 14px;
    padding: 14px;
}
.traffic-modal__chart-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
}
.traffic-modal__chart-wrap canvas {
    width: 100% !important;
    height: 100% !important;
}
.traffic-modal__chart-center {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}
.traffic-modal__chart-total {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--tm-text);
}
.traffic-modal__chart-label {
    font-size: 0.68rem;
    color: var(--tm-muted);
    font-weight: 600;
    text-transform: uppercase;
}
.traffic-modal__list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.traffic-modal__item {
    background: var(--tm-card);
    border: 1px solid var(--tm-border);
    border-radius: 12px;
    padding: 12px 14px;
    display: grid;
    grid-template-columns: 40px 1fr auto;
    align-items: center;
    gap: 12px;
}
.traffic-modal__item-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.95rem;
}
.traffic-modal__item-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--tm-text);
    margin-bottom: 6px;
}
.traffic-modal__item-bar {
    height: 6px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
}
.traffic-modal__item-bar span {
    display: block;
    height: 100%;
    border-radius: 999px;
}
.traffic-modal__item-count {
    font-size: 0.95rem;
    font-weight: 800;
    color: var(--tm-text);
}
.traffic-modal__item-pct {
    font-size: 0.72rem;
    color: var(--tm-muted);
    font-weight: 600;
    text-align: right;
}
.traffic-modal__empty {
    text-align: center;
    padding: 40px 24px;
    background: var(--tm-card);
    border: 1px dashed var(--tm-border);
    border-radius: 14px;
}
.traffic-modal__empty-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 14px;
    background: #eff6ff;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2563eb;
    font-size: 1.4rem;
}
@media (max-width: 640px) {
    .traffic-modal__layout { grid-template-columns: 1fr; }
    .traffic-modal__chart-box { max-width: 220px; margin: 0 auto; }
}
</style>

<div class="modal fade traffic-modal" id="themeTrafficModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="traffic-modal__hero">
                <div class="traffic-modal__hero-top">
                    <div>
                        <h5 class="traffic-modal__hero-title" id="themeTrafficTitle">
                            <i class="fas fa-chart-pie mr-2"></i>{{ __('Traffic Sources') }}
                        </h5>
                        <p class="traffic-modal__hero-sub" id="themeTrafficSub">{{ __('See where visitors come from') }}</p>
                    </div>
                    <button type="button" class="traffic-modal__close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="traffic-modal__summary">
                    <div class="traffic-modal__summary-item">
                        <div class="traffic-modal__summary-label">{{ __('Total Views') }}</div>
                        <div class="traffic-modal__summary-value" id="themeTrafficTotal">0</div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="themeTrafficContent"></div>
            </div>
        </div>
    </div>
</div>
