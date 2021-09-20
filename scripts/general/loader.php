<?php

include_once __DIR__ . '/former.php';

/**
 * [CLASS] loader
 * 
 * <h4>Loader v1.2.0</h4><hr>
 * Loaderは、サイト全体レイアウトを構成するHTMLコードをPHP上で定義したメソッドを用いて、ページ構成をオブジェクト単位で作成するクラスです。<br>
 * また、ここでJavaScriptのインポートやロゴの管理ができます。<br>
 * ※コンストラクタはありません。
 * 
 * @package VirtualControl_scripts_general
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class loader {

    /**
     * [GET] ページデータ取得
     * 
     * HTMLページ全体（原型）の取得を行います
     * 
     * @param string $site_title サイトタイトルを指定します
     * @param string $title ページタイトルを指定します
     * @param string $title_icon ページタイトルの左隣に設置するアイコンを指定します
     * @param string $js_file ページオリジナルJavaScriptファイルのファイル名のみを指定します（/scripts/js/pages/..）
     * @return void ページHTMLの文字列をechoします
     */
    public function getPage($site_title, $title, $title_icon, $js_file = '', $istitle = true) {
	$data = '<!DOCTYPE html><html><head>';
	$data .= $this->loadHeader($site_title, $title);
	$data .= Former::ExportClass();
	$data .= '</head><body class="text-monospace">';
	$data .= $this->loadLogo();
	if ($istitle) {
	    $data .= $this->Title($site_title, $title_icon);
	}
	$data .= '<div id="data_output"></div>';
	$data .= $this->footer();
	$data .= $this->loadFooter($js_file);
	$data .= '</body></html>';
	echo $data;
    }

    /**
     * [GET] ラジオボタン生成
     * 
     * チェックボックス・ラジオボタンを作成します。<br>
     * 同じグループにするには、$nameの引数を同じ名前にする必要があります。<br>
     * 
     * @param string $id IDを指定します
     * @param string $name フォームグループ内の名前を指定します（グループにする場合、名前は統一にする必要があります）
     * @param string|int $value ラジオボタンに対する値を指定します
     * @param mixed $outname 表示する名前を指定します
     * @param bool $selected 選択されている状態にするかどうかを指定します
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function Radio($id, $name, $value, $outname, $selected): string {
	$type_text = 'radio';
	$class_text = ($outname) ? 'radio01' : 'radio02';
	$sel_text = ($selected) ? 'checked' : '';
	return '<input ' . $sel_text . ' required id="' . $id . '" type="' . $type_text . '" name="' . $name . '" value="' . $value . '"><label for="' . $id . '" class="' . $class_text . '">' . $outname . '</label><br>';
    }

    /**
     * [GET] チェックボックス生成
     * 
     * @param string $id チェックボックスIDを指定します
     * @param string $name フォームグループ内の名前を指定します（グループにする場合、名前は統一にする必要があります）
     * @param string|int $value ラジオボタンに対する値を指定します
     * @param string $outname 表示する名前を指定します
     * @param bool $selected 選択されている状態にするかどうかを指定します
     * @param bool $required 必須入力かどうかを指定します（Default: false）
     * @param bool $autofocus 自動フォーカスを行うかどうかを指定します（Default: false）
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function Check($id, $name, $value, $outname, $selected, $required = false, $autofocus = false): string {
	$type_text = 'checkbox';
	$class_text = 'checkbox02';
	$sel_text = ($selected) ? 'checked' : '';
	$req_text = ($required) ? 'required' : '';
	$foc_text = ($autofocus) ? 'autofocus' : '';
	return '<input ' . $sel_text . ' id="' . $id . '" type="' . $type_text . '" name="' . $name . '" value="' . $value . '" ' . $foc_text . ' ' . $req_text . '><label for="' . $id . '" class="' . $class_text . '">' . $outname . '</label><br>';
    }

    /**
     * [GET] ページタイトル生成
     * 
     * タイトルとアイコンで、黒背景のサイトタイトル（上部）を作成します。<br>
     * Formerのタイトルと違い、黒背景ベースで作成できます。
     * 
     * @param string $title ページタイトルを指定します。
     * @param string $icon 左隣につけるアイコンを指定します。
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function Title($title, $icon): string {
	return "<div class=\"py-2 vc-back-black\"><div class=\"container\"><div class=\"col-md-12 py-2\"><h2><i class=\"fas fa-fw fa-$icon\"></i>$title</h2></div></div></div>";
    }

    /**
     * [SET] サブタイトル（キャプションあり）作成
     * 
     * サブタイトルを作成します。<br>
     * サブタイトルは、タイトルより拡張したもので、タイトルの左隣にアイコン、その下を区切り線で区切り、その下にテキストがあります。
     * 
     * @param string $title	    タイトル名を指定します
     * @param string $caption	    タイトルの下部につける説明を入力します
     * @param string $icon	    タイトルの左隣につけるアイコンの情報を入力します
     * @param string $badgetext	    タイトル横にある背景付きのテキストを表示させます（Default: ''）
     * 
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function SubTitle($title, $caption, $icon, $badgetext = '') {
	$b_text = ($badgetext) ? '<span class = "badge-dark badge-pill">' . $badgetext . '</span>' : '';
	return '<div class="form-group pt-2"><div class="w-100"><h3 class="sub-title"><i class="fas fa-' . $icon . ' fa-fw"></i>' . $title . ' ' . $b_text . '</h3><p class="sub-caption">' . $caption . '<p></div></div>';
    }

    /**
     * [GET] ヘッダー取得
     * 
     * ページタイトルとともに、ヘッダー部の取得を行います。<br>
     * アイコン、CSSおよびJavaScriptの定義を行います。
     * 
     * @param string $site_title サイトタイトルを指定します
     * @param string $title ページタイトルを指定します
     * @param string $hide_text 現在位置（またはルート）からの相対距離を表す記号（. .. ../../../ など）を指定します（Default: /）
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function loadHeader($site_title, $title): string {
	$loader_css = ['font-awesome/css/all.min.css', 'font-awesome/css/brands.min.css', 'font-awesome/css/regular.min.css', 'font-awesome/css/solid.min.css', 'style.css', 'button.css', 'details.css', 'select.css', 'check.css', 'list.css', 'radio.css', 'background.css', 'popup.css', 'jquery.bxslider.css'];

	$loader_text = '';

	foreach ($loader_css as $l) {
	    $loader_text .= '<link rel="stylesheet" type="text/css" href="/style/' . $l . '">';
	}

	return '<meta charset="utf-8"><meta name="application-name" content="' . $site_title . '"><link rel="icon" type="image/svg+xml" href="/style/images/favicon.svg"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . $title . ' - ' . $site_title . '</title><meta name="description" content="' . $site_title . '">' . $loader_text . '<script src="/scripts/js/ajax/functionid.js"></script>';
    }

    /**
     * [GET] サイトロゴ取得
     * 
     * ヘッダーに表示するサイトロゴを追加します。
     * 
     * @return string HTMLを生成し、文字列として渡します
     */
    function loadLogo(): string {
	return '<div class="vc-back pt-5"><div class="container"><div class="row mg-2"><div class="col-md-12 m-3">'
	. ''
		. '</div></div></div></div>';
    }

    /**
     * [GET] ナビゲーション取得
     * 
     * サイトに表示するナビゲーションを取得します。<br>
     * 権限に合わせて調整します。<br>
     * 0: VCServer<br>
     * 1: VCHost<br>
     * 999: サイトリンクなし（ロゴのみ）<br>
     * それ以外: ゲスト
     * 
     * @param int $permission 権限の番号を指定します
     * @return string HTMLを生成し、文字列として渡します
     */
    function navigation(): string {
	    $data = '<nav class="fixed-top navbar navbar-expand-md navbar-dark bg-dark shadow-bottom"><div class="container"> <a class="navbar-brand" href="/"><svg class="navbar-logo" version="1.1" viewBox="0 0 106.53 18.638" xmlns="http://www.w3.org/2000/svg" fill="#ff5a00"><g transform="translate(-35.835 -38.875)"><path d="m43.285 38.875v11.51h-5.4704v-11.505c-1.2387 1.5318-1.9763 4.006-1.9789 6.6455-2.39e-4 4.5173 2.1109 8.1796 4.7141 8.1792 2.6032 3.7e-4 4.712-3.662 4.7118-8.1792-0.0017-2.641-0.73723-5.1182-1.9766-6.6508zm50.155 4.4828v9.5148h1.9834v-9.5148zm46.947 0v9.5148h1.9834v-9.5148zm-74.813 0.03164c-0.33473 0-0.60845 0.0942-0.82184 0.28248-0.2134 0.18829-0.32015 0.43136-0.32015 0.72844 0 0.2887 0.10675 0.53286 0.32015 0.7337 0.21338 0.19664 0.48711 0.29529 0.82184 0.29529 0.33892 0 0.61338-0.09605 0.82259-0.28851 0.2134-0.19247 0.32015-0.43923 0.32015-0.74048 0-0.29708-0.10675-0.54015-0.32015-0.72844-0.2092-0.18829-0.48368-0.28248-0.82259-0.28248zm10.601 1.1051-1.9774 0.56422v1.3876h-1.0418v1.4621h1.0418v2.9439c0 1.4519 0.6989 2.1778 2.0964 2.1778 0.58996 0 1.0314-0.07795 1.3243-0.23277v-1.4682c-0.22175 0.12134-0.43712 0.18154-0.64632 0.18154-0.53139 0-0.79698-0.33467-0.79698-1.0041v-2.5981h1.4433v-1.4621h-1.4433zm48.728 0-1.9766 0.56422v1.3876h-1.0418v1.4621h1.0418v2.9439c0 1.4519 0.69892 2.1778 2.0964 2.1778 0.58999 0 1.0314-0.07795 1.3243-0.23277v-1.4682c-0.22178 0.12134-0.43712 0.18154-0.64632 0.18154-0.53141 0-0.79774-0.33467-0.79774-1.0041v-2.5981h1.4441v-1.4621h-1.4441zm-35.793 1.7943c-0.39313 6.6e-5 -0.83059 0.05475-1.3115 0.16346-0.477 0.10878-0.85379 0.23438-1.1299 0.37664v1.4938c0.69038-0.45607 1.4188-0.68399 2.1845-0.68399 0.76152 0 1.142 0.35092 1.142 1.0539l-1.7446 0.23277c-1.477 0.19247-2.2154 0.91205-2.2154 2.1589 0 0.58997 0.17768 1.0628 0.53333 1.4184 0.35984 0.35147 0.8515 0.52731 1.4749 0.52731 0.8452 0 1.4831-0.35979 1.9141-1.0795h0.02561v0.92203h1.8764v-3.841c0-1.8282-0.91662-2.7425-2.7488-2.7427zm14.762 0c-1.0962 0-1.9728 0.31583-2.6297 0.94764-0.65273 0.62762-0.97928 1.488-0.97928 2.58 0 0.94562 0.30511 1.7173 0.916 2.3156 0.61089 0.59833 1.4103 0.89793 2.3977 0.89793 0.8452 0 1.492-0.13003 1.9397-0.38945v-1.594c-0.47281 0.30963-0.95636 0.46402-1.4501 0.46402-0.55649 0-0.99348-0.16068-1.3115-0.48286-0.318-0.32636-0.47683-0.77408-0.47683-1.3431 0-0.58578 0.1651-1.0442 0.49566-1.3748 0.33474-0.33473 0.78613-0.50169 1.3552-0.50169 0.51046 0 0.97335 0.15441 1.3876 0.46403v-1.6821c-0.33891-0.20085-0.88712-0.30132-1.6444-0.30132zm6.1002 0c-1.0837 0-1.9433 0.30143-2.5793 0.90395-0.63599 0.59834-0.95442 1.4291-0.95442 2.4919 0 1.0293 0.30587 1.8449 0.91675 2.4474 0.61507 0.59833 1.4584 0.89793 2.5296 0.89793 1.0879 0 1.943-0.30956 2.5665-0.92881 0.62763-0.61926 0.94162-1.4626 0.94162-2.5296 0-0.98746-0.3033-1.7806-0.90998-2.3789-0.6067-0.60252-1.4438-0.90395-2.5107-0.90395zm8.7804 0c-0.90378 0-1.592 0.39119-2.0648 1.1736h-0.0249v-1.0162h-1.9834v6.4263h1.9834v-3.6648c0-0.40586 0.11048-0.74054 0.3322-1.0041 0.22179-0.2636 0.50434-0.39548 0.84745-0.39548 0.7113 0 1.0667 0.49795 1.0667 1.4938v3.5706h1.9774v-3.9352c0-1.7657-0.71147-2.6486-2.1341-2.6486zm16.927 0c-1.0837 0-1.9433 0.30143-2.5793 0.90395-0.63602 0.59834-0.95443 1.4291-0.95443 2.4919 0 1.0293 0.30586 1.8449 0.91676 2.4474 0.61507 0.59833 1.4577 0.89793 2.5288 0.89793 1.0879 0 1.9438-0.30956 2.5672-0.92881 0.62763-0.61926 0.94161-1.4626 0.94161-2.5296 0-0.98746-0.30324-1.7806-0.90997-2.3789-0.60669-0.60252-1.4438-0.90395-2.5107-0.90395zm-63.741 0.04444c-0.81591 0-1.3811 0.43515-1.6949 1.3055h-0.02486v-1.1925h-1.9834v6.4263h1.9834v-3.0689c0-0.54394 0.12116-0.97281 0.36384-1.2866 0.2427-0.318 0.57995-0.47683 1.0109-0.47683 0.31799 0 0.59615 0.06463 0.83465 0.19435v-1.826c-0.11716-0.0502-0.28042-0.07533-0.48964-0.07533zm59.222 0c-0.8159 0-1.3804 0.43515-1.6942 1.3055h-0.0256v-1.1925h-1.9834v6.4263h1.9834v-3.0689c0-0.54394 0.12111-0.97281 0.36385-1.2866 0.24266-0.318 0.57994-0.47683 1.0109-0.47683 0.31801 0 0.59614 0.06463 0.83465 0.19435v-1.826c-0.11705-0.0502-0.28056-0.07533-0.48964-0.07533zm-74.379 0.11299 2.278 6.4263h2.2591l2.3917-6.4263h-2.0716l-1.142 3.9977c-0.12554 0.43934-0.20272 0.80725-0.23201 1.1043h-0.02561c-0.02087-0.31381-0.09367-0.69428-0.2192-1.142l-1.1171-3.9601zm7.7883 0v6.4263h1.9834v-6.4263zm14.059 0v3.8787c0 1.8034 0.74545 2.7051 2.235 2.7051 0.8201 0 1.4743-0.37863 1.9638-1.136h0.03164v0.97853h1.9774v-6.4263h-1.9774v3.6776c0 0.42678-0.10677 0.76589-0.32015 1.0169-0.21341 0.24687-0.49783 0.36986-0.85348 0.36986-0.71967 0-1.0795-0.45399-1.0795-1.362v-3.7024zm31.306 1.362c0.9582 0 1.4373 0.60027 1.4373 1.8011 0 1.2678-0.47467 1.9013-1.4245 1.9013-0.99582 0-1.4938-0.61651-1.4938-1.8508 0-0.58996 0.13005-1.0465 0.38946-1.3687 0.25941-0.32218 0.62289-0.48286 1.0915-0.48286zm25.708 0c0.95818 0 1.4373 0.60027 1.4373 1.8011 0 1.2678-0.47466 1.9013-1.4245 1.9013-0.99584 0-1.4938-0.61651-1.4938-1.8508 0-0.58996 0.1293-1.0465 0.3887-1.3687 0.25931-0.32218 0.62365-0.48286 1.0923-0.48286zm-85.997 0.27872c-2.6032-2.39e-4 -4.712 2.1087-4.7118 4.7118-1.89e-4 2.6033 2.1087 4.7143 4.7118 4.7141 1.5218-0.0019 2.9503-0.73916 3.8335-1.9781h-6.3909l2.7194-5.4712h3.6685c-0.88266-1.2388-2.3095-1.9741-3.8305-1.9766zm40.35 1.7424v0.43239c0 0.39331-0.11677 0.71986-0.35104 0.97928-0.23431 0.25522-0.53758 0.38267-0.90998 0.38267-0.26777 0-0.4813-0.07091-0.6403-0.21318-0.15483-0.14645-0.23277-0.33224-0.23277-0.55819 0-0.49792 0.32286-0.78938 0.96723-0.87306zm-50.883 0.98305c0.35202 1.58e-4 0.63698 0.28518 0.63804 0.63728-1.58e-4 0.35264-0.2854 0.63863-0.63804 0.63879-0.35264-1.58e-4 -0.63863-0.28605-0.63879-0.63879 1e-3 -0.35202 0.28679-0.63712 0.63879-0.63728zm2.8776 0c0.35266-7.45e-4 0.63924 0.28457 0.6403 0.63728-1.58e-4 0.35327-0.28684 0.63954-0.6403 0.63879-0.35266-1.58e-4 -0.63863-0.28605-0.63879-0.63879 1e-3 -0.35202 0.28676-0.63712 0.63879-0.63728zm5.1653 0.07985c0.46293-7.98e-4 0.83831 0.37476 0.83841 0.83766-9.53e-4 0.46229-0.37613 0.83622-0.83841 0.8354-0.46104-9.57e-4 -0.83445-0.37434-0.8354-0.8354 1.04e-4 -0.46168 0.37375-0.83668 0.8354-0.83766z"></path></g></svg><br></a><button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbar2SupportedContent" aria-controls="navbar2SupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span></button><div class="navbar-collapse text-center justify-content-end collapse" id="navbar2SupportedContent"><ul class="navbar-nav w-100"></div></div></nav>';
	return $data;
    }

    /**
     * [GET] フッタースクリプト取得
     * 
     * フッターのJavaScript（jQuery）を宣言するHTMLを取得します
     * 
     * @param string $js_file jQueryコードで独自のイベントハンドラスクリプトがある場合は、そのファイル名を指定します（Default: ''）
     * @return string HTMLを生成し、文字列として渡します
     */
    function loadFooter($js_file = ''): string {
	$sources = ['source/jquery.min.js', 'source/bootstrap.min.js', 'ui/pass_modify.js', 'ui/animation.js', 'ui/select.js', 'ajax/ajax_dynamic.js'];
	if ($js_file) {
	    array_push($sources, 'page/' . $js_file);
	}
	$in_text = '';
	foreach ($sources as $s) {
	    $in_text .= '<script src="/scripts/js/' . $s . '"></script>';
	}
	return $in_text;
    }

    /**
     * [GET] フッター部の表示HTMLの取得
     * 
     * フッターでロゴを表示させるためのHTMLを取得します
     * 
     * @return string HTMLを生成し、文字列として渡します
     */
    function footer(): string {
	return '<div class="vc-back-black pt-0"><div class="container"><div class="row"><div class="col-md-12 m-2 text-center">(C) 2020 -  Project GSC All Rights Reserved.</div></div></div></div>';
    }

    /**
     * [GET] リストグループ開始
     * 
     * リストグループを作成します。<br>
     * リストグループとは、ボタン群の一種で、クリック時にそのリストのリンク先へ遷移できるようになります。<br>
     * ※閉じる場合は必ずcloseListGroup()を利用してください。
     * 
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function openListGroup(): string {
	return '<div class="list-group">';
    }

    /**
     * [GET] リストグループデータ追加
     * 
     * リストグループにリストを追加します。
     * 
     * @param string $id このリストに対するIDを指定します
     * @param string $title リストグループのタイトルを指定します
     * @param string $icon リストグループのアイコンを指定します
     * @param string $text リストグループのテキストを追加します
     * @param string $small_text リストグループの小さなテキストを追加します
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function addListGroup($id, $title, $icon, $text, $small_text): string {
	return '<div tabindex="0" class="list-group-item list-group-item-action flex-column align-items-start active vc-back-card mb-2" id="' . $id . '"><div class="d-flex w-100 justify-content-between"><h5 class="list-group-title"><i class="fas fa-fw fa-' . $icon . ' fa-lg"></i>' . $title . '</h5></div><p class="mb-1">' . $text . '</p> <small>' . $small_text . '</small></div>';
    }

    /**
     * [GET] リストグループ終了
     * 
     * リストグループのデータを閉じます。
     * 
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function closeListGroup() {
	return "</div>";
    }

    /**
     * [GET] メイン詳細作成
     * 
     * detailsタグのメイン（第1層）を作成します
     * 
     * @param string $summary_title サマリータイトルを指定します
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function openDetails($summary_title): string {
	return '<details class="main"><summary class="summary">' . $summary_title . '</summary><div class="details-content">';
    }

    /**
     * [GET] サブ詳細作成
     * 
     * detailsタグのサブ（第2層）を作成します
     * 
     * @param string $summary_title サマリータイトルを指定します
     * @return string 引数に合ったHTMLを生成し、文字列として渡します
     */
    function openSubDetails($summary_title): string {
	return '<details class="sub"><summary class="summary-sub">' . $summary_title . '</summary><div class="details-content-sub">';
    }

    /**
     * [GET] 詳細を閉じる
     * 
     * details タグを閉じます
     * 
     * @return string HTMLを生成し、文字列として渡します
     */
    function closeDetails(): string {
	return '</div></details>';
    }
    
    /**
     * [GET] 説明を加える
     * テキストをホバーするとバルーンポップアップする要素を取り入れます
     * 
     * @param $text 説明するテキストを付加します
     * @return string HTMLを生成し、文字列として取り入れます
     */
    function addExplan($text) {
	return '<a class="s">?<span class="s-balloon">' . $text . '</span></a>';
    }
}
