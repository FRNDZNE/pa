<li class="nav-header">Dashboard</li>
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
        <i class="nav-icon bi bi-speedometer2"></i>
        <p>Dashboard</p>
    </a>
</li>
<li class="nav-header">Master</li>
<li class="nav-item">
    <a href="{{ route('admin.dosen.index') }}" class="nav-link {{ Route::is('admin.dosen.index') ? 'active' : '' }}">
        <i class="nav-icon bi bi-person-badge"></i>
        <p>Dosen</p>
    </a>
</li>
<li class="nav-item">
    <a href="" class="nav-link">
        <i class="nav-icon bi bi-people"></i>
        <p>Kelas</p>
    </a>
</li>
<li class="nav-item">
    <a href="" class="nav-link">
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
