<div>
    <!-- Modal trigger button -->
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
        Tambah Data
    </button>

    <div wire:ignore.self class="modal fade" id="modalCreate" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="storeData">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="modalTitleId">
                            Tambah Data Dosen
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Form --}}
                        <div class="form-group mb-2">
                            <label for="lecture_number" class="for-label">NIP</label>
                            <input type="text" wire:model="lecture_number" id="lecture_number" class="form-control">
                            @error('lecture_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label for="name" class="for-label">Nama</label>
                            <input type="text" wire:model="name" id="name" class="form-control">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label for="email" class="for-label">E-Mail</label>
                            <input type="email" wire:model="email" id="email" class="form-control">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Script untuk close modal otomatis --}}
    <script>
        window.addEventListener('closeModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCreate'));
            modal.hide();
        });
    </script>
</div>
