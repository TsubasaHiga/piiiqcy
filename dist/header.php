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
	<div class="l-header__inner l-spacer">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( is_home() ) { ?>
				<h1 class="text-2xl font-bold">piiiQcy</h1>
			<?php } else { ?>
				<p class="text-2xl font-bold">piiiQcy</p>
			<?php } ?>
		</a>
		<nav class="c-nav">
			<div class="c-nav__inner">
				<ul class="c-nav__list">
					<li class="c-nav__item">
						<a class="c-nav__link" href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a>
					</li>
					<li class="c-nav__item">
						<a class="c-nav__link" href="<?php echo esc_url( home_url( '/' ) ); ?>about/">About</a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>
