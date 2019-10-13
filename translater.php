<?php
/**
 * @package Translater
 * @version 1.0
 */
/*
Plugin Name: Translater
Plugin URI: https://github.com/zinovchik/translater
Description: This is a plugin for parse and translate articles.
Author: Maxim Zinovchik
Version: 1.0
*/
// Страница настроек плагина

require_once('translater_settings_page.php');

//****************************************************************************************************************************************
// Функция добавляет пункты меню в админку
function translater_add_menu()
{
    add_menu_page('Translater', 'Translater', 8, 'translater_get_posts', 'translater_get_posts');
    add_submenu_page('translater_get_posts', 'Settings', 'Settings', 8, 'translater_settings', 'translater_settings');
}

add_action('admin_menu', 'translater_add_menu');

function translater_get_posts()
{   ?>
    <h2>Parse and translate articles:</h2>
    <style type='text/css'>
        .translater_error {color: red;}
        .translater_ok {color: green;}
        
        .translaterForm {
            width: 700px;
            padding: 0;
        }
        .translaterForm input[type='text'] {
            width: 100%;
            line-height: 30px;
            height: 30px;
            padding: 0 10px;
            border-radius: 3px;

            display: block;
            margin: 10px 0;
            width: 99%; 
            border:1px solid #777;
        }

        .translaterForm textarea {
        	display: block;
        	height: 400px;
        	margin: 10px 0;
        	width: 99%; 
        	border:1px solid #777;
        }
        .translaterForm input[type='submit'] {
            font-size: 16px;
        }
    </style>
    <?
    if (isset($_POST['translaterUrl']) && $_POST['translaterUrl'] != '') {
    	
    	// подключение библиотеки
        include_once('simple_html_dom.php');
		
		global $wpdb;
        $translater_title = get_option('translater_title');
        $translater_content = get_option('translater_content');
        $translater_dell_images = get_option('translater_dell_images');
        $translater_dell_links = get_option('translater_dell_links');
        $translater_dell_props = get_option('translater_dell_props');
        $translater_dell_script = get_option('translater_dell_script');
        $translater_dell_comment = get_option('translater_dell_comment');
        $translater_decode = get_option('translater_decode');

        $html = file_get_html($_POST['translaterUrl'], $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT);
        
        // Если кодировка страниці windows1251 то декодируем в utf-8
        if($translater_decode) {
            $str = $html;
            $str = mb_convert_encoding($str, 'utf-8', 'cp1251');
            $html = str_get_html($str);
            echo "<br> Код страницы декодирован из windows1251 в utf-8";
        }
        
        $title = $html->find($translater_title, 0)->plaintext;
        $content = $html->find($translater_content, 0);
                
        echo "<br> Длина исходного текста: ".strlen($title . $content);
        
        //Remove img
        if($translater_dell_images){
            $i = 0;
            foreach($content->find('img') as $img) {
                $img->outertext = '';
                $i++;
            }
            echo "<br> Удалено картинок: ".$i;
        }

        //Remove links
        if($translater_dell_links){
            $i = 0;
            foreach($content->find('a') as $link) {
                $link->outertext = $link->plaintext;
                $i++;
            }
            echo "<br> Удалено ссылок: ".$i;
        }

        //Remove adsbygoogle

        $i = 0;
        foreach($content->find('.adsbygoogle') as $elem) {
            $elem->outertext = '';
            $i++;
        }
        echo "<br> Удалено .adsbygoogle: ".$i;   


        //Remove script
        if($translater_dell_script){
            $i = 0;
            foreach($content->find('script') as $elem) {
                $elem->outertext = '';
                $i++;
            }
            echo "<br> Удалено скриптов: ".$i;
        }

        //Remove all props
        if($translater_dell_props){

            $i = 0;
            foreach ($content->find('div, p, span, strong, b, i, li, ul, ol, table, h1, h2, h3, h4, h5, h6, sup') as $e3) {
                if($e3->style != '') { 
                    $e3->style = null;
                    $i++;
                }

                if($e3->class != '') {
                    $e3->class = null;
                    $i++;
                }

                if($e3->id != '') {
                    $e3->id = null;
                    $i++;
                }

                // $e3->outertext = '<'.$e3->tag.'>'.$e3->innertext.'</'.$e3->tag.'>'; 
                    
            }
            echo "<br> Удалены все id, стили и классы: ".$i;

           //  //попытка доудалять лишние атрибуты, а то не все удаляются почему то
           //  $tagArray = array('div', 'p', 'span', 'strong', 'b', 'i', 'li', 'ol', 'ul', 'table');
           //  $i = 0;

           //  foreach($tagArray as $tag) {
           //      foreach($content->find($tag) as $elem) {
           //          $elem->outertext = '<'.$tag.'>'.$elem->innertext.'</'.$tag.'>'; 
           //          $i++;
           //      }
           //  }
           // // над для того чтобы внутри заголовков не было тегов
           //  $tagArray = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');

           //  foreach($tagArray as $tag) {
           //      foreach($content->find($tag) as $elem) {
           //          $elem->outertext = '<'.$tag.'>'.trim($elem->plaintext).'</'.$tag.'>';
           //          $i++;
           //      }
           //  }
           //  echo "<br> Удалены все пропсы (класы, id итд)".$i;

            
        }

        //Remove comment
        if($translater_dell_comment){
            $i = 0;
            foreach($content->find('comment') as $elem) {
                $elem->outertext = '';
                $i++;
            }
            echo "<br> Удалено коментов: ".$i;
        }



        $content = trim($content->innertext);

        $title = transtateText($title);
        $content = transtateText($content);

        //print_r($title);
        if($title['code'] == '200' && $title['code'] == '200') {
			$title = $title['text'][0];
	        $content = $content['text'][0];

	        echo "<br> Длина переведенного текста: ".strlen($title . $content);

	        ?>
		    <form method="post" class="translaterForm">
		      	<p>
		      		<label for="translaterTitle">Title</label><br>
		      		<input name="translaterTitle" id="translaterTitle" value="<?php echo $title; ?>" type="text"/>
		      	</p>
				<p>
					<label for="translaterContent">Content</label><br>
					<textarea  name="translaterContent" id="translaterContent"><?php echo $content; ?></textarea>
				</p>
		      	<p>
		      		<input class="translaterSave" type="submit" value="Save Post" />
		      	</p>
		    </form>
		    <?php
        } else {
        	echo "<p class='translater_error'>В процесе перевода возникла ошибка</p>";
        }

    } elseif (isset($_POST['translaterTitle']) && $_POST['translaterTitle'] != '') {
        $np = array(
            'title' => $_POST['translaterTitle'],
            'content' => $_POST['translaterContent'],
        );
        $insertedPostId = wp_insert_post(array(
            'post_title' => $np['title'],
            'post_type' => 'post', // тип записи
            'comment_status' => 'closed', // обсждение закрыть
            'ping_status' => 'closed', // пинги запретить
            'post_content' => $np['content'],
            // 'post_status' => 'publish', // опубликовать
        ));

        if ($insertedPostId) {
            wp_redirect(get_site_url() . "/wp-admin/post.php?post=$insertedPostId&action=edit"); exit;
        } else {
            echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><b>Error</b></p></div>';
        }
    } else {
    ?>
    <form method="post" class="translaterForm">
      <p><input class='translaterUrl' type="text" name="translaterUrl"></p>
      <p><input class="translaterParse" type="submit" value="Parse And Translate"></p>
    </form>
    <?php
    }
}






// Function fot translate text ru-ua
function transtateText($originalText){
	global $wpdb;
    $apiKey = get_option('translater_apiKey'); // trnsl.1.1.20160215T065938Z.21a7a1d8c0bef863.8332b4f2590416425007da37570bbe20e1d87e1a
    $postData = http_build_query(array('key'    => $apiKey,
                                       'text'   => $originalText,
                                       'lang'   => 'ru-uk',
                                       'format' => 'html' ));

    $opts = array('http' => array('method'  => 'POST',
                                  'header'  => 'Content-type: application/x-www-form-urlencoded',
                                  'content' => $postData ));

    $context  = stream_context_create($opts);

    $result = file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate', false, $context);
    $result = json_decode($result, true);
    return $result;
}



