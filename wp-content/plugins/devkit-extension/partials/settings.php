<?php

// Settings
global $layers_devkit;

// Load needed WP resources for media uploader
wp_enqueue_media(); ?>
<div class="wrap">

	<h2><?php _e( 'DevKit Settings', 'layers-devkit' ) ?></h2>

	<?php if ( TRUE == $this->saved ) { ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', 'wsw' ); ?></strong></p></div>
	<?php } ?>

	<form method="post" action="" novalidate>

		<input type="hidden" name="option_page" value="general">
		<input type="hidden" name="action" value="update">

		<?php wp_nonce_field( 'layers-devkit-settings', 'layers-devkit-nonce' ); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<?php _e( 'Code Panels', 'layers-devkit' ) ?>
					</th>
					<td>

						<ul class="devkit-settings-optionrow">
							<li>
								<label for="layers-devkit-css-active">
									<input type="checkbox" disabled name="layers-devkit-css-active" id="layers-devkit-css-active" value="yes" checked >
									<?php _e( 'CSS', 'layers-devkit' ) ?>
								</label>
							</li>
							<li>
								<label for="layers-devkit-desktop-active">
									<input type="checkbox" name="layers-devkit-desktop-active" id="layers-devkit-desktop-active" value="yes" <?php checked( layers_devkit_get_option( 'layers-devkit-desktop-active', TRUE ), 'yes', TRUE ); ?> >
									<?php _e( 'Desktop', 'layers-devkit' ) ?>
								</label>
								<?php if ( !Layers_DevKit::is_layers_active() ) { ?>
									<label for="layers-devkit-desktop-width">
										<?php _e( 'Breakpoint:', 'layers-devkit' ) ?>
										<input type="text" name="layers-devkit-desktop-width" id="layers-devkit-desktop-width" value="<?php echo esc_attr( layers_devkit_get_option( 'layers-devkit-desktop-width', FALSE ) ); ?>" class="small-text"> px
									</label>
								<?php } ?>
							</li>
							<li>
								<label for="layers-devkit-tablet-active">
									<input type="checkbox" name="layers-devkit-tablet-active" id="layers-devkit-tablet-active" value="yes" <?php checked( layers_devkit_get_option( 'layers-devkit-tablet-active', TRUE ), 'yes', TRUE ); ?> >
									<?php _e( 'Tablet', 'layers-devkit' ) ?>
								</label>
								<?php if ( !Layers_DevKit::is_layers_active() ) { ?>
									<label for="layers-devkit-tablet-width">
										<?php _e( 'Breakpoint:', 'layers-devkit' ) ?>
										<input type="text" name="layers-devkit-tablet-width" id="layers-devkit-tablet-width" value="<?php echo esc_attr( layers_devkit_get_option( 'layers-devkit-tablet-width', FALSE ) ); ?>" class="small-text"> px
									</label>
								<?php } ?>
							</li>
							<li>
								<label for="layers-devkit-mobile-active">
									<input type="checkbox" name="layers-devkit-mobile-active" id="layers-devkit-mobile-active" value="yes" <?php checked( layers_devkit_get_option( 'layers-devkit-mobile-active', TRUE ), 'yes', TRUE ); ?> >
									<?php _e( 'Mobile', 'layers-devkit' ) ?>
								</label>
								<?php if ( !Layers_DevKit::is_layers_active() ) { ?>
									<label for="layers-devkit-mobile-width">
										<?php _e( 'Breakpoint:', 'layers-devkit' ); ?>
										<input type="text" name="layers-devkit-mobile-width" id="layers-devkit-mobile-width" value="<?php echo esc_attr( layers_devkit_get_option( 'layers-devkit-mobile-width', FALSE ) ); ?>" class="small-text"> px
									</label>
								<?php } ?>

							</li>
							<li>
								<label for="layers-devkit-js-active">
									<input type="checkbox" name="layers-devkit-js-active" id="layers-devkit-js-active" value="yes" <?php checked( layers_devkit_get_option( 'layers-devkit-js-active', TRUE ), 'yes', TRUE ); ?> >
									<?php _e( 'JavaScript', 'layers-devkit' ) ?>
								</label>
							</li>
						</ul>

						<p class="description"><?php _e( "Choose which screen sizes you'd like to toggle when using DevKit.", 'layers-devkit' ) ?></p>

					</td>
				</tr>

				<tr>
					<th scope="row">
						<?php _e( 'Code Linting', 'layers-devkit' ) ?>
					</th>
					<td>

						<label for="layers-devkit-code-linting">
							<input type="checkbox" name="layers-devkit-code-linting" id="layers-devkit-code-linting" value="yes" <?php checked( layers_devkit_get_option( 'layers-devkit-code-linting', TRUE ), 'yes', TRUE ); ?> >
							<?php _e( 'Enable Code Linting', 'layers-devkit' ) ?>
						</label>

						<p class="description"><?php _e( "Enable live linting/reporting of code errors.", 'layers-devkit' ) ?></p>

					</td>
				</tr>

			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'layers-devkit' ) ?>">
		</p>

	</form>

</div>
