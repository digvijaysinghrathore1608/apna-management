<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title','Dashboard')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* Minimal custom CSS only */
body{
    background:#f8fafc;
}

/* Sidebar */
.sidebar{
    width:240px;
    min-height:100vh;
    position:fixed;
    top:0;
    left:0;
    background:#111827;
    transition:.3s;
}

.sidebar a{
    color:#d1d5db;
    text-decoration:none;
    padding:12px 20px;
    display:block;
    font-size:15px;
}

.sidebar a:hover,
.sidebar a.active{
    background:#1f2937;
    color:#fff;
}

/* Hide sidebar */
.sidebar.hide{
    left:-240px;
}

/* Content */
.main{
    margin-left:240px;
    transition:.3s;
}

.main.full{
    margin-left:0;
}

/* Mobile */
@media(max-width:768px){
    .sidebar{
        left:-240px;
    }
    .sidebar.show{
        left:0;
    }
    .main{
        margin-left:0;
    }
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="p-3 text-white fw-bold border-bottom">
        Admin Panel
    </div>

    <a href="#" class="active">Dashboard</a>
    <a href="#">Users</a>
    <a href="#">Reports</a>
    <a href="#">Settings</a>
    <a href="#">Logout</a>
</div>

<!-- Main -->
<div class="main" id="main">

    <!-- Top Navbar -->
    <nav class="navbar navbar-light bg-white border-bottom px-3">
        <button class="btn btn-outline-secondary btn-sm" onclick="toggleSidebar()">☰</button>
        <span class="ms-3 fw-semibold">@yield('page-title')</span>
    </nav>

    <!-- Page Content -->
    <div class="container-fluid py-4">
        @yield('content')
    </div>

</div>

<script>
function toggleSidebar(){
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');

    if(window.innerWidth < 768){
        sidebar.classList.toggle('show');
    }else{
        sidebar.classList.toggle('hide');
        main.classList.toggle('full');
    }
}
</script>

</body>
</html>
