<?php
/**
 * Header template
 *
 * @since 1.0.0
 */

?>

<header class="l-header l-spacer">
	<div class="l-header__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( is_home() ) { ?>
				<h1 class="l-header__logo">piiiQcy</h1>
			<?php } else { ?>
				<p class="l-header__logo">piiiQcy</p>
			<?php } ?>
		</a>
		<nav class="c-nav">
			<ul class="c-nav__list">
				<li><a class="c-nav__link" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
				<li><a class="c-nav__link" href="<?php echo esc_url( home_url( '/' ) ); ?>about-page/">About</a></li>
			</ul>
		</nav>
	</div>
</header>
