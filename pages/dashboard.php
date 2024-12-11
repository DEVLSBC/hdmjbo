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
					<div class="dash_header_nome_sair">
						<h1><?php echo "Bem-vindo(a), " . htmlspecialchars($_SESSION['username']) . "!";?></h1>
						<a class="dash_sair_button" href='/hdmjbo/pages/logout.php'>Sair</a>						
					</div>
				</div>
			<hr>
		</header>
		<section class="dash_modulos">
			<div>
				<?php
					if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'admin') {
						echo '<a href="usuarios.php" class="btn-dashboard">
								<div class="icon"></div>
								<hr>
								<span>GERENCIAMENTO DE USUÁRIO</span>
								</a>
								';
					}
				?>
			</div>
			<div>
				<?php
					if (isset($_SESSION['cargo'])) {
						echo '<a href="http://172.30.57.89/suportehdmjbo/" class="btn-dashboard" target="_blank">
								<div class="icon"></div>
								<hr>
								<span>SUPORTE HDMJBO</span>
								</a>
								';
					}
				?>
			</div>
			<div>
				<?php
					if (isset($_SESSION['cargo'])) {
						echo '<a href="http://172.30.57.89/hdmjbo/" class="btn-dashboard" target="_blank">
								<div class="icon"></div>
								<hr>
								<span>ATALHOS DOS SETORES</span>
								</a>
								';
					}
				?>
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