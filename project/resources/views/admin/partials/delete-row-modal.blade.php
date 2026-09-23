<div class="modal fade adm-pro" id="confirm-delete-{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="adm-delete-icon"><i class="fas fa-trash-alt"></i></div>
                <h6 class="font-weight-bold mb-1">{{ $title ?? __('Are you sure?') }}</h6>
                @if(!empty($message))
                    <p class="text-muted small mb-3">{{ $message }}</p>
                @endif
                <div class="d-flex justify-content-center mt-3" style="gap:10px">
                    <button type="button" class="btn btn-default btn-sm px-3" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm px-3">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
