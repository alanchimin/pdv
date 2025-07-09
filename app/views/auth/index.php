<?php include '../views/layout/header.php'; ?>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="w-100" style="max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger text-center">Usuário ou senha inválidos.</div>
        <?php endif; ?>

        <form method="POST" action="/auth/login">
            <div class="mb-3">
                <label for="user" class="form-label">Usuário:</label>
                <input type="text" name="user" id="user" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="pass" class="form-label">Senha:</label>
                <input type="password" name="pass" id="pass" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>

<?php include '../views/layout/footer.php'; ?>
