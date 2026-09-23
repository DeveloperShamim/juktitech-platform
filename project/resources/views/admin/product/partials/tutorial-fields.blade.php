@php
    $tutorialRows = old('tutorials', $tutorials ?? []);
    if (empty($tutorialRows)) {
        $tutorialRows = [['title' => '', 'url' => '']];
    }
@endphp

<div class="row">
    <div class="col-lg-4 text-right">
        <h4 class="heading">{{ __('Tutorials') }}</h4>
        <small class="text-muted">একাধিক ভিডিও বা টিউটোরিয়াল লিংক যোগ করুন</small>
    </div>
    <div class="col-lg-7">
        <div id="tutorialRows">
            @foreach($tutorialRows as $index => $tutorial)
                <div class="tutorial-row border rounded p-3 mb-3 bg-light">
                    <div class="form-group mb-2">
                        <input type="text"
                               name="tutorials[{{ $index }}][title]"
                               class="input-field"
                               value="{{ $tutorial['title'] ?? '' }}"
                               maxlength="255"
                               placeholder="টিউটোরিয়ালের শিরোনাম">
                    </div>
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <input type="url"
                               name="tutorials[{{ $index }}][url]"
                               class="input-field mb-0"
                               value="{{ $tutorial['url'] ?? '' }}"
                               maxlength="2048"
                               placeholder="YouTube ভিডিও লিংক">
                        <button type="button" class="btn btn-outline-danger tutorial-remove" title="মুছুন">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" id="addTutorialRow" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-plus-circle mr-1"></i> আরও টিউটোরিয়াল যোগ করুন
        </button>
        <small class="text-muted d-block mt-2">প্রতিটি ভিডিওর title ও YouTube link আলাদাভাবে দিন। সর্বোচ্চ ৩০টি ভিডিও।</small>
    </div>
</div>

<template id="tutorialRowTemplate">
    <div class="tutorial-row border rounded p-3 mb-3 bg-light">
        <div class="form-group mb-2">
            <input type="text" data-name="title" class="input-field" maxlength="255" placeholder="টিউটোরিয়ালের শিরোনাম">
        </div>
        <div class="d-flex align-items-center" style="gap:10px;">
            <input type="url" data-name="url" class="input-field mb-0" maxlength="2048" placeholder="YouTube ভিডিও লিংক">
            <button type="button" class="btn btn-outline-danger tutorial-remove" title="মুছুন">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('tutorialRows');
    var addButton = document.getElementById('addTutorialRow');
    var template = document.getElementById('tutorialRowTemplate');
    var nextIndex = container.querySelectorAll('.tutorial-row').length;

    addButton.addEventListener('click', function () {
        if (container.querySelectorAll('.tutorial-row').length >= 30) {
            alert('সর্বোচ্চ ৩০টি টিউটোরিয়াল যোগ করা যাবে।');
            return;
        }

        var row = template.content.firstElementChild.cloneNode(true);
        row.querySelector('[data-name="title"]').name = 'tutorials[' + nextIndex + '][title]';
        row.querySelector('[data-name="url"]').name = 'tutorials[' + nextIndex + '][url]';
        nextIndex++;
        container.appendChild(row);
    });

    container.addEventListener('click', function (event) {
        var removeButton = event.target.closest('.tutorial-remove');
        if (!removeButton) return;

        var rows = container.querySelectorAll('.tutorial-row');
        var row = removeButton.closest('.tutorial-row');
        if (rows.length === 1) {
            row.querySelectorAll('input').forEach(function (input) { input.value = ''; });
            return;
        }
        row.remove();
    });
});
</script>
