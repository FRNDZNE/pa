<div>
    <table id="responsive-datatable" class="table table-bordered table-bordered dt-responsive nowrap" wire:ignore.self>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lecturers as $lecturer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $lecturer->lecture_number }}</td>
                    <td>{{ $lecturer->user->name }}</td>
                    <td>{{ $lecturer->user->email }}</td>
                    <td>
                        nanti button
                        {{-- <span class="d-inline-block">@livewire('admin.lecturer.update-data', ['lecturer' => $lecturer], key($lecturer->id))</span>

                        <span class="d-inline-block">@livewire('admin.lecturer.delete-data', ['lecturer' => $lecturer], key($lecturer->id))</span> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script></script>
