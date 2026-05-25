<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Japanese language pack for theme_boost_union
 *
 * @package    theme_boost_union
 * @category   string
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * NOTE:
 * This file was translated by Toshimi Hatanaka (RubliX) using AI-assisted translation,
 * following Moodle core Japanese terminology and Boost Union context.
 */

defined('MOODLE_INTERNAL') || die();

// Let codechecker ignore some sniffs for this file as it is perfectly well ordered, just not alphabetically.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

// General.
$string['pluginname'] = 'Boost Union';
$string['choosereadme'] = 'Boost Union テーマは Boost を拡張した子テーマで、Boost をより柔軟に設定できるようにするとともに、管理者・教師・学生の日常的な Moodle 運用を支援する便利な機能を提供します。Boost Union は Moodle an Hochschulen e.V. が ssystems GmbH、bdecent GmbH、lern.link GmbH と協力してメンテナンスしています。';
$string['configtitle'] = 'Boost Union';
$string['githubissueslink'] = '<a href="https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues">Github の課題管理</a>';
$string['warningboostunioninactive'] = 'Boost Union（または Boost Union の子テーマ）が現在アクティブなテーマではありません。<em>この</em>ページで設定を変更しても、<a href="{$a->url}">Boost Union をアクティブテーマにする</a>か、カテゴリ／コース／ユーザ／コホートのテーマとして Boost Union の使用を許可しない限り、反映されません。';

// General select options.
$string['never'] = '使用しない';
$string['always'] = '常に使用する';
$string['auto'] = '自動';
$string['bycapability'] = 'ケイパビリティで制御';
$string['nochange'] = '変更しない';
$string['forguestsonly'] = 'ゲストおよび未ログインユーザのみ';
$string['showastext'] = 'テキストとして表示';
$string['showasbadge'] = 'バッジとして表示';
$string['imageposition_center_center'] = '中央（横）・中央（縦）';
$string['imageposition_center_top'] = '中央（横）・上（縦）';
$string['imageposition_center_bottom'] = '中央（横）・下（縦）';
$string['imageposition_left_top'] = '左（横）・上（縦）';
$string['imageposition_left_center'] = '左（横）・中央（縦）';
$string['imageposition_left_bottom'] = '左（横）・下（縦）';
$string['imageposition_right_top'] = '右（横）・上（縦）';
$string['imageposition_right_center'] = '右（横）・中央（縦）';
$string['imageposition_right_bottom'] = '右（横）・下（縦）';
$string['logininstructionposition_between'] = 'イントロとログインプロバイダの間';
$string['logininstructionposition_below'] = 'ログインプロバイダの下';
$string['logindividertypeoption_none'] = 'なし';
$string['logindividertypeoption_margin'] = '余白を追加';
$string['logindividertypeoption_line'] = '実線';
$string['logindividertypeoption_linewithor'] = '線（OR 付き）';
$string['buttoncolorprimaryfilled'] = 'プライマリ（塗りつぶし）';
$string['buttoncolorsecondaryfilled'] = 'セカンダリ（塗りつぶし）';
$string['buttoncolorprimaryoutline'] = 'プライマリ（アウトライン）';
$string['buttoncolorsecondaryoutline'] = 'セカンダリ（アウトライン）';
$string['bootstrap0to5_0'] = '0（なし）';
$string['bootstrap0to5_1'] = '1（極小）';
$string['bootstrap0to5_2'] = '2（小）';
$string['bootstrap0to5_3'] = '3（中）';
$string['bootstrap0to5_4'] = '4（大）';
$string['bootstrap0to5_5'] = '5（特大）';
$string['horizontalalignment_left'] = '左揃え';
$string['horizontalalignment_center'] = '中央揃え';
$string['horizontalalignment_right'] = '右揃え';

// Course overrides: General strings.
$string['courseoverride'] = 'コース設定にも追加する';
$string['courseoverride_desc'] = 'このセクションの Boost Union 設定は、ここで設定したグローバル設定を各コースで上書きできるようにするオプションを備えています。「コース設定にも追加する」にチェックを入れると、その設定項目がコース設定画面に追加され、教師・マネージャ・管理者（または theme/boost_union:overridecourseheaderincourse ケイパビリティを付与したロール）が、コースごとにグローバル設定を上書きできるようになります。チェックを入れない場合、その設定はコース設定画面に表示されず、常にグローバル設定が適用されます。';
$string['useglobaldefault'] = 'グローバルデフォルトを使用（{$a}）';
$string['nocourseoverride'] = 'この設定はコース設定で上書きできません。';

// Settings: General strings.
$string['dontchange'] = '変更しない';
$string['tertiarysettings'] = 'Boost Union 設定ページ一覧';
$string['settingoverridenotificationtitle'] = '設定の上書きが可能です';
$string['settingoverrideactioninfo'] = '設定の上書き方法を説明';
$string['settingoverrideactionflavours'] = 'フレーバーを管理';
$string['settingoverridemodallms'] = '<strong>フレーバー</strong><br />Boost Union のフレーバー機能を使うと、特定のコンテキストに応じて Moodle の外観設定を上書きできます。ここではグローバルのデフォルト設定を定義します。フレーバーを使うことで、特定のコンテキストやユーザグループごとに設定を分けることができます。「フレーバーを管理」アイコンをクリックすると、フレーバー管理ページに移動し、フレーバーを定義できます。';
$string['settingoverridelms'] = 'この設定は Boost Union のフレーバー内で上書きできます。';
$string['settingsupplementlms'] = 'この設定は Boost Union のフレーバー内で補足設定を追加できます。';

// Settings: General warnings.
$string['warningslashargumentsdisabled'] = '警告: 現在、Moodle の設定で <a href="{$a->url}">slasharguments</a> が無効になっています。しかし、この設定は以下の Boost Union の機能を正しく動作させるために必要です。slasharguments を有効にしない場合、以下の Boost Union の設定は反映されません。';

// Settings: Overview page.
$string['settingsoverview'] = '設定概要';
$string['settingsoverview_title'] = 'Boost Union 設定概要';
$string['settingsoverview_look_desc'] = 'Moodle サイトのブランド設定（色、アイコン、画像、サイズ、カスタム SCSS など）はここにあります。';
$string['settingsoverview_feel_desc'] = 'Moodle サイト全体の動作（ナビゲーション項目、ナビゲーション補助、ブロック、リンクなど）に関する設定はここにあります。';
$string['settingsoverview_content_desc'] = 'Moodle サイトのグローバルコンテンツ（フッター、静的ページ、情報バナー、広告タイル、スライダーなど）に関する設定はここにあります。';
$string['settingsoverview_functionality_desc'] = 'Moodle サイト全体またはコース関連の追加機能に関する設定はここにあります。';
$string['settingsoverview_accessibility_desc'] = 'アクセシビリティ関連の機能に関する設定はここにあります。';
$string['settingsoverview_flavours_desc'] = 'フレーバーを使うと、コホートやコースカテゴリごとに Moodle サイトの外観を変えることができます。';
$string['settingsoverview_snippets_desc'] = 'SCSS スニペットを使うと、追加のビジュアル効果や外観の微調整を有効にできます。';
$string['settingsoverview_smartmenus_desc'] = 'スマートメニューを使うと、メインメニューやユーザメニューを拡張したり、ボトムメニューやトップメニューを追加できます。';
$string['settingsoverview_recommendations_desc'] = '推奨設定では、Moodle コア、Boost Union、サードパーティプラグインの関連設定を提示し、より堅牢な Boost Union の構成を支援します。';
$string['settingsoverview_all'] = 'すべての設定を 1 ページで表示';
$string['settingsoverview_all_desc'] = 'ここから、Boost Union の標準的な Moodle カテゴリ設定ページを開き、すべての設定を 1 ページで確認できます。ただし、非常に多くの設定が含まれているため注意してください。';

// Settings: Look page.
$string['configtitlelook'] = '外観';

// Settings: General settings tab.
// ... Section: Theme presets.
$string['presetheading'] = 'テーマプリセット';
$string['presetheading_desc'] = 'テーマプリセットを使用すると、テーマの外観を大きく変更できます。Boost Union はテーマプリセット設定を再実装していません。テーマプリセットを使用したい場合は、Boost テーマ側で直接設定してください。Boost Union はそこで設定されたプリセットを継承して使用します。';
$string['presetbutton'] = 'Boost でテーマプリセットを設定';

// Settings: SCSS tab.
$string['scsstab'] = 'SCSS';
// ... Section: Raw SCSS.
$string['scssheading'] = 'Raw SCSS';

// ... Section: External SCSS.
$string['extscssheading'] = '外部 SCSS';
$string['extscssheading_desc'] = '上記の Raw SCSS に加えて、Boost Union は外部ソースから SCSS を読み込むことができます。外部 SCSS は Raw SCSS より前に読み込まれるため、集中管理された外部 SCSS を使いつつ、必要に応じてローカルの SCSS を追加できます。';
$string['extscssheading_instr'] = '手順:';
$string['extscssheading_drop'] = 'Boost Union が外部 SCSS ファイルを取得できない場合、SCSS のコンパイルエラーやフロントエンドの破損を避けるため、外部 SCSS は無視されます。';
$string['extscssheading_structure'] = '外部 SCSS は、ヘッダーやフッターのないプレーンテキスト形式で、SCSS コードのみを含む必要があります。';
$string['extscssheading_prepost'] = 'Raw SCSS と同様に、外部 SCSS も「Pre SCSS」と「Post SCSS」に分かれています。Pre SCSS は SCSS 変数の初期化に、Post SCSS は実際の SCSS コードに使用します。';
$string['extscssheading_sources'] = 'Boost Union は、外部 SCSS を「公開ダウンロード URL（認証なしの cURL で取得）」または「プライベート Github リポジトリ（Github API トークンで取得）」から読み込むよう設定できます。';
$string['extscssheading_task'] = '外部 SCSS を定期的に取得してコンパイルするための <a href="{$a}">スケジュールタスク theme_boost_union\task\purge_cache</a> があります（デフォルトでは無効）。必要に応じて有効化してください。';
$string['invalidurl'] = '指定された URL が無効です';
// ... ... Setting: External SCSS source.
$string['extscsssource'] = '外部 SCSS の取得元';
$string['extscsssource_desc'] = '外部 SCSS を取得するソースの種類を選択します。';
$string['extscsssourcenone'] = 'なし';
$string['extscsssourcedownload'] = '公開ダウンロード URL';
$string['extscsssourcegithub'] = 'プライベート Github リポジトリ';
// ... ... Setting: External Pre SCSS download URL.
$string['extscssurlpre'] = '外部 Pre SCSS ダウンロード URL';
$string['extscssurlpre_desc'] = 'Pre SCSS を取得する公開ダウンロード URL を指定します。';
// ... ... Setting: External Post SCSS download URL.
$string['extscssurlpost'] = '外部 Post SCSS ダウンロード URL';
$string['extscssurlpost_desc'] = 'Post SCSS を取得する公開ダウンロード URL を指定します。';
// ... ... Setting: External SCSS Github API token.
$string['extscssgithubtoken'] = '外部 SCSS 用 Github API トークン';
$string['extscssgithubtoken_desc'] = 'プライベート Github リポジトリから SCSS を取得する際に使用する Github API トークンです。';
$string['extscssgithubtoken_docs'] = '<a href="https://github.com/settings/tokens">Github トークン設定ページ</a>で API トークンを生成し、公式ドキュメントを確認できます。';
// ... ... Setting: External SCSS Github API user.
$string['extscssgithubuser'] = '外部 SCSS 用 Github API ユーザ';
$string['extscssgithubuser_desc'] = 'プライベート Github リポジトリを所有するユーザまたは組織名を指定します。';
$string['extscssgithubuser_example'] = '例: https://github.com/moodle-an-hochschulen/moodle-theme_boost_union-extscsstest/blob/main/extscss.scss にファイルがある場合、ユーザは <em>moodle-an-hochschulen</em> です。';
// ... ... Setting: External SCSS Github API repository.
$string['extscssgithubrepo'] = '外部 SCSS 用 Github API リポジトリ';
$string['extscssgithubrepo_desc'] = 'SCSS ファイルが配置されているプライベート Github リポジトリ名を指定します。';
$string['extscssgithubrepo_example'] = '例: https://github.com/moodle-an-hochschulen/moodle-theme_boost_union-extscsstest/blob/main/extscss.scss にファイルがある場合、リポジトリ名は <em>moodle-theme_boost_union-extscsstest</em> です。';
// ... ... Setting: External Pre SCSS Github file path.
$string['extscssgithubprefilepath'] = '外部 Pre SCSS の Github ファイルパス';
$string['extscssgithubprefilepath_desc'] = 'プライベート Github リポジトリ内で、Pre SCSS ファイルが配置されているパスを指定します。';
$string['extscssgithubfilepath_example'] = '例: https://github.com/moodle-an-hochschulen/moodle-theme_boost_union-extscsstest/blob/main/extscss.scss にファイルがある場合、ファイルパスは <em>/extscss.scss</em> です。';
// ... ... Setting: External Post SCSS Github file path.
$string['extscssgithubpostfilepath'] = '外部 Post SCSS の Github ファイルパス';
$string['extscssgithubpostfilepath_desc'] = 'プライベート Github リポジトリ内で、Post SCSS ファイルが配置されているパスを指定します。';
// ... ... Setting: External SCSS validation.
$string['extscssvalidationsetting'] = '外部 SCSS の検証';
$string['extscssvalidationsetting_desc'] = 'この設定を有効にすると、外部 SCSS が SCSS としてコンパイル可能かどうかを事前に検証します。コンパイルできない外部 SCSS は無視され、使用されません。ただし、この検証は外部 SCSS 単体に対してのみ行われ、Moodle コアや Bootstrap の SCSS 変数を使用している場合は検証に失敗します。その場合は検証を無効にし、フロントエンドが壊れないように SCSS の正当性を手動で確認する必要があります。';

// Settings: Page tab.
$string['pagetab'] = 'ページ';
// ... Section: Page width.
$string['pagewidthheading'] = 'ページ幅';
// ... ... Setting: Course content max width.
$string['coursecontentmaxwidthsetting'] = 'コースコンテンツの最大幅';
$string['coursecontentmaxwidthsetting_desc'] = 'この設定では、SCSS を手動で編集することなく、Moodle のコースコンテンツ幅を上書きできます。この幅はコースページや一部の活動内でページ幅として使用されます。デフォルトでは Moodle は 830px を使用しています。1200px のようなピクセル値のほか、100% のようなパーセンテージ値、90vw のようなビューポート幅も指定できます。';
// ... Section: Drawer width.
$string['drawerwidthheading'] = 'ドロワー幅';
// ... ... Setting: Course index drawer width.
$string['courseindexdrawerwidthsetting'] = 'コースインデックスドロワーの幅';
$string['courseindexdrawerwidthsetting_desc'] = 'この設定では、SCSS を手動で編集することなく、Moodle のコースインデックスドロワーの幅を上書きできます。デフォルトでは 285px が使用されています。320px のようなピクセル値は指定できますが、パーセンテージやビューポート幅（vw）などの単位は使用できません。';
// ... ... Setting: Block drawer width.
$string['blockdrawerwidthsetting'] = 'ブロックドロワーの幅';
$string['blockdrawerwidthsetting_desc'] = 'この設定では、SCSS を手動で編集することなく、Moodle のブロックドロワーの幅を上書きできます。デフォルトでは 315px が使用されています。400px のようなピクセル値は指定できますが、パーセンテージやビューポート幅（vw）などの単位は使用できません。';

// Settings: Site branding tab.
$string['sitebrandingtab'] = 'サイトブランディング';
// ... Section: Logos.
$string['logosheading'] = 'ロゴ';
// ... ... Setting: Logo.
$string['logosetting'] = 'ロゴ';
$string['logosetting_desc'] = 'ここでは、装飾用として使用するフルサイズのロゴ画像をアップロードできます。この画像は特にログインページで使用されます。高解像度の画像でも問題ありません。表示時に自動的に縮小されます。';
// ... ... Setting: Compact logo.
$string['logocompactsetting'] = 'コンパクトロゴ';
$string['logocompactsetting_desc'] = 'ここでは、上記ロゴのコンパクト版（エンブレム、シールド、アイコンなど）をアップロードできます。この画像は Moodle ページ上部のナビゲーションバーで使用されます。小さなサイズでも視認性が高い画像を推奨します。';
// ... Section: Favicon.
$string['faviconheading'] = 'ファビコン';
// ... ... Setting: Favicon
$string['faviconsetting'] = 'ファビコン';
$string['faviconsetting_desc'] = 'ここでは、ブラウザのタブに表示されるファビコン画像（.ico または .png）をアップロードできます。カスタムファビコンを設定しない場合、標準の Moodle ファビコンが使用されます。';
// ... Section: Background images.
$string['backgroundimagesheading'] = '背景画像（全体）';
// ... ... Setting: Background image
$string['backgroundimagesetting'] = '背景画像';
$string['backgroundimagesetting_desc'] = 'ここでは、サイト全体の背景として表示するカスタム画像をアップロードできます。ここで設定した背景画像は、テーマプリセット内の背景画像より優先されます。';
// ... ... Setting: Background image position
$string['backgroundimagepositionsetting'] = '背景画像の位置';
$string['backgroundimagepositionsetting_desc'] = '背景画像をブラウザウィンドウ内のどこに配置するかを指定します。最初の値が横方向、2つ目の値が縦方向の位置です。';
// ... Section: Brand colors.
$string['brandcolorsheading'] = 'ブランドカラー';
// ... ... Setting: Primary brand color.
$string['brandcolor'] = 'プライマリブランドカラー';
$string['brandcolor_desc'] = 'サイト全体のアクセントや強調表示に使用される主要なブランドカラーです。また、段階的なブランドカラーの生成にも使用されます。リンクやボタンの色も、下の設定で個別に指定しない限り、この色が使用されます。';
// ... ... Setting: Use branded gray tones.
$string['brandedgraytones'] = 'ブランドグレーを使用する';
$string['brandedgraytones_desc'] = 'この設定を有効にすると、テーマ全体で使用される Bootstrap のグレー系カラーが、ニュートラルグレーではなくプライマリブランドカラーを基調に生成されます。ページ全体のグレー要素に統一感が生まれます。※プライマリブランドカラーが設定されている場合のみ有効です。';
// ... Section: Link colors.
$string['linkcolorsheading'] = 'リンクカラー';
// ... ... Setting: Link brand color.
$string['linkcolorsetting'] = 'リンクのブランドカラー';
$string['linkcolorsetting_desc'] = 'リンク専用のブランドカラーを設定できます。未設定の場合、Boost Union のプライマリブランドカラーが使用されます。';
// ... ... Setting: Button brand color.
$string['buttonbrandcolorsetting'] = 'ボタンのブランドカラー';
$string['buttonbrandcolorsetting_desc'] = 'プライマリボタン専用のブランドカラーを設定できます。未設定の場合、Boost Union のプライマリブランドカラーが使用されます。';
// ... Section: Bootstrap colors.
$string['bootstrapcolorsheading'] = 'Bootstrap カラー';
// ... ... Setting: Bootstrap color for "Success".
$string['bootstrapcolorsuccesssetting'] = '「Success」の Bootstrap カラー';
$string['bootstrapcolorsuccesssetting_desc'] = '「Success」に使用される Bootstrap カラーです。';
// ... ... Setting: Bootstrap color for "Info".
$string['bootstrapcolorinfosetting'] = '「Info」の Bootstrap カラー';
$string['bootstrapcolorinfosetting_desc'] = '「Info」に使用される Bootstrap カラーです。';
// ... ... Setting: Bootstrap color for "Warning".
$string['bootstrapcolorwarningsetting'] = '「Warning」の Bootstrap カラー';
$string['bootstrapcolorwarningsetting_desc'] = '「Warning」に使用される Bootstrap カラーです。';
// ... ... Setting: Bootstrap color for "Danger".
$string['bootstrapcolordangersetting'] = '「Danger」の Bootstrap カラー';
$string['bootstrapcolordangersetting_desc'] = '「Danger」に使用される Bootstrap カラーです。';
// ... Section: Navbar.
$string['navbarheading'] = 'ナビバー';
// ... Section: Maximal width of logo in navbar.
$string['maxlogowidth'] = 'ナビバー内ロゴの最大幅';
$string['maxlogowidth_desc'] = 'ナビバーでは、アップロードしたコンパクトロゴは通常、高さ 100%・幅は比率維持で表示されます。ロゴが横長すぎる場合や特殊な比率の場合、ここで最大幅を指定できます。120px のようなピクセル値、10% のようなパーセンテージ、5vw のようなビューポート幅が使用できます。未設定の場合はデフォルトの表示になります。';
$string['maxsitenamewidth'] = 'ナビバー内サイト名の最大幅';
$string['maxsitenamewidth_desc'] = 'サイト名が非常に長い場合、ナビバーのレイアウト（特に編集ボタン）を崩さないよう、ここで最大幅を指定できます。指定幅を超える場合、サイト名は省略記号（…）で切り詰められます。200px のようなピクセル値、20% のようなパーセンテージ、15vw のようなビューポート幅が使用できます。未設定の場合はフル幅で表示されます。';
// ... ... Setting: Navbar color.
$string['navbarcolorsetting'] = 'ナビバーの色';
$string['navbarcolorsetting_desc'] = 'ナビバーの色を、デフォルトのライト、ダーク、またはカラーバーに変更できます。';
$string['navbarcolorsetting_light'] = 'ライト（文字色はダーク）※Moodle コアのデフォルト';
$string['navbarcolorsetting_dark'] = 'ダーク（文字色はライト）';
$string['navbarcolorsetting_coloreddark'] = 'カラーバー（文字色はライト）';
$string['navbarcolorsetting_coloredlight'] = 'カラーバー（文字色はダーク）';
// ... ... Setting: Navbar tint.
$string['navbartintsetting'] = 'ナビバーの色合い';
$string['navbartintsetting_desc'] = 'カラーナビバーの色を指定します。上記で「カラーバー」を選択した場合のみ有効です。未設定の場合、プライマリブランドカラーが使用されます。';

// Settings: Activity branding tab.
$string['activitybrandingtab'] = 'アクティビティブランディング';
// ... Section: Activity icon colors.
$string['activityiconcolorsheading'] = 'アクティビティアイコンの色';
// ... ... Setting: Activity icon color for 'Administration'.
$string['activityiconcoloradministrationsetting'] = '「管理」アクティビティのアイコン色';
$string['activityiconcoloradministrationsetting_desc'] = '「管理」カテゴリのアクティビティに使用されるアイコン色です。';
// ... ... Setting: Activity icon color for 'Assessment'.
$string['activityiconcolorassessmentsetting'] = '「評価」アクティビティのアイコン色';
$string['activityiconcolorassessmentsetting_desc'] = '「評価」カテゴリのアクティビティに使用されるアイコン色です。';
// ... ... Setting: Activity icon color for 'Collaboration'.
$string['activityiconcolorcollaborationsetting'] = '「協働」アクティビティのアイコン色';
$string['activityiconcolorcollaborationsetting_desc'] = '「協働」カテゴリのアクティビティに使用されるアイコン色です。';
// ... ... Setting: Activity icon color for 'Communication'.
$string['activityiconcolorcommunicationsetting'] = '「コミュニケーション」アクティビティのアイコン色';
$string['activityiconcolorcommunicationsetting_desc'] = '「コミュニケーション」カテゴリのアクティビティに使用されるアイコン色です。';
// ... ... Setting: Activity icon color for 'Content'.
$string['activityiconcolorcontentsetting'] = '「コンテンツ」アクティビティのアイコン色';
$string['activityiconcolorcontentsetting_desc'] = '「コンテンツ」カテゴリのアクティビティに使用されるアイコン色です。';
// ... ... Setting: Activity icon color for 'Interactive content'.
$string['activityiconcolorinteractivecontentsetting'] = '「インタラクティブコンテンツ」アクティビティのアイコン色';
$string['activityiconcolorinteractivecontentsetting_desc'] = '「インタラクティブコンテンツ」カテゴリのアクティビティに使用されるアイコン色です。';
// ... ... Setting: Activity icon color for 'Interface'.
$string['activityiconcolorinterfacesetting'] = '「インターフェース」アクティビティのアイコン色';
$string['activityiconcolorinterfacesetting_desc'] = '「インターフェース」カテゴリのアクティビティに使用されるアイコン色です。';
// ... Section: Activity icon purposes.
$string['activitypurposeheading'] = 'アクティビティ目的別のアイコン色';
$string['activitypurposeheading_desc'] = 'ここでは、アクティビティの「目的」に基づいて設定されている背景色（各アクティビティプラグインにハードコードされている色）を上書きできます。';
$string['activitypurposeheadingpleasenote'] = '注意: コース内のアクティビティ一覧ページでは、ブックなどのリソース系アクティビティは「リソース」カテゴリにまとめて表示されます。そのため、個別のリソースタイプの目的色を変更する必要はありません。「リソース」カテゴリの色を変更するだけで適用されます。';
$string['activitypurposeheadingtechnote'] = '技術メモ: Moodle コアではアクティビティ目的と色がハードコードされているため、Boost Union は CSS によって目的色を上書きしています。Moodle コアのほとんどの画面で目的色は上書きされますが、もし未対応の画面やサードパーティプラグインがあれば {$a} に報告してください。';
$string['activitypurposeadministration'] = '管理';
$string['activitypurposeassessment'] = '評価';
$string['activitypurposecollaboration'] = '協働';
$string['activitypurposecommunication'] = 'コミュニケーション';
$string['activitypurposecontent'] = 'コンテンツ';
$string['activitypurposeinteractivecontent'] = 'インタラクティブコンテンツ';
$string['activitypurposeinterface'] = 'インターフェース';
$string['activitypurposeother'] = 'その他';
// ... Section: Activity icons.
$string['modiconsheading'] = 'アクティビティアイコン';
// ... ... Setting: Enable custom icons for activities and resources.
$string['modiconsenablesetting'] = 'アクティビティ／リソースのカスタムアイコンを有効にする';
$string['modiconsenablesetting_desc'] = 'この設定では、コースページやアクティビティ選択画面で使用されるアクティビティ／リソースのアイコンをカスタマイズできます。';
// ... ... Setting: Custom icon files.
$string['modiconsfiles'] = 'カスタムアイコンファイル';
$string['modiconsfiles_desc'] = 'ここでは、インストールされているアクティビティモジュールのカスタムアイコンをアップロードできます。';
$string['modiconsfileshowto'] = '特定のアクティビティのカスタムアイコンをアップロードするには、まずアクティビティの内部名（例: 課題なら <em>assign</em>）のフォルダを作成します。そのフォルダに、Moodle 4 用のアイコンとして monologo.svg（可能なら monologo.png も）をアップロードします。Moodle 3 時代のカラーアイコンをカスタマイズしたい場合は、icon.svg / icon.png をアップロードできます。ただし、可能であれば単色 SVG アイコンを推奨します。設定を保存すると、アップロードされたファイルの一覧が表示され、正しくアップロードされたか確認できます。';
$string['modiconsfilestech'] = '技術メモ: 設定を保存すると、アップロードしたフォルダ構造とアイコンファイルは Moodledata ディレクトリ内の pix_plugins/mod フォルダにコピーされます。Moodle コアはこの場所をカスタムアイコンの検索に使用します。既存のアイコンファイルは上書きされます。';
$string['modiconserrorcreatingpath'] = 'Moodledata ディレクトリ内に pix_plugins/mod フォルダを作成できませんでした。<br />例外メッセージ: {$a}';
// ... ... Information: Custom icons files list.
$string['modiconlistsetting'] = 'カスタムアイコンファイル一覧';
$string['modiconlistsetting_desc'] = 'ここには、上記のファイルエリアにアップロードされたカスタムアイコンファイルが一覧表示されます。有効なアイコンファイルに加え、無効なファイルは「破損ファイル」として表示されます。';
$string['modiconsuccess4x'] = 'このアイコンは <em>{$a}</em> アクティビティの Moodle 4 アイコンとして使用されます。';
$string['modiconsuccess3x'] = 'このアイコンは <em>{$a}</em> アクティビティの Moodle 3 レガシーアイコンとして使用されます。';
$string['modiconnamefail'] = 'このファイルは <em>{$a}</em> アクティビティ用の正しいフォルダにアップロードされていますが、ファイル名が無効です。Moodle 4 用は <em>monologo.svg</em> / <em>monologo.png</em>、Moodle 3 用は <em>icon.svg</em> / <em>icon.png</em> に変更してください。';
$string['modiconnotexist'] = 'このファイルは不適切な場所にアップロードされており、ファイルパス <em>{$a}</em> からアクティビティを特定できません。';
$string['modiconactivity'] = 'アクティビティ';
$string['modiconactivityunknown'] = '不明';
$string['modiconversion'] = 'アイコンバージョン';
$string['modicongtmoodle4'] = 'Moodle 4 アイコン';
$string['modiconltmoodle311'] = 'Moodle 3 レガシーアイコン';

// Settings: Calendar branding tab.
$string['calendarbrandingtab'] = 'カレンダーブランディング';
// Placeholders: Calendar event types.
$string['calendareventtypecategory'] = 'カテゴリ';
$string['calendareventtypecourse'] = 'コース';
$string['calendareventtypegroup'] = 'グループ';
$string['calendareventtypeother'] = 'その他';
$string['calendareventtypesite'] = 'サイト';
$string['calendareventtypeuser'] = 'ユーザ';
// ... Section: Event types.
$string['calendareventcolorsheading'] = 'カレンダーイベントタイプ: {$a}';
// ... ... Setting: Main color of the calendar event.
$string['calendareventcolormainsetting'] = 'イベントタイプ「{$a}」のメインカラー';
$string['calendareventcolormainsetting_desc'] = 'イベントタイプ「{$a}」に使用されるメインカラーです。アイコンや背景色として使用されます。';
// ... ... Setting: Border color of the calendar event.
$string['calendareventcolorbordersetting'] = 'イベントタイプ「{$a}」の枠線カラー';
$string['calendareventcolorbordersetting_desc'] = 'イベントタイプ「{$a}」に使用される枠線の色です。';
// ... Section: General calendar branding.
$string['calendarbrandingheading'] = 'カレンダー全体のブランディング';
// ... ... Setting: Calendar icon colors.
$string['calendariconscolorsetting'] = 'カレンダーアイコンの色';
$string['calendariconscolorsetting_desc'] = 'カレンダービューで使用される一部のアイコンの色を設定します。デフォルトは青ですが、上で設定したイベントタイプの色と競合する場合があります。';

// Settings: Login page tab.
$string['loginpagetab'] = 'ログインページ';
// ... Section: Login page arrangement.
$string['loginarrangementheading'] = 'ログインページのレイアウト';
// ... ... Setting: Login page arrangement.
$string['loginarrangementsetting'] = 'ログインページのレイアウト';
$string['loginarrangementsetting_desc'] = 'この設定では、ログインページ全体のレイアウトを制御できます。デフォルトでは、Moodle 5.2 以降と同様に、ログインコンテナが片側、背景画像がもう片側に表示されるスプリットスクリーン形式です。代わりに、Moodle 5.1 以前で使用されていたレガシーレイアウト（背景画像の上にログインコンテナを重ねる形式）を選択することもできます。なお、レガシーレイアウトは Boost Union 内で再現されたものであり、Moodle 5.1 と完全に同一のコードではありません。';
$string['loginarrangementsetting_sidebyside'] = '左右に並べる（スプリットスクリーン）';
$string['loginarrangementsetting_legacy'] = 'レガシー（背景画像の上にログインコンテナ）';
// ... ... Setting: Login container position.
// These strings do not fully match the setting name as the setting was renamend during its lifetime, but the string IDs were keps to ease the life of the translators.
$string['loginformpositionsetting'] = 'ログインコンテナの位置';
$string['loginformpositionsetting_desc'] = 'この設定では、背景画像とのバランスを最適化するためにログインコンテナの位置を調整できます。デフォルトでは中央に配置されますが、背景画像の見せ方に応じて左寄せ・右寄せに変更できます。背景画像を設定していない場合でも使用できます。';
$string['loginformpositionsetting_center'] = '中央';
$string['loginformpositionsetting_left'] = '左寄せ';
$string['loginformpositionsetting_right'] = '右寄せ';
// ... ... Setting: Login container transparency.
// These strings do not fully match the setting name as the setting was renamend during its lifetime, but the string IDs were keps to ease the life of the translators.
$string['loginformtransparencysetting'] = 'ログインコンテナの透明度';
$string['loginformtransparencysetting_desc'] = 'この設定では、ログインコンテナを半透明にして背景画像をより見せることができます。';
// ... ... Setting: Login container width.
$string['logincontainerwidthsetting'] = 'ログインコンテナの幅';
$string['logincontainerwidthsetting_desc'] = 'この設定では、Moodle の固定幅 500px を上書きできます。600px のようなピクセル値のほか、90% のようなパーセンテージ値、50vw のようなビューポート幅も指定できます。';
$string['logincontainerwidthsetting_note'] = '注意: タブ形式のログインフォームを使用している場合、ログインプロバイダが多い、またはタブのラベルが長いと、ログインコンテナが設定した幅より広がることがあります。これは、すべてのタブを横並びに表示するために必要な動作です。';
// ... Section: Login page background images.
$string['loginbackgroundimagesheading'] = 'ログインページの背景画像';
// ... ... Setting: Login page background image.
$string['loginbackgroundimage'] = 'ログインページの背景画像';
$string['loginbackgroundimage_desc'] = 'ログインページの背景として表示する画像です。複数アップロードした場合、ページ訪問時にランダムで 1 枚が選ばれます。背景画像にテキストを表示したい場合、ファイル名に非 ASCII 文字を使用しないでください。';
// ... ... Setting: Login page background image position.
$string['loginbackgroundimagepositionsetting'] = '背景画像の位置';
$string['loginbackgroundimagepositionsetting_desc'] = '背景画像をブラウザウィンドウ内のどこに配置するかを指定します。最初の値が横方向、2つ目の値が縦方向です。';
// ... ... Setting: Login page background image text.
$string['loginbackgroundimagetextsetting'] = '背景画像に表示するテキスト';
$string['loginbackgroundimagetextsetting_desc'] = 'この任意設定では、アップロードしたログイン背景画像に対して、著作権表記などのテキストを追加できます。このテキストはログインページのフッター上部に表示されます。ただし、画面スペースの都合上、大きな画面サイズでのみ表示されます。<br/>
各行は「ファイル識別子（ファイル名）｜表示したいテキスト｜テキストカラー」をパイプ記号で区切って記述します。各宣言は 1 行ずつ記述してください。<br/>
例：<br/>
background-image-1.jpg|Copyright: CC0|dark<br/>
テキストカラーには "dark" または "light" を使用できます。<br />
アップロードしたログイン背景画像が複数ある場合、それぞれ任意の数だけテキストを宣言できます。テキストは、この設定で指定した識別子（ファイル名）と一致する画像にのみ適用されます。';
// ... Section: Login page branding.
$string['loginbrandingheading'] = 'ログインページのブランディング';
// ... ... Setting: Login page brand.
$string['loginpagebranding'] = 'ログインページのブランド表示';
$string['loginpagebranding_desc'] = 'この設定では、ログインページにどのブランディング要素を表示するかを制御できます。選択内容に応じて、ロゴ（アップロード済みの場合）、サイト見出し、サイトのタグラインが表示されます。';
$string['loginpagebrand_logoheadingtagline'] = 'ロゴ（アップロード済み）+ 見出し + タグライン';
$string['loginpagebrand_logoheading'] = 'ロゴ（アップロード済み）＋ 見出し';
$string['loginpagebrand_logotagline'] = 'ロゴ（アップロード済み）＋ タグライン';
$string['loginpagebrand_headingtagline'] = '見出し + タグライン';
$string['loginpagebrand_heading'] = '見出しのみ';
$string['loginpagebrand_tagline'] = 'タグラインのみ';
// ... ... Setting: Login page heading.
$string['loginpageheadingsetting'] = 'ログインページの見出し';
$string['loginpageheadingsetting_desc'] = 'この設定では、ログインページに表示する見出しテキストを制御できます。';
$string['loginpageheadingsetting_options'] = 'サイト名の表示方法を複数のバリエーションから選択できます。また、シンプルな挨拶メッセージを選ぶこともできます。「Welcome! / Welcome back!」オプションは、ユーザが再訪者かどうかを判定し、適切な挨拶を表示します。';
// ... ... Setting: Login page tagline text.
$string['loginpagetaglinesetting'] = 'ログインページのタグライン';
$string['loginpagetaglinesetting_desc'] = 'この設定では、ログインページに表示するタグライン（短い説明文）を指定できます。';
// ... ... Options for login page heading and tagline settings.
$string['loginpagelabel_welcome'] = 'Welcome!';
$string['loginpagelabel_welcomeback'] = 'Welcome back!';
$string['loginpagelabel_welcometo'] = '{$a} へようこそ';
// ... ... Setting: Login logo max width.
$string['loginlogomaxwidthsetting'] = 'ログインロゴの最大幅';
$string['loginlogomaxwidthsetting_desc'] = 'この設定では、ログインページに表示されるロゴの最大幅を指定できます。120px のようなピクセル値、10% のようなパーセンテージ値が使用できます。未設定の場合、ロゴは比率を保ったまま自動調整されます。';
// ... ... Setting: Login logo max height.
$string['loginlogomaxheightsetting'] = 'ログインロゴの最大高さ';
$string['loginlogomaxheightsetting_desc'] = 'この設定では、ログインページに表示されるロゴの最大高さを指定できます。120px のようなピクセル値、10% のようなパーセンテージ値が使用できます。未設定の場合、ロゴは比率を保ったまま自動調整されます。';
// ... ... Setting: Login logo alignment.
$string['loginlogoalignmentsetting'] = 'ログインロゴの配置';
$string['loginlogoalignmentsetting_desc'] = 'この設定では、ログインページに表示されるロゴの水平方向の配置を制御できます。';
// ... ... Setting: Login logo margin bottom.
$string['loginlogomarginbottomsetting'] = 'ログインロゴの下マージン';
$string['loginlogomarginbottomsetting_desc'] = 'この設定では、ロゴコンテナに Bootstrap のスペーシングクラス（mb-0 ～ mb-5）を追加して、ロゴ下の余白を調整できます。ロゴ周辺の縦方向の空白を最適化するのに役立ちます。';
// ... Section: Login form layout.
$string['loginlayoutheading'] = 'ログインフォームのレイアウト';
// ... ... Setting: Login form layout.
$string['loginlayoutsetting'] = 'ログインフォームのレイアウト';
$string['loginlayoutsetting_desc'] = 'この設定では、ログインページにログインプロバイダ（ローカルログイン、IDP、ゲスト、セルフ登録など）をどのように表示するかを制御できます。縦並び、タブ形式、アコーディオン形式から選択できます。';
$string['loginlayoutvertical'] = '縦並び（すべてを上下に表示）';
$string['loginlayouttabs'] = 'タブ（横並びのタブで切り替え）';
$string['loginlayoutaccordion'] = 'アコーディオン（折りたたみ式、クリックで展開）';
// ... ... Setting: Enhanced tabs layout behaviour.
$string['loginenhancedtabslayoutsetting'] = 'タブレイアウトの拡張動作';
$string['loginenhancedtabslayoutsetting_desc'] = 'タブレイアウトは、ログインコンテナ幅の設定で指定された幅を基本的に尊重し、他のログインフォームレイアウトと同様に、ログインコンテナは縦方向中央に配置されます。しかし、これだけでは十分でない場合があります。たとえば、タブごとの内容の高さが大きく異なり、タブを切り替えるたびに上下へ跳ねるように見えてしまう場合があります。また、非常に長いタブラベルによってログインコンテナが横方向に広がる一方で、複数行のログイン案内文がそれに追従しない場合もあります。<br/>これらは CSS だけではきれいに解決できないケースであり、そのような場合に備えて、ログインページ読み込み後に JavaScript モジュールを動作させ、ログイン関連コンテンツが可能な限り適切に表示・配置されるよう調整することができます。';
// ... Section: Login instructions.
$string['logininstructionsheading'] = 'ログイン案内文';
$string['logininstructionsheading_desc'] = '注意: Boost Union は独自のログイン案内文設定を使用しており、<a href="{$a}">Moodle コアの認証説明文設定</a> の内容は出力しません。';
$string['logininstructionsabove'] = 'ログインプロバイダ一覧の上に表示する案内文';
$string['logininstructionsabove_desc'] = 'この設定では、ログインページのログインプロバイダ一覧の上に表示する案内文を追加できます。すべてのログイン方法に共通する一般的な説明を記載するのに適しています。';
$string['logininstructionsbelow'] = 'ログインプロバイダ一覧の下に表示する案内文';
$string['logininstructionsbelow_desc'] = 'この設定では、ログインページのログインプロバイダ一覧の下に表示する案内文を追加できます。追加情報やサポート窓口など、全ログイン方法に共通する補足説明を記載するのに適しています。';
// ... Section: Login order.
$string['loginorderheading'] = 'ログイン順序';
$string['loginorderheading_desc'] = 'この設定では、ログインフォームに表示されるログインプロバイダの順序を制御します。Boost Union で無効化されているログインプロバイダやログイン要素はスキップされ、設定された序数（小さい数 → 大きい数）の順に表示されます。';
// ... ... Settings: Login order.
$string['loginorderlocalsetting'] = 'ローカルログイン';
$string['loginorderidpsetting'] = 'IDP ログイン';
$string['loginorderfirsttimesignupsetting'] = 'セルフ登録';
$string['loginorderguestsetting'] = 'ゲストログイン';
// ... ... Setting: Primary login provider.
$string['primaryloginsetting'] = '優先ログインプロバイダ';
$string['primaryloginsetting_desc'] = 'この設定では、ページ読み込み時にどのログインプロバイダを最初に開くかを指定できます。この設定はタブレイアウトおよびアコーディオンレイアウトにのみ適用されます。「なし」を選択した場合、タブレイアウトではログイン順序設定で最も序数が小さいプロバイダが自動的に開かれ、アコーディオンレイアウトではどのプロバイダも自動的には開かれません。';
// ... Section: Login provider: Local.
$string['loginproviderlocalheading'] = 'ログインプロバイダ: ローカル';
// ... ... Setting: Local login.
$string['loginlocalloginenablesetting'] = 'ローカルログイン';
$string['loginlocalloginenablesetting_desc'] = 'この設定では、ログインページにローカルログインフォーム（ユーザ名＋パスワード）を表示するかどうかを制御します。デフォルトでは表示され、通常どおり Moodle アカウントでログインできます。この設定を無効にすると、ローカルログインは非表示となり、OAuth2 や OIDC など外部 ID プロバイダのログインボタンのみを表示できます。';
$string['loginlocalloginenablesetting_core'] = 'Moodle コア設定との関係: Boost Union は <a href="{$a->url}">Moodle コアの認証設定</a> にある「{$a->settingname}」を処理しません。ローカルログインの有効／無効は、Boost Union のこの設定のみで制御されます。';
$string['loginlocalloginenablesetting_note'] = '注意: ローカルログインを非表示にすると、外部 ID プロバイダに問題が発生した場合、管理者がログインできなくなるリスクがあります。また、手動認証以外の認証方法が無効な場合も同様です。<br />このような状況でもローカルログインを可能にするため、Boost Union は自動的に <a href="{$a->url}">サイドエントランスのローカルログインページ</a> を有効にします。安全のため、この URL をブックマークしておくことを推奨します。';
$string['loginlocalloginformhead'] = 'ローカルログイン';
$string['loginlocalloginlocalnotdisabled'] = 'このサイドエントランスログインページでログインする必要はありません。通常のログインには <a href="{$a->url}">標準のログインページ</a> を使用してください。';
// ... ... Setting: Local login intro.
$string['loginlocalshowintrosetting'] = 'ローカルログインのイントロ';
$string['loginlocalshowintrosetting_desc'] = 'この設定では、ローカルログインフォームの上にイントロテキストを表示するかどうかを制御します。デフォルトでは非表示ですが、複数のログイン方法を提供している場合、ユーザがどの資格情報を使うべきか理解しやすくなります。';
$string['loginlocalintro'] = 'Moodle アカウントでログイン';
// ... ... Setting: Local login intro text.
$string['loginlocalintrotextsetting'] = 'ローカルログインのイントロテキスト';
$string['loginlocalintrotextsetting_desc'] = 'この設定では、デフォルトのイントロテキスト <em>\'{$a}\'</em> をカスタムテキストで上書きできます。空欄の場合はデフォルトが使用されます。';
// ... ... Setting: Local login tab label.
$string['loginlocalloginlabelsetting'] = 'ローカルログインのラベル';
$string['loginlocalloginlabelsetting_desc'] = 'この設定では、タブレイアウトおよびアコーディオンレイアウトで使用されるローカルログインのラベルをカスタマイズできます。';
$string['loginlocalloginlabelsetting_default'] = 'Moodle アカウント';
// ... ... Setting: Local login instruction.
$string['loginlocalshowinstruction'] = 'ローカルログインの案内文';
$string['loginlocalshowinstruction_desc'] = 'この設定では、ローカルログインプロバイダに案内文を表示するかどうかを制御します。';
$string['loginlocalinstructioncontent'] = 'ローカルログインの案内文内容';
$string['loginlocalinstructioncontent_desc'] = 'この設定では、ローカルログイン用の案内文をカスタムテキストで指定できます。ユーザにログイン方法をより詳しく説明したい場合に便利です。';
$string['loginlocalinstructionposition'] = 'ローカルログイン案内文の表示位置';
$string['loginlocalinstructionposition_desc'] = 'この設定では、案内文をログインフォームのどこに表示するかを指定できます。';
// ... ... Setting: Local login button color.
$string['loginlocalbuttoncolorsetting'] = 'ローカルログインボタンの色';
$string['loginlocalbuttoncolorsetting_desc'] = 'この設定では、ローカルログインボタンの Bootstrap カラースタイルを指定できます。';
// ... ... Setting: Local login divider type.
$string['loginlocaldividertypesetting'] = 'ローカルログインの区切り線タイプ';
$string['loginlocaldividertypesetting_desc'] = 'この設定では、縦レイアウトにおいてローカルログインの前に表示される区切り線の種類を指定できます。';
$string['logindividertypefirstmethodnote'] = '注意: 区切り線は縦レイアウトでのみ表示されます。また、ログイン順序で最初に表示されるログイン方法の前には、どの設定を選んでも区切り線は表示されません。';
// ... Section: Login provider: IDP.
$string['loginprovideridpheading'] = 'ログインプロバイダ: IDP（外部認証）';
// ... ... Setting: IDP login.
$string['loginidploginenablesetting'] = 'IDP ログイン';
$string['loginidploginenablesetting_desc'] = 'この設定では、ログインページに外部 ID プロバイダ（IDP）のログインボタンを表示するかどうかを制御します。デフォルトでは、IDP が設定されている場合に表示されます。この設定を無効にすると、認証プラグインの設定に関係なく、すべての IDP ログインボタンが非表示になります。';
$string['loginidploginenablesetting_core'] = 'Moodle コア設定との関係: IDP ログインボタンは OAuth2、CAS、Shibboleth などの認証プラグインによって提供されます。これらのプラグインは <a href="{$a->url}">Moodle コアの認証設定ページ</a> で管理できます。';
// ... ... Setting: IDP login intro.
$string['loginidpshowintrosetting'] = 'IDP ログインのイントロ';
$string['loginidpshowintrosetting_desc'] = 'この設定では、IDP ログインボタンの上にイントロテキストを表示するかどうかを制御します。デフォルトでは非表示ですが、有効にするとユーザに外部ログインの意味を説明するのに役立ちます。';
$string['loginidpintro'] = 'IDP アカウントでログイン';
// ... ... Setting: IDP login intro text.
$string['loginidpintrotextsetting'] = 'IDP ログインのイントロテキスト';
$string['loginidpintrotextsetting_desc'] = 'この設定では、デフォルトのイントロテキスト <em>\'{$a}\'</em> をカスタムテキストで上書きできます。空欄の場合はデフォルトが使用されます。';
// ... ... Setting: IDP login tab label.
$string['loginidploginlabelsetting'] = 'IDP ログインのラベル';
$string['loginidploginlabelsetting_desc'] = 'この設定では、タブレイアウトおよびアコーディオンレイアウトで使用される IDP ログインのラベルをカスタマイズできます。';
$string['loginidploginlabelsetting_default'] = 'IDP ログイン';
// ... ... Setting: IDP login instruction.
$string['loginidpshowinstruction'] = 'IDP ログインの案内文';
$string['loginidpshowinstruction_desc'] = 'この設定では、IDP ログインプロバイダに案内文を表示するかどうかを制御します。';
$string['loginidpinstructioncontent'] = 'IDP ログインの案内文内容';
$string['loginidpinstructioncontent_desc'] = 'この設定では、IDP ログインに関する追加説明をカスタムテキストで指定できます。外部認証の仕組みをユーザに説明したい場合に便利です。';
$string['loginidpinstructionposition'] = 'IDP ログイン案内文の表示位置';
$string['loginidpinstructionposition_desc'] = 'この設定では、案内文をログインボタンのどこに表示するかを指定できます。';
// ... ... Setting: IDP login button color.
$string['loginidpbuttoncolorsetting'] = 'IDP ログインボタンの色';
$string['loginidpbuttoncolorsetting_desc'] = 'この設定では、IDP ログインボタンの Bootstrap カラースタイルを指定できます。';
// ... ... Setting: IDP login divider type.
$string['loginidpdividertypesetting'] = 'IDP ログインの区切り線タイプ';
$string['loginidpdividertypesetting_desc'] = 'この設定では、縦レイアウトにおいて IDP ログインの前に表示される区切り線の種類を指定できます。';
// ... Section: Login provider: IDP (Expert settings).
$string['loginprovideridpexpertheading'] = 'ログインプロバイダ: IDP（上級設定）';
// ... ... Setting: Split per identity provider.
$string['loginidpsplitsetting'] = 'IDP ごとに分割表示';
$string['loginidpsplitsetting_desc'] = 'この設定を有効にすると、各 ID プロバイダがそれぞれ独立したタブ、アコーディオンパネル、またはセクションとして表示されます。タブ／アコーディオンレイアウトでは、プロバイダ名がラベルとして使用されます。イントロテキストや表示設定は各パネルに適用されます。無効にすると、すべての IDP は 1 つのタブ／パネル／セクションにまとめて表示されます。';
// ... ... Setting: Use internal Shibboleth WAYF.
$string['loginshibbolethinternalwayfsetting'] = '内部 Shibboleth WAYF を使用';
$string['loginshibbolethinternalwayfsetting_desc'] = 'この設定では、Shibboleth ログインボタンを内部 WAYF（Where Are You From）フォームに置き換えることができます。ログインページから離れずに組織選択を行えるため、Shibboleth ユーザにとってよりスムーズなログイン体験を提供します。<br />「Yes（auth_shibboleth の設定に基づく）」を選択すると、<a href="{$a->loginurl}">Shibboleth 認証プラグインの内部ログインページ</a> と同じ組織選択 UI が表示されます。組織リストは <a href="{$a->settingsurl}">Shibboleth 認証プラグインの「Identity providers」設定</a> から取得されます。リストが空、または Shibboleth 認証が無効な場合、この設定は無効化され、通常の IDP ボタンが表示されます。<br />「Yes（埋め込み JavaScript コードに基づく）」を選択すると、WAYF の動作を独自の JavaScript で実装できます。選択された IdP に応じて正しい URL にリダイレクトできるコードを記述する必要があります。特に <a href="https://help.switch.ch/aai/guides/discovery/embedded-wayf/">SWITCH AAI</a> を利用する Moodle サイト向けに公式コードが提供されています。';
$string['loginshibbolethinternalwayfsettingconfig'] = 'Yes（auth_shibboleth の設定に基づく）';
$string['loginshibbolethinternalwayfsettingcode'] = 'Yes（埋め込み JavaScript コードに基づく）';
// ... ... Setting: Internal WAYF JavaScript code.
$string['internalshibbolethwayfcodesetting'] = '内部 WAYF の JavaScript コード';
$string['internalshibbolethwayfcodesetting_desc'] = 'この設定では、Shibboleth の WAYF UI をログインページに直接表示するための JavaScript コードを埋め込むことができます。必要な HTML 要素やイベントハンドラを含む完全なコードを記述する必要があります。出力時に悪意のあるコードは除去されますが、それ以外はそのまま出力されます。この設定は、上記の「埋め込み JavaScript コードに基づく」オプションを選択した場合にのみ有効です。';
$string['internalshibbolethwayfcodesetting_providers'] = 'この設定は特に <a href="https://help.switch.ch/aai/guides/discovery/embedded-wayf/">SWITCH AAI</a> を利用する Moodle サイト向けに設計されています。<a href="https://rr.aai.switch.ch/gen_embedding_code.php">公式の埋め込み WAYF コード</a> が提供されていますが、独自の WAYF ソリューションを構築するために他の IDP 用コードを埋め込むことも可能です。';
// ... Section: Login provider: Self registration.
$string['loginproviderselfregistrationheading'] = 'ログインプロバイダ: セルフ登録';
// ... ... Setting: Self registration.
$string['loginselfregistrationenablesetting'] = 'セルフ登録';
$string['loginselfregistrationenablesetting_desc'] = 'この設定では、ログインページにセルフ登録ボタンとサインアップリンクを表示するかどうかを制御します。デフォルトでは、Moodle コアでセルフ登録が有効になっている場合に表示されます。この設定を無効にすると、Moodle コアの設定に関係なくセルフ登録は非表示になります。';
$string['loginselfregistrationenablesetting_core'] = 'Moodle コア設定との関係: セルフ登録は <a href="{$a->url}">Moodle コアの認証設定ページ</a> にある「{$a->settingname}」で管理されています。';
// ... ... Setting: Self registration intro.
$string['loginselfregistrationshowintrosetting'] = 'セルフ登録のイントロ';
$string['loginselfregistrationshowintrosetting_desc'] = 'この設定では、セルフ登録セクションの上にイントロテキストを表示するかどうかを制御します。デフォルトでは表示されませんが、有効にすると、セルフ登録の意味をユーザに説明するのに役立ちます。';
$string['loginselfregistrationintro'] = 'まだアカウントをお持ちではありませんか？';
// ... ... Setting: Self registration intro text.
$string['loginselfregistrationintrotextsetting'] = 'セルフ登録のイントロテキスト';
$string['loginselfregistrationintrotextsetting_desc'] = 'この設定では、デフォルトのイントロテキスト <em>\'{$a}\'</em> をカスタムテキストで上書きできます。空欄の場合はデフォルトが使用されます。';
// ... ... Setting: Self registration tab label.
$string['loginselfregistrationloginlabelsetting'] = 'セルフ登録のラベル';
$string['loginselfregistrationloginlabelsetting_desc'] = 'この設定では、タブレイアウトおよびアコーディオンレイアウトで使用されるセルフ登録のラベルをカスタマイズできます。';
$string['loginselfregistrationloginlabelsetting_default'] = 'セルフ登録';
// ... ... Setting: Self registration login instruction.
$string['loginselfregistrationshowinstruction'] = 'セルフ登録の案内文';
$string['loginselfregistrationshowinstruction_desc'] = 'この設定では、セルフ登録プロバイダに案内文を表示するかどうかを制御します。';
$string['loginselfregistrationinstructioncontent'] = 'セルフ登録の案内文内容';
$string['loginselfregistrationinstructioncontent_desc'] = 'この設定では、セルフ登録の手順や注意点など、ユーザ向けの追加説明をカスタムテキストで指定できます。新規アカウント作成の流れを案内したい場合に便利です。';
$string['loginselfregistrationinstructionposition'] = 'セルフ登録案内文の表示位置';
$string['loginselfregistrationinstructionposition_desc'] = 'この設定では、案内文をサインアップボタンのどこに表示するかを指定できます。';
// ... ... Setting: Self registration button color.
$string['loginselfregistrationbuttoncolorsetting'] = 'セルフ登録ボタンの色';
$string['loginselfregistrationbuttoncolorsetting_desc'] = 'この設定では、セルフ登録ボタンの Bootstrap カラースタイルを指定できます。';
// ... ... Setting: Self registration divider type.
$string['loginfirsttimesignupdividertypesetting'] = 'セルフ登録の区切り線タイプ';
$string['loginfirsttimesignupdividertypesetting_desc'] = 'この設定では、縦レイアウトにおいてセルフ登録の前に表示される区切り線の種類を指定できます。';
// ... Section: Login provider: Guest.
$string['loginproviderguestheading'] = 'ログインプロバイダ: ゲスト';
// ... ... Setting: Guest login.
$string['loginguestloginenablesetting'] = 'ゲストログイン';
$string['loginguestloginenablesetting_desc'] = 'この設定では、ログインページにゲストログインボタンを表示するかどうかを制御します。デフォルトでは、Moodle コアでゲストアクセスが有効な場合に表示されます。この設定を無効にすると、Moodle コアの設定に関係なくゲストログインボタンは非表示になります。';
$string['loginguestloginenablesetting_core'] = 'Moodle コア設定との関係: ゲストアクセスは <a href="{$a->url}">Moodle コアの認証設定ページ</a> にある「{$a->settingname}」で管理されています。';
// ... ... Setting: Guest login intro.
$string['loginguestshowintrosetting'] = 'ゲストログインのイントロ';
$string['loginguestshowintrosetting_desc'] = 'この設定では、ゲストログインボタンの上にイントロテキストを表示するかどうかを制御します。デフォルトでは表示されませんが、有効にすると、ゲストアクセスの意味をユーザに説明するのに役立ちます。';
$string['loginguestintro'] = 'ちょっとだけ見てみたいですか？';
// ... ... Setting: Guest login intro text.
$string['loginguestintrotextsetting'] = 'ゲストログインのイントロテキスト';
$string['loginguestintrotextsetting_desc'] = 'この設定では、デフォルトのイントロテキスト <em>\'{$a}\'</em> をカスタムテキストで上書きできます。空欄の場合はデフォルトが使用されます。';
// ... ... Setting: Guest login tab label.
$string['loginguestloginlabelsetting'] = 'ゲストログインのラベル';
$string['loginguestloginlabelsetting_desc'] = 'この設定では、タブレイアウトおよびアコーディオンレイアウトで使用されるゲストログインのラベルをカスタマイズできます。';
$string['loginguestloginlabelsetting_default'] = 'ゲストログイン';
// ... ... Setting: Guest login instruction.
$string['loginguestshowinstruction'] = 'ゲストログインの案内文';
$string['loginguestshowinstruction_desc'] = 'この設定では、ゲストログインプロバイダに案内文を表示するかどうかを制御します。';
$string['loginguestinstructioncontent'] = 'ゲストログインの案内文内容';
$string['loginguestinstructioncontent_desc'] = 'この設定では、ゲストアクセスに関する追加説明をカスタムテキストで指定できます。ユーザにゲストアクセスの仕組みを説明したい場合に便利です。';
$string['loginguestinstructionposition'] = 'ゲストログイン案内文の表示位置';
$string['loginguestinstructionposition_desc'] = 'この設定では、案内文をゲストログインボタンのどこに表示するかを指定できます。';
// ... ... Setting: Guest login button color.
$string['loginguestbuttoncolorsetting'] = 'ゲストログインボタンの色';
$string['loginguestbuttoncolorsetting_desc'] = 'この設定では、ゲストログインボタンの Bootstrap カラースタイルを指定できます。';
// ... ... Setting: Guest login divider type.
$string['loginguestdividertypesetting'] = 'ゲストログインの区切り線タイプ';
$string['loginguestdividertypesetting_desc'] = 'この設定では、縦レイアウトにおいてゲストログインの前に表示される区切り線の種類を指定できます。';
// ... Section: Side entrance login.
$string['sideentranceloginheading'] = 'サイドエントランスログイン';
// ... ... Setting: Enable side entrance login.
$string['sideentranceloginenablesetting'] = 'サイドエントランスログインを有効にする';
$string['sideentranceloginenablesetting_desc'] = 'この設定では、<a href="{$a->url}">サイドエントランスのローカルログインページ</a> を有効にできます。ローカルログインフォームを無効にした場合（上記参照）、このページは自動的に有効になりますが、SSO 環境などでメインのログインページを経由せずにローカルログインを許可したい場合、常に有効にしておくこともできます。サイドエントランスログインページでも Moodle のすべてのログインセキュリティ対策は適用されます。';

// Settings: Dashboard / My courses tab.
$string['dashboardtab'] = 'ダッシュボード / マイコース';
// ... Section: Course overview block.
$string['courseoverviewheading'] = 'コース概要ブロック';
// ... ... Setting: Show course images.
$string['courseoverviewshowcourseimagessetting'] = 'コース画像を表示する';
$string['courseoverviewshowcourseimagessetting_desc'] = 'この設定では、コース概要ブロック内にコース画像を表示するかどうかを制御できます。カード表示、サマリー表示、リスト表示ごとに個別に設定できます。';
// ... ... Setting: Show course completion progress.
$string['courseoverviewshowprogresssetting'] = 'コース完了状況を表示する';
$string['courseoverviewshowprogresssetting_desc'] = 'この設定では、コース概要ブロック内にコース完了状況を表示するかどうかを制御できます。';
// ... Section: Course overview images.
$string['courseoverviewimageheading'] = 'コース概要画像';
// ... ... Setting: Course overview image source.
$string['courseoverviewimagesourcesetting'] = 'コース概要画像のソース';
$string['courseoverviewimagesourcesetting_desc'] = 'この設定では、コース概要ブロック、カテゴリ一覧ページ、サイトホームのコース一覧に表示される画像のソースを制御します。基本的にはコース設定でアップロードされたコース画像が使用されますが、画像がない場合に幾何学模様のパターンを生成するか、フォールバック画像を使用するかを選択できます。<br />注意: 幾何学パターンを使用する場合、<a href="/admin/settings.php?section=coursecolors">コースカラー設定ページ</a> でパターンの色をカスタマイズできます。';
$string['courseoverviewimagesource_coursepluspattern'] = 'コース画像（なければ幾何学パターン）※Moodle コアと同じ動作';
$string['courseoverviewimagesource_courseplusfallback'] = 'コース画像（なければフォールバック画像）';
// ... ... Setting: Course overview fallback image.
$string['courseoverviewimagefallback'] = 'コース概要フォールバック画像';
$string['courseoverviewimagefallback_desc'] = 'ここでアップロードした画像は、「コース概要画像のソース」設定でフォールバック画像を使用するよう指定した場合に使用されます。<br />注意: フォールバック画像を使用する設定にしていても、ここに画像をアップロードしていない場合は幾何学パターンが使用されます。';

// Settings: Category index / site home tab.
$string['categoryindextab'] = 'カテゴリ一覧 / サイトホーム';
// ... Section: Course listing.
$string['courselistingheading'] = 'コース一覧';
// ... ... Setting: Course listing presentation.
$string['courselistingpresentation'] = 'コース一覧の表示形式';
$string['courselistingpresentation_desc'] = 'この設定では、カテゴリ一覧ページおよびサイトホームのコース一覧の見た目を変更できます。Moodle コアの標準表示に加えて、「コースカード形式」または「コースリスト形式」で表示することもできます。';
$string['courselistingpresentation_nochange'] = 'デザイナーの悪夢（Moodle コアのまま）';
$string['courselistingpresentation_cards'] = 'コースカード形式';
$string['courselistingpresentation_list'] = 'コースリスト形式';
$string['courselistingpresentation_note'] = '注意: コースカード／リスト形式を有効にしても、<a href="{$a->url1}">coursesperpage</a> の設定は引き続き適用され、表示されるカード／行数を制御します。ただし、<a href="{$a->url2}">courseswithsummarieslimit</a> の設定は無視され、すべてのコースが完全な情報付きで表示されます。大量のコースがある場合、<a href="{$a->url1}">coursesperpage</a> を高く設定しすぎるとページ読み込みが遅くなる可能性があります。';
// ... ... Setting: Course card column count.
$string['coursecardscolumncount'] = 'コースカードの列数';
$string['coursecardscolumncount_desc'] = 'コースカードはレスポンシブに表示され、小さな画面では折り返されます。この設定では、大きな画面での最大列数を指定できます。2 列にすると余裕のあるレイアウトになり、1 列にすると縦長のカードリストになります。';
// ... ... Setting: Show course image in the course listing.
$string['courselistinghowimage'] = 'コース画像を表示する';
$string['courselistinghowimage_desc'] = 'この設定では、コース一覧にコース画像を表示するかどうかを制御します。';
// ... ... Setting: Show course contacts in the course listing.
$string['courselistingshowcontacts'] = 'コース連絡先を表示する';
$string['courselistingshowcontacts_desc'] = 'この設定では、コース一覧にコース連絡先（教師など）の写真を表示するかどうかを制御します。注意: 連絡先の写真はコース画像と一緒に表示されるため、コース画像を非表示にした状態で連絡先のみを表示することはできません。';
// ... ... Setting: Show course shortname in the course listing.
$string['courselistinghowshortname'] = 'コース省略名を表示する';
$string['courselistinghowshortname_desc'] = 'この設定では、コース一覧にコース省略名を表示するかどうかを制御します。';
// ... ... Setting: Show course category in the course listing.
$string['courselistinghowcategory'] = 'コースカテゴリを表示する';
$string['courselistinghowcategory_desc'] = 'この設定では、コース一覧にコースカテゴリを表示するかどうかを制御します。';
// ... ... Setting: Show course completion progress in the course listing.
$string['courselistinghowprogress'] = 'コース完了状況を表示する';
$string['courselistinghowprogress_desc'] = 'この設定では、コース一覧にコース完了状況を表示するかどうかを制御します。';
// ... ... Setting: Course completion progress style in the course listing.
$string['courseistingprogressstyle'] = 'コース完了状況の表示形式';
$string['courseistingprogressstyle_desc'] = 'この設定では、コース完了状況を「パーセンテージ表示」または「プログレスバー」で表示するかを選択できます。';
$string['courseistingprogressstyle_percentage'] = 'パーセンテージ表示';
$string['courseistingprogressstyle_bar'] = 'プログレスバー';
// ... ... Setting: Show course enrolment icons in the course listing.
$string['courselistinghowenrolicons'] = '受講登録アイコンを表示する';
$string['courselistinghowenrolicons_desc'] = 'この設定では、コース一覧に受講登録アイコンを表示するかどうかを制御します。';
// ... ... Setting: Show course fields in the course listing.
$string['courselistingshowfields'] = 'コースフィールドを表示する';
$string['courselistingshowfields_desc'] = 'この設定では、コース一覧にカスタムコースフィールドを表示するかどうかを制御します。';
// ... ... Setting: Select course fields to be shown in the course listing.
$string['courselistingselectfields'] = '表示するコースフィールドを選択';
$string['courselistingselectfields_desc'] = 'この設定では、コース一覧に表示するカスタムコースフィールドを選択できます。選択されていない場合、フィールドは表示されません。';
$string['courselistingselectfields_nofield'] = '表示するカスタムコースフィールドを選択できますが、現在使用可能なフィールドがありません。まず <a href="{$a->url}">{$a->linktitle}</a> でカスタムコースフィールドを作成してください。';
// ... ... Setting: Style course fields in the course listing.
$string['courselistingstylefields'] = 'コースフィールドの表示形式';
$string['courselistingstylefields_desc'] = 'この設定では、コース一覧に表示されるカスタムコースフィールドを「テキスト（ラベル＋値）」または「バッジ（値のみ）」で表示するかを選択できます。';
// ... ... Setting: Show goto button in the course listing.
$string['courselistinghowgoto'] = '「コースへ移動」ボタンを表示する';
$string['courselistinghowgoto_desc'] = 'この設定では、コース一覧に「コースへ移動」ボタンを表示するかどうかを制御します。無効にしても、コースタイトルやコース画像をクリックすればコースに移動できます。';
$string['courselistinggoto'] = 'コースへ移動';
// ... ... Setting: Show details popup in the course listing.
$string['courselistinghowpopup'] = '詳細ポップアップを表示する';
$string['courselistinghowpopup_desc'] = 'この設定では、コース一覧に「コース詳細」ボタンを表示するかどうかを制御します。ボタンをクリックすると、コース概要、コース連絡先、コースフィールドを含むポップアップが表示されます。これらの情報は、カード／行で非表示にしていてもポップアップ内では表示されます。';
$string['courselistingpopup'] = '詳細';
$string['courselistingummary'] = 'コース概要';
$string['courselistingnosummary'] = 'このコースには概要がありません';
$string['courselistingcontacts'] = 'コース連絡先';
$string['courselistingviewprofile'] = 'プロフィールを見る';
$string['courselistingfields'] = 'コース分類';
// ... Section: Category listing.
$string['categorylistingheading'] = 'カテゴリ一覧';
// ... ... Setting: Category listing presentation.
$string['categorylistingpresentation'] = 'カテゴリ一覧の表示形式';
$string['categorylistingpresentation_desc'] = 'この設定では、カテゴリ一覧ページおよびサイトホームのカテゴリ表示を変更できます。Moodle コアの標準表示に加えて、刷新されたボックス形式で表示することもできます。';
$string['categorylistingpresentation_nochange'] = 'デザイナーの悪夢（Moodle コアのまま）';
$string['categorylistingpresentation_boxlist'] = 'ボックス形式のリスト';
$string['categorylistingpresentation_note'] = '注意: 上の「コース一覧の表示形式」を有効にしている場合、この設定も有効にすることを推奨します。両方を組み合わせることで統一感のあるデザインになります。';

// Settings: Course tab.
$string['coursetab'] = 'コース';
// ... Section: Course header.
$string['courseheaderheading'] = 'コースヘッダー';
// ... ... Setting: Enable enhanced course header.
$string['courseheaderenabled'] = '拡張コースヘッダーを有効にする';
$string['courseheaderenabled_desc'] = 'この設定を有効にすると、Moodle コアではタイトルのみのコースヘッダーが、コース画像（コース設定でアップロード可能）やその他のコースメタデータを含む拡張ヘッダーに置き換わります。';
$string['courseheaderenabled_help'] = '拡張コースヘッダーでは、コース画像やコースメタデータが表示されます。';
// ... ... Setting: Course header layout.
$string['courseheaderlayout'] = 'コースヘッダーのレイアウト';
$string['courseheaderlayout_desc'] = 'この設定では、コースタイトルやコースメタデータを表示するコースヘッダーのレイアウトを選択できます。全面画像を使用するレイアウトや部分的に画像を使用するレイアウトなど、複数のプリセットから選べます。';
$string['courseheaderlayout_help'] = 'コースタイトルとメタデータを表示するコースヘッダーのレイアウトです。';
$string['courseheaderlayoutstacked'] = 'コースタイトルを全面画像の上に積み重ねて表示';
$string['courseheaderlayoutheadingabove'] = 'コースタイトルを全面画像の上部に表示';
// ... ... Setting: Course header image source.
$string['courseheaderimagesource'] = 'コースヘッダー画像のソース';
$string['courseheaderimagesource_desc'] = '一部のコースヘッダーレイアウトでは、コースヘッダー画像が表示されます。この設定では、その画像をどこから取得するかを制御します。';
$string['courseheaderimagesource_explanation'] = '用語の説明:<br /><ul><li><em>コース画像</em>: Moodle コアの機能として、各コースの設定画面でアップロードできるコース画像。</li><li><em>グローバルコースヘッダー画像</em>: 下の「グローバルコースヘッダー画像」設定でアップロードする、すべてのコースで共通して使用できる画像。</li><li><em>専用コースヘッダー画像</em>: 各コースの設定画面でアップロードできる、コースヘッダー専用の画像。この設定で専用画像を使用するオプションを選ぶと、コース設定画面に専用のアップロード項目が追加されます。また、設定画面の構造を整理するため、Moodle コアの「コース画像」設定と統合され、新しい「コース画像」セクションとして表示されます。</li></ul>';
$string['courseheaderimagesource_courseplusglobal'] = 'コース画像（なければグローバルコースヘッダー画像）';
$string['courseheaderimagesource_coursenoglobal'] = 'コース画像（フォールバックなし）';
$string['courseheaderimagesource_dedicatedplusfallback'] = '専用コースヘッダー画像（なければグローバル cursosヘッダー画像）';
$string['courseheaderimagesource_dedicatednofallback'] = '専用コースヘッダー画像（フォールバックなし）';
$string['courseheaderimagesource_dedicatedpluscourseplusfallback'] = '専用コースヘッダー画像 → コース画像 → グローバル画像の順でフォールバック';
$string['courseheaderimagesource_dedicatedpluscoursenofallback'] = '専用コースヘッダー画像 → コース画像（フォールバックなし）';
$string['courseheaderimagesource_global'] = 'すべてのコースでグローバルコースヘッダー画像を使用する';
$string['courseimagesheading'] = 'コース画像';
$string['courseheaderimage'] = 'コースヘッダー画像';
$string['courseheaderimageplusfallback'] = 'コースヘッダー画像';// 以下の3つは help_icon のためのダミー文字列（実際には使用されない）
$string['courseheaderimageplusfallback_help'] = 'コースページ上部のコースヘッダーに表示される画像です。ここに画像をアップロードしない場合、グローバルコースヘッダー画像が使用されます。';
$string['courseheaderimagenofallback'] = 'コースヘッダー画像';// 以下の3つは help_icon のためのダミー文字列（実際には使用されない）
$string['courseheaderimagenofallback_help'] = 'コースページ上部のコースヘッダーに表示される画像です。ここに画像をアップロードしない場合、コースタイトルのみが表示されます。';
$string['courseheaderimagenoimage'] = 'コースヘッダー画像';// 以下の3つは help_icon のためのダミー文字列（実際には使用されない）
$string['courseheaderimagenoimage_help'] = 'コースページ上部のコースヘッダーに表示される画像です。ここに画像をアップロードしない場合、背景は無地になります。';
// ... ... Setting: Global course header image.
$string['courseheaderimageglobal'] = 'グローバルコースヘッダー画像';
$string['courseheaderimageglobal_desc'] = 'ここでアップロードした画像は、「コースヘッダー画像のソース」設定で指定したフォールバックとして使用されます。';
// ... ... Setting: Course header image requirement.
$string['courseheaderimagerequirement'] = 'コースヘッダー画像の必須条件';
$string['courseheaderimagerequirement_desc'] = '拡張コースヘッダーは、基本的にコース画像があることを前提にデザインされています。画像がない場合、見栄えが大きく損なわれる可能性があります。この設定では、画像が決定できない場合に拡張コースヘッダーを表示するか、標準の Moodle コースヘッダーに戻すかを選択できます。後者を選ぶと、コース連絡先やコースフィールドなどの追加要素も表示されません。';
$string['courseheaderimagerequirement_standardonly'] = '画像がない場合は標準コースヘッダーを表示する';
$string['courseheaderimagerequirement_enhancedwithoutimage'] = '画像がなくても拡張コースヘッダーを表示する';
// ... ... Setting: Course header height.
$string['courseheaderheight'] = 'コースヘッダーの高さ';
$string['courseheaderheight_desc'] = 'この設定では、コースヘッダーの高さを指定できます。';
$string['courseheaderheight_help'] = 'コースヘッダーの高さを指定します。';
// ... ... Setting: Course header canvas border.
$string['courseheadercanvasborder'] = 'コースヘッダー枠線';
$string['courseheadercanvasborder_desc'] = 'この設定では、コースヘッダーの枠線スタイルを指定できます。';
$string['courseheadercanvasborder_none'] = '枠線なし';
$string['courseheadercanvasborder_grey'] = 'グレーの枠線';
$string['courseheadercanvasborder_brandcolor'] = 'ブランドカラーの枠線';
$string['courseheadercanvasborder_help'] = 'コースヘッダーの枠線スタイルです。';
// ... ... Setting: Course header canvas background.
$string['courseheadercanvasbackground'] = 'コースヘッダー背景';
$string['courseheadercanvasbackground_desc'] = 'この設定では、コースヘッダーの背景色を指定できます。';
$string['courseheadercanvasbackground_transparent'] = '透明';
$string['courseheadercanvasbackground_white'] = '白';
$string['courseheadercanvasbackground_lightgrey'] = 'ライトグレー';
$string['courseheadercanvasbackground_lightbrandcolor'] = 'ブランドカラー（淡色）';
$string['courseheadercanvasbackground_brandcolorgradientlight'] = 'ブランドカラーのライトグラデーション';
$string['courseheadercanvasbackground_brandcolorgradientfull'] = 'ブランドカラーのフルグラデーション';
$string['courseheadercanvasbackground_help'] = 'コースヘッダーの背景色です。';
// ... ... Setting: Course header text on image style.
$string['courseheadertextonimagestyle'] = '画像上のテキストスタイル';
$string['courseheadertextonimagestyle_desc'] = 'この設定では、コースヘッダー画像の上に表示されるテキストのスタイルを指定できます。';
$string['courseheadertextonimagestyle_help'] = 'コースヘッダー画像上のテキストスタイルです。';
$string['courseheadertextonimagestyle_light'] = 'ライト（暗い背景向けの明るい文字色）';
$string['courseheadertextonimagestyle_lightshadow'] = 'ライト＋影（暗い背景向けの明るい文字＋暗い影）';
$string['courseheadertextonimagestyle_lightbg'] = 'ライト＋背景（暗い背景向けの明るい文字＋半透明背景）';
$string['courseheadertextonimagestyle_dark'] = 'ダーク（明るい背景向けの暗い文字色）';
$string['courseheadertextonimagestyle_darkshadow'] = 'ダーク＋影（明るい背景向けの暗い文字＋明るい影）';
$string['courseheadertextonimagestyle_darkbg'] = 'ダーク＋背景（明るい背景向けの暗い文字＋半透明背景）';
// ... ... Setting: Course header image position.
$string['courseheaderimageposition'] = 'コースヘッダー画像の位置';
$string['courseheaderimageposition_desc'] = 'この設定では、コースヘッダー画像をコンテナ内のどこに配置するかを指定します。最初の値が横方向、2つ目の値が縦方向です。';
$string['courseheaderimageposition_help'] = 'コースヘッダー画像の配置位置（横方向、縦方向）です。';
// ... ... Setting: Show course contacts in the course header.
$string['courseheadershowcontacts'] = 'コース連絡先を表示する';
$string['courseheadershowcontacts_desc'] = 'この設定では、コースヘッダーにコース連絡先（教師など）を表示するかどうかを制御します。';
// ... ... Setting: Show course shortname in the course header.
$string['courseheadershowshortname'] = 'コース省略名を表示する';
$string['courseheadershowshortname_desc'] = 'この設定では、コースヘッダーにコース省略名を表示するかどうかを制御します。';
// ... ... Setting: Show course category in the course header.
$string['courseheadershowcategory'] = 'コースカテゴリを表示する';
$string['courseheadershowcategory_desc'] = 'この設定では、コースヘッダーにコースカテゴリを表示するかどうかを制御します。';
// ... ... Setting: Show course completion progress in the course header.
$string['courseheadershowprogress'] = 'コース完了状況を表示する';
$string['courseheadershowprogress_desc'] = 'この設定では、コースヘッダーにコース完了状況を表示するかどうかを制御します。';
// ... ... Setting: Course completion progress style in the course header.
$string['courseheaderprogressstyle'] = 'コース完了状況の表示形式';
$string['courseheaderprogressstyle_desc'] = 'この設定では、コース完了状況を「パーセンテージ表示」または「プログレスバー」で表示するかを選択できます。';
$string['aria:courseprogress'] = 'コース進捗:';
$string['completepercent'] = '{$a}% 完了';
// ... ... Setting: Show course fields in the course header.
$string['courseheadershowfields'] = 'コースフィールドを表示する';
$string['courseheadershowfields_desc'] = 'この設定では、コースヘッダーにカスタムコースフィールドを表示するかどうかを制御します。';
// ... ... Setting: Select course fields to be shown in the course header.
$string['courseheaderselectfields'] = '表示するコースフィールドを選択';
$string['courseheaderselectfields_desc'] = 'この設定では、コースヘッダーに表示するカスタムコースフィールドを選択できます。選択されていない場合、フィールドは表示されません。';
$string['courseheaderselectfields_nofield'] = '表示できるカスタムコースフィールドがまだありません。まず <a href="{$a->url}">{$a->linktitle}</a> でフィールドを作成してください。';
// ... ... Setting: Style course fields in the course header.
$string['courseheaderstylefields'] = 'コースフィールドの表示形式';
$string['courseheaderstylefields_desc'] = 'この設定では、コースヘッダーに表示されるカスタムコースフィールドを「テキスト（ラベル＋値）」または「バッジ（値のみ）」で表示するかを選択できます。';
// ... ... Setting: Show details popup in the course header.
$string['courseheadershowpopup'] = '詳細ポップアップを表示する';
$string['courseheadershowpopup_desc'] = 'この設定では、コースヘッダーに「コース詳細」ポップアップを表示するかどうかを制御します。ポップアップにはコース概要が表示されます。';
$string['courseheadershowpopup_label'] = 'コース詳細';
// ... ... Setting: Show edit icon in the course header.
$string['courseheadershowediticon'] = '編集アイコンを表示する';
$string['courseheadershowediticon_desc'] = 'この設定では、編集モードがオンのときにコースヘッダーに編集アイコンを表示できます。教師がコースヘッダー設定に素早くアクセスするのに役立ちます。ただし、コース画像の上書き設定を有効にしている場合や、コースヘッダー画像ソースが「コース画像」を使用する設定になっている場合にのみ意味があります。Boost Union はこれを自動判定しません。';
$string['courseheadershowediticon_label'] = 'コースヘッダー設定を編集';
// ... ... Setting: Course header layouts exclusion list.
$string['courseheaderlayoutexclusionlist'] = 'コースヘッダーレイアウトの除外リスト';
$string['courseheaderlayoutexclusionlist_desc'] = 'この設定では、特定のコースヘッダーレイアウトをコースごとの上書き設定から除外できます。選択したレイアウトは教師のコース設定画面には表示されませんが、管理者はグローバル設定として使用できます。なお、管理者が上で選択したグローバルレイアウトは、ここで除外してもコース設定からは除外されません。';
// ... ... Setting: Course format exclusion list.
$string['courseheaderformatexclusionlist'] = 'コース形式の除外リスト';
$string['courseheaderformatexclusionlist_desc'] = 'この設定では、特定のコース形式をコースヘッダー機能の対象外にできます。選択されたコース形式を使用するコースでは、他の設定に関係なくコースヘッダーは変更されません。コース形式によっては独自のヘッダー実装があるため、そのような形式を除外する際に便利です。';
// ... ... Setting: Transfer course-specific header settings during course import.
$string['courseheaderimporttransfer'] = 'コースインポート時にコースヘッダー設定を引き継ぐ';
$string['courseheaderimporttransfer_desc'] = 'この設定では、コースインポート時にコースヘッダー設定およびコースヘッダー画像を引き継ぐかどうかを制御します。<ul><li><strong>Always</strong>: コース全体または一部をインポートする場合でも、すべてのコースヘッダー設定がコピーされます。</li><li><strong>Never</strong>: コースヘッダー設定は一切引き継がれません。</li><li><strong>Controlled by capability</strong>: インポートを実行するユーザが、対象コースで「theme/boost_union:transfercourseheaderduringimport」権限を持っている場合のみ引き継がれます。</li></ul>なお、Moodle コアの技術的制限により、教師がインポート時に毎回選択することはできません。';
$string['courseheaderrestoreoption'] = 'コースヘッダー設定を含める';
// ... Section: Breadcrumbs.
$string['breadcrumbsheading'] = 'パンくずリスト';
// ... ... Setting: Course category breadcrumb.
$string['categorybreadcrumbs'] = 'コースカテゴリのパンくずリストを表示する';
$string['categorybreadcrumbs_desc'] = 'デフォルトでは、コースページのコースヘッダーにカテゴリのパンくずリストは表示されません。この設定を有効にすると、コース名の上にカテゴリのパンくずリストが表示されます。';
// ... Section: Course index.
$string['courseindexheading'] = 'コースインデックス';
// ... ... Setting: Course index.
$string['courseindexmodiconenabled'] = 'コースインデックスに活動アイコンを表示する';
$string['courseindexmodiconenabled_desc'] = 'この設定を有効にすると、コースインデックスの各行の先頭に活動タイプのアイコンが表示されます。これにより、活動タイプが完了状況インジケータの代わりに表示されるか、または完了状況の色で着色されます。';
$string['courseindexcompletioninfoposition'] = '活動完了インジケータの位置';
$string['courseindexcompletioninfoposition_desc'] = '完了インジケータをどこに表示するかを選択します。<em>行末</em> は標準の完了インジケータをコースインデックス行の末尾に表示します。<em>行頭</em> は標準の完了インジケータを行の先頭に表示します。<em>アイコンの色</em> は標準のインジケータを表示せず、活動アイコンの背景色として完了状態を表現します。';
$string['courseindexcompletioninfopositionendofline'] = '行末';
$string['courseindexcompletioninfopositioniconcolor'] = 'アイコンの色';
$string['courseindexcompletioninfopositionstartofline'] = '行頭';

// Settings: E-Mail branding tab.
$string['emailbrandingtab'] = 'メールブランディング';
$string['templateemailhtmlprefix'] = '';
$string['templateemailhtmlsuffix'] = '';
$string['templateemailtextprefix'] = '';
$string['templateemailtextsuffix'] = '';
// ... Section: E-Mails introduction.
$string['emailbrandingintroheading'] = 'イントロダクション';
$string['emailbrandingintronote'] = '注意: この機能は高度な機能であり、メールブランディングを実現するためにいくつかのワークアラウンドを使用しています。必ず手順に従ってください。';
$string['emailbrandinginstruction'] = '手順';
$string['emailbrandinginstruction0'] = 'Boost Union のこの機能を使うと、Moodle が送信するすべてのメールにブランディングを適用できます。';
$string['emailbrandinginstructionli1'] = '<a href="{$a->url}" target="_blank">言語カスタマイズ設定ページ</a> に移動し、<em>{$a->lang}</em> 言語パックを編集します。';
$string['emailbrandinginstructionli2'] = '次の文字列を <code>theme_boost_union</code> 言語パック内で検索し、編集します:';
$string['emailbrandinginstructionli2li1'] = '<code>templateemailhtmlprefix</code>: すべての <em>HTML メール</em> の本文の <em>前</em> に追加されるスニペットです。';
$string['emailbrandinginstructionli2li2'] = '<code>templateemailhtmlsuffix</code>: すべての <em>HTML メール</em> の本文の <em>後</em> に追加されるスニペットです。';
$string['emailbrandinginstructionli2li3'] = '<code>templateemailtextprefix</code>: すべての <em>プレーンテキストメール</em> の本文の <em>前</em> に追加されるスニペットです。';
$string['emailbrandinginstructionli2li4'] = '<code>templateemailtextsuffix</code>: すべての <em>プレーンテキストメール</em> の本文の <em>後</em> に追加されるスニペットです。';
$string['emailbrandinginstructionli3'] = '言語パックの変更を保存します。';
$string['emailbrandinginstructionli4'] = 'このページに戻り、下のメールプレビューを確認します。';
$string['emailbrandingpitfalls'] = '注意点';
$string['emailbrandingpitfalls0'] = 'この機能を使用する際、以下の点に注意してください:';
$string['emailbrandingpitfallsli1'] = '必ずサイトの <em>現在のデフォルト言語</em> の言語パックを編集してください。他の言語パックを編集しても反映されません。';
$string['emailbrandingpitfallsli2'] = '受信者の言語に合わせることはできません。そのため、ブランディングには言語に依存しない表現を使用してください。';
$string['emailbrandingpitfallsli3'] = '将来デフォルト言語を変更した場合、編集した文字列を新しいデフォルト言語パックに移行する必要があります。';
$string['emailbrandingpitfallsli4'] = 'プレーンテキストメールでは、prefix の後に自動で改行と空行が追加され、suffix の前にも空行が追加されます。本文と接続しないようにするためです。';
$string['emailbrandingpitfallsli5'] = 'HTML メールでは、prefix と suffix は本文の直前・直後にそのまま挿入されます。HTML タグを使いやすくするためですが、余白調整は自分で行う必要があります。';
$string['emailbrandingpitfallsli6'] = 'HTML メールでは、prefix で開いたタグを suffix で閉じることも可能です。ただし、最終的に正しい HTML になるよう注意してください。';
// ... Section: HTML E-Mails.
$string['emailbrandinghtmlheading'] = 'HTML メールプレビュー';
$string['emailbrandinghtmlintro'] = '現在の言語パックに設定されている prefix / suffix を反映した HTML メールのプレビューです。';
$string['emailbrandinghtmlnopreview'] = 'この機能で HTML メールはまだカスタマイズされていません。メールは通常どおり送信されます。';
$string['emailbrandinghtmldemobody'] = '<p>メール本文はここから始まります。</p><p>Lorem ipsum dolor sit amet...</p><p>メール本文はここで終わります。</p>';
// ... Section: Plaintext E-Mails.
$string['emailbrandingtextheading'] = 'プレーンテキストメールプレビュー';
$string['emailbrandingtextintro'] = '現在の言語パックに設定されている prefix / suffix を反映したプレーンテキストメールのプレビューです。';
$string['emailbrandingtextnopreview'] = 'この機能でプレーンテキストメールはまだカスタマイズされていません。メールは通常どおり送信されます。';
$string['emailbrandingtextdemobody'] = 'メール本文はここから始まります。

Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.

Id donec ultrices tincidunt arcu non sodales. Id volutpat lacus laoreet non curabitur gravida arcu.

Cursus turpis massa tincidunt dui. Pellentesque nec nam aliquam sem et tortor consequat id. In ornare quam viverra orci sagittis eu volutpat. Sem nulla pharetra diam sit amet nisl suscipit. Justo donec enim diam vulputate ut pharetra.

メール本文はここで終わります。';

// Settings: Resources tab.
$string['resourcestab'] = 'リソース';
$string['resourcescachecontrolnote'] = '注意: ここでアップロードしたファイルはブラウザにキャッシュされるように配信されます。ファイルを今後変更しない場合は永続 URL を使用できますが、同じファイル名で更新する可能性がある場合は、リビジョン付き URL を使用し、更新のたびにリンクを貼り直すことを推奨します。';
// ... Section: Additional resources.
$string['additionalresourcesheading'] = '追加リソース';
// ... ... Setting: Additional resources.
$string['additionalresourcessetting'] = '追加リソース';
$string['additionalresourcessetting_desc'] = 'この設定では、テーマに追加リソースをアップロードできます。この領域にアップロードしたファイルはログインチェックなしで配信されるため、誰でもアクセス可能なファイルのみをアップロードしてください。ファイルをアップロードして設定を保存すると、各ファイルの URL が一覧表示されます。';
// ... ... Information: Additional resources list.
$string['additionalresourceslistsetting'] = '追加リソース一覧';
$string['additionalresourceslistsetting_desc'] = 'ここには追加リソース領域にアップロードしたファイルの一覧が表示されます。表示される URL を使用して、カスタム CSS やフッターノートなどから参照できます。';
$string['additionalresourcesfileurlpersistent'] = 'URL（永続）';
$string['additionalresourcesfileurlrevisioned'] = 'URL（リビジョン付き）';
// ... Section: Custom fonts.
$string['customfontsheading'] = 'カスタムフォント';
// ... ... Setting: Custom fonts.
$string['customfontssetting'] = 'カスタムフォント';
$string['customfontssetting_desc'] = 'この設定では、テーマにカスタムフォントをアップロードできます。アップロードしたフォントはログインチェックなしで配信され、サイト全体でローカルフォントとして使用できます。フォントをアップロードして設定を保存すると、SCSS で使用できる CSS スニペットが表示されます。';
// ... ... Information: Custom fonts list.
$string['customfontslistsetting'] = 'カスタムフォント一覧';
$string['customfontslistsetting_desc'] = 'ここにはアップロードしたフォントの一覧が表示されます。表示される CSS スニペットをカスタム SCSS に追加して使用できます。ただし、font-family、font-style、font-weight などの指定は自分で行う必要があります。';
$string['customfontsfileurlpersistent'] = 'URL（永続）';
$string['customfontsfileurlrevisioned'] = 'URL（リビジョン付き）';

// Settings: H5P tab.
$string['h5ptab'] = 'H5P';
// ... Section: Raw CSS for H5P.
$string['cssh5pheading'] = 'H5P 用生 CSS';
// ... ... Setting: Raw CSS for H5P.
$string['cssh5psetting'] = 'H5P 用生 CSS';
$string['cssh5psetting_desc'] = 'この設定では、mod_h5p および mod_hvp によって表示される H5P コンテンツに適用する CSS を記述できます。必要な CSS セレクタは H5P コンテンツタイプを調査して確認してください。';
// ... Section: Content width.
$string['contentwidthheading'] = 'コンテンツ幅';
// ... ... Setting: H5P content bank max width.
$string['h5pcontentmaxwidthsetting'] = 'H5P コンテンツバンクの最大幅';
$string['h5pcontentmaxwidthsetting_desc'] = 'この設定では、Moodle の H5P コンテンツバンクの幅を SCSS を書かずに上書きできます。この幅はコンテンツバンク内の H5P エディタに適用され、H5P 活動の幅には適用されません。デフォルトは 960px です。1200px のようなピクセル値、100% のようなパーセンテージ値、90vw のようなビューポート幅も指定できます。';

// Settings: Mobile tab.
$string['mobiletab'] = 'モバイル';
// ... Section: Mobile app.
$string['mobileappheading'] = 'モバイルアプリ';
// ... ... Setting: Additional CSS for Mobile app.
$string['mobilecss'] = 'モバイルアプリ用追加 CSS';
$string['mobilecss_desc'] = 'この設定では、モバイルアプリの UI をカスタマイズするための CSS を記述できます。この CSS はモバイルアプリにのみ適用され、ブラウザ版には影響しません。詳細は <a href="https://moodledev.io/general/app/customisation/remote-themes#how-do-remote-themes-work">Moodle dev docs</a> を参照してください。';
$string['mobilecss_set'] = 'この設定に CSS を追加して保存すると、<a href="{$a->url}">Moodle コア設定 <em>mobilecssurl</em></a> が自動的に Boost Union の URL に設定されます。';
$string['mobilecss_overwrite'] = 'この設定に CSS を追加して保存すると、<a href="{$a->url}">Moodle コア設定 <em>mobilecssurl</em></a> は Boost Union の URL に自動的に上書きされます。現在の設定値は <a href="{$a->value}">{$a->value}</a> です。';
$string['mobilecss_donotchange'] = 'この URL はモバイルアプリに CSS を配信するために必要です。CSS を削除したい場合を除き、変更しないでください。';
// ... Section: Mobile appearance.
$string['mobileappearanceheading'] = 'モバイル外観';
// ... ... Setting: Touch icon files for iOS.
$string['touchiconfilesios'] = 'iOS 用タッチアイコン';
$string['touchiconfilesios_desc'] = 'この設定では、Moodle サイトを iOS のホーム画面に追加した際に使用されるアイコンをアップロードできます。';
$string['touchiconfilesios_recommended'] = '推奨ファイル:';
$string['touchiconfilesios_optional'] = '任意ファイル:';
$string['touchiconfilesios_example'] = '例: apple-icon-152x152.png';
$string['touchiconfilesios_note'] = '推奨ファイルは最新の iOS デバイスで適切に表示されるサイズです。任意ファイルは古いデバイス向けの互換性のためのものです。';
$string['touchiconfilesioslist'] = 'iOS タッチアイコン一覧';
$string['touchiconfilesioslist_desc'] = 'ここにはアップロードした iOS タッチアイコンの一覧が表示されます。';
$string['touchiconlistiosrecommendeduploaded'] = '推奨アイコンがアップロードされています。';
$string['touchiconlistiosrecommendedmissing'] = '推奨アイコンがアップロードされていません。';
$string['touchiconlistiosoptionaluploaded'] = '任意アイコンがアップロードされています。';
$string['touchiconlistiosoptionalmissing'] = '任意アイコンがアップロードされていません。';

// Settings: Feel page.
$string['configtitlefeel'] = 'Feel';

// Settings: Navigation tab.
$string['navigationtab'] = 'ナビゲーション';
// ... Section: Primary navigation.
$string['primarynavigationheading'] = 'プライマリナビゲーション';
// ... ... Settings: Hide nodes in primary navigation.
$string['hidenodesprimarynavigationsetting'] = 'プライマリナビゲーションの項目を非表示にする';
$string['hidenodesprimarynavigationsetting_desc'] = 'この設定では、プライマリナビゲーションから特定の項目を非表示にできます。<br /><br />
注意: ナビゲーション項目を追加したい場合は、<a href="{$a->url}">Boost Union のスマートメニュー機能</a> を使用してください。';
$string['hidenodesprimarynavigationonlyguest'] = 'この項目はゲストのみに表示されます';
// ... ... Settings: Alternative logo link URL.
$string['alternativelogolinkurlsetting'] = 'ロゴのリンク先 URL を変更する';
$string['alternativelogolinkurlsetting_desc'] = 'この設定では、ナビゲーションバーのロゴのリンク先を変更できます。組織の Web サイトなどにリンクさせることで、組織全体で統一したナビゲーションを実現できます。';

// ... Section: User menu.
$string['usermenuheading'] = 'ユーザメニュー';
// ... ... Settings: Show full name in the user menu.
$string['showfullnameinusermenussetting'] = 'ユーザメニューにフルネームを表示する';
$string['showfullnameinusermenussetting_desc'] = 'この設定では、ユーザメニューの上部にログイン中のユーザのフルネームを表示できます。試験監督が本人確認を行う場合や、ユーザ自身にとっても便利です。Classic テーマのようにナビバーのスペースを消費しない点が利点です。';
$string['showfullnameinusermenussetting_loggedinas'] = 'ログイン中のユーザ:';
// ... ... Settings: Add preferred language link to language menu.
$string['addpreferredlangsetting'] = '言語メニューに「優先言語を設定」を追加する';
$string['addpreferredlangsetting_desc'] = 'この設定では、ユーザメニュー内の言語メニューに「優先言語を設定」リンクを追加できます。ただし、<a href="{$a->url1}">言語メニューの表示</a> が有効であり、<a href="{$a->url2}">複数の言語パックがインストールされ</a>、<a href="{$a->url3}">選択可能になっている</a> 場合にのみ適用されます。';
$string['setpreferredlanglink'] = '優先言語を設定';
// ... Section: Navbar heading.
$string['navbarheading'] = 'ナビバー';
// Setting: Display login link as button.
$string['loginlinkbuttonenabled'] = '「ログイン」リンクをボタン表示にする';
$string['loginlinkbuttonenabled_desc'] = 'この設定では、ページ上部の「ログイン」リンクをボタンとして表示できます。未ログインであることをユーザに分かりやすく示すのに役立ちます。';
// ... ... Setting: Show starred courses popover in the navbar.
$string['shownavbarstarredcoursessetting'] = 'ナビバーに「スター付きコース」ポップオーバーを表示する';
$string['shownavbarstarredcoursessetting_desc'] = 'この設定では、ナビバーのメッセージ・通知メニューの横に、スター付きコースへのリンクを含むポップオーバーメニューを表示できます。';
$string['shownavbarstarredcourses_config'] = 'スター付きコースは「{$a}」ページで設定できます';
$string['shownavbarstarredcourses_label'] = 'スター付きコース';
// ... ... Setting: Starred courses popover cog icon link target.
$string['starredcourseslinktargetsetting'] = 'スター付きコースポップオーバーの歯車アイコンのリンク先';
$string['starredcourseslinktargetsetting_desc'] = 'この設定では、スター付きコースポップオーバー内の歯車アイコンのリンク先を指定できます。デフォルトでは「マイコース」ページにリンクしますが、プライマリナビゲーションで「マイコース」を無効にしている場合は「ダッシュボード」ページにリンクさせることもできます。';
// ... Section: Navigation.
$string['navigationheading'] = 'ナビゲーション';
// ... ... Setting: Back to top button.
$string['backtotop'] = 'ページ上部へ戻る';
$string['backtotopbuttonsetting'] = '「ページ上部へ戻る」ボタン';
$string['backtotopbuttonsetting_desc'] = 'この設定を有効にすると、ユーザがページをスクロールした際に、画面右下に「ページ上部へ戻る」ボタンが表示されます。このボタンは Moodle コアの Boost テーマに Moodle 3.11 まで存在していましたが、4.0 で削除されました。Boost Union ではこの機能を復活させることができます。';
// ... ... Setting: Scroll-spy
$string['scrollspysetting'] = 'スクロールスパイ';
$string['scrollspysetting_desc'] = 'この設定を有効にすると、編集モードのオン／オフを切り替えた際に、切り替え前のスクロール位置が保持されます。長いページでの編集作業が快適になります。';
// ... ... Setting: Activity & section navigation
$string['activitynavigationsetting'] = '活動・セクションナビゲーション';
$string['activitynavigationsetting_desc'] = 'この設定を有効にすると、前後の活動/リソースへ移動するナビゲーション要素や、特定の活動/リソースへジャンプするプルダウンメニューが表示されます。また、「1 セクションごとに 1 ページ」形式のコースでは、前後のセクションへ移動する UI も表示されます。これらの UI は Moodle コアの Boost テーマに Moodle 3.11 まで存在していましたが、4.0 で削除されました。Boost Union ではこれらを復活させることができます。';

// Settings: Blocks tab.
$string['blockstab'] = 'ブロック';
// ... Section: General blocks.
$string['blocksgeneralheading'] = '一般ブロック';
// ... Section: Block regions.
$string['blockregionsheading'] = '追加ブロック領域';
$string['blockregionsheading_desc'] = '<p>Boost Union では、Moodle ページ全体に多数の追加ブロック領域を提供します:</p>
<ul><li><em>Outside ブロック領域</em>: ページの四辺（左・右・上・下）に配置され、ページ内容とは独立した補助的なブロックを表示できます。</li>
<li><em>Header ブロック領域</em>: Outside（上）領域とメインコンテンツの間に配置され、コースヘッダー情報などを表示できます。</li>
<li><em>Content ブロック領域</em>: メインコンテンツの上下に配置され、コース内容の流れの中にブロックを挿入できます。</li>
<li><em>Footer ブロック領域</em>: ページ下部に配置され、フッターノートの上に 3 つの領域を使ってカラム構成を作れます。</li>
<li><em>Off-canvas ブロック領域</em>: ナビゲーションバー右端の 9 ドットアイコンで開くドロワー内に表示される特別な領域です。3 つの領域を使ってカラム構成が可能です。</li></ul>
<p>注意:</p>
<ul><li>追加ブロック領域はデフォルトではすべて無効です。必要なページレイアウトで必要な領域のみ有効にしてください。領域が多すぎるとユーザが混乱する可能性があります。</li>
<li>追加ブロック領域を有効にすると、認証ユーザおよびゲストユーザに表示され、教師やマネージャーが編集できます（ページ編集権限がある場合）。また、theme/boost_union:viewregion* および theme/boost_union:editregion* 権限で細かく制御できます。</li>
<li>Outside（左・右）、Content（上・下）、Header ブロック領域は、すべてのページレイアウトで利用できるわけではありません。</li></ul>';
$string['blockregionsheading_guestrole'] = '<strong>注意!</strong><br />Boost Union v5.0 より前のバージョンからアップグレードした場合、追加ブロック領域はゲストユーザに対してデフォルトで非表示のままです。新しいデフォルトに自動修正するか、現在の設定を維持するかを選択できます。';
$string['blockregionsheading_guestrole_fix'] = 'ゲストロールを新しいデフォルトに修正する';
$string['blockregionsheading_guestrole_fixed'] = 'ゲストロールは新しいデフォルトに更新され、この通知は削除されます。';
$string['blockregionsheading_guestrole_keep'] = '現在のゲストロール設定を維持する';
$string['blockregionsheading_guestrole_kept'] = 'ゲストロールは変更されず、この通知は削除されます。';
$string['error:infobannerdismissnonotvalidnotset'] = 'この Boost Union インスタンスは v5.0 より前からのアップグレードではないか、すでに新しいゲストロールデフォルトが適用済みです。操作は不要です。';
$string['region-none'] = 'なし';
$string['region-outside-left'] = 'Outside（左）';
$string['region-outside-top'] = 'Outside（上）';
$string['region-outside-bottom'] = 'Outside（下）';
$string['region-outside-right'] = 'Outside（右）';
$string['region-content-upper'] = 'Content（上）';
$string['region-content-lower'] = 'Content（下）';
$string['region-footer-left'] = 'Footer（左）';
$string['region-footer-right'] = 'Footer（右）';
$string['region-footer-center'] = 'Footer（中央）';
$string['region-header'] = 'Header';
$string['region-offcanvas-left'] = 'Off-canvas（左）';
$string['region-offcanvas-right'] = 'Off-canvas（右）';
$string['region-offcanvas-center'] = 'Off-canvas（中央）';
$string['closeoffcanvas'] = 'Off-canvas ドロワーを閉じる';
$string['openoffcanvas'] = 'Off-canvas ドロワーを開く';
// ... ... Setting: Block regions for 'x' layout.
$string['blockregionsforlayout'] = '「{$a}」レイアウトの追加ブロック領域';
$string['blockregionsforlayout_desc'] = 'この設定では、「{$a}」レイアウトで使用できる追加ブロック領域を有効にできます。';
$string['blockregionsstickyonly'] = '注意: このページレイアウトは Moodle コアの制限により、ページ上に直接ブロックを追加できません。ただし、ここでブロック領域を有効にすると、<a href="{$a}" target="_blank">サイト全体のスティッキーブロック</a> はこのレイアウトでも表示されます。';
// ... Section: Block Manager.
$string['blockmanagerheading'] = 'ブロックマネージャー';
$string['blockmanagerheading_desc'] = 'Boost Union は独自のブロックマネージャークラスを使用して高度なブロック管理機能を提供できます。ただし、ブロックマネージャーは Moodle の中心的な仕組みであるため、必要な場合のみ有効にしてください。';
// ... ... Setting: Harden block regions.
$string['hardenblockregions'] = 'ブロック領域の保護を強化する';
$string['hardenblockregions_desc'] = '無効の場合、Boost Union は編集権限のないユーザに対してブロック領域の編集コントロールを非表示にするだけです。しかし、これは部分的な保護であり、ユーザは既存ブロックをドラッグ＆ドロップで非編集領域に移動できてしまいます。有効にすると、Boost Union 独自のブロックマネージャーが使用され、必要な権限がないユーザによるブロック領域の編集を防ぎます。完全ではありませんが、Moodle コアより強力な保護を提供します。';
$string['blockactionnotallowed_add'] = 'ブロック領域 {$a} にブロックを追加';
$string['blockactionnotallowed_configure'] = 'ブロック領域 {$a} のブロックを設定';
$string['blockactionnotallowed_move'] = 'ブロックをブロック領域 {$a} に移動';
// ... Section: Outside regions.
$string['outsideregionsheading'] = 'Outside（外側）領域';
$string['outsideregionsheading_desc'] = 'Outside 領域はレイアウト設定で有効化できるだけでなく、外観もカスタマイズできます。';
// ... ... Setting: Block region width for Outside (left) region.
$string['blockregionoutsideleftwidth'] = 'Outside（左）領域の幅';
$string['blockregionoutsideleftwidth_desc'] = 'この設定では、メインコンテンツの左側に表示される「Outside（左）」ブロック領域の幅を設定できます。デフォルトでは 300px が使用されています。200px のようなピクセル値だけでなく、10% のようなパーセンテージ値や 10vw のようなビューポート幅も指定できます。';
// ... ... Setting: Block region width for Outside (right) region.
$string['blockregionoutsiderightwidth'] = 'Outside（右）領域の幅';
$string['blockregionoutsiderightwidth_desc'] = 'この設定では、メインコンテンツの右側に表示される「Outside（右）」ブロック領域の幅を設定できます。デフォルトでは 300px が使用されています。ピクセル値、パーセンテージ値、ビューポート幅のいずれも指定できます。';
// ... ... Setting: Block region width for Outside (top) region.
$string['blockregionoutsidetopwidth'] = 'Outside（上）領域の幅';
$string['blockregionoutsidetopwidth_desc'] = 'この設定では、ページ最上部に表示される「Outside（上）」ブロック領域の幅を設定できます。「全幅」「コースコンテンツ幅」「ヒーロー幅」から選択できます。';
$string['outsideregionswidthfullwidth'] = '全幅';
$string['outsideregionswidthcoursecontentwidth'] = 'コースコンテンツ幅';
$string['outsideregionswidthherowidth'] = 'ヒーロー幅';
// ... ... Setting: Block region width for Outside (bottom) region.
$string['blockregionoutsidebottomwidth'] = 'Outside（下）領域の幅';
$string['blockregionoutsidebottomwidth_desc'] = 'この設定では、メインコンテンツの下に表示される「Outside（下）」ブロック領域の幅を設定できます。「全幅」「コースコンテンツ幅」「ヒーロー幅」から選択できます。';
// ... Section: Footer regions.
$string['footerregionsheading'] = 'フッター領域';
$string['footerregionsheading_desc'] = 'フッター領域はレイアウト設定で有効化できるだけでなく、外観もカスタマイズできます。';
// ... ... Setting: Block region width for Footer region.
$string['blockregionfooterwidth'] = 'Footer 領域の幅';
$string['blockregionfooterwidth_desc'] = 'この設定では、フッター領域の幅を設定できます。「全幅」「コースコンテンツ幅」「ヒーロー幅」から選択できます。';
// ... ... Setting: Outside regions horizontal placement.
$string['outsideregionsplacement'] = 'Outside 領域の横方向配置';
$string['outsideregionsplacement_desc'] = 'この設定では、大画面で「Outside（左）」「Outside（右）」領域をメインコンテンツの近くに配置するか、ウィンドウ端に配置するかを制御できます。';
$string['outsideregionsplacementnextmaincontent'] = 'Outside（左・右）領域をメインコンテンツの横に配置する';
$string['outsideregionsplacementnearwindowedges'] = 'Outside（左・右）領域をウィンドウ端に配置する';
// ... ... Setting: Outside regions vertical alignment.
$string['outsideregionsverticalalignment'] = 'Outside 領域の縦方向整列';
$string['outsideregionsverticalalignment_desc'] = 'この設定では、大画面で「Outside（左）」「Outside（右）」領域をページコンテンツと縦方向に揃えるかどうかを制御できます。';
$string['outsideregionsverticalaligndefault'] = 'なし';
$string['outsideregionsverticalalignpagecontent'] = 'Outside（左・右）領域をページコンテンツと縦方向に揃える';
// ... ... Setting: Outside regions wrapping.
$string['outsideregionswrap'] = 'Outside 領域の折り返し';
$string['outsideregionswrap_desc'] = 'この設定では、小画面で「Outside（左）」「Outside（右）」領域をメインコンテンツの上下どちらに折り返すかを制御できます。';
$string['outsideregionswrap_abovebelow'] = 'Outside 領域をメインコンテンツの上と下に折り返す';
$string['outsideregionswrap_bothbelow'] = 'Outside 領域をメインコンテンツの下にまとめて折り返す';
// ... Section: Site home right-hand block drawer behaviour.
$string['sitehomerighthandblockdrawerbehaviour'] = 'サイトホーム右側ブロックドロワー';
// ... ... Setting: Show right-hand block drawer of site home on visit.
$string['showsitehomerighthandblockdraweronvisitsetting'] = 'サイトホーム訪問時に右側ブロックドロワーを表示する';
$string['showsitehomerighthandblockdraweronvisitsetting_desc'] = 'この設定を有効にすると、未ログインユーザがサイトホームを訪れた際、右側ブロックドロワーがデフォルトで展開された状態で表示されます。個々のユーザのトグル状態は上書きされません。';
// ... ... Setting: Show right-hand block drawer of site home on first login.
$string['showsitehomerighthandblockdraweronfirstloginsetting'] = '初回ログイン時に右側ブロックドロワーを表示する';
$string['showsitehomerighthandblockdraweronfirstloginsetting_desc'] = 'この設定を有効にすると、ユーザが初めてログインした際に、右側ブロックドロワーが展開された状態で表示されます。個々のユーザのトグル状態は上書きされません。';
// ... ... Setting: Show right-hand block drawer of site home on guest login.
$string['showsitehomerighthandblockdraweronguestloginsetting'] = 'ゲストログイン時に右側ブロックドロワーを表示する';
$string['showsitehomerighthandblockdraweronguestloginsetting_desc'] = 'この設定を有効にすると、ゲストとしてログインしたユーザに対して、右側ブロックドロワーが展開された状態で表示されます。';

// Settings: Page layouts tab.
$string['pagelayoutstab'] = 'ページレイアウト';
// ... Section: tool_policy heading.
$string['policyheading'] = 'ポリシー';
// ... ... Setting: Navigation on policy overview page.
$string['policyoverviewnavigationsetting'] = 'ポリシー概要ページにナビゲーションを表示する';
$string['policyoverviewnavigationsetting_desc'] = 'デフォルトでは、<a href="{$a->url}">tool_policy が提供するポリシー概要ページ</a> にはナビゲーションメニューやフッターが表示されません。この設定を有効にすると、プライマリナビゲーションとフッターを表示できます。';

// Settings: Links tab.
$string['linkstab'] = 'リンク';
// ... Section: Special links markup.
$string['speciallinksmarkupheading'] = '特別リンクのマークアップ';
// ... ... Setting: Mark external links.
$string['markexternallinkssetting'] = '外部リンクをマークする';
$string['markexternallinkssetting_desc'] = 'Moodle 外部へ移動するリンクの後に「外部リンク」アイコンを追加します。';
// ... ... Setting: Mark external links scope.
$string['markexternallinksscopesetting'] = '外部リンクのマーク範囲';
$string['markexternallinksscopesetting_desc'] = '外部リンクのマークを適用する範囲を制御します。デフォルトでは Moodle 全体に適用されますが、エッジケースを避けるために範囲を限定することもできます。';
$string['marklinksscopesetting_wholepage'] = 'ページ全体';
$string['marklinksscopesetting_coursemain'] = 'コースメインページのメインコンテンツ領域のみ';
// ... ... Setting: Mark mailto links.
$string['markmailtolinkssetting'] = 'mailto リンクをマークする';
$string['markmailtolinkssetting_desc'] = 'mailto リンクの前に「封筒」アイコンを追加します。';
// ... ... Setting: Mark mailto links scope.
$string['markmailtolinksscopesetting'] = 'mailto リンクのマーク範囲';
$string['markmailtolinksscopesetting_desc'] = 'mailto リンクのマークを適用する範囲を制御します。';
// ... ... Setting: Mark broken links.
$string['markbrokenlinkssetting'] = '壊れたリンクをマークする';
$string['markbrokenlinkssetting_desc'] = '処理されていないドラフトファイルなど、壊れたリンクの前に「壊れたチェーン」アイコンを追加し、Bootstrap の danger 色で強調表示します。この設定は範囲を限定できません。';

// Settings: Misc tab.
$string['misctab'] = 'その他';
// ... Section: JavaScript.
$string['javascriptheading'] = 'JavaScript';
// ... ... Setting: JavaScript disabled hint.
$string['javascriptdisabledhint'] = 'JavaScript 無効時の警告';
$string['javascriptdisabledhint_desc'] = 'ブラウザで JavaScript が無効になっている場合、ページ上部に警告を表示します。Moodle の多くの機能は JavaScript が必要です。';
$string['javascriptdisabledhinttext'] = 'ブラウザで JavaScript が無効になっています。<br />Moodle の多くの機能が使用できないか、正しく動作しません。<br />Moodle を快適に利用するには JavaScript を有効にしてください。';

// Settings: Content page.
$string['configtitlecontent'] = 'コンテンツ';

// Settings: Footer tab.
$string['footertab'] = 'フッター';
// ... Section: Footnote.
$string['footnoteheading'] = 'フットノート';
// ... ... Setting: Footnote.
$string['footnotesetting'] = 'フットノート';
$string['footnotesetting_desc'] = 'ここに入力した内容は、「drawers」「columns2」「login」レイアウトを使用するページのフッター（固定フッターではない）に表示されます。著作権表示、利用規約、組織名などに利用できます。削除したい場合は空欄にしてください。';
// ... Section: Footer.
$string['footerheading'] = 'フッター';
// ... ... Setting: Enable footer.
$string['enablefooterbutton'] = 'フッターボタンを有効にする';
$string['enablefooterbutton_desc'] = 'ここでいう「フッター」とは、ページ下部の「？」アイコンのことです。クリックするとオーバーレイが表示され、「このページのドキュメント」「データ保持サマリー」などのリンクが表示されます。この設定では、このフッターボタンを表示するかどうかを制御します。';
$string['enablefooterbutton_note'] = '注意: <a href="{$a->url}">Moodle コア設定 additionalhtmlfooter</a> の内容は通常フッター内に表示されます。しかし、ここで「すべてのデバイスで非表示」を選択すると、additionalhtmlfooter の内容がどこにも表示されなくなります。この問題を回避するため、Boost Union はその内容をページ最下部に移動して表示します。';
$string['enablefooterbuttonboth'] = 'デスクトップ・タブレット・モバイルで表示';
$string['enablefooterbuttondesktop'] = 'デスクトップ・タブレットで表示、モバイルでは非表示（Moodle コアと同じ）';
$string['enablefooterbuttonmobile'] = 'モバイルで表示、デスクトップ・タブレットでは非表示';
$string['enablefooterbuttonhidden'] = 'すべてのデバイスで非表示';
// ... ... Setting: Suppress icons in front of the footer links.
$string['footersuppressiconssetting'] = 'フッターリンクのアイコンを非表示にする';
$string['footersuppressiconssetting_desc'] = 'フッターリンクの前に表示されるアイコン（例: 本アイコン、救命浮輪アイコンなど）をすべて非表示にします。';
// ... ... Setting: Suppress 'Chat to course participants' link.
$string['footersuppresschatsetting'] = '「コース参加者とチャット」リンクを非表示にする';
$string['footersuppresschatsetting_desc'] = 'コース設定でコミュニケーションルームが追加されている場合に表示される「コース参加者とチャット」リンクを非表示にします。';
// ... ... Setting: Suppress 'Documentation for this page' link.
$string['footersuppresshelpsetting'] = '「このページのドキュメント」リンクを非表示にする';
$string['footersuppresshelpsetting_desc'] = 'Moodle Docs のルートが設定されている場合に表示される「このページのドキュメント」リンクを非表示にします。';
// ... ... Setting: Suppress 'Services and support' link.
$string['footersuppressservicessetting'] = '「サービスとサポート」リンクを非表示にする';
$string['footersuppressservicessetting_desc'] = '管理者向けに表示される「サービスとサポート」リンクを非表示にします。';
// ... ... Setting: Suppress 'Contact site support' link.
$string['footersuppresscontactsetting'] = '「サイトサポートへの連絡」リンクを非表示にする';
$string['footersuppresscontactsetting_desc'] = 'サイトサポートリンクが設定されている場合に表示される「サイトサポートへの連絡」リンクを非表示にします。';
// ... ... Setting: Suppress Login info.
$string['footersuppresslogininfosetting'] = 'ログイン情報を非表示にする';
$string['footersuppresslogininfosetting_desc'] = 'フッターに表示される「プロフィール」「ログアウト」などのログイン情報を非表示にします。';
// ... ... Setting: Suppress 'Reset user tour on this page' link.
$string['footersuppressusertoursetting'] = '「このページのユーザツアーをリセット」リンクを非表示にする';
$string['footersuppressusertoursetting_desc'] = 'ユーザツアーをリセットするためのリンクを非表示にします。';
// ... ... Setting: Suppress telemetry trace ID link.
$string['footersuppresstelemetrytraceidsetting'] = 'テレメトリートレース ID を非表示にする';
$string['footersuppresstelemetrytraceidsetting_desc'] = 'テレメトリートレース ID の表示を非表示にします（生成自体は抑制されません）。';
// ... ... Setting: Suppress theme switcher links.
$string['footersuppressthemeswitchsetting'] = 'テーマ切り替えリンクを非表示にする';
$string['footersuppressthemeswitchsetting_desc'] = 'デバイス別テーマ機能は Moodle 4.3 で削除されましたが、出力処理は残っているため、テーマ切り替えリンクを非表示にできます。';
// ... ... Setting: Suppress 'Powered by Moodle' link.
$string['footersuppresspoweredsetting'] = '「Powered by Moodle」リンクを非表示にする';
$string['footersuppresspoweredsetting_desc'] = 'Moodle を使用していることを示す「Powered by Moodle」リンクを非表示にします。';
// ... ... Setting: Suppress footer output by core components.
$string['footersuppressstandardfootercore'] = 'コアコンポーネント「{$a}」のフッター出力を非表示にする';
$string['footersuppressstandardfootercore_desc'] = 'コアコンポーネント「{$a}」がフッターに追加する可能性のある出力を非表示にします。';
// ... ... Setting: Suppress footer output by plugins.
$string['footersuppressstandardfooter'] = 'プラグイン「{$a}」のフッター出力を非表示にする';
$string['footersuppressstandardfooter_desc'] = 'プラグイン（Moodle コアに含まれるものも含む）がフッターに追加する可能性のある出力を非表示にします。注意: この設定は保存後、2 回目のページ読み込みで反映される場合があります。';
$string['footersuppressstandardfooter_configoverride_desc'] = 'プラグイン「{$a}」のフッター出力は、すでに <code>$CFG->hooks_callback_overrides</code> により <code>config.php</code> で抑制されています。ここで設定を管理したい場合は、config.php の該当設定を削除してください。';

// Settings: Static pages tab.
$string['staticpagestab'] = '静的ページ';
// ... Section: About us.
$string['aboutusheading'] = 'About us';
// ... ... Setting: Enable about us page.
$string['enableaboutussetting'] = 'About us ページを有効にする';
$string['aboutusdisabled'] = 'このサイトでは About us ページが無効になっています。表示する内容はありません。';
// ... ... Setting: About us content.
$string['aboutuscontentsetting'] = 'About us ページの内容';
$string['aboutuscontentsetting_desc'] = 'この設定では、About us ページに表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: About us page title.
$string['aboutuspagetitledefault'] = 'About us';
$string['aboutuspagetitlesetting'] = 'About us ページのタイトル';
$string['aboutuspagetitlesetting_desc'] = 'この設定では、About us ページのタイトルを定義できます。このタイトルは、後述の「About us リンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: About us link position.
$string['aboutuslinkpositionnone'] = 'About us ページへのリンクを自動表示しない';
$string['aboutuslinkpositionfootnote'] = 'フットノートに About us ページへのリンクを追加する';
$string['aboutuslinkpositionfooter'] = 'フッター（？アイコン）に About us ページへのリンクを追加する';
$string['aboutuslinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['aboutuslinkpositionsetting'] = 'About us リンク位置';
$string['aboutuslinkpositionsetting_desc'] = 'この設定では、About us ページへのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';
// ... Section: Offers.
$string['offersheading'] = 'Offers';
// ... ... Setting: Enable offers page.
$string['enableofferssetting'] = 'Offers ページを有効にする';
$string['offersdisabled'] = 'このサイトでは Offers ページが無効になっています。表示する内容はありません。';
// ... ... Setting: Offers content.
$string['offerscontentsetting'] = 'Offers ページの内容';
$string['offerscontentsetting_desc'] = 'この設定では、Offers ページに表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: Offers page title.
$string['offerspagetitledefault'] = 'Offers';
$string['offerspagetitlesetting'] = 'Offers ページのタイトル';
$string['offerspagetitlesetting_desc'] = 'この設定では、Offers ページのタイトルを定義できます。このタイトルは、後述の「Offers リンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Offers link position.
$string['offerslinkpositionnone'] = 'Offers ページへのリンクを自動表示しない';
$string['offerslinkpositionfootnote'] = 'フットノートに Offers ページへのリンクを追加する';
$string['offerslinkpositionfooter'] = 'フッター（？アイコン）に Offers ページへのリンクを追加する';
$string['offerslinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['offerslinkpositionsetting'] = 'Offers リンク位置';
$string['offerslinkpositionsetting_desc'] = 'この設定では、Offers ページへのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';
// ... Section: Imprint.
$string['imprintheading'] = 'Imprint';
// ... ... Setting: Enable imprint.
$string['enableimprintsetting'] = 'Imprint ページを有効にする';
$string['imprintdisabled'] = 'このサイトでは Imprint ページが無効になっています。表示する内容はありません。';
// ... ... Setting: Imprint content.
$string['imprintcontentsetting'] = 'Imprint ページの内容';
$string['imprintcontentsetting_desc'] = 'この設定では、Imprint ページに表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: Imprint page title.
$string['imprintpagetitledefault'] = 'Imprint';
$string['imprintpagetitlesetting'] = 'Imprint ページのタイトル';
$string['imprintpagetitlesetting_desc'] = 'この設定では、Imprint ページのタイトルを定義できます。このタイトルは、後述の「Imprint リンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Imprint link position.
$string['imprintlinkpositionnone'] = 'Imprint ページへのリンクを自動表示しない';
$string['imprintlinkpositionfootnote'] = 'フットノートに Imprint ページへのリンクを追加する';
$string['imprintlinkpositionfooter'] = 'フッター（？アイコン）に Imprint ページへのリンクを追加する';
$string['imprintlinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['imprintlinkpositionsetting'] = 'Imprint リンク位置';
$string['imprintlinkpositionsetting_desc'] = 'この設定では、Imprint ページへのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';
// ... Section: Contact page.
$string['contactheading'] = 'Contact';
// ... ... Setting: Enable contact page.
$string['enablecontactsetting'] = 'Contact ページを有効にする';
$string['contactdisabled'] = 'このサイトでは Contact ページが無効になっています。表示する内容はありません。';
// ... ... Setting: Contact page content.
$string['contactcontentsetting'] = 'Contact ページの内容';
$string['contactcontentsetting_desc'] = 'この設定では、Contact ページに表示するリッチテキストコンテンツを追加できます（Moodle 標準の「サイトサポートへの連絡」ページとは異なります）。';
// ... ... Setting: Contact page title.
$string['contactpagetitledefault'] = 'Contact';
$string['contactpagetitlesetting'] = 'Contact ページのタイトル';
$string['contactpagetitlesetting_desc'] = 'この設定では、Contact ページのタイトルを定義できます。このタイトルは、後述の「Contact ページリンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Contact page link position.
$string['contactlinkpositionnone'] = 'Contact ページへのリンクを自動表示しない';
$string['contactlinkpositionfootnote'] = 'フットノートに Contact ページへのリンクを追加する';
$string['contactlinkpositionfooter'] = 'フッター（？アイコン）に Contact ページへのリンクを追加する';
$string['contactlinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['contactlinkpositionsetting'] = 'Contact ページリンク位置';
$string['contactlinkpositionsetting_desc'] = 'この設定では、Contact ページへのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';
// ... Section: Help page.
$string['helpheading'] = 'Help';
// ... ... Setting: Enable help page.
$string['enablehelpsetting'] = 'Help ページを有効にする';
$string['helpdisabled'] = 'このサイトでは Help ページが無効になっています。表示する内容はありません。';
// ... ... Setting: Help page content.
$string['helpcontentsetting'] = 'Help ページの内容';
$string['helpcontentsetting_desc'] = 'この設定では、Help ページに表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: Help page title.
$string['helppagetitledefault'] = 'Help';
$string['helppagetitlesetting'] = 'Help ページのタイトル';
$string['helppagetitlesetting_desc'] = 'この設定では、Help ページのタイトルを定義できます。このタイトルは、後述の「Help ページリンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Help page link position.
$string['helplinkpositionnone'] = 'Help ページへのリンクを自動表示しない';
$string['helplinkpositionfootnote'] = 'フットノートに Help ページへのリンクを追加する';
$string['helplinkpositionfooter'] = 'フッター（？アイコン）に Help ページへのリンクを追加する';
$string['helplinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['helplinkpositionsetting'] = 'Help ページリンク位置';
$string['helplinkpositionsetting_desc'] = 'この設定では、Help ページへのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';
// ... Section: Maintenance page.
$string['maintenanceheading'] = 'Maintenance';
// ... ... Setting: Enable maintenance page.
$string['enablemaintenancesetting'] = 'メンテナンス情報ページを有効にする';
$string['maintenancedisabled'] = 'このサイトではメンテナンス情報ページが無効になっています。表示する内容はありません。';
// ... ... Setting: Maintenance page content.
$string['maintenancecontentsetting'] = 'メンテナンス情報ページの内容';
$string['maintenancecontentsetting_desc'] = 'この設定では、メンテナンス情報ページに表示するリッチテキストコンテンツを追加できます（Moodle 標準のメンテナンスモードページとは異なります）。';
// ... ... Setting: Maintenance page title.
$string['maintenancepagetitledefault'] = 'Maintenance';
$string['maintenancepagetitlesetting'] = 'メンテナンス情報ページのタイトル';
$string['maintenancepagetitlesetting_desc'] = 'この設定では、メンテナンス情報ページのタイトルを定義できます。このタイトルは、後述の「メンテナンス情報ページリンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Maintenance page link position.
$string['maintenancelinkpositionnone'] = 'メンテナンス情報ページへのリンクを自動表示しない';
$string['maintenancelinkpositionfootnote'] = 'フットノートにメンテナンス情報ページへのリンクを追加する';
$string['maintenancelinkpositionfooter'] = 'フッター（？アイコン）にメンテナンス情報ページへのリンクを追加する';
$string['maintenancelinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
// ... Section: Generic page 1.
$string['page1heading'] = 'Generic page 1';
// .// ... Section: Generic page 1.
$string['page1heading'] = '汎用ページ 1';
// ... ... Setting: Enable generic page 1.
$string['enablepage1setting'] = '汎用ページ 1 を有効にする';
$string['page1disabled'] = 'このサイトでは汎用ページ 1 が無効になっています。表示する内容はありません。';
// ... ... Setting: Generic page 1 content.
$string['page1contentsetting'] = '汎用ページ 1 の内容';
$string['page1contentsetting_desc'] = 'この設定では、汎用ページ 1 に表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: Generic page 1 title.
$string['page1pagetitledefault'] = '汎用ページ 1';
$string['page1pagetitlesetting'] = '汎用ページ 1 のタイトル';
$string['page1pagetitlesetting_desc'] = 'この設定では、汎用ページ 1 のタイトルを定義できます。このタイトルは、後述の「汎用ページ 1 リンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Generic page 1 link position.
$string['page1linkpositionnone'] = '汎用ページ 1 へのリンクを自動表示しない';
$string['page1linkpositionfootnote'] = 'フットノートに汎用ページ 1 へのリンクを追加する';
$string['page1linkpositionfooter'] = 'フッター（？アイコン）に汎用ページ 1 へのリンクを追加する';
$string['page1linkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['page1linkpositionsetting'] = '汎用ページ 1 のリンク位置';
$string['page1linkpositionsetting_desc'] = 'この設定では、汎用ページ 1 へのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';
// ... Section: Generic page 2.
$string['page2heading'] = '汎用ページ 2';
// ... ... Setting: Enable generic page 2.
$string['enablepage2setting'] = '汎用ページ 2 を有効にする';
$string['page2disabled'] = 'このサイトでは汎用ページ 2 が無効になっています。表示する内容はありません。';
// ... ... Setting: Generic page 2 content.
$string['page2contentsetting'] = '汎用ページ 2 の内容';
$string['page2contentsetting_desc'] = 'この設定では、汎用ページ 2 に表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: Generic page 2 title.
$string['page2pagetitledefault'] = '汎用ページ 2';
$string['page2pagetitlesetting'] = '汎用ページ 2 のタイトル';
$string['page2pagetitlesetting_desc'] = 'この設定では、汎用ページ 2 のタイトルを定義できます。このタイトルは、後述の「汎用ページ 2 リンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Generic page 2 link position.
$string['page2linkpositionnone'] = '汎用ページ 2 へのリンクを自動表示しない';
$string['page2linkpositionfootnote'] = 'フットノートに汎用ページ 2 へのリンクを追加する';
$string['page2linkpositionfooter'] = 'フッター（？アイコン）に汎用ページ 2 へのリンクを追加する';
$string['page2linkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['page2linkpositionsetting'] = '汎用ページ 2 のリンク位置';
$string['page2linkpositionsetting_desc'] = 'この設定では、汎用ページ 2 へのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';
// ... Section: Generic page 3.
$string['page3heading'] = '汎用ページ 3';
// ... ... Setting: Enable generic page 3.
$string['enablepage3setting'] = '汎用ページ 3 を有効にする';
$string['page3disabled'] = 'このサイトでは汎用ページ 3 が無効になっています。表示する内容はありません。';
// ... ... Setting: Generic page 3 content.
$string['page3contentsetting'] = '汎用ページ 3 の内容';
$string['page3contentsetting_desc'] = 'この設定では、汎用ページ 3 に表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: Generic page 3 title.
$string['page3pagetitledefault'] = '汎用ページ 3';
$string['page3pagetitlesetting'] = '汎用ページ 3 のタイトル';
$string['page3pagetitlesetting_desc'] = 'この設定では、汎用ページ 3 のタイトルを定義できます。このタイトルは、後述の「汎用ページ 3 リンク位置」設定でリンクを表示する場合、そのリンクテキストとしても使用されます。';
// ... ... Setting: Generic page 3 link position.
$string['page3linkpositionnone'] = '汎用ページ 3 へのリンクを自動表示しない';
$string['page3linkpositionfootnote'] = 'フットノートに汎用ページ 3 へのリンクを追加する';
$string['page3linkpositionfooter'] = 'フッター（？アイコン）に汎用ページ 3 へのリンクを追加する';
$string['page3linkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['page3linkpositionsetting'] = '汎用ページ 3 のリンク位置';
$string['page3linkpositionsetting_desc'] = 'この設定では、汎用ページ 3 へのリンクを Moodle ページに自動追加するかどうかを制御します。自動追加しない場合は、{$a->url} へのリンクを任意の場所に手動で追加できます。';

// Settings: Info banners tab.
$string['infobannertab'] = 'インフォバナー';
// ... Section: Info banners.
$string['infobannerheading'] = 'インフォバナー {$a->no}';
$string['infobannerpageloginpage'] = 'ログインページ';
$string['infobannermodeperpetual'] = '常時表示';
$string['infobannermodetimebased'] = '時間制御';
$string['bootstrapprimarycolor'] = 'Primary（主要）カラー';
$string['bootstrapsecondarycolor'] = 'Secondary（補助）カラー';
$string['bootstrapsuccesscolor'] = 'Success（成功）カラー';
$string['bootstrapdangercolor'] = 'Danger（警告）カラー';
$string['bootstrapwarningcolor'] = 'Warning（注意）カラー';
$string['bootstrapinfocolor'] = 'Info（情報）カラー';
$string['bootstraplightcolor'] = 'Light（明色）';
$string['bootstrapdarkcolor'] = 'Dark（暗色）';
$string['bootstrapnone'] = 'Bootstrap カラーなし';
$string['infobannerclose'] = '閉じる';
$string['infobannerdismissreset'] = '非表示にしたインフォバナーの可視性をリセットする';
$string['infobannerdismissresetbutton'] = 'インフォバナー {$a->no} の可視性をリセットする';
$string['infobannerdismissconfirm'] = '本当にインフォバナー {$a->no} の可視性をリセットし、非表示にしたすべてのユーザに再表示しますか？';
$string['infobannerdismisssuccess'] = 'インフォバナー {$a->no} の可視性がリセットされました';
$string['infobannerdismissfail'] = 'インフォバナー {$a->no} の可視性リセットが一部ユーザで失敗しました';
$string['error:infobannerdismissnonotvalid'] = '指定されたインフォバナー番号は無効です';
$string['error:infobannerdismissnonotdismissible'] = '指定されたインフォバナーは非表示可能ではありません';
$string['infobannerenabledsetting'] = 'インフォバナー {$a->no} を有効にする';
$string['infobannerenabledsetting_desc'] = 'この設定では、インフォバナー {$a->no} を有効にできます。';
$string['infobannercontentsetting'] = 'インフォバナー {$a->no} の内容';
$string['infobannercontentsetting_desc'] = 'ここに入力した内容がインフォバナー {$a->no} に表示されます。';
$string['infobannerpagessetting'] = 'インフォバナー {$a->no} を表示するページレイアウト';
$string['infobannerpagessetting_desc'] = 'この設定では、インフォバナー {$a->no} を表示するページレイアウトを選択できます。';
$string['infobannerbsclasssetting'] = 'インフォバナー {$a->no} の Bootstrap クラス';
$string['infobannerbsclasssetting_desc'] = 'インフォバナー {$a->no} に適用する Bootstrap スタイルを選択します。「Bootstrap カラーなし」を選ぶと、リッチテキストエディタで自由にスタイルを設定できます。';
$string['infobannerordersetting'] = 'インフォバナー {$a->no} の表示順';
$string['infobannerordersetting_desc'] = 'インフォバナー {$a->no} の表示順を設定します。同じ順序番号を複数のバナーに設定した場合、設定ページの並び順に従って再調整されます。ページヘッダーの上下に配置される場合、それぞれ別に並び替えられます。';
$string['infobannermodesetting'] = 'インフォバナー {$a->no} の表示モード';
$string['infobannermodesetting_desc'] = 'インフォバナー {$a->no} を常時表示するか、指定した期間のみ表示するかを設定します。';
$string['infobannerdismissiblesetting'] = 'インフォバナー {$a->no} をユーザが閉じられるようにする';
$string['infobannerdismissiblesetting_desc'] = 'この設定を有効にすると、ユーザがインフォバナー {$a->no} を閉じることができます。閉じたバナーは自動では再表示されません。再表示したい場合は「可視性リセット」ボタンを使用してください。';
$string['infobannerstartsetting'] = 'インフォバナー {$a->no} の開始時刻';
$string['infobannerstartsetting_desc'] = 'インフォバナー {$a->no} を表示開始する時刻を設定します。サーバー時刻で解釈されます。';
$string['infobannerendsetting'] = 'インフォバナー {$a->no} の終了時刻';
$string['infobannerendsetting_desc'] = 'インフォバナー {$a->no} を表示終了する時刻を設定します。サーバー時刻で解釈されます。';
$string['infobannerpositionsetting'] = 'インフォバナー {$a->no} のページヘッダーに対する位置';
$string['infobannerpositionsetting_desc'] = 'インフォバナー {$a->no} をページヘッダーの上に表示するか、下に表示するかを設定します。ログインページにはヘッダーがないため、この設定は影響しません。';
$string['infobannerpositionabove'] = 'ページヘッダーの上';
$string['infobannerpositionbelow'] = 'ページヘッダーの下';
// Settings: Advertisement tiles tab.
$string['tilestab'] = '広告タイル';
// ... Section: Advertisement tiles general.
$string['tilesgeneralheading'] = '広告タイル（全般）';
$string['tilecolumnssetting'] = '1 行あたりの広告タイル列数';
$string['tilecolumnssetting_desc'] = '広告タイルを並べる際の列数を設定します。この列数はデスクトップなどの大画面に適用され、小さい画面では自動的に折り返されます。';
$string['tilefrontpagepositionsetting'] = 'サイトホームでの広告タイルの表示位置';
$string['tilefrontpagepositionsetting_desc'] = '広告タイルはサイトホームにのみ表示されます。この設定では、サイトホームコンテンツの前に表示するか、後に表示するかを制御できます。広告タイルのみを表示したい場合は、<a href="{$a->url}">サイトホーム設定</a> で他のコンテンツを非表示にできます。';
$string['tilefrontpagepositionsetting_before'] = 'サイトホームコンテンツの前';
$string['tilefrontpagepositionsetting_after'] = 'サイトホームコンテンツの後';
$string['tileheightsetting'] = '広告タイルの高さ';
$string['tileheightsetting_desc'] = '広告タイルの最小高さを設定します。タイル内のコンテンツがこの高さを超える場合、行全体が自動的に拡張されます。';
// ... Section: Advertisement tiles.
$string['tileheading'] = '広告タイル {$a->no}';
$string['tilebackgroundimagepositionsetting'] = '広告タイル {$a->no} の背景画像位置';
$string['tilebackgroundimagepositionsetting_desc'] = '広告タイル {$a->no} の背景画像の表示位置を設定します。最初の値が横方向、2つ目が縦方向です。';
$string['tilebackgroundimagesizesetting'] = '広告タイル {$a->no} の背景画像サイズ';
$string['tilebackgroundimagesizesetting_desc'] = '背景画像の拡大縮小方法を設定します。「Cover」はタイル全体を埋めます（画像が切れる場合があります）。「Contain」は画像全体を表示します（余白が出る場合があります）。「Auto」は元のサイズを使用します。パーセンテージ指定も可能です。';
$string['tilebackgroundimagesizesetting_auto'] = 'Auto';
$string['tilebackgroundimagesizesetting_contain'] = 'Contain';
$string['tilebackgroundimagesizesetting_cover'] = 'Cover';
$string['tilebackgroundimagesizesetting_90percent'] = '90%';
$string['tilebackgroundimagesizesetting_75percent'] = '75%';
$string['tilebackgroundimagesizesetting_50percent'] = '50%';
$string['tilebackgroundimagesizesetting_25percent'] = '25%';
$string['tilebackgroundimagesetting'] = '広告タイル {$a->no} の背景画像';
$string['tilebackgroundimagesetting_desc'] = '広告タイル {$a->no} の背景として表示する画像をアップロードできます。背景画像なしでもタイルは表示されますが、内容が読みやすいか確認してください。';
$string['tilecontentsetting'] = '広告タイル {$a->no} の内容';
$string['tilecontentsetting_desc'] = '広告タイル {$a->no} に表示する内容を入力します。内容はタイル中央に表示されます。設定しなくてもタイルは表示されます。';
$string['tilecontentstylesetting'] = '広告タイル {$a->no} の内容スタイル';
$string['tilecontentstylesetting_dark'] = 'Dark（明るい背景向けの黒文字）';
$string['tilecontentstylesetting_darkshadow'] = 'Dark + Shadow（明るい背景向けの黒文字 + 薄い影）';
$string['tilecontentstylesetting_desc'] = 'ここでは、広告タイル {$a->no} に表示されるコンテンツのスタイルを変更できます。通常、コンテンツのスタイルは上のリッチテキストエディタで設定した内容が適用されます。しかし、特に背景画像の上にテキストを配置する場合など、統一したスタイルを簡単に適用したいときには、ここでスタイルを上書きできます。';
$string['tilecontentstylesetting_nochange'] = '変更しない（リッチテキストエディタで設定）';
$string['tilecontentstylesetting_light'] = 'Light（暗い背景向けの白文字）';
$string['tilecontentstylesetting_lightshadow'] = 'Light + Shadow（暗い背景向けの白文字 + 濃い影）';
$string['tileenabledsetting'] = '広告タイル {$a->no} を有効にする';
$string['tileenabledsetting_desc'] = '広告タイル {$a->no} を有効にします。';
$string['tilelinksetting'] = '広告タイル {$a->no} のリンク URL';
$string['tilelinksetting_desc'] = 'ここでは、広告タイル {$a->no} の末尾に表示されるリンクボタンに設定する URL（Moodle 内部リンクまたは外部リンク）を指定できます。これは任意の設定であり、リンク URL を設定しなくても広告タイル自体は問題なく動作します。';
$string['tilelinktitlefallback'] = 'リンク';
$string['tilelinktitlesetting'] = '広告タイル {$a->no} のリンクタイトル';
$string['tilelinktitlesetting_desc'] = 'ここでは、広告タイル {$a->no} にリンク URL を設定した際に、リンクボタンに表示されるタイトル（ラベル）を指定できます。なお、リンク URL を設定してもリンクタイトルを設定しなかった場合、ボタンのラベルは「Link」と表示されます。';
$string['tilelinktargetsetting'] = '広告タイル {$a->no} のリンクターゲット';
$string['tilelinktargetsetting_desc'] = 'ここでは、広告タイル {$a->no} にリンク URL を設定した際、そのリンクボタンがどのウィンドウで開くか（同じウィンドウか新しいタブか）を指定できます。';
$string['tilelinktargetsetting_samewindow'] = '同じウィンドウ';
$string['tilelinktargetsetting_newtab'] = '新しいタブ';
$string['tileordersetting'] = '広告タイル {$a->no} の表示順序';
$string['tileordersetting_desc'] = 'この設定では、広告タイル {$a->no} の表示順序を指定できます。デフォルトでは、この設定ページに表示されている順番どおりに、上から下へ、左から右へ並びます。しかし、この設定を使って別の順序番号を割り当てることもできます。複数の広告タイルに同じ順序番号を割り当てた場合は、この設定ページ上の並び順に基づいて再度並べ替えられます。';
$string['tiletitlesetting'] = '広告タイル {$a->no} のタイトル';
$string['tiletitlesetting_desc'] = 'ここでは、広告タイル {$a->no} に表示するタイトルを入力します。これは任意の設定であり、タイトルを設定しなくても広告タイル自体は表示されます。';
// Settings: Slider tab.
$string['slidertab'] = 'スライダー';
// ... Section: Slider general.
$string['slidergeneralheading'] = 'スライダー（全般）';
$string['slideranimationsetting'] = 'スライダーのアニメーションタイプ';
$string['slideranimationsetting_desc'] = 'スライダーのアニメーションを設定します。「Slide」はスライドアニメーション、「Fade」はフェードアニメーションを適用します。';
$string['slideranimationsetting_fade'] = 'フェード';
$string['slideranimationsetting_slide'] = 'スライド';
$string['slidervariantsetting'] = 'スライダーのバリアント';
$string['slidervariantsetting_desc'] = 'この設定では、スライダーのバリアント（配色）を選択できます。ライトバリアントは暗い背景に適した明るい色のコントロール・インジケーター・キャプションを使用し、ダークバリアントは明るい背景に適した暗い色のこれらの要素を使用します。';
$string['slidervariantsetting_dark'] = 'Dark（明るい背景向け）';
$string['slidervariantsetting_light'] = 'Light（暗い背景向け）';
$string['sliderarrownavsetting'] = '矢印ナビゲーションを有効にする';
$string['sliderarrownavsetting_desc'] = 'スライダーの左右にナビゲーション矢印を表示します。';
$string['sliderfrontpagepositionsetting'] = 'サイトホームでのスライダー表示位置';
$string['sliderfrontpagepositionsetting_desc'] = 'スライダーはサイトホームにのみ表示されます。この設定では、スライダーをサイトホームのコンテンツより前に表示するか、後に表示するかを選択できます。もしサイトホームにスライダーだけを表示したい場合は、<a href="{$a->url}">サイトホーム設定</a>を変更して、他のサイトホームコンテンツをすべて非表示にできます。';
$string['sliderfrontpagepositionsetting_afterafter'] = 'サイトホームコンテンツの後（広告タイルの後）';
$string['sliderfrontpagepositionsetting_afterbefore'] = 'サイトホームコンテンツの後（広告タイルの前）';
$string['sliderfrontpagepositionsetting_beforeafter'] = 'サイトホームコンテンツの前（広告タイルの後）';
$string['sliderfrontpagepositionsetting_beforebefore'] = 'サイトホームコンテンツの前（広告タイルの前）';
$string['sliderindicatornavsetting'] = 'スライダーインジケータナビゲーションを有効にする';
$string['sliderindicatornavsetting_desc'] = 'スライダー下部にインジケータ（ドット）を表示します。';
$string['sliderintervalsetting'] = 'スライダーの切り替え速度';
$string['sliderintervalsetting_desc'] = '各スライドの表示時間（ミリ秒）を設定します。最小 1000、最大 10000。';
$string['sliderkeyboardsetting'] = 'キーボード操作を許可する';
$string['sliderkeyboardsetting_desc'] = '矢印キーでスライダーを操作できるようにします。無効にするとアクセシビリティが低下します。';
$string['sliderpausesetting'] = 'マウスオーバーでスライダーを一時停止する';
$string['sliderpausesetting_desc'] = 'ユーザがスライドにマウスオーバーした際、自動切り替えを停止します。無効にするとアクセシビリティが低下します。';
$string['sliderridesetting'] = 'スライドの自動切り替え';
$string['sliderridesetting_desc'] = 'スライダーの自動切り替え動作を設定します。「ページ読み込み時」「ユーザ操作後」「自動切り替えなし」から選択できます。';
$string['sliderridesetting_afterinteraction'] = 'ユーザ操作後';
$string['sliderridesetting_never'] = '自動切り替えなし';
$string['sliderridesetting_onpageload'] = 'ページ読み込み時';
$string['sliderwrapsetting'] = 'スライドをループさせる';
$string['sliderwrapsetting_desc'] = '有効にすると最後のスライドの後に最初のスライドへ戻ります。無効にすると最後のスライドで停止します。';
// ... Section: Slides.
$string['slideheading'] = 'スライド {$a->no}';
$string['slidebackgroundimagealtsetting'] = 'スライド {$a->no} の背景画像 alt 属性';
$string['slidebackgroundimagealtsetting_desc'] = 'スライド {$a->no} の背景画像に alt 属性を設定できます。設定しなくてもスライドは表示されますが、アクセシビリティのため設定を推奨します。';
$string['slidebackgroundimagesetting'] = 'スライド {$a->no} の背景画像';
$string['slidebackgroundimagesetting_desc'] = 'スライド {$a->no} の背景として表示する画像をアップロードします。背景画像は必須で、設定しない場合スライドは表示されません。すべてのスライドで同じアスペクト比を使用すると高さの揺れを防げます。';
$string['slidecaptionsetting'] = 'スライド {$a->no} のキャプション';
$string['slidecaptionsetting_desc'] = 'スライド {$a->no} に表示するキャプションを入力します。設定しなくてもスライドは表示されます。';
$string['slidecontentsetting'] = 'スライド {$a->no} の内容';
$string['slidecontentsetting_desc'] = 'スライド {$a->no} に表示する内容を入力します。キャプションがある場合はその下に表示されます。内容が多すぎると小さい画面で隠れる場合があります。';
$string['slidecontentstylesetting'] = 'スライド {$a->no} の内容スタイル';
$string['slidecontentstylesetting_desc'] = 'スライド {$a->no} の内容スタイルを上書きできます。背景画像に応じて文字色を調整する際に便利です。';
$string['slidecontentstylesetting_dark'] = 'Dark（明るい背景向けの黒文字）';
$string['slidecontentstylesetting_darkshadow'] = 'Dark + Shadow（黒文字＋薄い影）';
$string['slidecontentstylesetting_light'] = 'Light（暗い背景向けの白文字）';
$string['slidecontentstylesetting_lightshadow'] = 'Light + Shadow（白文字＋濃い影）';
$string['slidecontentstylesetting_nochange'] = 'スライダーバリアントに従う';
$string['slideenabledsetting'] = 'スライド {$a->no} を有効にする';
$string['slideenabledsetting_desc'] = 'スライド {$a->no} を有効にします。';
$string['slidelinksetting'] = 'スライド {$a->no} のリンク URL';
$string['slidelinksetting_desc'] = 'スライド {$a->no} の内容全体にリンクを設定できます。設定しなくてもスライドは表示されます。';
$string['slidelinktitlesetting'] = 'スライド {$a->no} のリンクタイトル';
$string['slidelinktitlesetting_desc'] = 'スライド {$a->no} のリンクにマウスオーバーした際に表示されるツールチップを設定します。設定しなくてもリンクは動作しますが、アクセシビリティのため設定を推奨します。';
$string['slidelinksourcesetting'] = 'スライド {$a->no} のリンク対象';
$string['slidelinksourcesetting_desc'] = 'スライドのどの部分をリンクとして扱うかを設定します。背景画像のみ、テキストのみ、両方から選択できます。';
$string['slidelinksourcesetting_both'] = '背景画像とテキストの両方';
$string['slidelinksourcesetting_image'] = '背景画像のみ';
$string['slidelinksourcesetting_text'] = 'テキストのみ';
$string['slidelinktargetsetting'] = 'スライド {$a->no} のリンクターゲット';
$string['slidelinktargetsetting_desc'] = 'スライド {$a->no} のリンクを同じウィンドウで開くか、新しいタブで開くかを設定します。';
$string['slidelinktargetsetting_samewindow'] = '同じウィンドウ';
$string['slidelinktargetsetting_newtab'] = '新しいタブ';
$string['slideordersetting'] = 'スライド {$a->no} の表示順';
$string['slideordersetting_desc'] = 'スライド {$a->no} の表示順を設定します。同じ順序番号を複数のスライドに設定した場合、設定ページの並び順に従って再調整されます。';
$string['slideintervalsetting'] = 'スライド {$a->no} の個別インターバル';
$string['slideintervalsetting_desc'] = 'スライド {$a->no} の表示時間（ミリ秒）を個別に設定します。空欄の場合はスライダー全体の設定が適用されます。';

// Settings: Functionality page.
$string['configtitlefunctionality'] = '機能';

// Settings: Courses tab.
$string['coursestab'] = 'コース';
// ... Section: Course related hints for teachers.
$string['courserelatedhintsforteachersheading'] = '教師向けコース関連ヒント';
// ... ... Setting: Show hint for switched role setting.
$string['showswitchedroleincoursesetting'] = 'ロール切り替え時のヒントを表示する';
$string['showswitchedroleincoursesetting_desc'] = 'ユーザがコース内でロールを切り替えている場合、コースヘッダーにヒントを表示します。デフォルトではユーザメニューのアバター横にのみ表示されますが、この設定でコースページにも表示できます。';
$string['switchedroleto'] = '現在、このコースを <strong>{$a->role}</strong> のロールで閲覧しています。';
// ... ... Setting: Show hint for hidden course.
$string['showhintcoursehiddensetting'] = '非公開コースのヒントを表示する';
$string['showhintcoursehiddensetting_desc'] = 'コースが非公開の場合、コースヘッダーにヒントを表示します。コース設定を開かなくても公開状態を確認できます。';
$string['showhintcoursehiddengeneral'] = 'このコースは現在 <strong>非公開</strong> です。登録済み教師のみアクセスできます。';
$string['showhintcoursehiddensettingslink'] = '公開状態は <a href="{$a->url}">コース設定</a> で変更できます。';
// ... ... Setting: Show hint for forum notifications in hidden courses.
$string['showhintforumnotificationssetting'] = '非公開コースのフォーラム通知ヒントを表示する';
$string['showhintforumnotificationssetting_desc'] = 'コースが非公開の場合、フォーラム内にも通知が学生に送信されない旨のヒントを表示します。';
$string['showhintforumnotifications'] = 'このコースは現在 <strong>非公開</strong> です。そのため、フォーラムに投稿しても <strong>学生には通知されません</strong>。';
// ... ... Setting: Show hint for unrestricted self enrolment.
$string['showhintcourseselfenrolsetting'] = 'キーなし自己登録のヒントを表示する';
$string['showhintcourseselfenrolsetting_desc'] = 'コースが公開され、登録キーなしで自己登録が可能な場合、コースヘッダーにヒントを表示します。';
$string['showhintcourseselfenrolstartcurrently'] = 'このコースは現在公開されており、<strong>登録キーなしで自己登録</strong>が可能です。';
$string['showhintcourseselfenrolstartfuture'] = 'このコースは公開されており、将来的に <strong>登録キーなしで自己登録</strong> が可能になる予定です。';
$string['showhintcourseselfenrolunlimited'] = '<strong>{$a->name}</strong> 登録インスタンスは無期限で登録キーなしの自己登録を許可しています。';
$string['showhintcourseselfenroluntil'] = '<strong>{$a->name}</strong> 登録インスタンスは {$a->until} まで登録キーなしの自己登録を許可しています。';
$string['showhintcourseselfenrolfrom'] = '<strong>{$a->name}</strong> 登録インスタンスは {$a->from} から登録キーなしの自己登録を許可します。';
$string['showhintcourseselfenrolsince'] = '<strong>{$a->name}</strong> 登録インスタンスは現在、登録キーなしの自己登録を許可しています。';
$string['showhintcourseselfenrolfromuntil'] = '<strong>{$a->name}</strong> 登録インスタンスは {$a->from} から {$a->until} まで登録キーなしの自己登録を許可しています。';
$string['showhintcourseselfenrolsinceuntil'] = '<strong>{$a->name}</strong> 登録インスタンスは {$a->until} まで登録キーなしの自己登録を許可しています。';
$string['showhintcourseselfenrolinstancecallforaction'] = '誰でも自由にアクセスできないようにしたい場合は、自己登録設定を制限してください。';
// ... ... Setting: Show hint for guest enrolment.
$string['showhintcourseguestenrolsetting'] = 'ゲストアクセスのヒントを表示する';
$string['showhintcourseguestenrolsetting_desc'] = 'コースが公開され、ゲストアクセスが可能な場合、コースヘッダーにヒントを表示します。';
$string['showhintcourseguestenrolsetting_note'] = 'ヒントにコースへのリンクを追加したい場合、言語カスタマイズで {&dollar;a->courseid} プレースホルダを使用できます。';
$string['showhintcourseguestenrolsetting_withoutpassword'] = 'ゲストパスワードが設定されていない場合のみ表示する';
$string['showhintcourseguestenrolsetting_always'] = 'ゲストパスワードが設定されていても常に表示する';
$string['showhintcourseguestenrolhint'] = 'このコースは公開されており、<strong>ゲストパスワードなしでゲストアクセス</strong>が可能です。';
$string['showhintcourseguestenrolhintalways'] = 'このコースは公開されており、ゲストパスワードを知っていればゲストアクセスが可能です。';
$string['showhintcourseguestenrolauthonly'] = 'ログイン済み Moodle ユーザは、登録せずにこのコースの内容にアクセスできます。';
$string['showhintcourseguestenrolauthonlyalways'] = 'ログイン済み Moodle ユーザは、ゲストパスワードを知っていれば登録せずにこのコースにアクセスできます。';
$string['showhintcourseguestenroleveryone'] = 'Moodle アカウントを持たないユーザも含め、誰でも登録せずにこのコースにアクセスできます。';
$string['showhintcourseguestenroleveryonealways'] = 'Moodle アカウントを持たないユーザも、ゲストパスワードを知っていれば登録せずにこのコースにアクセスできます。';
$string['showhintcourseguestenrolcallforaction'] = '自由アクセスを避けたい場合は、ゲストアクセスを無効にするか、ゲストパスワードを設定してください。';
$string['showhintcourseguestenrolcallforactionalways'] = 'ゲストアクセスを許可したくない場合は、コース設定でゲストアクセスを無効にしてください。';
// ... Section: Course related hints for students.
$string['courserelatedhintsforstudentsheading'] = '学生向けコース関連ヒント';
// ... ... Setting: Show hint for guest access.
$string['showhintcoursguestaccesssetting'] = 'ゲストアクセス時のヒントを表示する';
$string['showhintcourseguestaccesssetting_desc'] = 'ユーザがゲストアクセスでコースを閲覧している場合、コースヘッダーにヒントを表示します。コースに自己登録が有効な場合は、そのページへのリンクも表示されます。';
$string['showhintcourseguestaccessgeneral'] = '現在、このコースを <strong>{$a->role}</strong> として閲覧しています。';
$string['showhintcourseguestaccesslink'] = 'コースに完全アクセスするには、<a href="{$a->url}">自己登録</a>できます。';

// Settings: Accessibility page.
$string['configtitleaccessibility'] = 'アクセシビリティ';

// Settings: Administration tab.
$string['administrationtab'] = '管理';
// ... Section: Course management.
$string['coursemanagementheading'] = 'コース管理';
// ... ... Setting: Show view course icon in course management.
$string['showviewcourseiconincoursemgntsetting'] = '「コースを表示」アイコンを表示する';
$string['showviewcourseiconincoursemgntsesetting_desc'] = 'デフォルトでは、<a href="{$a}">コース管理ページ</a>でコースを表示するには、コース詳細またはコース設定を開く必要があります。この設定を有効にすると、カテゴリ一覧に直接「コースを表示」アイコンを追加できます。';

// Settings: Declaration tab.
$string['accessibilitydeclarationtab'] = 'アクセシビリティ声明';
// ... Section: Declaration of accessibility page.
$string['accessibilitydeclarationheading'] = 'アクセシビリティ声明';
// ... ... Setting: Enable declaration of accessibility page.
$string['enableaccessibilitydeclarationsetting'] = 'アクセシビリティ声明ページを有効にする';
$string['enableaccessibilitydeclarationsetting_desc'] = 'この設定では、アクセシビリティ声明ページを有効にできます。他の Boost Union の静的ページと同様に動作します。';
$string['accessibilitydeclarationdisabled'] = 'このサイトではアクセシビリティ声明ページが無効になっています。表示する内容はありません。';
// ... ... Setting: Declaration of accessibility page content.
$string['accessibilitydeclarationcontentsetting'] = 'アクセシビリティ声明ページの内容';
$string['accessibilitydeclarationcontentsetting_desc'] = 'アクセシビリティ声明ページに表示するリッチテキストコンテンツを追加できます。';
// ... ... Setting: Declaration of accessibility page title.
$string['accessibilitydeclarationpagetitledefault'] = 'アクセシビリティ声明';
$string['accessibilitydeclarationpagetitlesetting'] = 'アクセシビリティ声明ページのタイトル';
$string['accessibilitydeclarationpagetitlesetting_desc'] = 'アクセシビリティ声明ページのタイトルを設定します。このタイトルはリンクテキストとしても使用されます。';
// ... ... Setting: Declaration of accessibility page link position.
$string['accessibilitydeclarationlinkpositionnone'] = 'アクセシビリティ声明ページへのリンクを自動表示しない';
$string['accessibilitydeclarationlinkpositionfootnote'] = 'フットノートにリンクを追加する';
$string['accessibilitydeclarationlinkpositionfooter'] = 'フッター（？アイコン）にリンクを追加する';
$string['accessibilitydeclarationlinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['accessibilitydeclarationlinkpositionsetting'] = 'アクセシビリティ声明ページのリンク位置';
$string['accessibilitydeclarationlinkpositionsetting_desc'] = 'アクセシビリティ声明ページへのリンクを自動追加するかどうかを設定します。自動追加しない場合は、{$a->url} へのリンクを手動で追加できます。';
// Settings: Support page tab.
$string['accessibilitysupporttab'] = 'サポートページ';
// ... Section: Accessibility support page.
$string['accessibilitysupportheading'] = 'アクセシビリティサポートページ';
$string['accessibilitysupportsubmit'] = '送信';
// ... ... Setting: Enable accessibility support page.
$string['enableaccessibilitysupportsetting'] = 'アクセシビリティサポートページを有効にする';
$string['enableaccessibilitysupportsetting_desc'] = 'この設定では、アクセシビリティサポートページを有効にできます。Moodle コアの「サイトサポートページ」と同様に動作します。';
$string['accessibilitysupportdisabled'] = 'このサイトではアクセシビリティサポートページが無効になっています。表示する内容はありません。';
$string['accessibilitysupportdefaultsubject'] = 'アクセシビリティに関するフィードバック';
$string['accessibilitysupportusermailsubject'] = 'アクセシビリティサポートリクエスト';
$string['accessibilitysupportmessagesent'] = 'アクセシビリティサポートリクエストが送信されました。';
$string['accessibilitysupportmessagenotsent'] = 'サポートリクエストを送信できませんでした。';
$string['accessibilitysupportmessagetryagain'] = '後でもう一度お試しください。';
$string['accessibilitysupportmessagetryalternative'] = '後でもう一度お試しください。または <a href="mailto:{$a}">{$a}</a> に直接メールを送信してください。';
// ... ... Setting: Accessibility support page content.
$string['accessibilitysupportcontentsetting'] = 'アクセシビリティサポートページの内容';
$string['accessibilitysupportcontentsetting_desc'] = 'アクセシビリティサポートページに表示するリッチテキストコンテンツを追加できます。フォームと併せて表示されます。';
$string['accessibilitysupportcontentdefault'] = '<p>アクセシビリティに関するフィードバックや障壁の報告がある場合は、以下のフォームをご利用ください。</p><p>スクリーンリーダー、拡大鏡、音声コントロール、音声認識ソフトなどの支援技術を使用している場合は、その種類を記載してください。また、リクエスト処理を助けるため、フォームに「参照元 URL」やブラウザ情報を自動送信することを許可できます。</p>';
// ... ... Setting: Accessibility support page title.
$string['accessibilitysupportpagetitledefault'] = 'アクセシビリティサポート';
$string['accessibilitysupportpagetitlesetting'] = 'アクセシビリティサポートページのタイトル';
$string['accessibilitysupportpagetitlesetting_desc'] = 'アクセシビリティサポートページのタイトルを設定します。このタイトルはリンクテキストとしても使用されます。';
// ... ... Setting: Accessibility support page link position.
$string['accessibilitysupportlinkpositionnone'] = 'アクセシビリティサポートページへのリンクを自動表示しない';
$string['accessibilitysupportlinkpositionfootnote'] = 'フットノートにリンクを追加する';
$string['accessibilitysupportlinkpositionfooter'] = 'フッター（？アイコン）にリンクを追加する';
$string['accessibilitysupportlinkpositionboth'] = 'フットノートとフッター（？アイコン）の両方にリンクを追加する';
$string['accessibilitysupportlinkpositionsetting'] = 'アクセシビリティサポートページのリンク位置';
$string['accessibilitysupportlinkpositionsetting_desc'] = 'アクセシビリティサポートページへのリンクを自動追加するかどうかを設定します。';
// ... ... Setting: Allow accessibility support page without login.
$string['allowaccessibilitysupportwithoutlogin'] = 'ログインなしでサポートページを表示する';
$string['allowaccessibilitysupportwithoutlogin_desc'] = '有効にすると、未ログインユーザもアクセシビリティサポートページにアクセスできます。';
// ... ... Setting: Enable accessibility button.
$string['enableaccessibilitysupportfooterbuttonsetting'] = 'アクセシビリティサポートのフッターボタンを有効にする';
$string['enableaccessibilitysupportfooterbuttonsetting_desc'] = 'フッター（？アイコン）の上にアクセシビリティサポートページへのフローティングアイコンを追加します。';
// ... ... Setting: Allow anonymous support page submissions
$string['allowanonymoussubmitssetting'] = '匿名でのサポート送信を許可する';
$string['allowanonymoussubmitssetting_desc'] = 'ユーザが匿名でアクセシビリティフィードバックを送信できるようにします。';
$string['accessibilitysupportanonymouscheckbox'] = '匿名でアクセシビリティサポートリクエストを送信します';
$string['accessibilitysupportanonymoususer'] = '匿名ユーザ';
$string['accessibilitysupportanonymousemail'] = 'anonymous@email.invalid';
$string['accessibilitysupportsentforanonymoususer'] = 'ユーザは匿名での送信を希望しました。';
// ... ... Setting: Allow sending technical information along.
$string['allowsendtechinfoalongsetting'] = '技術情報の送信を許可する';
$string['allowsendtechinfoalongsetting_desc'] = 'ユーザが技術情報を併せて送信できるようにします。';
$string['accessibilitysupporttechinfocheckbox'] = '以下の技術情報をメッセージと一緒に送信することに同意します';
$string['accessibilitysupporttechinfo'] = '技術情報';
$string['accessibilitysupporttechinfolabel'] = '送信する技術情報';
$string['accessibilitysupporttechinforeferrer'] = '参照元ページ';
$string['accessibilitysupporttechinfosysinfo'] = 'システム情報';
// ... ... Setting: Accessibility support user mail.
$string['accessibilitysupportusermail'] = 'アクセシビリティサポートの送信先メールアドレス';
$string['accessibilitysupportusermail_desc'] = 'アクセシビリティサポートリクエストの送信先メールアドレスを設定します。空欄の場合、<a href="{$a->url}">サイトサポート連絡先</a>が使用されます。';
$string['accessibilitysupportuserfirstname'] = 'Accessibility';
$string['accessibilitysupportuserlastname'] = 'support';
// ... ... Setting: Accessibility support page screenreader title
$string['accessibilitysupportpagesrlinktitledefault'] = 'アクセシビリティサポートを受ける';
$string['accessibilitysupportpagesrlinktitlesetting'] = 'アクセシビリティサポートページ（スクリーンリーダー用リンクタイトル）';
$string['accessibilitysupportpagesrlinktitlesetting_desc'] = 'スクリーンリーダー専用のリンクタイトルを設定します。';
// ... ... Setting: Add re-captcha to accessibility support page
$string['accessibilitysupportrecaptcha'] = 'アクセシビリティサポートページに reCAPTCHA を追加する';
$string['accessibilitysupportrecaptcha_desc'] = 'スパム防止のため、アクセシビリティサポートページに reCAPTCHA を追加できます。ただし、支援技術ユーザにとってはアクセシビリティ障壁となる可能性があるため注意が必要です。API キーが設定されていない場合は表示されません。';

// Settings: Flavours page.
$string['configtitleflavours'] = 'フレーバー';
$string['flavoursactivityiconcoloradministration'] = '「管理」アクティビティアイコンの色';
$string['flavoursactivityiconcoloradministration_help'] = 'この設定では、Boost Union の外観設定で定義された「管理」アイコンの色をフレーバーで上書きできます。';
$string['flavoursactivityiconcolorassessment'] = '「評価」アクティビティアイコンの色';
$string['flavoursactivityiconcolorassessment_help'] = 'Boost Union の外観設定で定義された「評価」アイコンの色を上書きします。';
$string['flavoursactivityiconcolorcollaboration'] = '「コラボレーション」アクティビティアイコンの色';
$string['flavoursactivityiconcolorcollaboration_help'] = 'Boost Union の外観設定で定義された「コラボレーション」アイコンの色を上書きします。';
$string['flavoursactivityiconcolorcommunication'] = '「コミュニケーション」アクティビティアイコンの色';
$string['flavoursactivityiconcolorcommunication_help'] = 'Boost Union の外観設定で定義された「コミュニケーション」アイコンの色を上書きします。';
$string['flavoursactivityiconcolorcontent'] = '「コンテンツ」アクティビティアイコンの色';
$string['flavoursactivityiconcolorcontent_help'] = 'Boost Union の外観設定で定義された「コンテンツ」アイコンの色を上書きします。';
$string['flavoursactivityiconcolorinteractivecontent'] = '「インタラクティブコンテンツ」アクティビティアイコンの色';
$string['flavoursactivityiconcolorinteractivecontent_help'] = 'Boost Union の外観設定で定義された「インタラクティブコンテンツ」アイコンの色を上書きします。';
$string['flavoursactivityiconcolorinterface'] = '「インターフェース」アクティビティアイコンの色';
$string['flavoursactivityiconcolorinterface_help'] = 'Boost Union の外観設定で定義された「インターフェース」アイコンの色を上書きします。';
$string['flavoursappliesto'] = '適用対象';
$string['flavoursapplytocategories'] = 'コースカテゴリに適用する';
$string['flavoursapplytocategories_help'] = 'このフレーバーを特定のコースカテゴリに適用するかどうかを設定します。';
$string['flavoursapplytocategories_ids'] = 'コースカテゴリ';
$string['flavoursapplytocategories_ids_help'] = 'このフレーバーを適用するコースカテゴリを 1 つ以上選択します。ページがこれらのカテゴリ内にある場合、フレーバーが適用されます。';
$string['flavoursapplytocohorts'] = 'コホートに適用する';
$string['flavoursapplytocohorts_help'] = 'このフレーバーを特定のコホートに適用するかどうかを設定します。';
$string['flavoursapplytocohorts_ids'] = 'コホート';
$string['flavoursapplytocohorts_ids_help'] = 'このフレーバーを適用するコホートを 1 つ以上選択します。ユーザがこれらのコホートのいずれかに属していればフレーバーが適用されます。カテゴリコホートもシステムコホートと同様に扱われます。';
$string['flavoursbackgroundimage'] = '背景画像';
$string['flavoursbackgroundimage_help'] = 'Boost Union の外観設定で定義された背景画像をフレーバーで上書きします。';
$string['flavoursbackgroundimageposition'] = '背景画像の位置';
$string['flavoursbackgroundimageposition_help'] = 'Boost Union の外観設定で定義された背景画像の位置をフレーバーで上書きします。';
$string['flavoursbacktooverview'] = 'フレーバー一覧に戻る';
$string['flavoursbootstrapcolordanger'] = 'Bootstrap「Danger」カラー';
$string['flavoursbootstrapcolordanger_help'] = 'Boost Union の外観設定で定義された Danger カラーを上書きします。';
$string['flavoursbootstrapcolorinfo'] = 'Bootstrap「Info」カラー';
$string['flavoursbootstrapcolorinfo_help'] = 'Boost Union の外観設定で定義された Info カラーを上書きします。';
$string['flavoursbootstrapcolorsuccess'] = 'Bootstrap「Success」カラー';
$string['flavoursbootstrapcolorsuccess_help'] = 'Boost Union の外観設定で定義された Success カラーを上書きします。';
$string['flavoursbootstrapcolorwarning'] = 'Bootstrap「Warning」カラー';
$string['flavoursbootstrapcolorwarning_help'] = 'Boost Union の外観設定で定義された Warning カラーを上書きします。';
$string['flavoursbrandcolor'] = 'ブランドのプライマリカラー';
$string['flavoursbrandcolor_help'] = 'Boost Union の外観設定で定義されたブランドカラーを上書きします。';
$string['flavourslinkcolor'] = 'リンクカラー';
$string['flavourslinkcolor_help'] = 'この設定では、Boost Union の外観設定で定義されているリンクカラーをフレーバーで上書きできます。';
$string['flavoursbuttonbrandcolor'] = 'ボタンのブランドカラー';
$string['flavoursbuttonbrandcolor_help'] = 'この設定では、Boost Union の外観設定で定義されているボタンのブランドカラーをフレーバーで上書きできます。';
$string['flavoursbrandedgraytones'] = 'ブランドのグレートーンを使用する';
$string['flavoursbrandedgraytones_help'] = 'この設定では、Boost Union の外観設定で定義されているブランドグレートーン設定をフレーバーで上書きできます。';
$string['flavourscreateflavour'] = 'フレーバーを作成する';
$string['flavourscustomscss'] = '生（Raw）SCSS';
$string['flavourscustomscss_help'] = 'この設定では、フレーバー専用のカスタム SCSS を記述できます。フレーバーが適用される際、この SCSS が CSS スタックの末尾に追加されます。';
$string['flavourscustomscsspre'] = '初期（Pre）SCSS';
$string['flavourscustomscsspre_help'] = 'この設定では、フレーバー適用時に CSS ビルドプロセスへ組み込まれる初期 SCSS を記述できます。';
$string['flavoursdelete'] = '削除';
$string['flavoursdeleteflavour'] = 'フレーバーを削除する';
$string['flavoursdeleteconfirmation'] = '本当にフレーバー <em>{$a}</em> を削除しますか？';
$string['flavoursdescription'] = '説明';
$string['flavoursdescription_help'] = 'フレーバーの説明は内部管理用で、フレーバー一覧で識別しやすくするために使用されます。';
$string['flavoursedit'] = '編集';
$string['flavourseditflavour'] = 'フレーバーを編集する';
$string['flavoursfavicon'] = 'ファビコン';
$string['flavoursfavicon_help'] = 'この設定では、Boost Union の外観設定で定義されているファビコンをフレーバーで上書きできます。';
$string['flavoursfootnote'] = 'フットノート';
$string['flavoursfootnote_help'] = 'この設定では、Boost Union のコンテンツ設定で定義されているフットノートをフレーバーで上書きできます。';
$string['flavoursflavours'] = 'フレーバー';
$string['flavoursgeneralsettings'] = '一般設定';
$string['flavoursincludesubcategories'] = 'サブカテゴリを含める';
$string['flavoursincludesubcategories_help'] = '有効にすると、選択したカテゴリのサブカテゴリにもフレーバーが適用されます。';
$string['flavourslogo'] = 'ロゴ';
$string['flavourslogo_help'] = 'この設定では、Boost Union の外観設定で定義されているロゴをフレーバーで上書きできます。';
$string['flavourslogocompact'] = 'コンパクトロゴ';
$string['flavourslogocompact_help'] = 'この設定では、Boost Union の外観設定で定義されているコンパクトロゴをフレーバーで上書きできます。';
$string['flavoursnavbarcolor'] = 'ナビバーの色';
$string['flavoursnavbarcolor_help'] = 'この設定では、Boost Union の外観設定で定義されているナビバーの色をフレーバーで上書きできます。';
$string['flavoursnavbartint'] = 'ナビバーのティントカラー';
$string['flavoursnavbartint_help'] = 'この設定では、Boost Union の外観設定で定義されているナビバーのティントカラーをフレーバーで上書きできます。ナビバーの色が「カラーナビバー」オプションのいずれかに設定されている場合のみ有効です。';
$string['flavoursnotificationcreated'] = 'フレーバーが正常に作成されました';
$string['flavoursnotificationdeleted'] = 'フレーバーが正常に削除されました';
$string['flavoursnotificationedited'] = 'フレーバーが正常に編集されました';
$string['flavoursnothingtodisplay'] = 'まだフレーバーが作成されていません。最初のフレーバーを作成してください。';
$string['flavoursoverview_desc'] = '<p>Boost Union のフレーバー機能を使うと、特定のコンテキストに応じて Moodle の外観設定を上書きできます。このページでは、フレーバーの作成と管理を行えます。</p><p>各フレーバーでは、どのコースカテゴリやどのコホートに適用するかを指定できます。その後、Moodle の各ページが描画される際に、Boost Union は適用可能なフレーバーがあるかをチェックします。なお、ページ描画ごとに適用されるのはリスト内で最初に一致したフレーバーのみで、残りのフレーバーは無視されます。そのため、このページでのフレーバーの並び順が非常に重要になります。</p><p>また、フレーバーの設定を変更するたびにテーマキャッシュがパージされます。これは、すべてのアセットが正しく最新の状態でブラウザに配信されるようにするために必要な処理です。</p>';
$string['flavourspreview'] = 'プレビュー';
$string['flavourspreviewflavour'] = 'フレーバーをプレビューする';
$string['flavourspreviewblindtext'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc id cursus metus aliquam eleifend mi in nulla. Felis imperdiet proin fermentum leo vel orci porta. Sed nisi lacus sed viverra tellus in hac habitasse. Vivamus arcu felis bibendum ut. Nisi porta lorem mollis aliquam ut porttitor. Odio euismod lacinia at quis risus sed vulputate odio. Sed felis eget velit aliquet sagittis id consectetur purus. Nec ullamcorper sit amet risus nullam eget. Pellentesque sit amet porttitor eget dolor. Cursus mattis molestie a iaculis at erat pellentesque.';
$string['flavourstitle'] = 'タイトル';
$string['flavourstitle_help'] = 'フレーバーのタイトルは内部管理用で、フレーバー一覧の中で識別しやすくするために使用されます。';

// Settings: SCSS snippets page.
$string['configtitlesnippets'] = 'SCSS スニペット';

// Settings: Overview tab.
$string['snippetsshowthecode'] = 'SCSS コードを表示';
$string['snippetscreator'] = '作成者';
$string['snippetsdescription'] = '説明';
$string['snippetsdetails'] = '詳細';
$string['snippetsdetailspreview'] = 'プレビュー';
$string['snippetsdisable'] = '無効化';
$string['snippetsenable'] = '有効化';
$string['snippetsgoal'] = '目的';
$string['snippetsgoalaccessibility'] = 'アクセシビリティ';
$string['snippetsgoalbugfix'] = 'バグ修正';
$string['snippetsgoaldevsonly'] = '開発者向け';
$string['snippetsgoaleaseofuse'] = '使いやすさ';
$string['snippetsgoaleyecandy'] = 'ビジュアル改善';
$string['snippetsnothingtodisplay'] = '使用可能な SCSS スニペットがありません。「設定」タブで組み込みスニペットを有効にするか、独自のスニペットをアップロードしてください。';
$string['snippetsoverview'] = '概要';
$string['snippetsoverview_desc'] = 'Boost Union の SCSS スニペットは、Moodle サイトに小規模（または中規模）の SCSS を追加する仕組みです。Moodle コアの小さな視覚的問題を修正したり、見た目を改善したりするのに便利です。';
$string['snippetsoverview_notes'] = 'SCSS スニペットを使用する際の基本事項:';
$string['snippetsoverview_notes1'] = 'スニペットは複数のソースから提供されます。「設定」タブで各ソースを有効化できます。';
$string['snippetsoverview_notes2'] = 'スニペットは上から順に SCSS スタックへ追加されます。順序が重要です。';
$string['snippetsoverview_notes3'] = 'スニペットを変更するとテーマキャッシュがパージされます。反映に数秒かかる場合があります。';
$string['snippetsscope'] = '適用範囲';
$string['snippetsscopecourse'] = 'コース';
$string['snippetsscopedashboard'] = 'ダッシュボード';
$string['snippetsscopesitehome'] = 'サイトホーム';
$string['snippetsscopeglobal'] = '全体';
$string['snippetsshowdetails'] = '詳細を表示';
$string['snippetssnippets'] = 'SCSS スニペット';
$string['snippetssource'] = 'ソース';
$string['snippetssourcetheme_boost_union'] = 'Boost Union 組み込み';
$string['snippetssourceuploaded'] = 'アップロード';
$string['snippetstestedon'] = 'テスト環境';
$string['snippetstitle'] = 'タイトル';
$string['snippetstrackerissue'] = 'トラッカー課題';
$string['snippetsusagenote'] = '使用上の注意';

// Settings: Settings tab.
$string['snippetssettings'] = '設定';
// ... Section: Built-in snippets.
$string['snippetsbuiltinsnippetsheading'] = '組み込みスニペット';
// ... ... Setting: Enable built-in snippets.
$string['enablebuiltinsnippets'] = '組み込みスニペットを有効にする';
$string['enablebuiltinsnippets_desc'] = 'Boost Union に含まれる組み込み SCSS スニペットを有効または無効にします。無効にすると、組み込みスニペットは SCSS スタックに追加されません。';
// ... Section: Uploaded snippets.
$string['snippetsuploadedsnippetsheading'] = 'アップロードされたスニペット';
$string['snippetsuploadedsnippetsheading_desc'] = 'Boost Union チームが公式に管理する組み込みスニペットに加えて、コミュニティ向けの <a href="{$a}" target="_blank">SCSS スニペットリポジトリ（GitHub）</a> があります。ここではコミュニティスニペットの利用方法や独自スニペット作成のテンプレートが提供されています。';
// ... ... Setting: Enable uploaded snippets.
$string['enableuploadedsnippets'] = 'アップロードスニペットを有効にする';
$string['enableuploadedsnippets_desc'] = 'Boost Union 設定内で SCSS スニペットをアップロードできるようにします。無効にするとアップロードも既存スニペットの読み込みも行われません。';
// ... ... Setting: Upload snippets.
$string['uploadedsnippets'] = 'スニペットをアップロード';
$string['uploadedsnippets_desc'] = '個別の SCSS ファイル、または複数の SCSS を含む ZIP をアップロードできます。ZIP は自動的に展開され、個別ファイルとして利用可能になります。';
$string['uploadedsnippets_note'] = 'Boost Union SCSS スニペットの構造と文法は <a href="{$a->url}" target="_blank">SCSS スニペットリポジトリ</a> に記載されています。コミュニティスニペットの利用方法や投稿方法も確認できます。';

// Settings: Smart menus page.
$string['smartmenus'] = 'スマートメニュー';
$string['error:smartmenusmenuitemnotfound'] = 'スマートメニュー項目が見つかりません';
$string['error:smartmenusmenunotfound'] = 'スマートメニューが見つかりません';
$string['smartmenus_desc'] = '<p>スマートメニューは、サイト管理者がカスタマイズ可能なメニューを作成し、サイトメインメニュー、モバイル下部メニュー、ユーザメニューなどに配置できる機能です。</p><p>メニューにはページリンク、カテゴリリンク、ユーザプロフィールリンクなど、さまざまな項目を追加できます。また、表示条件（ロール、言語、日付、コホートなど）を設定して、特定のユーザにのみ表示することもできます。</p>';
$string['smartmenusbycohort'] = 'コホートで制限';
$string['smartmenusbycohort_help'] = 'ユーザが所属するコホートに基づいて表示を制限します。';
$string['smartmenusbydate'] = '日付で制限';
$string['smartmenusbydate_help'] = '日付に基づいて表示を制限します。';
$string['smartmenusbydatefrom'] = '開始日';
$string['smartmenusbydatefrom_help'] = '指定した日付に達するまで表示しません。';
$string['smartmenusbydateuntil'] = '終了日';
$string['smartmenusbydateuntil_help'] = '指定した日付を過ぎると表示しません。';
$string['smartmenusbylanguage'] = '言語で制限';
$string['smartmenusbylanguage_help'] = 'ユーザの使用言語に基づいて表示を制限します。';
$string['smartmenusbyrole'] = 'ロールで制限';
$string['smartmenusbyrole_help'] = 'ユーザのロールに基づいて表示を制限します。';
$string['smartmenusbyadmin'] = '表示対象';
$string['smartmenusbyadmin_help'] = 'ユーザがサイト管理者かどうかに基づいて表示を制限します。';
$string['smartmenusbyadmin_all'] = 'すべてのユーザ';
$string['smartmenusbyadmin_admins'] = 'サイト管理者のみ';
$string['smartmenusbyadmin_nonadmins'] = '管理者以外のユーザ';
$string['smartmenusdynamiccoursescompletionstatus'] = 'コース完了状況';
$string['smartmenusdynamiccoursescompletionstatus_help'] = '動的コースメニュー項目には、選択した完了ステータスに一致するユーザのすべてのコースが表示されます。たとえば、完了ステータスとして「進行中」を選択した場合、現在ユーザが受講中のコースのみが一覧に表示されます。';
$string['smartmenusdynamiccoursescompletionstatuscompleted'] = '完了済み';
$string['smartmenusdynamiccoursescompletionstatusenrolled'] = '登録済み';
$string['smartmenusdynamiccoursescompletionstatusinprogress'] = '進行中';
$string['smartmenusdynamiccoursescoursecategory'] = 'コースカテゴリ';
$string['smartmenusdynamiccoursescoursecategory_help'] = '動的コースメニュー項目には、選択したコースカテゴリに属するすべてのコースが表示されます。';
$string['smartmenusdynamiccoursescoursecategorysubcats'] = 'サブカテゴリを含める';
$string['smartmenusdynamiccoursescoursecategorysubcats_help'] = '有効にすると、選択したカテゴリのサブカテゴリに属するコースも一覧に含まれます。';
$string['smartmenusdynamiccoursesdaterange'] = '日付範囲';
$string['smartmenusdynamiccoursesdaterange_help'] = '動的コースメニュー項目には、選択した日付範囲に該当するコースが表示されます。';
$string['smartmenusdynamiccoursesdaterangefuture'] = '未来';
$string['smartmenusdynamiccoursesdaterangepast'] = '過去';
$string['smartmenusdynamiccoursesdaterangepresent'] = '現在';
$string['smartmenusdynamiccoursesenrolmentrole'] = '登録ロール';
$string['smartmenusdynamiccoursesenrolmentrole_help'] = '動的コースメニュー項目には、ユーザが選択したロールで登録されているコースが表示されます。';
$string['smartmenusdynamiccoursesstarredcourses'] = 'スター付きコース';
$string['smartmenusdynamiccoursesstarredcourses_help'] = '動的コースメニューにすべてのコースを表示するか、スター付きコースのみを表示するかを選択します。<br />スター付きのみを選択した場合、サーバー側更新とクライアント側更新のどちらを使用するか選択できます。<br /><br /><strong>サーバー側更新：</strong>より安全で確実ですが遅くなります。JavaScript が無効でも確実に更新されます。<br /><strong>クライアント側更新：</strong>高速ですが不安定になる可能性があります。ブラウザイベントに基づいて更新されますが、サードパーティプラグインによるスター状態の変更を見逃す可能性があります。';
$string['smartmenusdynamiccoursesstarredcoursesall'] = 'すべてのコースを表示';
$string['smartmenusdynamiccoursesstarredcoursesonly'] = 'スター付きコースのみ（サーバー側更新：安全だが遅い）';
$string['smartmenusdynamiccoursesstarredcoursesonlyclient'] = 'スター付きコースのみ（クライアント側更新：高速だが不安定）';
$string['smartmenusexperimental'] = '注意：スマートメニュー機能は現時点で十分利用可能ですが、設定の組み合わせが非常に多いため、予期せぬ問題が発生する可能性があり、<em>実験的機能</em>として扱われます。個々の設定に応じて十分にテストしてください。問題が発生した場合は、再現手順を添えて <a href="https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues">GitHub</a> に報告してください。';
$string['smartmenusgeneralsectionheader'] = '一般設定';
$string['smartmenusmenuaddnewitem'] = 'メニュー項目を追加';
$string['smartmenusmenucardform'] = 'カード形式';
$string['smartmenusmenucardform_help'] = 'カード形式のメニューで使用するカードの形状を、正方形・縦長・横長・全幅から選択します。';
$string['smartmenusmenucardformfullwidth'] = '全幅';
$string['smartmenusmenucardformlandscape'] = '横長';
$string['smartmenusmenucardformportrait'] = '縦長';
$string['smartmenusmenucardformsquare'] = '正方形';
$string['smartmenusmenucardoverflowbehavior'] = 'カードのオーバーフロー動作';
$string['smartmenusmenucardoverflowbehavior_help'] = 'カードがコンテナからはみ出す場合の動作を、スクロール表示または折り返し表示から選択します。';
$string['smartmenusmenucardoverflowbehaviornowrap'] = '折り返さない';
$string['smartmenusmenucardoverflowbehaviorwrap'] = '折り返す';
$string['smartmenusmenucardsize'] = 'カードサイズ';
$string['smartmenusmenucardsize_help'] = 'カード形式メニューで使用するカードのサイズを、Tiny / Small / Medium / Large から選択します。';
$string['smartmenusmenucardsizelarge'] = 'Large';
$string['smartmenusmenucardsizemedium'] = 'Medium';
$string['smartmenusmenucardsizesmall'] = 'Small';
$string['smartmenusmenucardsizetiny'] = 'Tiny';
$string['smartmenusmenucreate'] = 'メニューを作成';
$string['smartmenusmenucreatesuccess'] = 'スマートメニューが正常に作成されました';
$string['smartmenusmenucssclass'] = 'CSS クラス';
$string['smartmenusmenucssclass_help'] = 'メニューに適用する CSS クラスを入力します。独自のスタイルを適用する際に使用できます。';
$string['smartmenusmenudeleteconfirm'] = 'このスマートメニューを削除してもよろしいですか？';
$string['smartmenusmenudeletesuccess'] = 'スマートメニューが正常に削除されました';
$string['smartmenusmenudescription'] = '説明';
$string['smartmenusmenudescription_help'] = 'メニューの説明です。主に内部ドキュメントとして使用されますが、「説明を表示」オプションを使えばメニュー内に表示することもできます。';
$string['smartmenusmenuduplicate'] = 'メニューと項目を複製';
$string['smartmenusmenuduplicatesuccess'] = 'メニューとその項目が正常に複製されました';
$string['smartmenusmenuedit'] = 'メニューを編集';
$string['smartmenusmenueditsuccess'] = 'スマートメニューが正常に更新されました';
$string['smartmenusmenuitemcardappearanceheader'] = 'カードの外観';
$string['smartmenusmenuitemcardbackgroundcolor'] = 'カード背景色';
$string['smartmenusmenuitemcardbackgroundcolor_help'] = 'メニュー項目カードの背景色を選択します。';
$string['smartmenusmenuitemcardimage'] = 'カード画像';
$string['smartmenusmenuitemcardimage_help'] = 'メニュー項目タイトルの横に表示する画像を選択します。';
$string['smartmenusmenuitemcardimagealt'] = 'カード画像の alt テキスト';
$string['smartmenusmenuitemcardimagealt_help'] = 'このメニュー項目のカード画像に設定する alt テキストです。{menutitle} を使用すると、設定したメニュー項目タイトルを alt テキストに挿入できます。空欄の場合、メニュー項目のタイトルが alt テキストとして使用されます。';
$string['smartmenusmenuitemcardtextcolor'] = 'カード文字色';
$string['smartmenusmenuitemcardtextcolor_help'] = 'メニュー項目カードの文字色を選択します。';
$string['smartmenusmenuitemcreate'] = 'メニュー項目を作成';
$string['smartmenusmenuitemcreatesuccess'] = 'スマートメニュー項目が正常に作成されました';
$string['smartmenusmenuitemcssclass'] = 'CSS クラス';
$string['smartmenusmenuitemcssclass_help'] = 'メニュー項目に適用する CSS クラスを入力します。独自のスタイルを適用する際に使用できます。';
$string['smartmenusmenuitemdeleteconfirm'] = 'このメニュー項目をスマートメニューから削除してもよろしいですか？';
$string['smartmenusmenuitemdeletesuccess'] = 'スマートメニュー項目が正常に削除されました';
$string['smartmenusmenuitemdisplayallcourses'] = '非表示コースを表示する';
$string['smartmenusmenuitememail'] = '宛先';
$string['smartmenusmenuitememail_help'] = 'mailto リンクの宛先メールアドレスを入力します。複数指定する場合はカンマ区切りで入力します。「mailto:」は付けないでください。';
$string['smartmenusmenuitememail_required'] = '必須項目です。「mailto:」を付けずに、少なくとも 1 つの有効なメールアドレスを入力してください。';
$string['smartmenusmenuitememail_invalid'] = '有効なメールアドレスのみを入力してください。複数指定する場合はカンマで区切ります。';
$string['smartmenusmenuitememail_cc'] = 'Cc';
$string['smartmenusmenuitememail_cc_help'] = 'Cc に追加するメールアドレス（任意）。複数指定する場合はカンマ区切りで入力します。';
$string['smartmenusmenuitememail_bcc'] = 'Bcc';
$string['smartmenusmenuitememail_bcc_help'] = 'Bcc に追加するメールアドレス（任意）。複数指定する場合はカンマ区切りで入力します。';
$string['smartmenusmenuitememail_subject'] = '件名';
$string['smartmenusmenuitememail_subject_help'] = 'メールのデフォルト件名（任意）。mailto リンク内で URL エンコードされます。';
$string['smartmenusmenuitememail_body'] = '本文';
$string['smartmenusmenuitememail_body_help'] = 'メールのデフォルト本文（任意）。改行を含めて URL エンコードされます。';
$string['smartmenusmenuitemhidehiddencourses'] = '非表示コースを隠す';
$string['smartmenusmenuitemhiddencoursessorting_help'] = '動的コースメニュー項目における非表示コースの並び順を選択します。';
$string['smartmenusmenuitemhiddencoursessorting'] = '非表示コースの並び順';
$string['smartmenusmenuitemhiddencoursessortingend'] = '非表示コースを一覧の最後に表示する';
$string['smartmenusmenuitemhiddencoursessortingtogether'] = '非表示コースと表示コースをまとめて並べる';
$string['smartmenusmenuitemlistsort'] = 'コース一覧の並び順';
$string['smartmenusmenuitemlistsort_help'] = 'コース一覧を並べ替える基準と順序を選択します。基準は「フルネーム」「ショートネーム」「コース ID」「コース ID 番号」から選択できます。';
$string['smartmenusmenuitemlistsortfullnameasc'] = 'コースフルネーム（昇順）';
$string['smartmenusmenuitemlistsortfullnamedesc'] = 'コースフルネーム（降順）';
$string['smartmenusmenuitemlistsortshortnameasc'] = 'コースショートネーム（昇順）';
$string['smartmenusmenuitemlistsortshortnamedesc'] = 'コースショートネーム（降順）';
$string['smartmenusmenuitemlistsortcourseidasc'] = 'コース ID（昇順）';
$string['smartmenusmenuitemlistsortcourseiddesc'] = 'コース ID（降順）';
$string['smartmenusmenuitemlistsortcourseidnumberasc'] = 'コース ID 番号（昇順）';
$string['smartmenusmenuitemlistsortcourseidnumberdesc'] = 'コース ID 番号（降順）';
$string['smartmenusmenuitemdisplayfield'] = 'コース名の表示形式';
$string['smartmenusmenuitemdisplayfield_help'] = '動的コースメニュー項目でタイトルとして使用するコース名を、フルネームまたはショートネームから選択します。';
$string['smartmenusmenuitemdisplayfieldcoursefullname'] = 'コースフルネーム';
$string['smartmenusmenuitemdisplayfieldcourseshortname'] = 'コースショートネーム';
$string['smartmenusmenuitemdisplayoptions'] = 'タイトルの表示形式';
$string['smartmenusmenuitemdisplayoptions_help'] = 'メニュー項目タイトルの表示方法を選択します。';
$string['smartmenusmenuitemdisplayoptionshidetitle'] = 'タイトルを非表示にし、アイコンのみ表示（すべてのデバイス）';
$string['smartmenusmenuitemdisplayoptionshidetitlemobile'] = 'タイトルを非表示にし、アイコンのみ表示（モバイルのみ）';
$string['smartmenusmenuitemdisplayoptionsshowtitleicon'] = 'タイトルとアイコンを表示';
$string['smartmenusmenuitemdisplayonlyvisiblecourses'] = '非表示コースの扱い';
$string['smartmenusmenuitemdisplayonlyvisiblecourses_help'] = '有効にすると、管理者や moodle/course:viewhiddencourses 権限を持つユーザであっても、非表示コースはスマートメニューに表示されません。';
$string['smartmenusmenuitemduplicate'] = 'メニュー項目を複製';
$string['smartmenusmenuitemduplicatesuccess'] = 'メニュー項目が正常に複製されました';
$string['smartmenusmenuitemedit'] = 'メニュー項目を編集';
$string['smartmenusmenuitemeditsuccess'] = 'スマートメニュー項目が正常に更新されました';
$string['smartmenusmenuitemicon'] = 'アイコン';
$string['smartmenusmenuitemicon_help'] = 'メニュー項目タイトルの横に表示するアイコンです。<br /><br />Moodle コアアイコンまたは FontAwesome アイコンを選択できます。<ul><li><strong>Moodle コアアイコン：</strong>内部的には「pix アイコン」として扱われ、FontAwesome へのマッピングは将来変更される可能性があります。また、一部の pix アイコンには Bootstrap カラーが適用されています。</li><li><strong>FontAwesome アイコン：</strong>そのまま使用され、将来の Moodle バージョンでも安定して表示されます。ただし、Moodle が FontAwesome の新バージョンに更新した場合、グリフが変わる可能性があります。</li></ul>特に理由がなければ、FontAwesome アイコンの使用を推奨します。';
$string['smartmenusmenuitemicon_placeholder'] = 'アイコンを選択';
$string['smartmenusmenuitemicon_noicon'] = 'アイコンなし';
$string['smartmenusmenuitemicon_sourcecore'] = 'Moodle コア';
$string['smartmenusmenuitemicon_sourcefablank'] = 'FontAwesome Blank';
$string['smartmenusmenuitemicon_sourcefasolid'] = 'FontAwesome Solid';
$string['smartmenusmenuitemicon_sourcefabrand'] = 'FontAwesome Brands';
$string['smartmenusmenuitemicon_ajaxtoomanyicons'] = 'アイコン数が多すぎます（{$a}）。検索条件を絞ってください。';
$string['smartmenusmenuitemlinktarget'] = 'リンクターゲット';
$string['smartmenusmenuitemlinktarget_help'] = 'メニュー項目のリンクを開くターゲットを設定します（同じウィンドウまたは新しいタブ）。';
$string['smartmenusmenuitemlinktargetnewtab'] = '新しいタブ';
$string['smartmenusmenuitemlinktargetsamewindow'] = '同じウィンドウ';
$string['smartmenusmenuitemmode'] = 'メニュー項目モード';
$string['smartmenusmenuitemmode_help'] = '<p>メニュー項目をメニュー内でどのように表示するか、その表示モードを選択します。</p><ul><li><strong>Inline（インライン）</strong>：通常のメニュー項目としてメニュー内に表示されます。これがデフォルトの表示モードです。</li><li><strong>Submenu（サブメニュー）</strong>：メニュー項目がサブメニューとして表示され、親項目をクリックすると展開・折りたたみができます。このモードは、第 3 階層のナビゲーションを構築したい場合や、動的コースメニュー項目でコース一覧をサブメニューとして表示したい場合に便利です。このメニュー項目のタイトルは、サブメニューの見出しとして使用されます。</li></ul>';
$string['smartmenusmenuitemnothingtodisplay'] = 'このスマートメニューにはまだ項目がありません。項目を追加してください。';
$string['smartmenusmenuitemorder'] = '並び順';
$string['smartmenusmenuitemorder_help'] = '必要に応じて項目の順序を変更します。すべての項目はこの順序値に基づいて並べ替えられます。';
$string['smartmenusmenuitempresentationheader'] = 'メニュー項目の表示設定';
$string['smartmenusmenuitemresponsive'] = 'レスポンシブ非表示設定';
$string['smartmenusmenuitemresponsive_help'] = 'チェックを入れたデバイスサイズでは、このメニュー項目が非表示になります。';
$string['smartmenusmenuitemresponsivedesktop'] = 'デスクトップ';
$string['smartmenusmenuitemresponsivemobile'] = 'モバイル';
$string['smartmenusmenuitemresponsivetablet'] = 'タブレット';
$string['smartmenusmenuitemrestriction'] = 'アクセスルール';
$string['smartmenusmenuitems'] = 'メニュー項目';
$string['smartmenusmenuitemstructureheader'] = 'メニュー項目の構造';
$string['smartmenusmenuitemtextcount'] = '単語数';
$string['smartmenusmenuitemtextcount_help'] = '動的コースメニュー項目でタイトルとして表示する最大単語数を指定します。このフィールドを空のままにすると、タイトルは全文が表示されます。';
$string['smartmenusmenuitemtextposition'] = 'カード内テキスト位置';
$string['smartmenusmenuitemtextposition_help'] = '<p>カード画像に対してメニュー項目のテキストをどこに表示するかを選択します（画像の下、上部オーバーレイ、下部オーバーレイ）。</p><ul><li>Top overlay: カード上部のオーバーレイ上にタイトルを表示します。</li><li>Bottom overlay: カード下部のオーバーレイ上にタイトルを表示します。</li><li>Below image: カード画像の下にタイトルを表示します。</li></ul>';
$string['smartmenusmenuitemtextpositionbelowimage'] = '画像の下';
$string['smartmenusmenuitemtextpositionoverlaybottom'] = '下部オーバーレイ';
$string['smartmenusmenuitemtextpositionoverlaytop'] = '上部オーバーレイ';
$string['smartmenusmenuitemtitle'] = 'タイトル';
$string['smartmenusmenuitemtitle_help'] = 'メニュー項目のタイトルです。このタイトルがメニュー項目のラベルとして使用されます。';
$string['smartmenusmenuitemtooltip'] = 'ツールチップ';
$string['smartmenusmenuitemtooltip_help'] = 'ユーザがメニュー項目にカーソルを合わせたときに表示されるツールチップです。';
$string['smartmenusmenuitemtype'] = 'メニュー項目タイプ';
$string['smartmenusmenuitemtype_help'] = '<p>作成するメニュー項目の種類を選択します。静的リンク、mailto、見出し、Moodle ドキュメント、動的コース、区切り線、プレースホルダ対応の静的リンク、プレースホルダ対応の見出しから選択できます。</p><ul><li>Static: 固定 URL にリンクする通常のメニュー項目です。</li><li>Mailto: メールクライアントを開き、メール作成画面を表示します。To、Cc、Bcc、件名、本文を設定できます。</li><li>Heading: 関連するメニュー項目をまとめる見出しです。リンクはありません。</li><li>Separator: メニュー内に水平線を表示し、項目を区切ります。</li><li>Moodle documentation: 対応する MoodleDocs 記事へのリンクを作成します。</li><li>Dynamic courses: コースカテゴリ、登録ロール、完了状況、日付範囲などの条件に基づき、コース一覧を自動生成します。</li><li>Static (with placeholders): タイトルや URL にプレースホルダを使用できます。キャッシュされません。</li><li>Heading (with placeholders): 見出しにプレースホルダを使用できます。キャッシュされません。</li></ul>';
$string['smartmenusmenuitemplaceholdersinfoheader'] = '利用可能なプレースホルダ';
$string['smartmenusmenuitemplaceholdersinfo'] = '<p>このメニュー項目タイプではプレースホルダが使用できます。以下のプレースホルダをタイトルや URL に使用すると、描画時にコンテキストに応じた値に置換されます。</p><ul><li><code>{courseid}</code> - 現在のコースの内部 ID</li><li><code>{coursefullname}</code> - 現在のコースのフルネーム</li><li><code>{courseshortname}</code> - 現在のコースの省略名</li><li><code>{editingtoggle}</code> - 編集モード切替用の値（on/off）</li><li><code>{userid}</code> - ログインユーザの内部 ID</li><li><code>{userusername}</code> - ログインユーザのユーザ名</li><li><code>{userfullname}</code> - ログインユーザのフルネーム</li><li><code>{pagecontextid}</code> - 現在のページのコンテキスト ID</li><li><code>{pagepath}</code> - 現在のページの URL パス</li><li><code>{sesskey}</code> - 現在のセッションキー（保護された URL 用）</li></ul>';
$string['smartmenusmenuitemtypedocs'] = 'Moodle ドキュメント';
$string['smartmenusmenuitemtypedynamiccourses'] = '動的コース';
$string['smartmenusmenuitemtypeheading'] = '見出し';
$string['smartmenusmenuitemtypedivider'] = '区切り線';
$string['smartmenusmenuitemtypemailto'] = 'Mailto';
$string['smartmenusmenuitemtypestatic'] = '静的リンク';
$string['smartmenusmenuitemtypestaticwithplaceholders'] = '静的リンク（プレースホルダ対応）';
$string['smartmenusmenuitemtypeheadingwithplaceholders'] = '見出し（プレースホルダ対応）';
$string['smartmenusmenuitemurl'] = 'メニュー項目 URL';
$string['smartmenusmenuitemurl_help'] = 'メニュー項目のリンク先 URL です。Static タイプでは固定 URL を入力します。Static（with placeholders）タイプでは、プレースホルダを含めることができます。';
$string['smartmenusmenulocation'] = 'メニューの表示位置';
$string['smartmenusmenulocation_help'] = '<p>メニューをページのどこに表示するか選択します。</p><ul><li>メインナビゲーション：ページ上部の Moodle 標準ナビゲーション領域。</li><li>メニューバー：メインナビゲーションのさらに上部にあるバー。</li><li>ユーザメニュー：ナビゲーションバーのユーザアバターからアクセスできます。</li><li>ボトムバー：画面下部に表示され、ダッシュボードやマイコースなどへの親指操作向けナビゲーションとして利用できます。</li></ul><p>ボトムバーを有効にすると、ハンバーガーアイコンはサイトロゴに置き換わります。</p>';
$string['smartmenusmenulocationbottom'] = 'ボトムバー';
$string['smartmenusmenulocationmain'] = 'メインナビゲーション';
$string['smartmenusmenulocationmenu'] = 'メニューバー';
$string['smartmenusmenulocationuser'] = 'ユーザメニュー';
$string['smartmenusmenumode'] = 'メニューモード';
$string['smartmenusmenumode_help'] = '<p>メニュー項目をどのように表示するか選択します。</p><ul><li>Submenu: メニュータイトルを親としてサブメニューとして表示します（デフォルト）。</li><li>Inline: メニュー項目をナビゲーション内に直接並べて表示します。ただしカードタイプのメニューでは使用できません。</li></ul>';
$string['smartmenusmenumoremenubehavior'] = 'More メニューの動作';
$string['smartmenusmenumoremenubehavior_help'] = '<p>メニューが多すぎて表示位置に収まらない場合の動作を選択します。</p><ul><li>Do not change anything: 特別な動作は行わず、入りきらないメニューは自動的に「More」メニューに移動します。</li><li>Force into more menu: スペースがあっても強制的に「More」メニューに移動します。</li><li>Keep outside of more menu: 可能な限り「More」メニューに入れず、代わりに後続のメニューを移動させます。</li></ul><p>この設定はメインナビゲーションとメニューバーに配置されたメニューにのみ適用されます。</p>';
$string['smartmenusmenumoremenubehaviorforceinto'] = '強制的に More メニューへ移動';
$string['smartmenusmenumoremenubehaviorkeepoutside'] = '可能な限り More メニューの外に保持';
$string['smartmenusmenunothingtodisplay'] = 'Smart menu はまだ作成されていません。まず最初の Smart menu を作成してください。';
$string['smartmenusmenupresentationheader'] = 'メニュー表示形式';
$string['smartmenusmenushowdescription'] = '説明文の表示';
$string['smartmenusmenushowdescription_help'] = '<p>説明文をどのように表示するか選択します（Never、Above、Below、Help）。</p><ul><li>Never: 説明文を表示せず、内部用途にのみ使用します（デフォルト）。</li><li>Above: メニュー項目リストの上に表示します。</li><li>Below: メニュー項目リストの下に表示します。</li><li>Help: メニュー項目リストの近くにヘルプアイコンとして表示します。</li></ul>';
$string['smartmenusmenushowdescriptionabove'] = '上に表示';
$string['smartmenusmenushowdescriptionbelow'] = '下に表示';
$string['smartmenusmenushowdescriptionhelp'] = 'ヘルプアイコン';
$string['smartmenusmenushowdescriptionnever'] = '表示しない';
$string['smartmenusmenustructureheader'] = 'メニュー構造';
$string['smartmenusmenutitle'] = 'タイトル';
$string['smartmenusmenutitle_help'] = 'メニューのタイトルです。メニューの親ノードのラベルとして使用されます。';
$string['smartmenusmenutype'] = '表示タイプ';
$string['smartmenusmenutype_help'] = '<p>メニューの表示形式を選択します（リストまたはカード）。</p><ul><li>List: シンプルなテキストリンクで構成されるリスト形式（デフォルト）。</li><li>Card: カードで構成されるカード形式。</li></ul>';
$string['smartmenusmenutypecard'] = 'カード';
$string['smartmenusmenutypelist'] = 'リスト';
$string['smartmenusmodeinline'] = 'インライン';
$string['smartmenusmodesubmenu'] = 'サブメニュー';
$string['smartmenusnorestrict'] = '制限なし';
$string['smartmenusoperator'] = '演算子';
$string['smartmenusoperator_help'] = 'コホート条件に使用する演算子を選択します（Any または All）。';
$string['smartmenusrestrictbycohortsheader'] = 'コホートによる表示制限';
$string['smartmenusrestrictbydateheader'] = '日付による表示制限';
$string['smartmenusrestrictbylanguageheader'] = '言語による表示制限';
$string['smartmenusrestrictbyrolesheader'] = 'ロールによる表示制限';
$string['smartmenusrestrictbyadminheader'] = 'サイト管理者ステータスによる表示制限';
$string['smartmenusrolecontext'] = 'コンテキスト';
$string['smartmenusrolecontext_help'] = 'ユーザのロールをどのコンテキストで確認するかを選択します（任意のコンテキスト または システムコンテキストのみ）。';
$string['smartmenussavechangesandconfigure'] = '保存して項目を設定';
$string['smartmenussettings'] = 'Smart menu 設定';

// Settings: Recommendations page.
$string['recommendations'] = '推奨設定';
$string['recommendations_desc'] = 'Boost Union の動作品質は Moodle 全体の設定に依存します。このページでは、Boost Union を最適に動作させるための推奨事項とチェック項目を確認できます。特定の推奨が自サイトに当てはまらない場合は、ミュートできます。';
$string['recommendationopensetting'] = '設定を開く';
$string['recommendationmoreinfo'] = '詳細情報';
$string['recommendationviewall'] = 'すべての推奨を表示';
$string['recommendationautofix'] = '自動修正';
$string['recommendationautofixsuccess'] = '推奨項目が自動的に修正されました。';
$string['recommendationstatusheader'] = 'ステータス';
$string['recommendationrecommendationheader'] = '推奨項目';
$string['recommendationsummaryheader'] = '概要';
$string['recommendationactionsheader'] = '操作';
$string['recommendationcategory_moodlecore'] = 'Moodle コア';
$string['recommendationcategory_boostunion'] = 'Boost Union';
$string['recommendationcategory_thirdparty'] = 'サードパーティプラグイン';
$string['recommendationcategory_usability'] = 'ユーザビリティ';
$string['recommendationcategory_accessibility'] = 'アクセシビリティ';
$string['recommendationstatus_ok'] = 'OK';
$string['recommendationstatus_ok_description'] = 'この推奨項目は問題ありません。対応は不要です。';
$string['recommendationstatus_check'] = '要確認';
$string['recommendationstatus_check_description'] = 'Boost Union が自動判定できないため、手動で確認が必要です。';
$string['recommendationstatus_notice'] = '注意';
$string['recommendationstatus_notice_description'] = '注意喚起が必要ですが、緊急の対応は不要です。';
$string['recommendationstatus_warning'] = '警告';
$string['recommendationstatus_warning_description'] = '設定に問題がある可能性があり、注意が必要です。';
$string['recommendationstatus_na'] = '対象外';
$string['recommendationstatus_na_description'] = 'この推奨項目は現在の設定には適用されません。対応は不要です。';
$string['recommendationstatus_muted'] = 'ミュート済み';
$string['recommendationstatus_muted_description'] = 'この推奨項目はミュートされています。再度有効化しない限り通知されません。';
$string['recommendationmute'] = 'ミュート';
$string['recommendationunmute'] = 'ミュート解除';
$string['recommendationmutesuccess'] = '推奨項目をミュートしました。<br />今後は通知されませんが、いつでもミュート解除できます。';
$string['recommendationunmutesuccess'] = '推奨項目のミュートを解除しました。';
$string['recommendationsattentionalert'] = 'Boost Union の推奨項目の中に注意が必要なものがあります。<a href="{$a->url}">推奨設定ページ</a>を確認してください。';
$string['recommendationsnotificationtitle'] = 'Boost Union 推奨項目';
$string['recommendationcurrentstatus'] = '現在のステータス';
$string['recommendationpossiblesolutions'] = '考えられる解決策';
$string['recommendationsolution_both'] = '魔法の杖アイコンをクリックして自動修正するか、歯車アイコンをクリックして該当設定を確認できます。';
$string['recommendationsolution_autofixonly'] = '魔法の杖アイコンをクリックすると自動修正できます。';
$string['recommendationsolution_actionurlonly'] = '歯車アイコンをクリックして該当設定を確認してください。自動修正は利用できません。';
$string['recommendationsolution_check'] = '推奨項目の概要に従って該当設定を確認してください。誤検知と思われる場合はミュートできます。';

// Recommendation: Slash arguments support.
$string['recommendation_slasharguments_title'] = 'Slash arguments のサポート';
$string['recommendation_slasharguments_summary'] = 'Boost Union の機能を正しく動作させるため、Slash arguments を有効にする必要があります。';
$string['recommendation_slasharguments_description'] = 'Boost Union の一部機能は Moodle コアの slasharguments に依存しています。これが無効だと、フレーバーのブランド設定などが正しく動作しません。Moodle の slasharguments を有効にしてください。';

// Recommendation: Theme Boost preset.
$string['recommendation_themeboostpreset_title'] = 'Boost テーマプリセット';
$string['recommendation_themeboostpreset_summary'] = 'Boost Union の最適な表示には default.scss の使用が推奨されます。';
$string['recommendation_themeboostpreset_description'] = 'Boost Union は Boost テーマの default.scss を前提に設計・テストされています。他のプリセットを使用することも可能ですが、表示品質が低下する可能性があります。';

// Recommendation: Moodle core brand assets.
$string['recommendation_corebrandasset_title'] = '{$a} のアップロード';
$string['recommendation_corebrandasset_summary'] = 'Boost Union は独自の {$a} 設定を使用するため、Moodle コア側に {$a} をアップロードすべきではありません。';
$string['recommendation_corebrandasset_description'] = 'Boost Union は Moodle コアの {$a} 設定を使用しません。他テーマで必要な場合を除き、コア側の {$a} は不要であり、削除が推奨されます。';

// Recommendation: Moodle core auth instructions.
$string['recommendation_coreauthinstructions_title'] = '認証説明文';
$string['recommendation_coreauthinstructions_summary'] = 'Boost Union は独自のログイン説明文を使用するため、Moodle コアの認証説明文は空にすべきです。';
$string['recommendation_coreauthinstructions_description'] = 'Boost Union は auth_instructions をログインページに表示しません。未ログインユーザ向けテーマとして使用する場合、コア側の説明文は表示されないため削除が推奨されます。';

// Recommendation: Infobanner on Login page with side-by-side login arrangement.
$string['recommendation_infobannerloginpagesidebyside_title'] = 'ログインページのインフォバナー';
$string['recommendation_infobannerloginpagesidebyside_summary'] = '左右分割レイアウトのログインページでは、インフォバナーは簡潔にする必要があります。';
$string['recommendation_infobannerloginpagesidebyside_details'] = '左右分割レイアウトでは、インフォバナーは右側のログインフォーム上部にのみ表示されます。幅が狭いため、内容はできるだけ短く簡潔にしてください。';

// Privacy API.
$string['privacy:metadata'] = 'Boost Union テーマはユーザの個人データを一切保存しません。';

// Capabilities.
$string['boost_union:configure'] = '管理者以外でもテーマ設定を構成できる権限';
$string['boost_union:viewhintcourseguestenrol'] = '公開コースでゲストアクセスのヒントを表示できる権限';
$string['boost_union:viewhintcourseselfenrol'] = '公開コースで登録キーなしの自己登録ヒントを表示できる権限';
$string['boost_union:viewhintinhiddencourse'] = '非公開コースでヒントを表示できる権限';
$string['boost_union:viewregionheader'] = 'ヘッダー領域のブロックを表示できる権限';
$string['boost_union:editregionheader'] = 'ヘッダー領域のブロックを編集できる権限';
$string['boost_union:viewregionoutsideleft'] = 'Outside（左）ブロック領域を表示できる権限';
$string['boost_union:editregionoutsideleft'] = 'Outside（左）ブロック領域を編集できる権限';
$string['boost_union:viewregionoutsideright'] = 'Outside（右）ブロック領域を表示できる権限';
$string['boost_union:editregionoutsideright'] = 'Outside（右）ブロック領域を編集できる権限';
$string['boost_union:viewregionoutsidetop'] = 'Outside（上）ブロック領域を表示できる権限';
$string['boost_union:editregionoutsidetop'] = 'Outside（上）ブロック領域を編集できる権限';
$string['boost_union:viewregionoutsidebottom'] = 'Outside（下）ブロック領域を表示できる権限';
$string['boost_union:editregionoutsidebottom'] = 'Outside（下）ブロック領域を編集できる権限';
$string['boost_union:viewregioncontentupper'] = 'Content（上部）ブロック領域を表示できる権限';
$string['boost_union:editregioncontentupper'] = 'Content（上部）ブロック領域を編集できる権限';
$string['boost_union:viewregioncontentlower'] = 'Content（下部）ブロック領域を表示できる権限';
$string['boost_union:editregioncontentlower'] = 'Content（下部）ブロック領域を編集できる権限';
$string['boost_union:viewregionfooterleft'] = 'Footer（左）ブロック領域を表示できる権限';
$string['boost_union:editregionfooterleft'] = 'Footer（左）ブロック領域を編集できる権限';
$string['boost_union:viewregionfooterright'] = 'Footer（右）ブロック領域を表示できる権限';
$string['boost_union:editregionfooterright'] = 'Footer（右）ブロック領域を編集できる権限';
$string['boost_union:viewregionfootercenter'] = 'Footer（中央）ブロック領域を表示できる権限';
$string['boost_union:editregionfootercenter'] = 'Footer（中央）ブロック領域を編集できる権限';
$string['boost_union:viewregionoffcanvasleft'] = 'Off-canvas（左）ブロック領域を表示できる権限';
$string['boost_union:editregionoffcanvasleft'] = 'Off-canvas（左）ブロック領域を編集できる権限';
$string['boost_union:viewregionoffcanvasright'] = 'Off-canvas（右）ブロック領域を表示できる権限';
$string['boost_union:editregionoffcanvasright'] = 'Off-canvas（右）ブロック領域を編集できる権限';
$string['boost_union:viewregionoffcanvascenter'] = 'Off-canvas（中央）ブロック領域を表示できる権限';
$string['boost_union:editregionoffcanvascenter'] = 'Off-canvas（中央）ブロック領域を編集できる権限';
$string['boost_union:overridecourseheaderincourse'] = 'コース内でコースヘッダー設定を上書きできる権限';
$string['boost_union:transfercourseheaderduringimport'] = 'コースインポート時にコースヘッダー設定を引き継ぐ権限';

// Caches.
$string['cachedef_flavours'] = '現在のユーザに対して、ページのカテゴリ ID に適用されるフレーバー';
$string['cachedef_smartmenus'] = 'スマートメニュー';
$string['cachedef_smartmenu_items'] = 'スマートメニュー項目';
$string['cachedef_touchiconsios'] = 'iOS 用タッチアイコンファイル';
$string['cachedef_hooksuppress'] = 'フック抑制設定';
$string['cachedef_fontawesomeicons'] = 'FontAwesome アイコンマップ';
$string['cachedef_courseoverrides'] = 'コース固有の設定上書き';

// Scheduled tasks.
$string['task_purgecache'] = 'テーマキャッシュをパージする';

// Checks API: Recommendations.
$string['checkrecommendations'] = 'Boost Union 推奨設定';
$string['checkrecommendationsok'] = '注意が必要な Boost Union 推奨項目はありません。';
$string['checkrecommendationswarning'] = '注意が必要な Boost Union 推奨項目があります。';
$string['checkrecommendationsdetails'] = '詳細は <a href="{$a->url}">推奨設定</a> ページをご確認ください。';

// Upgrade notices.
$string['upgradenotice_2022080922'] = 'このリリース以降、Boost Union は独自のロゴおよびコンパクトロゴ設定を持ち、Moodle コア設定のファイルは使用しません。';
$string['upgradenotice_2022080922_logo'] = 'ロゴ';
$string['upgradenotice_2022080922_logocompact'] = 'コンパクトロゴ';
$string['upgradenotice_2022080922_copied'] = 'Moodle コア設定の <strong>{$a}</strong> はアップグレード時に Boost Union の {$a} 設定へコピーされました。結果を確認してください。';
$string['upgradenotice_2022080922_notcopied'] = 'Boost Union の <strong>{$a}</strong> 設定は現在空です。今後 {$a} を使用する場合は、Boost Union の設定にアップロードしてください。';
$string['upgradenotice_2025041410'] = '「ゲストアクセスのヒントを表示する」設定が拡張されました。既存の設定（「はい」）は「ゲストパスワードが設定されていない場合のみ表示」に移行されました。';
$string['upgradenotice_2025041413'] = '設定名「courselistinghowfields」は誤字修正のため「courselistingshowfields」に変更されました。既存の設定は新しい名前に移行されています。';
$string['upgradenotice_2025041416'] = 'スマートメニューの区切り線が専用の項目タイプとして利用可能になりました。既存の区切り線（# を使った見出し）は自動的に新しい Divider タイプに変換されました。';
$string['upgradenotice_2025100623'] = 'ナビバー色のオプション名が変更されました。「Primary color navbar with dark/light font color」は「Colored navbar with dark/light font color」に変更され、既存設定は自動的に移行されました。また、従来の外観を維持するため、プライマリブランドカラーは新しい「Navbar tint」設定に移行されています。';
