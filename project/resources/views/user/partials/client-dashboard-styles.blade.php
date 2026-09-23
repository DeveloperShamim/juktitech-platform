<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Inter', 'Hind Siliguri', sans-serif; background: #f3f4f6; }
    .user-dashboard-wrap .sidebar-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #edf2f7;
    }
    .user-dashboard-wrap .user-profile-box {
        text-align: center;
        padding: 35px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .user-dashboard-wrap .user-avatar {
        width: 90px !important;
        height: 90px !important;
        max-width: 90px !important;
        max-height: 90px !important;
        border-radius: 50%;
        border: 4px solid #f8fafc;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        object-fit: cover;
        display: inline-block;
    }
    .user-dashboard-wrap .menu-link {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: #64748b;
        text-decoration: none;
        border-radius: 12px;
        margin: 5px 15px;
        transition: 0.3s;
        font-weight: 500;
    }
    .user-dashboard-wrap .menu-link i {
        width: 30px;
        font-size: 18px;
        flex-shrink: 0;
    }
    .user-dashboard-wrap .menu-link:hover,
    .user-dashboard-wrap .menu-link.active {
        background: #f1f5f9;
        color: #4e73df;
    }
    .user-dashboard-wrap .form-card,
    .user-dashboard-wrap .table-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #edf2f7;
        padding: 28px;
        box-shadow: 0 4px 6px rgba(0,0,0,.04);
    }
    .user-dashboard-wrap .form-control {
        border-radius: 12px;
        min-height: 46px;
    }
    .user-dashboard-wrap .wv-hero {
        background: linear-gradient(135deg, #14532d, #15803d 55%, #22c55e);
        border-radius: 20px;
        color: #fff;
        padding: 28px 32px;
        margin-bottom: 24px;
    }
    .user-dashboard-wrap .btn-wv {
        background: #15803d;
        color: #fff;
        border-radius: 999px;
        padding: 10px 20px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
    }
    .user-dashboard-wrap .btn-wv:hover { background: #166534; color: #fff; }
    .user-dashboard-wrap .badge-wv {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }
    .user-dashboard-wrap .detail-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #edf2f7;
        padding: 28px;
    }
    .user-dashboard-wrap .status-box {
        border-radius: 16px;
        padding: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
</style>
