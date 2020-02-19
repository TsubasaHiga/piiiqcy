<?php
/**
 * Footer.php
 *
 * <footer>の中身
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<footer class="l-footer">
	<div class="l-footer__inner">
		<p class="l-footer__copyright">©piiiQcy</p>
	</div>
</footer>

</div>
</div>

<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/bundle.js" defer="defer"></script>

<?php
require_once 'inc/analytics-footer.php';
wp_footer();
?>
</body>
</html>
