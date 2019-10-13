<?php
// Страница настроек плагина
function translater_settings()
{	?>
    <h2>Plugin settings: <b>Translater</b></h2>
    <style>
    	.translater_error {color: red;}
        .translater_ok {color: green;}
        
        .translaterForm {
            width: 700px;
            padding: 0;
        }
        .translaterForm input[type='text'] {
            width: 100%;
            min-width: 200px;
            line-height: 30px;
            height: 30px;
            padding: 0 10px;
            border-radius: 3px;
        }
        .translaterForm input[type='submit'] {
            font-size: 16px;
        }
    </style>
    <?php
    // Сохранение настроек
    if (isset($_POST['translater_save_settings'])) {
    	update_option('translater_apiKey', $_POST['translater_apiKey']);
        update_option('translater_title', $_POST['translater_title']);
        update_option('translater_content', $_POST['translater_content']);
        update_option('translater_dell_images', $_POST['translater_dell_images']);
        update_option('translater_dell_links', $_POST['translater_dell_links']);
        update_option('translater_dell_props', $_POST['translater_dell_props']);
        update_option('translater_dell_script', $_POST['translater_dell_script']);
        update_option('translater_dell_comment', $_POST['translater_dell_comment']);
        update_option('translater_decode', $_POST['translater_decode']);
    }

    // Если в базе нет настроек, то будут установлены по умолчанию
    add_option('translater_apiKey', '', '', 'no');
    add_option('translater_title', '', '', 'no');
    add_option('translater_content', '', '', 'no');
    add_option('translater_dell_images', '', '', 'no');
    add_option('translater_dell_links', '', '', 'no');
    add_option('translater_dell_props', '', '', 'no');
    add_option('translater_dell_script', '', '', 'no');
    add_option('translater_dell_comment', '', '', 'no');
    add_option('translater_decode', '', '', 'no');
    ?>
    <form method="POST" class="translaterForm">
		<table>
			<tbody>
				<tr>
					<td><label for="translater_apiKey">Yandex Translate (api key): </label></td>
					<td><input name="translater_apiKey" id="translater_apiKey" value="<?php echo get_option('translater_apiKey'); ?>" type="text" /></td>
				</tr>
				<tr>
					<td><label for="translater_title">Title path: </label></td>
					<td><input name="translater_title" id="translater_title" value="<?php echo get_option('translater_title'); ?>" type="text" /></td>
				</tr>
				<tr>
					<td><label for="translater_content">Content path: </label></td>
					<td><input name="translater_content" id="translater_content" value="<?php echo get_option('translater_content'); ?>" type="text" /></td>
				</tr>
				<tr>
					<td><label for="translater_dell_images">Remove tag img: </label></td>
					<td><input name="translater_dell_images" id="translater_dell_images" type="checkbox"<?php echo get_option('translater_dell_images') ? ' checked="checked"' : ''; ?> /></td>
				</tr>
				<tr>
					<td><label for="translater_dell_links">Remove tag link: </label></td>
					<td><input name="translater_dell_links" id="translater_dell_links" type="checkbox"<?php echo get_option('translater_dell_links') ? ' checked="checked"' : ''; ?> /></td>
				</tr>			
                <tr>
                    <td><label for="translater_dell_props">Remove all props in tag: </label></td>
                    <td><input name="translater_dell_props" id="translater_dell_props" type="checkbox"<?php echo get_option('translater_dell_props') ? ' checked="checked"' : ''; ?> /></td>
                </tr> 
                <tr>
                    <td><label for="translater_dell_script">Remove tag script: </label></td>
                    <td><input name="translater_dell_script" id="translater_dell_script" type="checkbox"<?php echo get_option('translater_dell_script') ? ' checked="checked"' : ''; ?> /></td>
                </tr>   
                <tr>
                    <td><label for="translater_dell_comment">Remove tag comment: </label></td>
                    <td><input name="translater_dell_comment" id="translater_dell_comment" type="checkbox"<?php echo get_option('translater_dell_comment') ? ' checked="checked"' : ''; ?> /></td>
                </tr> 
                <tr>
                    <td><label for="translater_decode">Decode 1251 to utf8: </label></td>
                    <td><input name="translater_decode" id="translater_decode" type="checkbox"<?php echo get_option('translater_decode') ? ' checked="checked"' : ''; ?> /></td>
                </tr>  
				<tr>
					<td colspan='2'><br /><input name='translater_save_settings' type='submit' value='Save settings' /></td>
				</tr>
			</tbody>
		</table>
	</form>
	<?php
}