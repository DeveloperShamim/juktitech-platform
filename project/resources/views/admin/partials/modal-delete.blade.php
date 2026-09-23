<div class="modal fade adm-pro" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title ?? __('Confirm Delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="adm-delete-icon"><i class="fas fa-trash-alt"></i></div>
                <p class="mb-1">{{ $message ?? __('You are about to delete this item.') }}</p>
                <p class="text-muted mb-0">{{ __('Are you sure?') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                @if(!empty($useForm))
                    <form action="" method="POST" id="delete-form" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Yes, Delete') }}</button>
                    </form>
                @else
                    <a class="btn btn-danger btn-ok">{{ __('Yes, Delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
