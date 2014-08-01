<?php

class cfs_radio extends cfs_field {

	function __construct() {
		$this->name = 'radio';
		$this->label = __( 'ラジオボタン', 'cfs' );
		$this->parent = $parent;
	}

	// 投稿画面出力設定
	function html( $field ) {
		?>

		<?php foreach ( $field->options['choices'] as $val => $label ) : ?>
			<?php $val = ('{empty}' == $val) ? '' : $val; ?>

			<?php $checked = in_array( $val, (array) $field->value ) ? ' checked="checked"' : ''; ?>

			<input type='radio' name='<?php echo $field->input_name; ?>' value='<?php echo esc_attr( $val ); ?>'<?php echo $selected; ?> class="<?php echo $field->input_class; ?>"<?php echo $checked; ?>> <?php echo esc_attr( $label ); ?><br>
		<?php endforeach; ?>

		<?php
	}

	// CFSフィールドグループ管理画面出力設定
	function options_html( $key, $field ) {
		// Convert choices to textarea-friendly format
		if ( isset( $field->options['choices'] ) && is_array( $field->options['choices'] ) ) {
			foreach ( $field->options['choices'] as $choice_key => $choice_val ) {
				$field->options['choices'][$choice_key] = "$choice_key : $choice_val";
			}
			$field->options['choices'] = implode( "\n", $field->options['choices'] );
		} else {
			$field->options['choices'] = '';
		}
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e( 'Choices', 'cfs' ); ?></label>
				<p class="description"><?php _e( 'Enter one choice per line', 'cfs' ); ?></p>
			</td>
			<td>
		<?php
		CFS()->create_field( array(
			'type' => 'textarea',
			'input_name' => "cfs[fields][$key][options][choices]",
			'value' => $this->get_option( $field, 'choices' ),
		) );
		?>
			</td>
		</tr>

		<?php
		}
	
		function format_value_for_input($value, $field = null) {
			return htmlspecialchars($value, ENT_QUOTES);
		}

//		function format_value_for_api( $value, $field = null ) {
//			return $value;
//		}

//		function prepare_value( $value, $field ) {
//			return $value;
//		}

		// CFSフィールドグループ管理画面で入力した選択肢をデータベースに保存
		function pre_save_field( $field ) {
			$new_choices = array();
			$choices = trim( $field['options']['choices'] );
			if ( !empty( $choices ) ) {
				$choices = str_replace( "\r\n", "\n", $choices );
				$choices = str_replace( "\r", "\n", $choices );
				$choices = (false !== strpos( $choices, "\n" )) ? explode( "\n", $choices ) : (array) $choices;

				foreach ( $choices as $choice ) {
					$choice = trim( $choice );
					if ( false !== ($pos = strpos( $choice, ' : ' )) ) {
						$array_key = substr( $choice, 0, $pos );
						$array_value = substr( $choice, $pos + 3 );
						$new_choices[$array_key] = $array_value;
					} else {
						$new_choices[$choice] = $choice;
					}
				}
			}

			$field['options']['choices'] = $new_choices;

			return $field;
		}

	}
		