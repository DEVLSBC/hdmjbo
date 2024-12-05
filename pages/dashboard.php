<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /hdmjbo/pages/dashboard.php");
    exit;
}

include '../includes/header.php';
?>
	<main class="dashboard">
		<!-- Cabeçalho da página, contendo imagem e título -->
		<header>
				<div class="dash_header">
					<div>
						<img class="dash_header_logo" src="/hdmjbo/assets/imagens/LOGO.png" alt="Logo da Prefeitura de Fortaleza">
					</div>
					<div></div>
					<div>
						<h1><?php echo "Bem-vindo(a), " . htmlspecialchars($_SESSION['username']) . "!";?></h1>
					</div>
					<div>
						<a class="dash_sair_button" href='/hdmjbo/pages/logout.php'>Sair</a>						
					</div>
				</div>

			<hr>
		</header>
		<section class="dash_modulos">
			<div>
				<?php
					if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') {
						echo '<a href="cadastro.php" class="btn-cadastro">Cadastrar Usuário</a>';
					}
				?>
			</div>
			<div>
				<a href="#" class="btn-cadastro">Botão 2</a>
			</div>
			<div>
				<a href="#" class="btn-cadastro">Botão 3</a>
			</div>
		</section>
		<!-- Rodape da página -->
		<footer>
			<hr>
				<div class="dash_footer">
					<p>&copy; 2024 HDMJBO. Todos os direitos reservados.</p>
				</div>
		</footer>
	</main>

<?php
include '../includes/footer.php';
?>