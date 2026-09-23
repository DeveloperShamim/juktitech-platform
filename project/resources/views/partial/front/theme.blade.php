<style>
:root {
    --primary-color: {{ $theme->primary }};
    --primary-hover: {{ $theme->primary_hover }};
    --primary-light: {{ $theme->primary_light }};
    --primary-lighter: {{ $theme->primary_lighter }};
    --primary-muted: {{ $theme->primary_muted }};
    --primary-border: {{ $theme->primary_border }};
    --primary-border-soft: {{ $theme->primary_border_soft }};
    --primary-bg-soft: {{ $theme->primary_bg_soft }};
    --primary-bg: {{ $theme->primary_bg }};
    --primary-bg-light: {{ $theme->primary_bg_light }};
    --primary-bg-lighter: {{ $theme->primary_bg_lighter }};
    --primary-rgb: {{ $theme->primary_rgb }};
    --accent-color: {{ $theme->accent }};
    --accent-hover: {{ $theme->accent_hover }};
    --secondary-color: {{ $theme->secondary }};
    --hero-accent-color: {{ $theme->hero_accent }};
    --dark-bg: {{ $theme->dark_bg }};
    --footer-link-hover: {{ $theme->footer_link_hover }};
    --footer-icon-color: {{ $theme->footer_icon }};
    --text-dark: {{ $theme->text_dark }};
    --text-muted: {{ $theme->text_muted }};
    --text-heading: {{ $theme->text_heading }};
    --brand-orange: var(--primary-color);
    --accent-gradient: {{ $theme->accent_gradient }};
    --accent-gradient-hover: {{ $theme->accent_gradient_hover }};
    --accent-gradient-vertical: {{ $theme->accent_gradient_vertical }};
    --accent-gradient-vertical-hover: {{ $theme->accent_gradient_vertical_hover }};
    --accent-gradient-text: {{ $theme->accent_gradient_text }};
    --accent-gradient-dual: {{ $theme->accent_gradient_dual }};
    --stats-gradient: {{ $theme->stats_gradient }};
    --cta-gradient: {{ $theme->cta_gradient }};
    --footer-gradient: {{ $theme->footer_gradient }};
    --stats-start: {{ $theme->stats_start }};
    --stats-mid: {{ $theme->stats_mid }};
    --stats-end: {{ $theme->stats_end }};
    --accent-soft: {{ $theme->primary_soft }};
    --accent-shadow: {{ $theme->primary_shadow }};
    --accent-shadow-md: {{ $theme->primary_shadow_md }};
    --accent-shadow-lg: {{ $theme->primary_shadow_lg }};
    --accent-shadow-sm: {{ $theme->primary_shadow_sm }};
    --accent-shadow-xs: {{ $theme->primary_shadow_xs }};
    --accent-shadow-card: {{ $theme->primary_shadow_card }};
    --navbar-border: {{ $theme->navbar_border }};
}

.text-orange,
.text-primary {
    color: var(--primary-color) !important;
}

.btn-orange,
.orange-active-btn,
.btn-primary {
    background: var(--accent-gradient) !important;
    border: none !important;
    color: #fff !important;
    box-shadow: 0 8px 22px var(--accent-shadow);
}

.btn-orange:hover,
.orange-active-btn:hover,
.btn-primary:hover {
    background: var(--accent-gradient-hover) !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 12px 28px var(--accent-shadow-lg);
}

.btn-outline-primary {
    color: var(--primary-color) !important;
    border-color: var(--primary-border-soft) !important;
    background: #fff !important;
}

.btn-outline-primary:hover {
    background: var(--primary-bg-light) !important;
    border-color: var(--primary-border) !important;
    color: var(--primary-hover) !important;
}

.btn-custom {
    background: var(--accent-gradient) !important;
    border: none !important;
    color: #fff !important;
    box-shadow: 0 8px 22px var(--accent-shadow);
}

.btn-custom:hover {
    background: var(--accent-gradient-hover) !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.btn-modern {
    background: var(--accent-gradient) !important;
    border: none !important;
    color: #fff !important;
}

.featured-card,
.featured-pricing {
    border-color: var(--primary-color) !important;
}

.popular-badge {
    background: var(--accent-gradient) !important;
    color: #fff !important;
}

.blue-outline-btn {
    border: 1.5px solid #e5e7eb !important;
    color: #1f2937 !important;
    border-radius: 50px !important;
    background: #fff !important;
    transition: 0.3s;
}

.blue-outline-btn:hover {
    background: var(--primary-bg-light) !important;
    border-color: var(--primary-border-soft) !important;
    color: var(--primary-hover) !important;
}

.orange-active-btn,
.orange-btn,
.orange-submit-btn {
    background: var(--accent-gradient) !important;
    border: none !important;
    color: #fff !important;
    border-radius: 50px;
    box-shadow: 0 10px 24px var(--accent-shadow);
}

.orange-active-btn:hover,
.orange-btn:hover,
.orange-submit-btn:hover {
    background: var(--accent-gradient-hover) !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.featured-card {
    background-color: var(--primary-bg-light) !important;
}

.hero-accent {
    color: var(--hero-accent-color) !important;
}

section.text-white .hero-accent,
.hero-wrapper .hero-accent,
.pricing-hero .hero-accent,
.about-hero .hero-accent,
.team-hero .hero-accent,
.portfolio-hero .hero-accent,
.domain-hero .hero-accent,
.contact-hero .hero-accent {
    color: var(--hero-accent-color) !important;
}
</style>
