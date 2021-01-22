<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'mbl' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'fs!u9E&*kpb@h*D!,<)Sp<h#?1*Ke/:Hwo9-{$gtgSUhby[Z0?C L<sovGVE71%c' );
define( 'SECURE_AUTH_KEY',  'yW^v57*7 73YzJg`=x1:*[Qu,Q=qqJF@W49(*4rk,W?_:&RaBL4!5hQt6s~`JSAN' );
define( 'LOGGED_IN_KEY',    'o|3f JKJD>E/GW|H NdJ{XwCK^~ec%frL)kgBnsKRhj>`/RFi5^I,D/xy]Yq72#j' );
define( 'NONCE_KEY',        '%b0.XnnUg_AR>vyLg0e<;V(-RpX]YjT5 qBWJ<.0a*Px#OBGrfiCGvGeK0P-qRUd' );
define( 'AUTH_SALT',        '#Ru-l/!5d[c2nb#>^#[w_+uqg}vmgJi(*^*M0Fym5Uopm4Z_Fq`N6y%CGO8zK%sL' );
define( 'SECURE_AUTH_SALT', '.!eWV1f24Yg{!KlyZ-J^ET.]<|f&@rj,KiK14_m>gHl]w/Gca)=<`uz-X+SWblXc' );
define( 'LOGGED_IN_SALT',   'Zdo&L`mkuwO>q}hwA,0!xqSDr@{.=7&2P;g jQ<D!&H3<5qwtP s.HN|RpX/qc2=' );
define( 'NONCE_SALT',       '68EKGn!(QvNju7cI]Eb66y=n5bHP_oE-pI@Fexo^gBqlhiB<J!-X_eVMS>>.2iu]' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'mbladv_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
