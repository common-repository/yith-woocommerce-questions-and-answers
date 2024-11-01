<?php
/**
 * Template for displaying the password field
 *
 * @var array $field The field.
 * @package YITH\PluginFramework\Templates\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $class, $name, $std, $value, $custom_attributes, $data ) = yith_plugin_fw_extract( $field, 'id', 'class', 'name', 'std', 'value', 'custom_attributes', 'data' );

$class = isset( $class ) ? $class : 'yith-plugin-fw-text-input';
$class = $class . ' yith-password';
?>
<div class="yith-password-wrapper">
	<input type="password" id="<?php echo esc_attr( $field_id ); ?>"
			name="<?php echo esc_attr( $name ); ?>"
			class="<?php echo esc_attr( $class ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
		<?php if ( isset( $std ) ) : ?>
			data-std="<?php echo esc_attr( $std ); ?>"
		<?php endif; ?>
		<?php echo $custom_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo isset( $data ) ? yith_plugin_fw_html_data_to_string( $data ) : ''; ?>
	/>
	<span class="yith-password-eye"></span>
</div>
