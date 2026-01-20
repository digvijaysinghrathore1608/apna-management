<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* Very minimal custom CSS */
body{
    background:#f8fafc;
}
.login-card{
    max-width:380px;
}
</style>
</head>

<body>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card login-card shadow-sm w-100">
        <div class="card-body p-4">

            <h4 class="text-center mb-3 fw-semibold">Admin Login</h4>
            <p class="text-center text-muted mb-4">Please sign in to continue</p>

            <form method="POST">
                <!-- CSRF later -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="Enter email">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="Enter password">
                </div>

                <button class="btn btn-primary w-100">Login</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
