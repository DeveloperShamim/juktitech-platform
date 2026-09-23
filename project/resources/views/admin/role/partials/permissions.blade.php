@php
    $permissionItems = [
        ['menu_builder', __('Menu Builder')],
        ['pages', __('Pages')],
        ['categories', __('Categories')],
        ['add_post', __('Add Post')],
        ['add_gallery', __('Add Gallery')],
        ['posts', __('Posts')],
        ['schedule_post', __('Schedule Post')],
        ['drafts', __('Drafts')],
        ['rss_feeds', __('Rss Feeds')],
        ['polls', __('Polls')],
        ['widgets', __('Widgets')],
        ['create_ads', __('Create Ads')],
        ['newsLetter', __('NewsLetter')],
        ['contact_messages', __('Contact Messages')],
        ['languages', __('Languages')],
        ['general_settings', __('General Settings')],
        ['social_settings', __('Social Settings')],
        ['seo_tools', __('SEO Tools')],
        ['emails_settings', __('Email Settings')],
        ['role_management', __('Role Management')],
        ['font_option', __('Font Option')],
        ['user_management', __('User Management')],
        ['cache_management', __('Cache Management')],
        ['administration_management', __('Administration Management')],
    ];
    $selected = is_array($values ?? null) ? $values : [];
@endphp

<div class="role-perms">
    @foreach ($permissionItems as [$value, $label])
        <div class="role-perms__item">
            <span>{{ $label }}</span>
            <label class="switch">
                <input type="checkbox" name="section[]" value="{{ $value }}" {{ in_array($value, $selected, true) ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </div>
    @endforeach
</div>
