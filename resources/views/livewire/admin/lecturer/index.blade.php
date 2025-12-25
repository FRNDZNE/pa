<div>
    <div class="table-responsive">

        <table class="table table-default" id="lecturerTable">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">NIP</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lecturers as $lecturer)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td>{{ $lecturer->lecture_number }}</td>
                        <td>{{ $lecturer->user->name }}</td>
                        <td>
                            button
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@push('scripts')
    <script>
        $('#lecturerTable').DataTable();
    </script>
@endpush
