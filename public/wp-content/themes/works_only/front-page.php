<?php
/**
Template Name: top
***/
?>

<!DOCTYPE html>
<html lang="ja">
<?php include("head.php"); ?>

<body id="top">
      <?php get_header(); ?>
      <main>
            <section class="section">
                  <div class="section__inner">
                        <div class="section__head">
                              <div class="section__textarea">
                                    <p class="section__text">ご覧いただきありがとうございます。</p>
                                    <h1 class="section__heading">田中しょうごの実績を掲載しているサイトです。</h1>
                                    <p class="section__text">
                                          ポートフォリオサイトリニューアル中のため実績のみを掲載しております<br>
                                          また、実案件はNDA契約を結んでいるため掲載しておりません。<br>
                                          恐れ入りますがご承知おきください。
                                    </p>
                              </div>
                              <div class="section__about">
                                    <div class="section__img">
                                          <picture>
                                                <source srcset="<?php echo get_template_directory_uri(); ?>/assets/img/profile.webp 1x,<?php echo get_template_directory_uri(); ?>/assets/img/profile_2x.webp 2x" type="image/webp">
                                                <source srcset="<?php echo get_template_directory_uri(); ?>/assets/img/profile.png 1x,<?php echo get_template_directory_uri(); ?>/assets/img/profile_2x.png 2x">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/profile_2x.png" alt="">
                                          </picture>
                                    </div>
                                    <div class="section__info">
                                          <dl>
                                                <div class="section__row">
                                                      <dt>氏名：</dt>
                                                      <dd>田中 政伍　Tanaka Shogo</dd>
                                                </div>
                                                <div class="section__row">
                                                      <dt>所在地：</dt>
                                                      <dd>宮崎市内</dd>
                                                </div>
                                                <div class="section__row">
                                                      <dt>お問い合わせ：</dt>
                                                      <dd>shogotanaka0911@gmail.com</dd>
                                                </div>
                                                <div class="section__row">
                                                      <dt>事業内容：</dt>
                                                      <dd>HTML・CSSによるWebサイトやLP等のマークアップ、WordPress導入等</dd>
                                                </div>
                                          </dl>
                                    </div>
                              </div>
                        </div>
                        <div class="section__body">
                              <ul>
                                    <?php
                                    $args = array(
                                          'paged' => $paged,
                                          'post_type' => 'post',
                                          'orderby' => 'date',
                                          'order' => 'DSC',
                                          'post_status' => 'publish',
                                          'posts_per_page' => 10,
                                    );
                                    $news_query = new WP_Query($args);
                                    ?>
                                    <?php if ($news_query->have_posts()) : ?>
                                          <?php while ($news_query->have_posts()) : $news_query->the_post();  ?>
                                                <li>
                                                      <a href="<?php echo CFS()->get('url'); ?>" target="_blank">
                                                            <div class="section__thumbnail">
                                                                  <?php if (has_post_thumbnail()) : ?>
                                                                        <?php the_post_thumbnail(); ?>
                                                                  <?php else : ?>
                                                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/sample.webp" alt="サムネイル画像">
                                                                  <?php endif; ?>
                                                            </div>
                                                            <div class="section__desc">
                                                                  <h2 class="section__title"><?php the_title(); ?></h2>
                                                                  <p class="section__excerpt"><?php the_excerpt(); ?></p>
                                                            </div>
                                                      </a>
                                                </li>
                                          <?php endwhile; ?>
                                    <?php endif; ?>
                                    <?php wp_reset_postdata(); ?>
                              </ul>
                        </div>
                  </div>
            </section>
      </main>
      <?php get_footer(); ?>
</body>