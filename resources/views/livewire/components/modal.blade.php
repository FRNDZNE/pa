<div>
    <div wire:ignore.self class="modal fade" id="{{ $modalId }}" tabindex="-1">
        <div class="modal-dialog modal-{{ $size }}">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-modal', event => {
            $('#' + event.detail.modalId).modal('show');
        });

        window.addEventListener('close-modal', event => {
            $('#' + event.detail.modalId).modal('hide');
        });
    </script>

</div>
