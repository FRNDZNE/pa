<div>
    <form action="" method="post">
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                placeholder="Masukkan Nama Dosen" wire:model.defer="name">
            @error('name')
                <small class="invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
    </form>
</div>
