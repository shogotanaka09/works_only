const fs = require('fs-extra')
const prompts = require('prompts')
const packageJson = require('./package.json')

const questions = [
  {
    type: 'text',
    name: 'app_name',
    message: 'プロジェクト名を入力してください。\n例) sample-app',
    validate: value => value.match(/^[a-z][a-z0-9\-_]*[a-z]$/) ? true : '入力形式が不正です。'
  },
  {
    type: 'text',
    name: 'wp_dir',
    message: 'public/以下のWordPressのインストールディレクトリを入力してください。\npublic直下にインストール場合は何も入力しないでください。\n例) /wordpress',
    initial: '',
    validate: value => value.match(/^\/[a-z][a-z0-9\-_/]*[a-z]$/) || value === '' ? true : '入力形式が不正です。'
  },
  {
    type: 'text',
    name: 'theme_name',
    message: 'テーマ名を入力してください。\n例) sample-company',
    validate: value => value.match(/^[a-z][a-z0-9\-_]*[a-z]$/) ? true : '入力形式が不正です。'
  },
  {
    type: 'text',
    name: 'admin_email',
    message: '管理者（開発者）のメールアドレスを入力してください。\n例) developer@zeroyon.jp',
    validate: value => value.match(/[\w\-._]+@[\w\-._]+\.[A-Za-z]+/) ? true : '入力形式が不正です。'
  },
  {
    type: 'number',
    name: 'local_server_port',
    message: '仮想環境のWebサーバー用ポート番号を入力してください。\n例) 15300',
    validate: value => value < 65536 && value > 9999 ? true : '10000〜65535の間で指定してください。'
  },
  {
    type: 'number',
    name: 'local_db_port',
    message: '仮想環境のDBサーバー用ポート番号を入力してください。\n例) 15306',
    validate: value => value < 65536 && value > 9999 ? true : '10000〜65535の間で指定してください。'
  }
]

  ; (async () => {
    try {
      const { app_name, wp_dir, theme_name, admin_email, local_server_port, local_db_port } = await prompts(questions)

      const { isExecutable } = await prompts({
        type: 'toggle',
        name: 'isExecutable',
        message: `
以下の内容でセットアップを行います。
よろしいですか？

プロジェクト名：${app_name}
WordPressインストールディレクトリ：${wp_dir}
テーマ名：${theme_name}
管理者メールアドレス：${admin_email}
ローカルサーバー用ポート番号：${local_server_port}
ローカルDB用ポート番号：${local_db_port}
    `,
        initial: false,
        active: 'yes',
        inactive: 'no'
      })

      if (!isExecutable) {
        console.log('中止しました。')
        return
      }

      const newPackageJson = { ...packageJson }
      newPackageJson.name = app_name
      newPackageJson.config.themeName = theme_name
      newPackageJson.config.wpDir = wp_dir
      delete newPackageJson.scripts.setup

      const env =
        `APP_NAME=${app_name}
WP_DIR=${wp_dir}
THEME_NAME=${theme_name}
ADMIN_EMAIL=${admin_email}
LOCAL_SERVER_PORT=${local_server_port}
LOCAL_DB_PORT=${local_db_port}

STAGING_URL=http://xxx.xxx.xxx.xxx:10002
STAGING_WP_DIR=/path/to/wpdir
STAGING_DB_NAME=wordpress
STAGING_DB_USER=wordpress
STAGING_DB_PASSWORD=wordpress
STAGING_DB_HOST=xxx.xxx.xxx.xxx
STAGING_DB_PORT=10002
STAGING_SSH_HOST=xxx.xxx.xxx.xxx
STAGING_SSH_PORT=22
STAGING_SSH_USER={username}

PRODUCTION_URL=httpx://sample-company.dummy
PRODUCTION_WP_DIR=/pass/to/wp
PRODUCTION_DB_NAME={db_name}
PRODUCTION_DB_USER={db_user}
PRODUCTION_DB_PASSWORD={db_password}
PRODUCTION_DB_HOST={db_host}
PRODUCTION_DB_PORT=3306
PRODUCTION_SSH_HOST=xxx.xxx.xxx.xxx
PRODUCTION_SSH_USER={username}
PRODUCTION_SSH_PORT=22`

      await fs.writeFile('package.json', JSON.stringify(newPackageJson, '', '  '))
      await fs.writeFile('.env', env)
      await fs.rename('public/wordpress/wp-content/themes/sample-project', `public/wordpress/wp-content/themes/${theme_name}`)

      // public/直下にインストールする場合はpublic/index.phpを削除する
      if(wp_dir === '') {
        await fs.remove('public/index.php')
      }

      if(wp_dir !== '/wordpress') {
        await fs.copy('public/wordpress', `public${wp_dir}`)
        await fs.remove('public/wordpress')
      }

      await fs.remove('setup.js')

      console.log('開発環境構築の準備が完了しました。\n続いてDockerコンテナの構築を行ってください。')
    } catch (error) {
      console.log('エラーが発生しました。')
      console.error(error)
    }
  })()
