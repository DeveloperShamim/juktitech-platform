<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Vantor eCommerce Pro — রেডি Laravel ই-কমার্স ওয়েবসাইট | Jukti Tech</title>
<meta name="description" content="কুরিয়ার বুকিং, ফেক অর্ডার ধরা, POS, লাভের হিসাব আর সার্ভার-সাইড ট্র্যাকিংসহ রেডি অনলাইন শপ। সম্পূর্ণ সোর্স কোড {{ $priceBn }}, এককালীন।">
<link rel="canonical" href="{{ url('/') }}">
<meta property="og:type" content="product">
<meta property="og:title" content="Vantor eCommerce Pro — সম্পূর্ণ সোর্স কোড {{ $priceBn }}">
<meta property="og:description" content="বাংলাদেশের অনলাইন শপের জন্য বানানো রেডি Laravel ই-কমার্স ওয়েবসাইট।">
<meta property="og:image" content="{{ $assetBase }}/shots/store-home.webp">
<meta property="og:url" content="{{ url('/') }}">
@if(!empty($gs->favicon))<link rel="icon" href="{{ asset_image('assets/images/'.$gs->favicon) }}">@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wdth,wght@75..125,400..800&family=Hind+Siliguri:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap">
<style>
/* Layout: one product, one story — the order travels down the page: hero pipeline → features → dashboard → setup steps → price → FAQ */
:root {
  --paper: #F5F6F0;
  --surface: #FFFFFF;
  --ink: #10221D;
  --muted: #56665F;
  --line: #DCE2DA;
  --leaf: #0E6B4F;
  --leaf-soft: #E3F1EA;
  --marigold: #E89B0C;
  --marigold-ink: #8F5A00;
  --marigold-soft: #FCEFD3;
  --shadow: 0 1px 0 rgba(16,34,29,.04), 0 12px 32px -12px rgba(16,34,29,.18);
  --f-display: "Anek Bangla", "Hind Siliguri", "Noto Sans Bengali", system-ui, sans-serif;
  --f-body: "Hind Siliguri", "Noto Sans Bengali", system-ui, sans-serif;
  --f-mono: "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, monospace;
  --r: 14px;
}
@media (prefers-color-scheme: dark) {
  :root:not([data-theme="light"]) {
    --paper: #0C1714; --surface: #13221E; --ink: #EAF1EC; --muted: #9DB0A7; --line: #24372F;
    --leaf: #43C08F; --leaf-soft: #173329; --marigold: #F5B53A; --marigold-ink: #F5B53A; --marigold-soft: #2E2410;
    --shadow: 0 1px 0 rgba(0,0,0,.3), 0 16px 40px -16px rgba(0,0,0,.6);
    color-scheme: dark;
  }
}
:root[data-theme="dark"] {
  --paper: #0C1714; --surface: #13221E; --ink: #EAF1EC; --muted: #9DB0A7; --line: #24372F;
  --leaf: #43C08F; --leaf-soft: #173329; --marigold: #F5B53A; --marigold-ink: #F5B53A; --marigold-soft: #2E2410;
  --shadow: 0 1px 0 rgba(0,0,0,.3), 0 16px 40px -16px rgba(0,0,0,.6);
  color-scheme: dark;
}

* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
  background: var(--paper); color: var(--ink);
  font-family: var(--f-body); font-size: 17px; line-height: 1.65;
  -webkit-font-smoothing: antialiased;
}
.wrap { max-width: 1160px; margin: 0 auto; padding-inline: 20px; }
h1, h2, h3 { font-family: var(--f-display); line-height: 1.12; margin: 0; text-wrap: balance; letter-spacing: -.01em; }
h2 { font-size: clamp(30px, 4.2vw, 46px); font-weight: 700; font-stretch: 92%; }
h3 { font-size: 21px; font-weight: 650; }
p { margin: 0; }
a { color: inherit; }
:focus-visible { outline: 3px solid var(--marigold); outline-offset: 3px; border-radius: 6px; }
.eyebrow { font-family: var(--f-mono); font-size: 12.5px; letter-spacing: .08em; text-transform: uppercase; color: var(--leaf); }
.lede { color: var(--muted); max-width: 60ch; font-size: 18px; }
section { padding-block: clamp(64px, 9vw, 112px); }
.sec-head { display: grid; gap: 14px; margin-bottom: 44px; max-width: 720px; }

/* concept strip */
.concept { background: var(--ink); color: var(--paper); font-size: 13.5px; text-align: center; padding: 7px 16px; }

/* header */
.top {
  position: sticky; top: env(safe-area-inset-top, 0px); z-index: 20;
  background: color-mix(in srgb, var(--paper) 86%, transparent);
  backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--line);
}
.top .wrap { display: flex; align-items: center; gap: 28px; height: 64px; }
.brand { font-family: var(--f-display); font-weight: 800; font-size: 22px; font-stretch: 110%; text-decoration: none; display: flex; align-items: center; gap: 9px; }
.brand i { width: 26px; height: 26px; border-radius: 8px; background: var(--leaf); display: grid; place-items: center; }
.brand i::after { content: ""; width: 10px; height: 10px; border: 2.5px solid var(--marigold); border-radius: 50%; }
.nav { display: flex; gap: 22px; font-size: 15.5px; color: var(--muted); margin-left: auto; }
.nav a { text-decoration: none; }
.nav a:hover { color: var(--ink); }

.btn {
  --bg: var(--ink); --fg: var(--paper);
  display: inline-flex; align-items: center; gap: 10px; text-decoration: none; cursor: pointer;
  background: var(--bg); color: var(--fg); border: 0; border-radius: 999px;
  padding: 13px 22px; font: 600 16px/1 var(--f-body);
  transition: transform .2s cubic-bezier(.2,.8,.2,1), box-shadow .2s;
}
.btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -10px color-mix(in srgb, var(--bg) 70%, transparent); }
.btn .arr { transition: transform .2s; }
.btn:hover .arr { transform: translateX(4px); }
.btn.gold { --bg: var(--marigold); --fg: #1B1300; }
.btn.ghost { --bg: transparent; --fg: var(--ink); box-shadow: inset 0 0 0 1.5px var(--line); }
.btn.ghost:hover { box-shadow: inset 0 0 0 1.5px var(--ink); }
.btn.sm { padding: 10px 16px; font-size: 14.5px; }

/* hero */
.hero { padding-block: clamp(48px, 7vw, 88px) clamp(56px, 8vw, 96px); }
.hero .wrap { display: grid; grid-template-columns: 1.05fr .95fr; gap: clamp(32px, 5vw, 64px); align-items: center; }
.hero h1 { font-size: clamp(40px, 6.4vw, 76px); font-weight: 750; font-stretch: 88%; }
.hero h1 em { font-style: normal; color: var(--leaf); font-stretch: 115%; display: inline-block; }
.hero-copy { display: grid; gap: 22px; min-width: 0; }
.hero-cta { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px; }
.proof { display: flex; flex-wrap: wrap; gap: 10px 26px; margin-top: 10px; font-size: 14.5px; color: var(--muted); }
.proof b { font-family: var(--f-display); font-size: 22px; color: var(--ink); font-weight: 700; display: block; line-height: 1.1; }

/* pipeline — the one orchestrated moment */
.pipe {
  background: var(--surface); border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow);
  padding: 22px; position: relative; min-width: 0;
}
.pipe-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding-bottom: 16px; border-bottom: 1px dashed var(--line); margin-bottom: 8px; }
.pipe-head .live { display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--muted); }
.pipe-head .live::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--leaf); box-shadow: 0 0 0 0 var(--leaf); animation: ping 2s infinite; }
@keyframes ping { 0% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--leaf) 50%, transparent); } 80%,100% { box-shadow: 0 0 0 10px transparent; } }
.oid { font-family: var(--f-mono); font-size: 13px; background: var(--marigold-soft); color: var(--marigold-ink); padding: 4px 9px; border-radius: 6px; }
.steps { list-style: none; margin: 0; padding: 0; position: relative; }
.steps::before { content: ""; position: absolute; left: 17px; top: 26px; bottom: 26px; width: 2px; background: var(--line); }
.steps .rail { position: absolute; left: 17px; top: 26px; width: 2px; height: 0; background: var(--leaf); transition: height .6s cubic-bezier(.4,0,.2,1); }
.step { display: grid; grid-template-columns: 36px 1fr auto; gap: 14px; align-items: center; padding: 11px 0; position: relative; }
.dot {
  width: 36px; height: 36px; border-radius: 50%; display: grid; place-items: center; z-index: 1;
  background: var(--surface); border: 2px solid var(--line); color: var(--muted);
  transition: background .35s, border-color .35s, color .35s, transform .35s;
}
.dot svg { width: 17px; height: 17px; }
.step .t { font-weight: 600; font-size: 15.5px; line-height: 1.3; }
.step .d { font-size: 13.5px; color: var(--muted); line-height: 1.4; overflow-wrap: anywhere; }
.step .tm { font-family: var(--f-mono); font-size: 12px; color: var(--muted); opacity: .35; transition: opacity .35s; }
.step.done .dot { background: var(--leaf); border-color: var(--leaf); color: var(--surface); }
.step.done .tm { opacity: 1; }
.step.now .dot { border-color: var(--marigold); color: var(--marigold-ink); transform: scale(1.12); box-shadow: 0 0 0 6px var(--marigold-soft); }
.step.now .tm { opacity: 1; color: var(--marigold-ink); }
.pipe-foot { margin-top: 10px; padding-top: 14px; border-top: 1px dashed var(--line); display: flex; justify-content: space-between; gap: 12px; font-size: 14px; color: var(--muted); flex-wrap: wrap; }
.pipe-foot strong { color: var(--ink); font-family: var(--f-display); font-size: 17px; }
.cash { color: var(--leaf); font-weight: 600; }

/* courier marquee */
.couriers { border-block: 1px solid var(--line); background: var(--surface); overflow: hidden; padding-block: 18px; }
.couriers .wrap { display: flex; align-items: center; gap: 28px; }
.couriers .lbl { font-size: 14px; color: var(--muted); white-space: nowrap; }
.track { display: flex; gap: 48px; width: max-content; animation: slide 26s linear infinite; }
.track span { font-family: var(--f-display); font-weight: 700; font-stretch: 112%; font-size: 22px; color: var(--muted); white-space: nowrap; }
.mask { overflow: hidden; flex: 1; min-width: 0; -webkit-mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); }
@keyframes slide { to { transform: translateX(-50%); } }

/* features */
.feat { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; }
.card {
  background: var(--surface); border: 1px solid var(--line); border-radius: var(--r);
  padding: 24px; display: grid; gap: 10px; align-content: start; min-width: 0;
  transition: transform .3s cubic-bezier(.2,.8,.2,1), border-color .3s;
}
.card:hover { transform: translateY(-4px); border-color: color-mix(in srgb, var(--leaf) 45%, var(--line)); }
.card p { color: var(--muted); font-size: 15.5px; }
.card.w2 { grid-column: span 2; } .card.w3 { grid-column: span 3; } .card.w4 { grid-column: span 4; }
.ic { width: 42px; height: 42px; border-radius: 11px; background: var(--leaf-soft); color: var(--leaf); display: grid; place-items: center; }
.ic svg { width: 22px; height: 22px; }
.card:hover .ic svg { animation: nudge .5s cubic-bezier(.3,1.6,.5,1); }
@keyframes nudge { 40% { transform: rotate(-10deg) scale(1.12); } }
.chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
.chip { font-size: 13px; padding: 3px 10px; border-radius: 999px; border: 1px solid var(--line); color: var(--muted); }
.chip.on { background: var(--marigold-soft); border-color: transparent; color: var(--marigold-ink); font-weight: 600; }

/* fraud meter inside card */
.meter { display: grid; gap: 6px; margin-top: 6px; }
.meter .row { display: flex; justify-content: space-between; font-size: 13.5px; color: var(--muted); }
.meter .bar { height: 8px; border-radius: 99px; background: var(--line); overflow: hidden; }
.meter .bar i { display: block; height: 100%; width: 92%; background: var(--leaf); border-radius: 99px; transform-origin: left; }
.in .meter .bar i { animation: grow 1.2s cubic-bezier(.2,.8,.2,1) both; }
@keyframes grow { from { transform: scaleX(.08); } }

/* sms bubble */
.sms { background: var(--paper); border: 1px solid var(--line); border-radius: 14px 14px 14px 4px; padding: 10px 13px; font-size: 14px; color: var(--ink); margin-top: 4px; max-width: 34ch; }
.sms small { display: block; color: var(--muted); font-family: var(--f-mono); font-size: 11.5px; margin-top: 4px; }

/* dashboard */
.dash-sec { background: var(--ink); color: var(--paper); }
.dash-sec .eyebrow { color: var(--marigold); }
.dash-sec .lede { color: color-mix(in srgb, var(--paper) 70%, var(--ink)); }
.dash {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;
  background: var(--surface); color: var(--ink); border-radius: 20px; padding: 18px; box-shadow: 0 30px 60px -30px rgba(0,0,0,.5);
}
:root[data-theme="dark"] .dash { border: 1px solid var(--line); }
.kpi { border: 1px solid var(--line); border-radius: 12px; padding: 16px; display: grid; gap: 2px; min-width: 0; }
.kpi span { font-size: 13.5px; color: var(--muted); }
.kpi b { font-family: var(--f-display); font-size: clamp(24px, 2.6vw, 32px); font-weight: 700; font-variant-numeric: tabular-nums; }
.kpi em { font-style: normal; font-size: 13px; color: var(--leaf); font-weight: 600; }
.chart { grid-column: span 3; border: 1px solid var(--line); border-radius: 12px; padding: 16px; min-width: 0; }
.chart-h { display: flex; justify-content: space-between; gap: 10px; font-size: 14px; color: var(--muted); margin-bottom: 8px; flex-wrap: wrap; }
.chart svg { width: 100%; height: auto; display: block; overflow: visible; }
.chart .gl { stroke: var(--line); stroke-width: 1; }
.chart .ax { fill: var(--muted); font: 11px var(--f-body); }
.chart .b { fill: var(--leaf); transform-box: fill-box; transform-origin: bottom; }
.chart .b.hi { fill: var(--marigold); }
.in .chart .b { animation: rise .9s cubic-bezier(.2,.8,.2,1) both; animation-delay: calc(var(--i) * 70ms); }
@keyframes rise { from { transform: scaleY(.05); } }
.orders { border: 1px solid var(--line); border-radius: 12px; padding: 14px; display: grid; gap: 10px; align-content: start; min-width: 0; }
.orders h4 { margin: 0; font-size: 14px; color: var(--muted); font-weight: 500; }
.o { display: grid; grid-template-columns: 1fr auto; gap: 2px 8px; font-size: 13.5px; padding-bottom: 10px; border-bottom: 1px dashed var(--line); }
.o:last-child { border-bottom: 0; padding-bottom: 0; }
.o .id { font-family: var(--f-mono); font-size: 12px; color: var(--muted); }
.st { font-size: 12px; padding: 1px 8px; border-radius: 99px; align-self: center; grid-row: span 2; font-weight: 600; }
.st.ok { background: var(--leaf-soft); color: var(--leaf); }
.st.go { background: var(--marigold-soft); color: var(--marigold-ink); }

/* setup steps */
.how { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; counter-reset: s; }
.how li { list-style: none; border-top: 2px solid var(--ink); padding-top: 18px; display: grid; gap: 8px; min-width: 0; }
.how li::before { counter-increment: s; content: "ধাপ " counter(s, bengali); font-family: var(--f-mono); font-size: 12.5px; color: var(--leaf); letter-spacing: .06em; }
.how p { color: var(--muted); font-size: 15.5px; }
.how .when { font-family: var(--f-mono); font-size: 12.5px; color: var(--marigold-ink); }
.how-list { margin: 0; padding: 0; }

/* pricing */
.price { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; align-items: stretch; }
.plan { background: var(--surface); border: 1px solid var(--line); border-radius: 18px; padding: 28px; display: grid; gap: 16px; align-content: start; min-width: 0; }
.plan.best { border: 2px solid var(--leaf); position: relative; }
.plan.best::before { content: "সবচেয়ে বেশি বিক্রি"; position: absolute; top: -13px; left: 24px; background: var(--leaf); color: var(--surface); font-size: 13px; font-weight: 600; padding: 3px 12px; border-radius: 99px; }
.plan .amt { font-family: var(--f-display); font-size: 44px; font-weight: 750; line-height: 1; font-variant-numeric: tabular-nums; }
.plan .amt small { font-size: 16px; color: var(--muted); font-weight: 500; margin-left: 4px; }
.plan .amt del { font-size: 17px; color: var(--muted); font-weight: 500; margin-left: 8px; }
.plan ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 9px; font-size: 15.5px; }
.plan li { display: grid; grid-template-columns: 20px 1fr; gap: 8px; }
.plan li::before { content: ""; width: 16px; height: 16px; margin-top: 5px; border-radius: 50%; background: var(--leaf-soft); box-shadow: inset 0 0 0 4px var(--leaf-soft), inset 0 0 0 9px var(--leaf); }
.plan .btn { justify-content: center; margin-top: auto; }
.plan p { color: var(--muted); font-size: 15px; }
.note { margin-top: 18px; font-size: 14px; color: var(--muted); }

/* faq */
.faq { display: grid; gap: 10px; max-width: 820px; }
details { background: var(--surface); border: 1px solid var(--line); border-radius: 12px; padding: 4px 20px; }
summary { cursor: pointer; list-style: none; padding: 14px 0; font-weight: 600; display: flex; justify-content: space-between; gap: 16px; }
summary::-webkit-details-marker { display: none; }
summary::after { content: "+"; font-family: var(--f-mono); color: var(--leaf); transition: transform .25s; }
details[open] summary::after { transform: rotate(45deg); }
details p { color: var(--muted); padding-bottom: 16px; font-size: 15.5px; }

/* final cta */
.final { text-align: left; }
.final .box { background: var(--leaf); color: #F3FBF7; border-radius: 26px; padding: clamp(32px, 6vw, 64px); display: grid; grid-template-columns: 1.4fr 1fr; gap: 32px; align-items: center; position: relative; overflow: hidden; }
.final h2 { color: #fff; }
.final p { color: #CFEADD; margin-top: 12px; max-width: 48ch; }
.final .acts { display: grid; gap: 12px; justify-items: start; }
.final .btn.ghost { --fg: #fff; box-shadow: inset 0 0 0 1.5px rgba(255,255,255,.4); }
.final .phone { font-family: var(--f-mono); font-size: 15px; user-select: all; }
.ring { position: absolute; right: -80px; top: -80px; width: 280px; height: 280px; border-radius: 50%; border: 40px solid rgba(232,155,12,.25); pointer-events: none; }

footer { padding-block: 36px 96px; color: var(--muted); font-size: 14px; }
footer .wrap { display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; border-top: 1px solid var(--line); padding-top: 24px; }

/* mobile buy bar */
.buybar { display: none; }

/* scroll reveal: content is always visible; only position eases in */
.rv { transition: transform .8s cubic-bezier(.2,.8,.2,1); }
.rv.pre { transform: translateY(26px); }

@media (max-width: 960px) {
  .hero .wrap { grid-template-columns: 1fr; }
  .feat { grid-template-columns: repeat(2, 1fr); }
  .card.w2, .card.w3, .card.w4 { grid-column: span 1; }
  .card.wide-m { grid-column: span 2; }
  .dash { grid-template-columns: repeat(2, 1fr); }
  .chart { grid-column: span 2; }
  .orders { grid-column: span 2; }
  .how { grid-template-columns: repeat(2, 1fr); row-gap: 32px; }
  .price { grid-template-columns: 1fr; max-width: 520px; }
  .final .box { grid-template-columns: 1fr; }
  .nav { display: none; }
  .top .btn { margin-left: auto; }
}
@media (max-width: 560px) {
  body { font-size: 16px; }
  .feat { grid-template-columns: 1fr; }
  .card.wide-m { grid-column: span 1; }
  .how { grid-template-columns: 1fr; }
  .couriers .lbl { display: none; }
  .step { grid-template-columns: 36px 1fr; }
  .step .tm { display: none; }
  .buybar {
    display: flex; position: fixed; left: 0; right: 0; bottom: 0; z-index: 30; gap: 10px; align-items: center; justify-content: space-between;
    padding: 10px 16px calc(10px + env(safe-area-inset-bottom, 0px));
    background: var(--surface); border-top: 1px solid var(--line); box-shadow: 0 -8px 24px -12px rgba(0,0,0,.25);
  }
  .buybar b { font-family: var(--f-display); font-size: 20px; }
  .buybar span { font-size: 12.5px; color: var(--muted); display: block; line-height: 1.2; }
}
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation: none !important; transition: none !important; }
  .rv.pre { transform: none; }
}

/* ---- real screenshots ---- */
.browser { background: var(--surface); border: 1px solid var(--line); border-radius: 14px; overflow: hidden; box-shadow: var(--shadow); min-width: 0; }
.browser .bar { height: 34px; display: flex; align-items: center; gap: 6px; padding-inline: 12px; border-bottom: 1px solid var(--line); background: color-mix(in srgb, var(--surface) 70%, var(--paper)); }
.browser .bar i { width: 9px; height: 9px; border-radius: 50%; background: var(--line); flex: none; }
.browser .bar span { margin-left: 10px; font-family: var(--f-mono); font-size: 11.5px; color: var(--muted); background: var(--paper); border-radius: 6px; padding: 2px 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0; }
.browser img { display: block; width: 100%; height: auto; }
.phone { background: #17120F; border-radius: 30px; padding: 7px; box-shadow: 0 30px 60px -24px rgba(23,18,15,.55), 0 0 0 1px rgba(255,255,255,.06) inset; }
.phone img { display: block; width: 100%; height: auto; border-radius: 24px; }

.stage { position: relative; padding-bottom: 36px; padding-right: 8%; min-width: 0; }
.stage .phone { position: absolute; right: 0; bottom: 0; width: 27%; animation: bob 6s ease-in-out infinite; }
.stage .browser { animation: settle 1s cubic-bezier(.2,.8,.2,1) both; }
.stage .tag { position: absolute; left: -14px; bottom: 64px; background: var(--surface); border: 1px solid var(--line); border-radius: 12px; padding: 10px 14px; box-shadow: var(--shadow); font-size: 13.5px; display: grid; gap: 2px; animation: bob 7s ease-in-out -2s infinite; }
.stage .tag b { font-family: var(--f-display); font-size: 18px; }
.stage .tag em { font-style: normal; color: var(--leaf); font-weight: 600; font-size: 12.5px; }
@keyframes bob { 50% { transform: translateY(-10px); } }
@keyframes settle { from { transform: translateY(18px) rotate(-.6deg); } }

/* tour */
.tour-sec { background: var(--ink); color: var(--paper); }
.tour-sec .eyebrow { color: var(--marigold); }
.tour-sec h2 { color: var(--paper); }
.tour-sec .lede { color: color-mix(in srgb, var(--paper) 70%, var(--ink)); }
.tour { display: grid; grid-template-columns: 300px minmax(0, 1fr); gap: 28px; align-items: start; }
.tabs { display: grid; gap: 4px; }
.tab {
  all: unset; box-sizing: border-box; cursor: pointer; position: relative; overflow: hidden;
  display: grid; gap: 4px; padding: 12px 14px; border-radius: 12px;
  color: color-mix(in srgb, var(--paper) 62%, var(--ink));
  transition: background .25s, color .25s;
}
.tab:hover { color: var(--paper); }
.tab:focus-visible { outline: 3px solid var(--marigold); outline-offset: 2px; }
.tab .tt { font-family: var(--f-display); font-weight: 650; font-size: 17px; line-height: 1.25; }
.tab .td { display: none; font-size: 14.5px; line-height: 1.55; color: color-mix(in srgb, var(--paper) 78%, var(--ink)); }
.tab[aria-selected="true"] { background: color-mix(in srgb, var(--paper) 9%, var(--ink)); color: var(--paper); }
.tab[aria-selected="true"] .td { display: block; }
.tab .prog { position: absolute; left: 14px; right: 14px; bottom: 6px; height: 2px; background: color-mix(in srgb, var(--paper) 12%, transparent); border-radius: 2px; display: none; }
.tab .prog i { display: block; height: 100%; width: 100%; background: var(--marigold); transform-origin: left; transform: scaleX(0); }
.tab[aria-selected="true"] .prog { display: block; }
.tour.playing .tab[aria-selected="true"] .prog i { animation: fill var(--dur, 6s) linear forwards; }
@keyframes fill { to { transform: scaleX(1); } }
.screen { position: relative; }
.screen .browser { border-color: color-mix(in srgb, var(--paper) 14%, var(--ink)); box-shadow: 0 40px 80px -40px rgba(0,0,0,.7); }
.slides { position: relative; aspect-ratio: 1500 / 1065; background: #F7F4F1; }
.slides img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: top left; opacity: 0; transition: opacity .55s ease, transform 6s ease-out; transform: scale(1.015); }
.slides img.on { opacity: 1; transform: scale(1); }
.cap { display: none; margin-top: 14px; font-size: 15px; color: color-mix(in srgb, var(--paper) 78%, var(--ink)); }
.ctrl { display: flex; justify-content: space-between; align-items: center; margin-top: 14px; gap: 12px; font-size: 13.5px; color: color-mix(in srgb, var(--paper) 60%, var(--ink)); }
.ctrl button { all: unset; cursor: pointer; font: 600 13.5px var(--f-body); color: var(--paper); padding: 6px 12px; border-radius: 99px; box-shadow: inset 0 0 0 1.5px color-mix(in srgb, var(--paper) 25%, transparent); }
.ctrl button:focus-visible { outline: 3px solid var(--marigold); outline-offset: 2px; }

/* storefront */
.shop-grid { display: grid; grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr); gap: 36px; align-items: center; }
.phones { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; align-items: start; }
.phones .phone:nth-child(2) { margin-top: 48px; }
.in .phones .phone { animation: rise2 .9s cubic-bezier(.2,.8,.2,1) both; }
.in .phones .phone:nth-child(2) { animation-delay: .12s; }
@keyframes rise2 { from { transform: translateY(30px); } }
.ticks { list-style: none; padding: 0; margin: 22px 0 0; display: grid; gap: 10px; }
.ticks li { display: grid; grid-template-columns: 22px 1fr; gap: 10px; color: var(--muted); font-size: 15.5px; }
.ticks li::before { content: ""; width: 18px; height: 18px; margin-top: 4px; border-radius: 50%; background: var(--leaf-soft); box-shadow: inset 0 0 0 5px var(--leaf-soft), inset 0 0 0 9px var(--leaf); }
.ticks b { color: var(--ink); font-weight: 600; }
.wide-shot { margin-top: 36px; }

/* order flow */
.flow { display: grid; grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr); gap: 40px; align-items: center; }

@media (max-width: 960px) {
  .tour { grid-template-columns: 1fr; }
  .tabs { display: flex; overflow-x: auto; gap: 6px; padding-bottom: 6px; scrollbar-width: none; }
  .tabs::-webkit-scrollbar { display: none; }
  .tab { flex: none; padding: 9px 14px; border-radius: 99px; box-shadow: inset 0 0 0 1.5px color-mix(in srgb, var(--paper) 16%, transparent); }
  .tab .tt { font-size: 15px; white-space: nowrap; }
  .tab .td, .tab[aria-selected="true"] .td, .tab .prog { display: none !important; }
  .cap { display: block; }
  .shop-grid, .flow { grid-template-columns: 1fr; }
  .stage .tag { left: 8px; }
}
@media (max-width: 560px) {
  .stage { padding-right: 14%; }
  .stage .phone { width: 34%; border-radius: 20px; padding: 4px; }
  .stage .phone img { border-radius: 16px; }
  .stage .tag { display: none; }
  .phones { gap: 12px; }
}

/* feature demo clips */
.demo-h { font-size: 15px; font-family: var(--f-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--muted); font-weight: 500; margin: 8px 0 16px; }
.demo-grid { display: grid; gap: 22px; margin-bottom: 44px; }
.demo-m { grid-template-columns: repeat(3, minmax(0, 1fr)); max-width: 900px; }
@media (max-width: 560px) { .demo-m { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; } }
.demo-d { grid-template-columns: repeat(auto-fill, minmax(min(100%, 420px), 1fr)); }
.fc { margin: 0; display: grid; gap: 12px; align-content: start; min-width: 0; }
.fc video { display: block; width: 100%; height: auto; background: var(--paper); }
.fc .phone video { border-radius: 22px; aspect-ratio: 390 / 760; object-fit: cover; }
.fc .browser video { aspect-ratio: 1280 / 760; object-fit: cover; }
.fc figcaption { display: grid; gap: 3px; }
.fc figcaption b { font-family: var(--f-display); font-size: 17px; font-weight: 650; }
.fc figcaption span { color: var(--muted); font-size: 14.5px; line-height: 1.5; }
.fc .phone { padding: 6px; border-radius: 28px; }

/* single-price offer + WhatsApp */
.price-one { grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); max-width: 980px; align-items: start; }
.price-one .plan.best::before { content: "সম্পূর্ণ সোর্স কোড"; }
.price-one .plan .btn { justify-content: center; }
.btn.wa { --bg: #1FAF55; --fg: #fff; }
.phone-line { font-size: 14px; color: var(--muted); }
.sel { user-select: all; font-family: var(--f-mono); color: var(--ink); }
.final .acts { gap: 10px; }
.wa-float { position: fixed; right: 18px; bottom: calc(22px + env(safe-area-inset-bottom, 0px)); z-index: 25; width: 56px; height: 56px; border-radius: 50%; background: #1FAF55; color: #fff; display: grid; place-items: center; box-shadow: 0 12px 26px -10px rgba(31,175,85,.8); transition: transform .2s; }
.wa-float:hover { transform: scale(1.06); }
.wa-float svg { width: 30px; height: 30px; }
@media (max-width: 960px) { .price-one { grid-template-columns: 1fr; } }
@media (max-width: 560px) { .buybar .btn.wa { padding-inline: 12px; } .wa-float { bottom: calc(84px + env(safe-area-inset-bottom, 0px)); } }
</style>

</head>
<body>
<div class="concept">সব স্ক্রিনশট আর ভিডিও সফটওয়্যারের আসল পেজ, ডেমো ডেটা দিয়ে · পণ্যের ছবির জায়গায় প্লেসহোল্ডার</div>

<header class="top">
  <div class="wrap">
    <a class="brand" href="#top"><i></i>Vantor</a>
    <nav class="nav" aria-label="মূল মেনু">
      <a href="#demos">ফিচার ডেমো</a>
      <a href="#admin">অ্যাডমিন প্যানেল</a>
      <a href="#store">স্টোরফ্রন্ট</a>
      <a href="#features">সব ফিচার</a>
      <a href="#setup">কীভাবে পাবেন</a>
      <a href="#price">দাম</a>
      <a href="#faq">প্রশ্নোত্তর</a>
      <a href="{{ route('frontend.themes') }}">অন্যান্য প্রোডাক্ট</a>
      <a href="{{ route('login') }}">লগইন</a>
    </nav>
    <a class="btn sm" href="https://vantor.juktitech.com/" target="_blank" rel="noopener">লাইভ ডেমো <span class="arr">→</span></a>
  </div>
</header>

<main id="top">
  <section class="hero">
    <div class="wrap">
      <div class="hero-copy">
        <span class="eyebrow">Laravel ই-কমার্স ওয়েবসাইট · রেডি টু লঞ্চ</span>
        <h1>অর্ডার আসবে।<br>বাকি কাজ <em>সাইট নিজেই</em> করবে।</h1>
        <p class="lede">Vantor eCommerce Pro বাংলাদেশের অনলাইন শপের জন্য বানানো রেডি ওয়েবসাইট। কুরিয়ার বুকিং, ফেক অর্ডার ধরা, POS আর লাভের হিসাব একটাই অ্যাডমিন প্যানেলে। একটি চালু দোকানে প্রতিদিন ব্যবহার হচ্ছে।</p>
        <div class="hero-cta">
          <a class="btn gold" href="#price">এখনই কিনুন {{ $priceBn }} <span class="arr">→</span></a>
          <a class="btn ghost" href="https://vantor.juktitech.com/" target="_blank" rel="noopener">ডেমো শপ ঘুরে দেখুন</a>
        </div>
        <div class="proof">
          <div><b>২৮টি</b>অ্যাডমিন মডিউল</div>
          <div><b>৩টি</b>কুরিয়ার API</div>
          <div><b>POS</b>দোকানের কাউন্টারেও</div>
          <div><b>আজীবন</b>লাইসেন্স</div>
        </div>
      </div>
      <div class="stage">
        <div class="browser"><div class="bar"><i></i><i></i><i></i><span>yourshop.com</span></div><img src="{{ $assetBase }}/shots/store-home.webp" alt="ডেস্কটপে দোকানের হোমপেজ" fetchpriority="high"></div>
        <div class="phone"><img src="{{ $assetBase }}/shots/m-home.webp" alt="মোবাইলে দোকানের হোমপেজ"></div>
        <div class="tag"><span>আজকের বিক্রি</span><b>৳১৯,৪১০</b><em>৩টি অর্ডার · ডেমো</em></div>
      </div>
    </div>
  </section>

<div class="couriers" aria-label="যুক্ত কুরিয়ার, পেমেন্ট ও ট্র্যাকিং">
    <div class="wrap">
      <span class="lbl">আগে থেকে যুক্ত</span>
      <div class="mask">
        <div class="track"><span>Steadfast</span><span>Pathao</span><span>RedX</span><span>bKash</span><span>Nagad</span><span>Rocket</span><span>Meta Pixel + CAPI</span><span>GA4</span><span>TikTok Pixel</span><span>Google Ads</span><span>Google Login</span><span>BD Courier</span><span aria-hidden="true">Steadfast</span><span aria-hidden="true">Pathao</span><span aria-hidden="true">RedX</span><span aria-hidden="true">bKash</span><span aria-hidden="true">Nagad</span><span aria-hidden="true">Rocket</span><span aria-hidden="true">Meta Pixel + CAPI</span><span aria-hidden="true">GA4</span><span aria-hidden="true">TikTok Pixel</span><span aria-hidden="true">Google Ads</span><span aria-hidden="true">Google Login</span><span aria-hidden="true">BD Courier</span></div>
      </div>
    </div>
  </div>

    <section id="admin" class="tour-sec">
    <div class="wrap">
      <div class="sec-head rv">
        <span class="eyebrow">অ্যাডমিন প্যানেল · আসল স্ক্রিন</span>
        <h2>প্রতিদিন যেখানে দোকান চালাবেন</h2>
        <p class="lede">নিচের প্রতিটি ছবি সফটওয়্যারের আসল পেজ, ডেমো ডেটা দিয়ে। একটা বেছে নিন, অথবা নিজে থেকে ঘুরতে দিন।</p>
      </div>
      <div class="tour playing" id="tour">
        <div class="tabs" role="tablist" aria-label="অ্যাডমিন প্যানেলের পেজ">
          <button class="tab" role="tab" aria-selected="true" data-i="0"><span class="tt">ড্যাশবোর্ড</span><span class="td">কোন কাজ বাকি সেটা আগে দেখায়: পেমেন্ট যাচাই, ঝুঁকির অর্ডার, কার্টে ফেলে যাওয়া কাস্টমার, কম স্টক। তারপর আজকের বিক্রি আর মাসের নিট লাভ।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="1"><span class="tt">অর্ডার পেজ</span><span class="td">পরের ধাপ নিজেই বলে দেয়, যেমন bKash TrxID মিলিয়ে Verify। কাস্টমারকে এক ক্লিকে কল বা WhatsApp, ডেলিভারি হিস্ট্রি, ইনভয়েস আর পার্সেল লেবেল।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="2"><span class="tt">সব অর্ডার</span><span class="td">Pending, Confirmed, Shipped, Returned আলাদা ট্যাবে। কোনটা প্রিন্ট বাকি, কোনটা রিভিউ লাগবে, একসাথে অনেক অর্ডারে একই কাজ।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="3"><span class="tt">কুরিয়ার স্ক্যান</span><span class="td">কুরিয়ারকে পার্সেল দেওয়ার সময় বারকোড স্ক্যান, ফেরত এলেও স্ক্যান। আজ কী গেল আর কী ফিরল, তার হিসাব আর ম্যানিফেস্ট।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="4"><span class="tt">POS রেজিস্টার</span><span class="td">দোকানের কাউন্টারে বারকোড স্ক্যান করে বিক্রি। Cash, bKash, Nagad বা কার্ড, ফেরত টাকার হিসাব আর রিসিট।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="5"><span class="tt">লাভ ও অ্যানালিটিক্স</span><span class="td">খরচ বাদ দিয়ে আসল লাভ, অনলাইন আর দোকানের বিক্রি আলাদা, কুরিয়ার রিটার্নে কত গেল।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="6"><span class="tt">ইনভেন্টরি</span><span class="td">প্রতিটি পণ্য আর ভ্যারিয়েন্টের স্টক, কেনা দাম, কম স্টক আর শেষ হয়ে যাওয়া পণ্য এক জায়গায়।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="7"><span class="tt">কাস্টমার</span><span class="td">কে বারবার কেনে, কে কত টাকার কিনেছে, শেষ অর্ডার কবে। CSV এক্সপোর্ট আর ব্ল্যাকলিস্ট।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="8"><span class="tt">ফ্ল্যাশ সেল</span><span class="td">কাউন্টডাউন সেট করুন, কোন পণ্য কত দামে আর কতটা বিক্রি হলো দেখুন।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="9"><span class="tt">ইন্টিগ্রেশন</span><span class="td">Steadfast, Pathao, RedX-এর কী বসান, Google লগইন, ট্র্যাকিং আর ইমেইল OTP। কোডে হাত দিতে হয় না।</span><span class="prog"><i></i></span></button>
          <button class="tab" role="tab" aria-selected="false" data-i="10"><span class="tt">স্টাফ ও রোল</span><span class="td">স্টোর ম্যানেজার, অর্ডার ম্যানেজার আর ইনভেন্টরি ম্যানেজার আলাদা অ্যাক্সেস পায়। দরকার হলে সাসপেন্ড।</span><span class="prog"><i></i></span></button>
        </div>
        <div class="screen">
          <div class="browser">
            <div class="bar"><i></i><i></i><i></i><span id="tourUrl">yourshop.com/admin</span></div>
            <div class="slides" id="slides">
              <img class="on" src="{{ $assetBase }}/shots/a-dashboard.webp" alt="ড্যাশবোর্ড: পেমেন্ট যাচাই, শিপ করার অর্ডার আর মাসের নিট লাভ" data-url="/admin">
              <img src="{{ $assetBase }}/shots/a-order-detail.webp" alt="অর্ডার পেজ: bKash পেমেন্ট যাচাই, কাস্টমার আর কুরিয়ার" data-url="/admin/orders/order" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-orders.webp" alt="সব অর্ডারের তালিকা স্ট্যাটাস ট্যাবসহ" data-url="/admin/orders" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-courier-scan.webp" alt="কুরিয়ার স্ক্যান: হ্যান্ডওভার আর রিটার্ন" data-url="/admin/courier-scan" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-pos.webp" alt="POS রেজিস্টার" data-url="/admin/pos" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-analytics.webp" alt="লাভ ও অ্যানালিটিক্স" data-url="/admin/analytics" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-inventory.webp" alt="ইনভেন্টরি" data-url="/admin/inventory" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-customers.webp" alt="কাস্টমার তালিকা" data-url="/admin/customers" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-flash.webp" alt="ফ্ল্যাশ সেল" data-url="/admin/flash-sale" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-integrations.webp" alt="ইন্টিগ্রেশন: কুরিয়ার API" data-url="/admin/integrations" loading="lazy">
              <img src="{{ $assetBase }}/shots/a-staff.webp" alt="স্টাফ ও রোল" data-url="/admin/staff" loading="lazy">
            </div>
          </div>
          <p class="cap" id="cap"></p>
          <div class="ctrl"><span id="count">১ / ১১</span><button type="button" id="play">থামান</button></div>
        </div>
      </div>
    </div>
  </section>

  <section id="demos">
    <div class="wrap">
      <div class="sec-head rv">
        <span class="eyebrow">ফিচার ডেমো · আসল সফটওয়্যারে রেকর্ড করা</span>
        <h2>প্রতিটি ফিচার কীভাবে কাজ করে, নিজের চোখে দেখুন</h2>
        <p class="lede">নিচের প্রতিটি ক্লিপ সফটওয়্যারের আসল পেজে সত্যিকারের ক্লিক, টাইপ আর ক্যামেরা স্ক্যান দিয়ে রেকর্ড করা। ভিডিও স্ক্রিনে এলে নিজে চলে।</p>
      </div>
      <h3 class="demo-h">মোবাইলে</h3>
      <div class="demo-grid demo-m"><figure class="fc fc-m rv"><div class="phone clip"><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/01-store-order-mobile.jpg" data-src="{{ $assetBase }}/clips/01-store-order-mobile.mp4" aria-label="কাস্টমার অর্ডার করছে"></video></div><figcaption><b>কাস্টমার অর্ডার করছে</b><span>হোমপেজ থেকে পণ্য, রং বাছাই, এক পেজে চেকআউট, bKash TrxID, অর্ডার কনফার্ম।</span></figcaption></figure><figure class="fc fc-m rv"><div class="phone clip"><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/03-courier-scan-dispatch.jpg" data-src="{{ $assetBase }}/clips/03-courier-scan-dispatch.mp4" aria-label="কুরিয়ার স্ক্যান: হ্যান্ডওভার"></video></div><figcaption><b>কুরিয়ার স্ক্যান: হ্যান্ডওভার</b><span>ফোনের ক্যামেরায় পার্সেলের লেবেল স্ক্যান, অর্ডার নিজে Shipped, পরপর অনেক পার্সেল।</span></figcaption></figure><figure class="fc fc-m rv"><div class="phone clip"><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/04-courier-scan-return.jpg" data-src="{{ $assetBase }}/clips/04-courier-scan-return.mp4" aria-label="কুরিয়ার স্ক্যান: রিটার্ন"></video></div><figcaption><b>কুরিয়ার স্ক্যান: রিটার্ন</b><span>ফেরত পার্সেল স্ক্যান, কারণ লিখুন, স্টক ফেরত আর রিটার্ন খরচ হিসাবে।</span></figcaption></figure><figure class="fc fc-m rv"><div class="phone clip"><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/05-fraud-blacklist.jpg" data-src="{{ $assetBase }}/clips/05-fraud-blacklist.mp4" aria-label="ফেক অর্ডার আটকানো"></video></div><figcaption><b>ফেক অর্ডার আটকানো</b><span>ঝুঁকির কাস্টমারের ডেলিভারি রেকর্ড দেখে ব্ল্যাকলিস্ট, তারপর ওই নম্বরে অর্ডার বন্ধ।</span></figcaption></figure><figure class="fc fc-m rv"><div class="phone clip"><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/10-abandoned-cart.jpg" data-src="{{ $assetBase }}/clips/10-abandoned-cart.mp4" aria-label="অসম্পূর্ণ অর্ডার ফেরত আনা"></video></div><figcaption><b>অসম্পূর্ণ অর্ডার ফেরত আনা</b><span>চেকআউট ছেড়ে যাওয়া কাস্টমার সাথে সাথে তালিকায়, WhatsApp-এ এক ক্লিকে রিমাইন্ডার।</span></figcaption></figure><figure class="fc fc-m rv"><div class="phone clip"><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/12-track-order.jpg" data-src="{{ $assetBase }}/clips/12-track-order.mp4" aria-label="কাস্টমারের অর্ডার ট্র্যাকিং"></video></div><figcaption><b>কাস্টমারের অর্ডার ট্র্যাকিং</b><span>অর্ডার নম্বর আর ফোন দিয়ে কাস্টমার নিজেই দেখে পার্সেল কোথায়।</span></figcaption></figure></div>
      <h3 class="demo-h">কম্পিউটারে</h3>
      <div class="demo-grid demo-d"><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/02-order-verify-courier.jpg" data-src="{{ $assetBase }}/clips/02-order-verify-courier.mp4" aria-label="পেমেন্ট যাচাই থেকে কুরিয়ার"></video></div><figcaption><b>পেমেন্ট যাচাই থেকে কুরিয়ার</b><span>bKash মিলিয়ে Verify, সব কুরিয়ারে কাস্টমারের রেকর্ড, এক ক্লিকে Steadfast বুকিং ও ট্র্যাকিং কোড।</span></figcaption></figure><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/06-pos-counter-sale.jpg" data-src="{{ $assetBase }}/clips/06-pos-counter-sale.mp4" aria-label="POS: কাউন্টারে বিক্রি"></video></div><figcaption><b>POS: কাউন্টারে বিক্রি</b><span>বারকোড স্ক্যান, পণ্য যোগ, কাস্টমার, ক্যাশ ও ফেরত টাকার হিসাব, রিসিট।</span></figcaption></figure><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/07-barcode-labels.jpg" data-src="{{ $assetBase }}/clips/07-barcode-labels.mp4" aria-label="বারকোড লেবেল প্রিন্ট"></video></div><figcaption><b>বারকোড লেবেল প্রিন্ট</b><span>পণ্য বেছে থার্মাল প্রিন্টারের মাপে লেবেল, POS-এ স্ক্যান করার জন্য।</span></figcaption></figure><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/08-dashboard.jpg" data-src="{{ $assetBase }}/clips/08-dashboard.mp4" aria-label="ড্যাশবোর্ড"></video></div><figcaption><b>ড্যাশবোর্ড</b><span>কোন কাজ বাকি, কোন অর্ডার শিপ বাকি, আজকের বিক্রি আর নিট লাভ।</span></figcaption></figure><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/09-expenses-profit.jpg" data-src="{{ $assetBase }}/clips/09-expenses-profit.mp4" aria-label="খরচ ও আসল লাভ"></video></div><figcaption><b>খরচ ও আসল লাভ</b><span>Facebook অ্যাডের খরচ ডলারে লিখুন, নিট লাভ নিজে বদলে যায়।</span></figcaption></figure><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/11-flash-sale.jpg" data-src="{{ $assetBase }}/clips/11-flash-sale.mp4" aria-label="ফ্ল্যাশ সেল"></video></div><figcaption><b>ফ্ল্যাশ সেল</b><span>টাইমার সেট করলেই হোমপেজে কাউন্টডাউন আর অফারের দাম।</span></figcaption></figure><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/13-theme-colors.jpg" data-src="{{ $assetBase }}/clips/13-theme-colors.mp4" aria-label="নিজের ব্র্যান্ডের রং"></video></div><figcaption><b>নিজের ব্র্যান্ডের রং</b><span>একটা প্রিসেট বা ৩টি রং বেছে সেভ, পুরো দোকান নতুন রঙে।</span></figcaption></figure><figure class="fc fc-d rv"><div class="browser clip"><div class="bar"><i></i><i></i><i></i></div><video muted loop playsinline preload="none" poster="{{ $assetBase }}/clips/14-staff-roles.jpg" data-src="{{ $assetBase }}/clips/14-staff-roles.mp4" aria-label="স্টাফ ও অ্যাক্সেস"></video></div><figcaption><b>স্টাফ ও অ্যাক্সেস</b><span>অর্ডার ম্যানেজার যোগ করুন, সে শুধু নিজের কাজের পেজ দেখে।</span></figcaption></figure></div>
    </div>
  </section>

  <section id="store">
    <div class="wrap">
      <div class="sec-head rv">
        <span class="eyebrow">কাস্টমার যা দেখে</span>
        <h2>মোবাইলে কেনা সহজ, তাই অর্ডার বেশি</h2>
        <p class="lede">বেশিরভাগ কাস্টমার আসে Facebook অ্যাড থেকে, ফোনে। তাই প্রতিটি পেজ আগে মোবাইলের জন্য বানানো।</p>
      </div>
      <div class="shop-grid">
        <div class="browser rv"><div class="bar"><i></i><i></i><i></i><span>yourshop.com/product</span></div><img src="{{ $assetBase }}/shots/store-product.webp" alt="ডেস্কটপে পণ্যের পেজ: দাম, কালার, অর্ডার বাটন" loading="lazy"></div>
        <div>
          <div class="phones">
            <div class="phone"><img src="{{ $assetBase }}/shots/m-home.webp" alt="মোবাইলে হোমপেজ" loading="lazy"></div>
            <div class="phone"><img src="{{ $assetBase }}/shots/m-product.webp" alt="মোবাইলে পণ্যের পেজ" loading="lazy"></div>
          </div>
        </div>
      </div>
      <ul class="ticks">
        <li><span><b>এক পেজে অর্ডার:</b> কার্ট, কুপন আর bKash/Nagad/Rocket/COD, লগইন ছাড়াই।</span></li>
        <li><span><b>অর্ডার ট্র্যাক:</b> কাস্টমার নিজেই অর্ডার নম্বর আর ফোন দিয়ে কুরিয়ার স্ট্যাটাস দেখে।</span></li>
        <li><span><b>অফার চোখে পড়ে:</b> উপরে চলমান অফার, ফ্ল্যাশ সেলের কাউন্টডাউন, ফ্রি ডেলিভারির শর্ত।</span></li>
      </ul>
      <div class="browser wide-shot rv"><div class="bar"><i></i><i></i><i></i><span>yourshop.com</span></div><img src="{{ $assetBase }}/shots/store-home-2.webp" alt="হোমপেজে টপ সেলিং পণ্যের সারি" loading="lazy"></div>
    </div>
  </section>

<section id="features">
    <div class="wrap">
      <div class="sec-head rv">
        <span class="eyebrow">সব ফিচার</span>
        <h2>অনলাইন দোকান চালাতে যা যা লাগে, সব প্রথম দিন থেকেই</h2>
        <p class="lede">আলাদা প্লাগইন বা সফটওয়্যার কিনতে হবে না। অনলাইন অর্ডার, দোকানের বিক্রি, কুরিয়ার আর স্টাফ, সব এক জায়গায়।</p>
      </div>
      <div class="feat">
        <article class="card w3 rv wide-m">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v9H3z"/><path d="M14 10h4l3 3v3h-7"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg></span>
          <h3>এক ক্লিকে কুরিয়ার, স্ক্যান করে হ্যান্ডওভার</h3>
          <p>অর্ডার থেকেই পার্সেল বুক আর লেবেল প্রিন্ট। কুরিয়ারকে দেওয়ার সময় স্ক্যান করুন, ফেরত এলেও স্ক্যান। ডেলিভারি স্ট্যাটাস কুরিয়ার থেকে নিজে আপডেট হয়।</p>
          <div class="chips"><span class="chip on">Steadfast</span><span class="chip">Pathao</span><span class="chip">RedX</span><span class="chip">কুরিয়ার স্ক্যান</span><span class="chip">পার্সেল লেবেল</span></div>
        </article>
        <article class="card w3 rv wide-m">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg></span>
          <h3>ফেক অর্ডার ধরার ফ্রড চেক</h3>
          <p>প্রতিটি অর্ডারের রিস্ক স্কোর, bKash TrxID যাচাই আর ব্ল্যাকলিস্ট। BD Courier দিয়ে কাস্টমারের সব কুরিয়ারের ডেলিভারি রেকর্ড এক নজরে।</p>
          <div class="meter">
            <div class="row"><span>০১৭•••••২৩৪ · সফল ডেলিভারি</span><span>৯২%</span></div>
            <div class="bar"><i></i></div>
          </div>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg></span>
          <h3>bKash, Nagad, Rocket ও COD</h3>
          <p>নিরাপদ কাস্টমারের COD অর্ডার নিজে কনফার্ম হয়। স্টাফ শুধু ঝুঁকির অর্ডারে ফোন করে।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/></svg></span>
          <h3>অর্ডার ট্র্যাকিং পেজ</h3>
          <p>কাস্টমার অর্ডার নম্বর আর ফোন দিয়ে নিজেই দেখে পার্সেল কোথায়। আপনার ফোন কম বাজে।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 5-6"/></svg></span>
          <h3>অ্যাডের সঠিক হিসাব</h3>
          <p>Meta Pixel ও Conversions API, GA4 সার্ভার-সাইড, TikTok আর Google Ads। অ্যাড-ব্লকার থাকলেও অর্ডার গোনা হয়।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6v12M8 6v12M11 6v12M15 6v12M18 6v12M21 6v12"/></svg></span>
          <h3>POS ও বারকোড</h3>
          <p>দোকানের কাউন্টারে বিক্রি, রিসিট আর বারকোড প্রিন্ট। অনলাইন আর অফলাইনের স্টক একসাথে।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
          <h3>লাভ ও খরচ</h3>
          <p>খরচ লিখে রাখুন, প্রতিদিনের আসল লাভ দেখুন। ভিজিটর অ্যানালিটিক্স আর রিপোর্ট এক্সপোর্ট।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><path d="M12 9v3"/></svg></span>
          <h3>অসম্পূর্ণ অর্ডার ফেরত আনা</h3>
          <p>চেকআউটে এসে যারা চলে গেছে, তাদের WhatsApp বা SMS-এ এক ক্লিকে কার্টের লিংক পাঠান।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12 12 20l-8-8V4h8z"/><circle cx="8.5" cy="8.5" r="1.5"/></svg></span>
          <h3>অফার ও প্রচার</h3>
          <p>ফ্ল্যাশ সেল, কুপন, ফ্রি ডেলিভারির শর্ত আর হোমপেজে চলমান নোটিশ।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7 12 3 4 7v10l8 4 8-4z"/><path d="m4 7 8 4 8-4M12 11v10"/></svg></span>
          <h3>পণ্য ও স্টক</h3>
          <p>কালার-সাইজ ভ্যারিয়েন্ট, সাইজ গাইড, রিভিউ আর ছবি নিজে থেকে ছোট করার মিডিয়া লাইব্রেরি।</p>
        </article>
        <article class="card w2 rv">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M17 11l2 2 3-3"/></svg></span>
          <h3>স্টাফ ও অডিট লগ</h3>
          <p>ম্যানেজার, অর্ডার আর ইনভেন্টরি স্টাফের আলাদা অ্যাক্সেস। কে কখন কী বদলেছে, সব লেখা থাকে।</p>
        </article>
        <article class="card w3 rv wide-m">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 0 0 18"/><path d="M12 3c2.5 2.5 2.5 15.5 0 18"/></svg></span>
          <h3>নিজের ব্র্যান্ডের রঙে</h3>
          <p>অ্যাডমিন থেকে ৩টি রং বেছে নিন, পুরো সাইট আর চ্যাট উইজেট সেই রঙে বদলে যায়। লোগো, ব্যানার আর ট্রাস্ট ব্যাজ নিজেই বদলান।</p>
        </article>
        <article class="card w3 rv wide-m">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></span>
          <h3>Google লগইন ও OTP</h3>
          <p>কাস্টমার এক ক্লিকে Google দিয়ে লগইন করে। চাইলে ইমেইল OTP দিয়ে অ্যাকাউন্ট যাচাই।</p>
        </article>
      </div>

    </div>
  </section>

    <section id="flow" style="padding-top:0">
    <div class="wrap flow">
      <div class="sec-head rv" style="margin-bottom:0">
        <span class="eyebrow">অর্ডার আসার পর</span>
        <h2>বেশিরভাগ কাজ সাইট নিজেই করে</h2>
        <p class="lede">নিরাপদ কাস্টমারের COD অর্ডার নিজে কনফার্ম হয়। আপনি শুধু ঝুঁকির অর্ডারে ফোন করেন আর এক ক্লিকে কুরিয়ারে পাঠান। ডেলিভারির খবর কুরিয়ার থেকে নিজে আসে, লাভের হিসাবও।</p>
      </div>
      <div class="pipe rv" aria-label="একটি অর্ডার কীভাবে অটোমেটিক প্রসেস হয়">
        <div class="pipe-head">
          <span class="live">একটি অর্ডারের যাত্রা, লাইভ</span>
          <span class="oid">#VT-20418</span>
        </div>
        <ol class="steps" id="steps">
          <li class="rail" id="rail" aria-hidden="true"></li>
          <li class="step done">
            <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg></span>
            <div><div class="t">নতুন অর্ডার এসেছে</div><div class="d">রাকিব, মিরপুর ১০ · Facebook অ্যাড থেকে · ক্যাশ অন ডেলিভারি</div></div>
            <span class="tm">১০:৪২</span>
          </li>
          <li class="step done">
            <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg></span>
            <div><div class="t">ফ্রড চেক: ঝুঁকি কম</div><div class="d">সব কুরিয়ার মিলিয়ে আগের ১৩টির মধ্যে ১২টি ডেলিভারড</div></div>
            <span class="tm">১০:৪২</span>
          </li>
          <li class="step done">
            <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
            <div><div class="t">অটো-কনফার্ম হয়েছে</div><div class="d">নিরাপদ কাস্টমার, তাই ফোন করে কনফার্ম করতে হয়নি</div></div>
            <span class="tm">১০:৪২</span>
          </li>
          <li class="step done">
            <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v9H3z"/><path d="M14 10h4l3 3v3h-7"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg></span>
            <div><div class="t">Steadfast-এ বুক হয়েছে</div><div class="d">এক ক্লিকে বুক, পার্সেল লেবেল প্রিন্ট · SF-88421907</div></div>
            <span class="tm">১০:৪৩</span>
          </li>
          <li class="step done">
            <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/></svg></span>
            <div><div class="t">ডেলিভারড, লাভ হিসাব হয়েছে</div><div class="d">কুরিয়ার থেকে স্ট্যাটাস নিজে আপডেট · ৳১,৮৫০ কালেক্টেড</div></div>
            <span class="tm">পরদিন</span>
          </li>
        </ol>
        <div class="pipe-foot">
          <span>আপনার কাজ: <strong>০ ক্লিক</strong> থেকে <strong>১ ক্লিক</strong></span>
          <span class="cash">Meta ও GA4-এ পারচেজ গেছে</span>
        </div>
      </div>
    </div>
  </section>

<section id="setup">
    <div class="wrap">
      <div class="sec-head rv">
        <span class="eyebrow">কীভাবে পাবেন</span>
        <h2>পেমেন্ট করুন, ডাউনলোড করুন, দোকান চালু করুন</h2>
        <p class="lede">Jukti Tech-এ অ্যাকাউন্ট খুলে কিনুন। পেমেন্ট যাচাই হলেই আপনার অ্যাকাউন্টে ডাউনলোড লিংক আর ডোমেইনের লাইসেন্স।</p>
      </div>
      <ol class="how how-list">
        <li class="rv"><h3>এখনই কিনুন চাপুন</h3><p>যে ডোমেইনে চালাবেন সেটা লিখুন। নতুন হলে এক মিনিটে অ্যাকাউন্ট খুলুন।</p><span class="when">১ মিনিট</span></li>
        <li class="rv"><h3>bKash বা Nagad-এ পেমেন্ট</h3><p>{{ $priceBn }} পাঠিয়ে TrxID দিন। যাচাই হলেই অর্ডার কনফার্ম।</p><span class="when">৫ মিনিট</span></li>
        <li class="rv"><h3>সোর্স কোড ডাউনলোড</h3><p>“আমার ডাউনলোড” থেকে zip নামান। ভেতরে সব প্যাকেজ আছে, cPanel-এ Composer লাগে না।</p><span class="when">সাথে সাথে</span></li>
        <li class="rv"><h3>আপলোড করে চালু</h3><p>README দেখে আপলোড আর ডাটাবেস সেটআপ। নিজে না পারলে WhatsApp করুন, আমরা করে দিই।</p><span class="when">লাইভ</span></li>
      </ol>
    </div>
  </section>

  <section id="price" style="padding-top:0">
    <div class="wrap">
      <div class="sec-head rv">
        <span class="eyebrow">দাম</span>
        <h2>সম্পূর্ণ সোর্স কোড, এককালীন {{ $priceBn }}</h2>
        <p class="lede">মাসিক কোনো চার্জ নেই। উপরে যত ফিচার দেখলেন, সব এই দামে।</p>
      </div>
      <div class="price price-one">
        <div class="plan best rv">
          <h3>Vantor eCommerce Pro · সোর্স কোড</h3>
          <p>নিজের ডোমেইনে নিজের অনলাইন শপ</p>
          <div class="amt">{{ $priceBn }}<small>এককালীন</small></div>
          <ul>
            <li>সম্পূর্ণ Laravel সোর্স কোড, নিজের মতো বদলাতে পারবেন</li>
            <li>স্টোরফ্রন্ট, অ্যাডমিন প্যানেল আর POS</li>
            <li>Steadfast, Pathao, RedX আর BD Courier ফ্রড চেক</li>
            <li>bKash, Nagad, Rocket ও COD চেকআউট</li>
            <li>Meta Pixel + CAPI, GA4, TikTok, Google Ads ট্র্যাকিং</li>
            <li>আপনার ডোমেইনের লাইসেন্স আর ইনস্টল গাইড</li>
          </ul>
          <a class="btn gold" href="{{ $buyUrl }}">এখনই কিনুন {{ $priceBn }} <span class="arr">→</span></a>
          <a class="btn wa" href="{{ $waBase }}?text=%E0%A6%86%E0%A6%B8%E0%A6%B8%E0%A6%BE%E0%A6%B2%E0%A6%BE%E0%A6%AE%E0%A7%81%20%E0%A6%86%E0%A6%B2%E0%A6%BE%E0%A6%87%E0%A6%95%E0%A7%81%E0%A6%AE%20Jukti%20Tech%21%20%E0%A6%86%E0%A6%AE%E0%A6%BF%20Vantor%20eCommerce%20Pro%20%E0%A6%B8%E0%A7%8B%E0%A6%B0%E0%A7%8D%E0%A6%B8%20%E0%A6%95%E0%A7%8B%E0%A6%A1%20%28%E0%A7%B33%2C999%29%20%E0%A6%95%E0%A6%BF%E0%A6%A8%E0%A6%A4%E0%A7%87%20%E0%A6%9A%E0%A6%BE%E0%A6%87%E0%A5%A4" target="_blank" rel="noopener">WhatsApp-এ কথা বলুন</a>
        </div>
        <div class="plan rv">
          <h3>ইনস্টলেশনসহ চান?</h3>
          <p>হোস্টিং, ডোমেইন, কুরিয়ার API আর পেমেন্ট নম্বর বসিয়ে দোকান চালু করে দিই। খরচ জানতে WhatsApp করুন।</p>
          <a class="btn ghost" href="{{ $waBase }}?text=%E0%A6%86%E0%A6%B8%E0%A6%B8%E0%A6%BE%E0%A6%B2%E0%A6%BE%E0%A6%AE%E0%A7%81%20%E0%A6%86%E0%A6%B2%E0%A6%BE%E0%A6%87%E0%A6%95%E0%A7%81%E0%A6%AE%21%20Vantor%20eCommerce%20Pro%20%E0%A6%95%E0%A6%BF%E0%A6%A8%E0%A7%87%20%E0%A6%87%E0%A6%A8%E0%A6%B8%E0%A7%8D%E0%A6%9F%E0%A6%B2%E0%A7%87%E0%A6%B6%E0%A6%A8%E0%A6%B8%E0%A6%B9%20%E0%A6%A8%E0%A6%BF%E0%A6%A4%E0%A7%87%20%E0%A6%9A%E0%A6%BE%E0%A6%87%E0%A5%A4%20%E0%A6%96%E0%A6%B0%E0%A6%9A%20%E0%A6%95%E0%A6%A4%3F" target="_blank" rel="noopener">WhatsApp-এ জিজ্ঞেস করুন</a>
          <p class="phone-line">WhatsApp: <span class="sel">{{ $waDisplay }}</span></p>
        </div>
      </div>
    </div>
  </section>

  <section id="faq" style="padding-top:0">
    <div class="wrap">
      <div class="sec-head rv">
        <span class="eyebrow">প্রশ্নোত্তর</span>
        <h2>কেনার আগে যা জানতে চান</h2>
      </div>
      <div class="faq">
        <details open><summary>দোকান চালাতে কি কোডিং জানতে হবে?</summary><p>না। পণ্য যোগ করা, দাম বদলানো, অর্ডার দেখা সব অ্যাডমিন প্যানেল থেকে করা যায়। শুধু প্রথমবার হোস্টিংয়ে আপলোড করতে হয়, README-তে ধাপে ধাপে লেখা আছে।</p></details>
        <details><summary>কোন হোস্টিংয়ে চলবে?</summary><p>PHP ৮.৩ আর MySQL আছে, আর প্রতি মিনিটে cron চালানো যায়, এমন যেকোনো cPanel শেয়ার্ড হোস্টিং বা VPS-এ। zip-এর ভেতরে সব প্যাকেজ দেওয়া, Node.js বা Composer লাগে না।</p></details>
        <details><summary>পেমেন্টের পর কীভাবে পাব?</summary><p>পেমেন্ট যাচাই হলেই Jukti Tech অ্যাকাউন্টের “আমার ডাউনলোড” পেজে ডাউনলোড লিংক আসে। যতবার দরকার নামাতে পারবেন।</p></details>
        <details><summary>আমার ফিজিক্যাল দোকানও আছে, চলবে?</summary><p>হ্যাঁ। POS রেজিস্টার দিয়ে কাউন্টারে বিক্রি করুন, বারকোড প্রিন্ট করুন। অনলাইন আর দোকানের স্টক একসাথে কমে।</p></details>
        <details><summary>একটা লাইসেন্সে কয়টা ডোমেইন?</summary><p>প্রতিটি কেনায় একটি ডোমেইনের লাইসেন্স। আরেকটা দোকান খুলতে চাইলে WhatsApp করুন।</p></details>
      </div>
    </div>
  </section>

  <section class="final" id="contact" style="padding-top:0">
    <div class="wrap">
      <div class="box rv">
        <span class="ring"></span>
        <div>
          <h2>আপনার দোকানের অর্ডারও এভাবে চলুক</h2>
          <p>ডেমো শপে অর্ডার দিয়ে দেখুন, অ্যাডমিন প্যানেলে নিজে ঘুরে দেখুন। প্রশ্ন থাকলে WhatsApp করুন।</p>
        </div>
        <div class="acts">
          <a class="btn gold" href="https://vantor.juktitech.com/" target="_blank" rel="noopener">লাইভ ডেমো দেখুন <span class="arr">→</span></a>
          <a class="btn wa" href="{{ $waBase }}?text=%E0%A6%86%E0%A6%B8%E0%A6%B8%E0%A6%BE%E0%A6%B2%E0%A6%BE%E0%A6%AE%E0%A7%81%20%E0%A6%86%E0%A6%B2%E0%A6%BE%E0%A6%87%E0%A6%95%E0%A7%81%E0%A6%AE%20Jukti%20Tech%21%20%E0%A6%86%E0%A6%AE%E0%A6%BF%20Vantor%20eCommerce%20Pro%20%E0%A6%B8%E0%A7%8B%E0%A6%B0%E0%A7%8D%E0%A6%B8%20%E0%A6%95%E0%A7%8B%E0%A6%A1%20%28%E0%A7%B33%2C999%29%20%E0%A6%95%E0%A6%BF%E0%A6%A8%E0%A6%A4%E0%A7%87%20%E0%A6%9A%E0%A6%BE%E0%A6%87%E0%A5%A4" target="_blank" rel="noopener">WhatsApp-এ কথা বলুন</a>
          <span class="phone">WhatsApp: {{ $waDisplay }}</span>
        </div>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <span>Vantor eCommerce Pro · Jukti Tech · <a href="{{ route('frontend.themes') }}">অন্যান্য প্রোডাক্ট</a> · <a href="{{ route('login') }}">লগইন</a></span>
    <span>Laravel ১২ · PHP ৮.৩</span>
  </div>
</footer>

<div class="buybar">
  <div><b>{{ $priceBn }}</b><span>সম্পূর্ণ সোর্স কোড · এককালীন</span></div>
  <a class="btn wa sm" href="{{ $waBase }}?text=%E0%A6%86%E0%A6%B8%E0%A6%B8%E0%A6%BE%E0%A6%B2%E0%A6%BE%E0%A6%AE%E0%A7%81%20%E0%A6%86%E0%A6%B2%E0%A6%BE%E0%A6%87%E0%A6%95%E0%A7%81%E0%A6%AE%20Jukti%20Tech%21%20%E0%A6%86%E0%A6%AE%E0%A6%BF%20Vantor%20eCommerce%20Pro%20%E0%A6%B8%E0%A7%8B%E0%A6%B0%E0%A7%8D%E0%A6%B8%20%E0%A6%95%E0%A7%8B%E0%A6%A1%20%28%E0%A7%B33%2C999%29%20%E0%A6%95%E0%A6%BF%E0%A6%A8%E0%A6%A4%E0%A7%87%20%E0%A6%9A%E0%A6%BE%E0%A6%87%E0%A5%A4" target="_blank" rel="noopener" aria-label="WhatsApp">WhatsApp</a>
  <a class="btn gold sm" href="{{ $buyUrl }}">কিনুন →</a>
</div>
<a class="wa-float" href="{{ $waBase }}?text=%E0%A6%86%E0%A6%B8%E0%A6%B8%E0%A6%BE%E0%A6%B2%E0%A6%BE%E0%A6%AE%E0%A7%81%20%E0%A6%86%E0%A6%B2%E0%A6%BE%E0%A6%87%E0%A6%95%E0%A7%81%E0%A6%AE%20Jukti%20Tech%21%20%E0%A6%86%E0%A6%AE%E0%A6%BF%20Vantor%20eCommerce%20Pro%20%E0%A6%B8%E0%A7%8B%E0%A6%B0%E0%A7%8D%E0%A6%B8%20%E0%A6%95%E0%A7%8B%E0%A6%A1%20%28%E0%A7%B33%2C999%29%20%E0%A6%95%E0%A6%BF%E0%A6%A8%E0%A6%A4%E0%A7%87%20%E0%A6%9A%E0%A6%BE%E0%A6%87%E0%A5%A4" target="_blank" rel="noopener" aria-label="WhatsApp-এ কথা বলুন"><svg viewBox="0 0 32 32" aria-hidden="true"><path fill="currentColor" d="M16 3a13 13 0 0 0-11.2 19.6L3 29l6.6-1.7A13 13 0 1 0 16 3zm0 23.7a10.7 10.7 0 0 1-5.5-1.5l-.4-.2-3.9 1 1-3.8-.3-.4A10.7 10.7 0 1 1 16 26.7zm5.9-8c-.3-.2-1.9-1-2.2-1s-.5-.2-.7.2-.8 1-1 1.2-.4.2-.7 0a8.8 8.8 0 0 1-2.6-1.6 9.7 9.7 0 0 1-1.8-2.2c-.2-.3 0-.5.1-.7l.5-.6.3-.5v-.5l-1-2.4c-.3-.6-.5-.5-.7-.5h-.6a1.2 1.2 0 0 0-.9.4 3.6 3.6 0 0 0-1.1 2.7 6.3 6.3 0 0 0 1.3 3.3 14.4 14.4 0 0 0 5.5 4.9c2 .9 2.7 1 3.7.8a3.1 3.1 0 0 0 2-1.4 2.5 2.5 0 0 0 .2-1.4c-.1-.2-.3-.3-.6-.5z"/></svg></a>

<script>
(function () {
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var bn = '০১২৩৪৫৬৭৮৯';
  function toBn(n) {
    var s = Math.round(n).toString();
    if (s.length > 3) { // Bangladeshi grouping: 2,12,400
      var last3 = s.slice(-3), rest = s.slice(0, -3);
      rest = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ',');
      s = rest + ',' + last3;
    }
    return s.replace(/\d/g, function (d) { return bn[d]; });
  }

  // Order pipeline: loops through each step. Static (all done) at rest and for reduced motion.
  var steps = Array.prototype.slice.call(document.querySelectorAll('#steps .step'));
  var rail = document.getElementById('rail');
  function setRail(i) {
    if (!steps[i]) return;
    var first = steps[0].offsetTop + 18, cur = steps[i].offsetTop + 18;
    rail.style.top = first + 'px';
    rail.style.height = Math.max(0, cur - first) + 'px';
  }
  setRail(steps.length - 1);
  if (!reduce && steps.length) {
    var i = 0, n = steps.length;
    function tick() {
      // i === n is the "all done" frame before the loop restarts
      steps.forEach(function (s, k) {
        s.classList.toggle('done', k < i);
        s.classList.toggle('now', k === i);
      });
      setRail(Math.min(i, n - 1));
      i = (i + 1) % (n + 1);
    }
    setTimeout(function () { tick(); setInterval(tick, 1500); }, 1200);
  }
  window.addEventListener('resize', function () {
    var cur = steps.findIndex(function (s) { return s.classList.contains('now'); });
    setRail(cur < 0 ? steps.length - 1 : cur);
  });

  // Scroll reveal: only below-the-fold elements are offset; text is never hidden.
  if (!reduce && 'IntersectionObserver' in window) {
    var els = document.querySelectorAll('.rv, .card');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        e.target.classList.remove('pre');
        e.target.classList.add('in');
        
        io.unobserve(e.target);
      });
    }, { threshold: 0.15 });
    els.forEach(function (el, k) {
      var r = el.getBoundingClientRect();
      if (r.top > window.innerHeight) {
        if (el.classList.contains('rv')) el.classList.add('pre');
        el.style.transitionDelay = (k % 3) * 60 + 'ms';
      } else {
        el.classList.add('in');
      }
      io.observe(el);
    });
  } else {
    document.querySelectorAll('.card, .rv').forEach(function (el) { el.classList.add('in'); });
  }


  // Admin tour: auto-advances; pauses on hover/focus or the button; reduced motion = manual only.
  var tour = document.getElementById('tour');
  if (tour) {
    var tabs = Array.prototype.slice.call(tour.querySelectorAll('.tab'));
    var imgs = Array.prototype.slice.call(document.querySelectorAll('#slides img'));
    var cap = document.getElementById('cap'), cnt = document.getElementById('count'), url = document.getElementById('tourUrl'), btn = document.getElementById('play');
    var cur = 0, timer = null, DUR = 6000, userPaused = reduce, hover = false;
    tour.style.setProperty('--dur', DUR + 'ms');
    function show(i, focus) {
      cur = (i + tabs.length) % tabs.length;
      tabs.forEach(function (t, k) { t.setAttribute('aria-selected', k === cur ? 'true' : 'false'); t.tabIndex = k === cur ? 0 : -1; });
      imgs.forEach(function (im, k) { im.classList.toggle('on', k === cur); if (Math.abs(k - cur) <= 1) im.loading = 'eager'; });
      cap.textContent = tabs[cur].querySelector('.td').textContent;
      cnt.textContent = toBn(cur + 1) + ' / ' + toBn(tabs.length);
      url.textContent = 'yourshop.com' + imgs[cur].dataset.url;
      if (focus) tabs[cur].focus();
      var prog = tabs[cur].querySelector('.prog i');
      prog.style.animation = 'none'; void prog.offsetWidth; prog.style.animation = '';
      if (window.innerWidth <= 960) { var row = tabs[cur].parentNode; row.scrollTo({ left: tabs[cur].offsetLeft - (row.clientWidth - tabs[cur].offsetWidth) / 2, behavior: reduce ? 'auto' : 'smooth' }); }
      schedule();
    }
    function schedule() {
      clearTimeout(timer);
      var on = !userPaused && !hover;
      tour.classList.toggle('playing', on);
      btn.textContent = userPaused ? 'চালু করুন' : 'থামান';
      if (on) timer = setTimeout(function () { show(cur + 1); }, DUR);
    }
    tabs.forEach(function (t, k) {
      t.addEventListener('click', function () { show(k); });
      t.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') { e.preventDefault(); show(cur + 1, true); }
        if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') { e.preventDefault(); show(cur - 1, true); }
      });
    });
    btn.addEventListener('click', function () { userPaused = !userPaused; schedule(); });
    var scr = tour.querySelector('.screen');
    scr.addEventListener('mouseenter', function () { hover = true; schedule(); });
    scr.addEventListener('mouseleave', function () { hover = false; schedule(); });
    // only run while the tour is on screen
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (es) { es.forEach(function (e) { hover = !e.isIntersecting ? true : false; schedule(); }); }, { threshold: 0.25 }).observe(tour);
    }
    show(0);
  }


  // Feature clips: load and play only while on screen
  (function () {
    var vids = Array.prototype.slice.call(document.querySelectorAll('.fc video'));
    function load(v) { if (!v.src) { v.src = v.dataset.src; } }
    if (!('IntersectionObserver' in window)) { vids.forEach(function (v) { load(v); if (!reduce) v.play().catch(function () {}); }); return; }
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) {
        var v = e.target;
        if (e.isIntersecting) { load(v); if (!reduce) v.play().catch(function () {}); }
        else if (!v.paused) v.pause();
      });
    }, { threshold: 0.35 });
    vids.forEach(function (v) { io.observe(v); if (reduce) v.controls = true; });
  })();

  function countUp(root) {
    root.querySelectorAll('[data-count]').forEach(function (el) {
      var end = +el.dataset.count, pre = el.dataset.prefix || '', suf = el.dataset.suffix || '';
      var t0 = performance.now(), dur = 1100;
      function f(t) {
        var p = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - p, 3);
        el.textContent = pre + toBn(end * e) + suf;
        if (p < 1) requestAnimationFrame(f);
      }
      requestAnimationFrame(f);
    });
  }
})();
</script>
</body>
</html>
