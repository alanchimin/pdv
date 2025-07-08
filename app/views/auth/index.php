<?php include '../views/layout/header.php'; ?>

<div class="container mt-5">
    <h2>Login</h2>

    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger">Usuário ou senha inválidos.</div>
    <?php endif; ?>

    <form method="POST" action="/auth/login">
        <div class="mb-3">
            <label for="user">Usuário:</label>
            <input type="text" name="user" id="user" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="pass">Senha:</label>
            <input type="password" name="pass" id="pass" class="form-control" required>
        </div>

        <button class="btn btn-primary">Entrar</button>
    </form>
</div>

<?php include '../views/layout/footer.php'; ?>
