<li class="nav-header">Master</li>
<li class="nav-item">
    <a href="{{ route('lecturer.index') }}" class="nav-link {{ Route::is('lecturer.index') ? 'active' : '' }}">
        <i class="nav-icon bi bi-person-badge"></i>
        <p>Dosen</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('student.index') }}" class="nav-link {{ Route::is('student.index') ? 'active' : '' }}">
        <i class="nav-icon bi bi-person-badge"></i>
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
