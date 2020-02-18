<?php
/**
 * Header.php
 *
 * <header>の中身です
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<header class="l-header">
	<div class="l-header__inner">
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php if ( is_home() ) { ?>
			<h1 class="logo__txt">piiiQcy</h1>
		<?php } else { ?>
			<p class="logo__txt">piiiQcy</p>
		<?php } ?>
		</a>
		<nav class='l-nav'>
			<ul class="l-nav__list">
				<li class="l-nav__item">
					<a class="l-nav__link" data-linkname="top" href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a>
				</li>
				<li class="l-nav__item">
					<a class="l-nav__link" data-linkname="sample2" href="<?php echo esc_url( home_url( '/' ) ); ?>sample2/">Sample2</a>
				</li>
			</ul>
		</nav>
	</div>
	<button id="hmb" class="l-hmb l-sm" aria-label="メニューボタン"><span></span></button>
	<div id="hmb__bg" class="l-hmb-bg"></div>
</header>
