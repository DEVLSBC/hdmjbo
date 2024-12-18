<?php
// Verifica a existencia do arquivo setup.php
if (file_exists(__DIR__ . '/setup.php')) {
    die('O sistema já está configurado. Apague o arquivo "setup.php" da pasta raiz.');
}
session_start();
$erro = $_SESSION['erro'] ?? null; // Guarda a mensagem de erro na variavel $erro
unset($_SESSION['erro']); // Remove a mensagem após exibi-la
if (isset($_SESSION['cpf'])) {
    header("Location: /hdmjbo/pages/dashboard.php");
    exit;
}
include 'includes/header.php';
?>
    <main>
        <section>
            <div class="login">
                <article class="headerLogin">
                    <img src="/hdmjbo/assets/imagens/LOGO.png" alt="Logo da Prefeitura de Fortaleza">
                    <p>HDMJBO</p>
                </article>

                <hr>

                <form class="inputLogin" action="./pages/login.php" method="POST" autocomplete="off">
                    
                    <!-- Campo de login -->
                    <div class="boxLogin">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" maxlength="14" required oninput="formatCPF(this)">
                    </div>

                    <!-- Campo de senha -->
                    <div class="boxSenha">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                    </div>

                    <!-- Botão de login -->
                    <div class="loginBotao">
                        <button type="submit" class="custom-btn btn-4"><span>Login</span></button>
                    </div>
                </form>

                <hr>

                <article class="footerLogin">
                    <div class="footer">
                        <p>&copy; 2024 HDMJBO. Todos os direitos reservados.</p>
                    </div>
                </article>
            </div>
        </section>
    </main>
<?php include 'includes/footer.php'; ?>
