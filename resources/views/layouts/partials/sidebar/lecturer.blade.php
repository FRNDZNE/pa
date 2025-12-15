<li class="nav-header">Master</li>
<li class="nav-item">
    <a href="{{ route('lecturer.kelas.index') }}"
        class="nav-link {{ Route::is('lecturer.kelas.index') ? 'active' : '' }}">
        <i class="nav-icon bi bi-people"></i>
        <p>Kelas</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('lecturer.student.index') }}"
        class="nav-link {{ Route::is('lecturer.student.index') ? 'active' : '' }}">
        <i class="nav-icon bi bi-mortarboard"></i>
        <p>Mahasiswa</p>
    </a>
</li>
<li class="nav-header">Materi dan Soal</li>
<li class="nav-item">
    <a href="" class="nav-link">
        <i class="nav-icon bi bi-journal-bookmark"></i>
        <p>Materi</p>
    </a>
</li>
<li class="nav-item">
    <a href="" class="nav-link ">
        <i class="nav-icon bi bi-collection"></i>
        <p>Bank Soal</p>
    </a>
</li>
