<?php
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You are not allowed to view this content.' ) );
	}


	// Update options
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'update' ) {
		update_option( 'dur_role', $_POST['dur_role'] );
		if ( isset( $_POST['dur_tableprefixes'] ) && is_array( $_POST['dur_tableprefixes'] ) ) {
			$prefixes = array();
			foreach ( $_POST['dur_tableprefixes'] as $prefix ) {
				if ( ! empty( $prefix ) ) {
					$prefixes[] = $prefix;
				}
			}

			update_option( 'dur_tableprefixes', $prefixes );
		}

		echo '<div class="updated"><p><strong>' . __( 'Settings Saved Successfully.' ) . '</strong></p></div>';
	}

$roles = dur_find_all_roles();
$dur_role = get_option( 'dur_role' );
$prefixes = get_option( 'dur_tableprefixes' );

?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Default User Roles' ); ?></h2>

	<form method="post" action="" id="dur-options">
		<input type="hidden" name="action" value="update" />

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="dur_role"><?php _e( 'Default Role' ); ?></label></th>
				<td>
					<select name="dur_role" id="dur_role">
						<?php if ( $roles ) : ?>
							<?php foreach ( $roles as $role => $value ) : ?>
								<option value="<?php echo $role; ?>" <?php selected( $dur_role, $role ); ?>><?php echo ucfirst( $role ); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select><br />
					<?php _e( 'Choose the default role users should be given (if no role in other tables was found).' ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="dur_tableprefixes"><?php _e( 'Add WP Prefixes' ); ?></label></th>
				<td>
					<?php if ( $prefixes ) : ?>
						<?php $i = 1; ?>
						<?php foreach ( $prefixes as $prefix ) : ?>
							<?php _e( 'Prefix' ); ?> <?php echo $i; ?> <input name="dur_tableprefixes[]" id="dur_tableprefixes_<?php echo $i; ?>" class="regular-text" type="text" value="<?php echo $prefix; ?>" /><br />
							<?php $i++; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<br /><?php _e( 'Add New' ); ?> <input name="dur_tableprefixes[]" id="dur_tableprefixes" class="regular-text" type="text" value="" /><br />
					<?php _e( 'The list above includes prefixes of WordPress installations to be searched for user roles. To remove any field, leave it empty and save. The default WordPress prefix is <code>wp_</code>.' ); ?>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="submit" value="<?php _e( 'Save All' ); ?>" class="button-primary" />
		</p>

	</form>
</div>
