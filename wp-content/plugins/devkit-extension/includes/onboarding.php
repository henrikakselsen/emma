<section class="layers-area-wrapper">

	<div class="layers-onboard-wrapper">

		<div class="layers-onboard-controllers">
			<div class="onboard-nav-dots layers-pull-left" id="layers-onboard-anchors"></div>
			<a class="layers-button btn-link layers-pull-right" href="" id="layers-onboard-skip"><?php _e( 'Skip' , 'layers-devkit' ); ?></a>
		</div>

		<div class="layers-onboard-slider">

			<!-- Learn the Ropes: Widgets -->
			<div class="layers-onboard-slide layers-animate layers-onboard-slide-current">
				<div class="layers-column layers-span-6 postbox">
					<div class="layers-content-large">
						<!-- Your content goes here -->
						<div class="layers-section-title layers-small layers-no-push-bottom">
							<div class="layers-push-bottom-small">
								<small class="layers-label label-secondary">
								<?php _e( 'Welcome' , 'layers-devkit' ); ?>
								</small>
							</div>
							<h3 class="layers-heading">
								<?php _e( 'Thank you for installing DevKit!' , 'layers-devkit' ); ?>
							</h3>
							<div class="layers-excerpt">
								<p>
									<?php _e( 'To use, open the customizer by going to Layers > Customize, hover over the Layers badge and click on DevKit. ' , 'layers-devkit' ); ?>
								</p>
								<p>
									<?php _e( 'DevKit will slide out and you’ll see buttons for CSS, the responsive states for desktop, tablet and mobile and a button for Javascript.' , 'layers-devkit' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="layers-button-well">
						<a class="layers-button btn-primary layers-pull-right onbard-next-step" href="<?php echo admin_url( 'customize.php?panel=[devkit]' ); ?>"><?php _e( 'Got it, Next &rarr;' , 'layers-devkit' ); ?></a>
					</div>
				</div>
				<div class="layers-column layers-span-6 no-gutter layers-demo-video">
					<?php layers_show_html5_video( 'http://cdn.oboxsites.com/layers/videos/devkit-01.mp4', 490 ); ?>
				</div>
			</div>

			<!-- Learn the Ropes: Widgets -->
			<div class="layers-onboard-slide layers-animate layers-onboard-slide-inactive">
				<div class="layers-column layers-span-4 postbox">
					<div class="layers-content-large">
						<!-- Your content goes here -->
						<div class="layers-section-title layers-small layers-no-push-bottom">
							<div class="layers-push-bottom-small">
								<small class="layers-label label-secondary">
								<?php _e( 'Welcome' , 'layers-devkit' ); ?>
								</small>
							</div>
							<h3 class="layers-heading">
								<?php _e( 'Editing different screens' , 'layers-devkit' ); ?>
							</h3>
							<div class="layers-excerpt">
								<p>
									<?php _e( 'Code entered into the main CSS panel will be global. Code entered into the responsive panels will only affect those states.' , 'layers-devkit' ); ?>
								</p>
								<p>
									<?php _e( 'In the Javascript panel you can edit and test run your custom JS.' , 'layers-devkit' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="layers-button-well">
						<a class="layers-button btn-primary layers-pull-right" href="<?php echo admin_url( 'customize.php' ); ?>"><?php _e( 'Awesome, Let\'s Go &rarr;' , 'layers-devkit' ); ?></a>
					</div>
				</div>
				<div class="layers-column layers-span-8 no-gutter layers-demo-video">
					<?php layers_show_html5_video( 'http://cdn.oboxsites.com/layers/videos/devkit-02.mp4', 660 ); ?>
				</div>
			</div>

		</div>

	</div>

</section>
