<a href="{{ route('home') }}"
    class="list-group-item list-group-item-action {{ Route::is('admin.dashboard') ? 'active' : '' }}">Dashboard
</a>
<a href="{{ route('admin.dosen.index') }}"
    class="list-group-item list-group-item-action {{ Route::is('admin.dosen.index') ? 'active' : '' }}">Dosen
</a>
<a href="{{ route('admin.class.index') }}"
    class="list-group-item list-group-item-action {{ Route::is('admin.class.index') ? 'active' : '' }}">Kelas
</a>
<a href="{{ route('admin.student.kelas') }}"
    class="list-group-item list-group-item-action {{ Route::is('admin.student.kelas') ? 'active' : '' }}">Mahasiswa
</a>
