<?php
/**
 * Index.php
 *
 * Index.phpです
 *
 * @since 0.0.1
 * @package piiiQcy
 */

$page_name = 'top';

require_once 'inc/common.php';
?>

<!-- main -->
<main class="l-page">
	<div class="l-container">

		<!-- contents -->
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<div class="swiper-slide"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top/img-01.jpg" alt="slide image1"></div>
				<div class="swiper-slide"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top/img-02.jpg" alt="slide image2"></div>
				<div class="swiper-slide"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top/img-03.jpg" alt="slide image3"></div>
				<div class="swiper-slide"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top/img-04.jpg" alt="slide image4"></div>
			</div>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>

		<br>

		<a href="https://example.com" target="_blank">https://example.com</a>
		<a href="https://example.com">https://example.com</a>
		<a href="#a">#a</a>

		<h2>H2 見出し2</h2>
		<h3>H3 見出し3</h3>
		<h4 id="a">H4 見出し4</h4>
		<h5>H5 見出し5</h5>
		<h6>H6 見出し6</h6>
		<blockquote>一行の引用です。一行の引用です。</blockquote>
		<blockquote>
		引用元の参照のある複数行の引用です。引用元の参照のある複数行の引用です。引用元の参照のある複数行の引用です。引用元の参照のある複数行の引用です。引用元の参照のある複数行の引用です。引用元の参照のある複数行の引用です。
		<cite>引用元です。引用元です。</cite>
		</blockquote>

		<br>

		<table>
		<tbody>
			<tr>
			<th>テーブルヘッダ1</th>
			<th>テーブルヘッダ2</th>
			<th>テーブルヘッダ3</th>
			</tr>
			<tr>
			<td>サンプルテキスト1-1</td>
			<td>サンプルテキスト1-2</td>
			<td>サンプルテキスト1-3</td>
			</tr>
			<tr>
			<td>サンプルテキスト2-1</td>
			<td>サンプルテキスト2-2<br><a href="http://example.com/">テキストリンクです。</a></td>
			<td>サンプルテキスト2-3</td>
			</tr>
			<tr>
			<td>サンプルテキスト3-1</td>
			<td>サンプルテキスト3-2</td>
			<td>サンプルテキスト3-3</td>
			</tr>
		</tbody>
		</table>

		<br>

		<dl>
		<dt>定義リストタイトル1</dt>
		<dd>定義リスト1です。定義リスト1です。定義リスト1です。</dd>
		<dt>定義リストタイトル2</dt>
		<dd>定義リスト2です。定義リスト2です。定義リスト2です。</dd>
		<dt>定義リストタイトル3</dt>
		<dd>定義リスト3です。定義リスト3です。定義リスト3です。</dd>
		</dl>

		<br>

		<ul>
		<li>ulリストアイテム1
			<ul>
			<li>ulリストアイテム1
				<ul>
				<li>ulリストアイテム1</li>
				<li>ulリストアイテム2</li>
				<li>ulリストアイテム3</li>
				<li>ulリストアイテム4</li>
				</ul>
			</li>
			<li>ulリストアイテム2</li>
			<li>ulリストアイテム3</li>
			<li>ulリストアイテム4</li>
			</ul>
		</li>
		<li>ulリストアイテム2</li>
		<li>ulリストアイテム3</li>
		<li>ulリストアイテム4</li>
		</ul>

		<br>

		<ol>
		<li>olリストアイテム1
			<ol>
			<li>olリストアイテム1
				<ol>
				<li>olリストアイテム1</li>
				<li>olリストアイテム2</li>
				<li>olリストアイテム3</li>
				<li>olリストアイテム4</li>
				</ol>
			</li>
			<li>olリストアイテム2</li>
			<li>olリストアイテム3</li>
			<li>olリストアイテム4</li>
			</ol>
		</li>
		<li>olリストアイテム2</li>
		<li>olリストアイテム3</li>
		<li>olリストアイテム4</li>
		</ol>

		<br>

		<form action="" method="post">
		<p><label>type="email":<input type="email" name="email"></label></p>
		<p><label>type="url":<input type="url" name="url"></label></p>
		<p><label>type="search":<input type="search" name="search"></label></p>
		<p><label>type="telephone":<input type="tel" name="tel"></label></p>
		<p><label>type="number":<input type="number" name="number"></label></p>
		<p><label>type="date":<input type="date" name="date"></label></p>
		<p><label>type="datetime":<input type="datetime" name="datetime"></label></p>
		<p><label>type="datetime-local":<input type="datetime-local" name="datetime-local"></label></p>
		<p><label>type="month":<input type="month" name="month"></label></p>
		<p><label>type="week":<input type="week" name="week"></label></p>
		<p><label>type="time":<input type="time" name="time"></label></p>
		<p><label>type="range":<input type="range" name="range"></label></p>
		<p><label>type="color":<input type="color" name="color"></label></p>
		</form>

		<br>

		<form action="" method="post" enctype="multipart/form-data">
		<p><label>名前：<input type="text" name="name" size="30" maxlength="20"></label></p>
		<p><label>パスワード：<input type="password" name="pass" size="6" maxlength="4"></label></p>
		<p>学年：
			<label><input type="radio" name="gakunen" value="1">１年生</label>
			<label><input type="radio" name="gakunen" value="2">２年生</label>
			<label><input type="radio" name="gakunen" value="3">３年生</label>
			<label><input type="radio" name="gakunen" value="4">４年生</label>
			<label><input type="radio" name="gakunen" value="5">５年生</label>
			<label><input type="radio" name="gakunen" value="6">６年生</label>
		</p>
		<p>好きな課目：
			<label><input type="checkbox" name="kamoku" value="kokugo">国語</label>
			<label><input type="checkbox" name="kamoku" value="eigo">英語</label>
			<label><input type="checkbox" name="kamoku" value="sansu">算数</label>
			<label><input type="checkbox" name="kamoku" value="rika">理科</label>
			<label><input type="checkbox" name="kamoku" value="syakai">社会</label>
			<label><input type="checkbox" name="kamoku" value="taiiku">体育</label>
		</p>
		<p><label>宿題ファイル：<input type="file" name="syukudai"></label></p>
		<p>
			<input type="submit" value="送信">
			<input type="reset" value="リセット">
		</p>
		</form>

		<br>

		<address>addressタグ｜〒100-0000 東京都千代田区1-1-1 日本</address>
		<a href="http://example.com/" rel="nofollow">aタグ｜テキストリンクです。</a>
		<abbr title="abbreviation">abbrタグ｜サンプルテキストです。</abbr>
		<cite>Citeタグ｜サンプルテキストです。</cite>
		<code>codeタグ｜サンプルテキストです。</code>
		<em>emタグ｜サンプルテキストです。</em>
		<ins>insタグ｜サンプルテキストです。</ins>
		<kbd>kbdタグ｜サンプルテキストです。</kbd>
		<q>qタグ｜サンプルテキストです。</q>
		<strong>strongタグ｜サンプルテキストです。</strong>
		<sub>subタグ｜サンプルテキストです。</sub>
		<sup>supタグ｜サンプルテキストです。</sup>
		<var>varタグ｜サンプルテキストです。</var>

		<div class="u-temp__wrap">
			<p class="u-temp__wrap--tit">post_type=post取得例（4件取得 ※先頭固定表示は例外）</p>
			<?php
			$query = new WP_Query( c_get_args( 'post', 4 ) );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part( 'template/template-title' );
				}
			} else {
				get_template_part( 'inc/parts-nopost' );
			}
			wp_reset_postdata();
			?>
		</div>

		<div class="u-temp__wrap">
			<p class="u-temp__wrap--tit">post_type=post / タクソノミー名=categoryのターム取得例</p>
			<ul>
				<?php
				$taxonomies = 'category';
				$terms      = c_get_terms( $taxonomies, 10 );
				foreach ( $terms as $value ) {
					echo '<li>';
					$term_link = get_term_link( $value->slug, $taxonomies );
					echo '<a href="' . esc_html( $term_link ) . '">' . esc_html( $value->name ) . '</a>';
					echo esc_html( $value->count );
					echo '</li>';
				}
				?>
			</ul>
		</div>

	</div>
</main>

<?php get_footer(); ?>
