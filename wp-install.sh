# WordPressのインストール
#=======================
wp core install \
--url=http://localhost:$LOCAL_SERVER_PORT \
--title=$APP_NAME \
--admin_user='wordpress' \
--admin_password='wordpress' \
--admin_email=$ADMIN_EMAIL \
--path=/var/www/html$WP_DIR \
--allow-root

# 一般設定
# =======================
# URLを変更
wp option update siteurl http://localhost:$LOCAL_SERVER_PORT$WP_DIR --path=/var/www/html$WP_DIR
wp option update home http://localhost:$LOCAL_SERVER_PORT --path=/var/www/html$WP_DIR

# キャッチフレーズの設定 (空にする)
wp option update blogdescription '' --path=/var/www/html$WP_DIR --allow-root

# 日本語化
wp language core install ja --activate --path=/var/www/html$WP_DIR --allow-root

# タイムゾーンと日時表記
wp option update timezone_string 'Asia/Tokyo' --path=/var/www/html$WP_DIR --allow-root
wp option update date_format 'Y-m-d' --path=/var/www/html$WP_DIR --allow-root
wp option update time_format 'H:i' --path=/var/www/html$WP_DIR --allow-root


# パーマリンク設定
# =======================
wp option update permalink_structure /blog/%postname%/ --path=/var/www/html$WP_DIR --allow-root


# 外観
# =======================
# テーマのアクティベート
wp theme activate $THEME_NAME --path=/var/www/html$WP_DIR --allow-root

# テーマの削除
wp theme delete twentytwenty --path=/var/www/html$WP_DIR --allow-root
wp theme delete twentytwentyone --path=/var/www/html$WP_DIR --allow-root
wp theme delete twentytwentytwo --path=/var/www/html$WP_DIR --allow-root


# プラグイン
# =======================
# プラグインの削除 (不要な初期プラグインを削除)
wp plugin delete hello.php --path=/var/www/html$WP_DIR --allow-root
wp plugin delete akismet --path=/var/www/html$WP_DIR --allow-root

# プラグインのインストール (必要に応じてコメントアウトを外す)
wp plugin install wp-multibyte-patch --path=/var/www/html$WP_DIR --activate --allow-root
# wp plugin install backwpup --path=/var/www/html$WP_DIR --activate --allow-root
# wp plugin install siteguard --path=/var/www/html$WP_DIR --activate --allow-root
# wp plugin install contact-form-7 --path=/var/www/html$WP_DIR --activate --allow-root
# wp plugin install wp-mail-smtp --path=/var/www/html$WP_DIR --activate --allow-root
# wp plugin install all-in-one-seo-pack --path=/var/www/html$WP_DIR --activate --allow-root
# wp plugin install broken-link-checker --path=/var/www/html$WP_DIR --activate --allow-root
# wp plugin install addquicktag --path=/var/www/html$WP_DIR --activate --allow-root
