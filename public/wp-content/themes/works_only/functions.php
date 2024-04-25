<?php
// スタイルシートの読み込み
function my_styles()
{
	wp_enqueue_style('my-common', get_template_directory_uri() . '/assets/css/common.css', array());
	if (is_front_page()) {
		wp_enqueue_style('my-top', get_template_directory_uri() . '/assets/css/top.css', array());
	} elseif (is_page('ページのスラッグ名')) {
		wp_enqueue_style('任意の名前', get_template_directory_uri() . '/assets/css/{cssファイルの名前}', array(), '');
	} else if (is_archive()) {
		wp_enqueue_style('任意の名前', get_template_directory_uri() . '/assets/css/{デフォルト投稿のスラッグ名}.css', array());
	} else if (is_single()) {
		wp_enqueue_style('任意の名前', get_template_directory_uri() . '/assets/css/{デフォルト投稿のスラッグ名}-single.css', array());
	} else if (is_post_type_archive('カスタム投稿のスラッグ名')) {
		wp_enqueue_style('任意の名前', get_template_directory_uri() . '/assets/css/{カスタム投稿のスラッグ名}-archive.css', array(), '');
	} else if (is_singular('カスタム投稿のスラッグ名')) {
		wp_enqueue_style('任意の名前', get_template_directory_uri() . '/assets/css/{カスタム投稿のスラッグ名}-single.css', array());
	} elseif (is_404()) {
		wp_enqueue_style('任意の名前', get_template_directory_uri() . '/assets/css/404.css', array(), '');
	}
}
add_action('wp_enqueue_scripts', 'my_styles');


// スクリプトの読み込み
// function my_scripts()
// {
//           wp_enqueue_script('my-runtime', get_template_directory_uri() . '/assets/js/runtime.js', array());
//           wp_enqueue_script('my-vendors', get_template_directory_uri() . '/assets/js/vendors.js', array());
//           wp_enqueue_script('my-common', get_template_directory_uri() . '/assets/js/common.js', array());
//           if (is_post_type_archive('member')) {
//                     wp_enqueue_script('my-member', get_template_directory_uri() . '/assets/js/member.js', array());
//           } else if (is_front_page()) {
//                     wp_enqueue_script('my-top', get_template_directory_uri() . '/assets/js/top.js', array());
//           } else if (is_archive()) {
//                     wp_enqueue_style('my-gallery-archive', get_template_directory_uri() . '/assets/css/archive.css', array());
//           } else if (is_single()) {
//                     wp_enqueue_style('my-gallery-single', get_template_directory_uri() . '/assets/css/gallery-single.css', array());
//           }
// }
// add_action('wp_enqueue_scripts', 'my_scripts');


// アイキャッチ画像を有効。
add_theme_support('post-thumbnails');

// the_excerpt()のpタグを削除
remove_filter('the_excerpt', 'wpautop');


// エラーページをトップにリダイレクト
add_action('template_redirect', 'is404_redirect_home');
function is404_redirect_home()
{
	if (is_404()) {
		wp_safe_redirect(home_url('/'));
		exit();
	}
}

// ユーザー情報の取得をさせない
function my_filter_rest_endpoints($endpoints)
{
	if (isset($endpoints['/wp/v2/users'])) {
		unset($endpoints['/wp/v2/users']);
	}
	if (isset($endpoints['/wp/v2/users/(?P[\d]+)'])) {
		unset($endpoints['/wp/v2/users/(?P[\d]+)']);
	}
	return $endpoints;
}
add_filter('rest_endpoints', 'my_filter_rest_endpoints', 10, 1);


// カスタム投稿タイプの追加
function add_custom_post_type()
{
	register_post_type(
		'カスタム投稿のスラッグ名',
		array(
			'label' => '管理画面で表示されるラベル名',
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'ラベル横のアイコン',
			'show_in_rest' => true,
			'exclude_from_search' => false,
			'rewrite' => array(
				'with_front' => false,
			),
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'custom-fields',
				'revisions',
				'excerpt'
			),
		)
	);
}
add_action('init', 'add_custom_post_type');


// カスタムタクソノミーの追加
function add_custom_taxonomy()
{
	register_taxonomy(
		'member-category',
		'member',
		array(
			'label' => 'カテゴリー',
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_in_rest' => true,
			'rewrite' => array(
				'with_front' => false,
			),
			'labels' => array(
				'name' => 'カテゴリー',
				'add_new_item' => '新規カテゴリーを追加',
			)
		)
	);
}
add_action('init', 'add_custom_taxonomy');


// 「投稿」の表記変更 
// function Change_menulabel()
// {
// 	global $menu;
// 	global $submenu;
// 	$name = '';
// 	$menu[5][0] = $name;
// 	$submenu['edit.php'][5][0] = $name . '一覧';
// 	$submenu['edit.php'][10][0] = '新規' . $name . '投稿';
// }
// function Change_objectlabel()
// {
// 	global $wp_post_types;
// 	$name = 'お知らせ';
// 	$labels = &$wp_post_types['post']->labels;
// 	$labels->name = $name;
// 	$labels->singular_name = $name;
// 	$labels->add_new = _x('追加', $name);
// 	$labels->add_new_item = $name . 'の新規追加';
// 	$labels->edit_item = $name . 'の編集';
// 	$labels->new_item = '新規' . $name;
// 	$labels->view_item = $name . 'を表示';
// 	$labels->search_items = $name . 'を検索';
// 	$labels->not_found = $name . 'が見つかりませんでした';
// 	$labels->not_found_in_trash = 'ゴミ箱に' . $name . 'は見つかりませんでした';
// }
// add_action('init', 'Change_objectlabel');
// add_action('admin_menu', 'Change_menulabel');


// 投稿アーカイブページのスラッグをnewsに変更
// function post_has_archive($args, $post_type)
// {
// 	if ('post' == $post_type) {
// 		$args['rewrite'] = true;
// 		$args['has_archive'] = 'news';
// 		$args['menu_icon'] = 'dashicons-edit';
// 	}
// 	return $args;
// }
// add_filter('register_post_type_args', 'post_has_archive', 10, 2);
