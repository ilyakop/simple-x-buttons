<?php
declare(strict_types=1);
/**
 * Settings page template.
 *
 * @var array $options Current plugin options (passed from plugin_menu_view()).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$all_post_types = get_post_types( array( 'public' => true ), 'objects' );

$button_labels = array(
	'share'   => __( 'Share',   'simple-x-buttons' ),
	'follow'  => __( 'Follow',  'simple-x-buttons' ),
	'mention' => __( 'Mention', 'simple-x-buttons' ),
	'hashtag' => __( 'Hashtag', 'simple-x-buttons' ),
);
?>
<div class="wrap sxb-settings">
	<h1><?php esc_html_e( 'Simple X Buttons', 'simple-x-buttons' ); ?></h1>

	<p class="sxb-tagline"><?php esc_html_e( 'Add X share, repost, like, and reply buttons to your site — no API key, no external scripts, no rate limits. Built on X Web Intents.', 'simple-x-buttons' ); ?></p>

	<form method="post" action="options.php">
		<?php settings_fields( 'sxb_options' ); ?>

		<?php /* ── Section 1: Button Settings ───────────────────────────── */ ?>
		<div class="sxb-section">
			<h2 class="sxb-section__title">
				<span class="dashicons dashicons-admin-appearance" aria-hidden="true"></span>
				<?php esc_html_e( 'Button Settings', 'simple-x-buttons' ); ?>
			</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="sxb_button_style"><?php esc_html_e( 'Button Style', 'simple-x-buttons' ); ?></label></th>
					<td>
						<select id="sxb_button_style" name="sxb_options[button_style]">
							<option value="dark"    <?php selected( $options['button_style'], 'dark' ); ?>><?php esc_html_e( 'Dark', 'simple-x-buttons' ); ?></option>
							<option value="outline" <?php selected( $options['button_style'], 'outline' ); ?>><?php esc_html_e( 'Outline', 'simple-x-buttons' ); ?></option>
							<option value="ghost"   <?php selected( $options['button_style'], 'ghost' ); ?>><?php esc_html_e( 'Ghost', 'simple-x-buttons' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sxb_intent_window"><?php esc_html_e( 'Intent Window', 'simple-x-buttons' ); ?></label></th>
					<td>
						<select id="sxb_intent_window" name="sxb_options[intent_window]">
							<option value="popup"   <?php selected( $options['intent_window'], 'popup' ); ?>><?php esc_html_e( 'Popup', 'simple-x-buttons' ); ?></option>
							<option value="new_tab" <?php selected( $options['intent_window'], 'new_tab' ); ?>><?php esc_html_e( 'New Tab', 'simple-x-buttons' ); ?></option>
						</select>
						<p class="description"><?php esc_html_e( 'Popup opens a small X window. New Tab opens a full tab.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<?php /* ── Section 2: Placements ───────────────────────────────── */ ?>
		<div class="sxb-section">
			<h2 class="sxb-section__title">
				<span class="dashicons dashicons-welcome-add-page" aria-hidden="true"></span>
				<?php esc_html_e( 'Placements', 'simple-x-buttons' ); ?>
			</h2>
			<table class="form-table sxb-placements-table" role="presentation">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Placement', 'simple-x-buttons' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Enabled', 'simple-x-buttons' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Buttons', 'simple-x-buttons' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Post Types', 'simple-x-buttons' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( array( 'after_content' => __( 'After post content', 'simple-x-buttons' ), 'before_content' => __( 'Before post content', 'simple-x-buttons' ) ) as $placement => $label ) :
						$enabled_buttons = (array) ( $options[ $placement . '_buttons' ] ?? array( 'share' ) );
						$enabled_types   = (array) ( $options[ $placement . '_post_types' ] ?? array( 'post' ) );
					?>
					<tr>
						<td><?php echo esc_html( $label ); ?></td>
						<td>
							<label class="sxb-toggle">
								<input type="checkbox" name="sxb_options[<?php echo esc_attr( $placement ); ?>_enabled]" value="1"
									<?php checked( 1, (int) $options[ $placement . '_enabled' ] ); ?> />
								<span class="sxb-toggle__slider"></span>
							</label>
						</td>
						<td>
							<?php foreach ( $button_labels as $type => $btn_label ) : ?>
								<label class="sxb-post-type-label">
									<input type="checkbox"
										name="sxb_options[<?php echo esc_attr( $placement ); ?>_buttons][]"
										value="<?php echo esc_attr( $type ); ?>"
										<?php checked( in_array( $type, $enabled_buttons, true ) ); ?> />
									<?php echo esc_html( $btn_label ); ?>
								</label>
							<?php endforeach; ?>
						</td>
						<td>
							<?php foreach ( $all_post_types as $pt ) : ?>
								<label class="sxb-post-type-label">
									<input type="checkbox"
										name="sxb_options[<?php echo esc_attr( $placement ); ?>_post_types][]"
										value="<?php echo esc_attr( $pt->name ); ?>"
										<?php checked( in_array( $pt->name, $enabled_types, true ) ); ?> />
									<?php echo esc_html( $pt->labels->singular_name ); ?>
								</label>
							<?php endforeach; ?>
						</td>
					</tr>
					<?php endforeach; ?>

					<?php // Pro placement rows — disabled until Pro is ready. ?>
					<?php // <!-- SXB_PRO_HOOK: floating_panel --> ?>
					<?php // <!-- SXB_PRO_HOOK: popup_trigger --> ?>
					<?php // <!-- SXB_PRO_HOOK: display_rules --> ?>
				</tbody>
			</table>
		</div>

		<?php /* ── Section 3: Share Button ─────────────────────────────── */ ?>
		<div class="sxb-section">
			<h2 class="sxb-section__title">
				<span class="dashicons dashicons-share" aria-hidden="true"></span>
				<?php esc_html_e( 'Share Button', 'simple-x-buttons' ); ?>
			</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="sxb_share_label"><?php esc_html_e( 'Button Label', 'simple-x-buttons' ); ?></label></th>
					<td>
						<input type="text" id="sxb_share_label" name="sxb_options[share_label]"
							value="<?php echo esc_attr( $options['share_label'] ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Tweet Text', 'simple-x-buttons' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="sxb_options[share_include_title]" value="1"
								<?php checked( 1, (int) $options['share_include_title'] ); ?> />
							<?php esc_html_e( 'Include post title', 'simple-x-buttons' ); ?>
						</label><br>
						<label>
							<input type="checkbox" name="sxb_options[share_include_url]" value="1"
								<?php checked( 1, (int) $options['share_include_url'] ); ?> />
							<?php esc_html_e( 'Include permalink', 'simple-x-buttons' ); ?>
						</label><br>
						<label>
							<input type="checkbox" name="sxb_options[share_include_tags]" value="1"
								<?php checked( 1, (int) $options['share_include_tags'] ); ?> />
							<?php esc_html_e( 'Include post tags as hashtags', 'simple-x-buttons' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Up to 5 post tags are added. Tweets are 280 characters — too many hashtags leave no room to write.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sxb_share_hashtags"><?php esc_html_e( 'Custom Hashtags', 'simple-x-buttons' ); ?></label></th>
					<td>
						<input type="text" id="sxb_share_hashtags" name="sxb_options[share_hashtags]"
							value="<?php echo esc_attr( $options['share_hashtags'] ); ?>" class="regular-text" placeholder="wordpress,blogging" />
						<p class="description"><?php esc_html_e( 'Comma-separated, no # sign.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sxb_share_via"><?php esc_html_e( 'Via Handle', 'simple-x-buttons' ); ?></label></th>
					<td>
						<input type="text" id="sxb_share_via" name="sxb_options[share_via]"
							value="<?php echo esc_attr( $options['share_via'] ); ?>" class="regular-text" placeholder="yourhandle" />
						<p class="description"><?php esc_html_e( 'Without the @ sign. Appended as ?via= to the share URL.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<?php /* ── Section 4: Follow Button ────────────────────────────── */ ?>
		<div class="sxb-section">
			<h2 class="sxb-section__title">
				<span class="dashicons dashicons-admin-users" aria-hidden="true"></span>
				<?php esc_html_e( 'Follow Button', 'simple-x-buttons' ); ?>
			</h2>
			<p class="description"><?php esc_html_e( 'Used when the Follow button appears in auto-placements, shortcodes, or blocks without a handle override.', 'simple-x-buttons' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="sxb_follow_handle"><?php esc_html_e( 'X Handle', 'simple-x-buttons' ); ?></label></th>
					<td>
						<input type="text" id="sxb_follow_handle" name="sxb_options[follow_handle]"
							value="<?php echo esc_attr( $options['follow_handle'] ); ?>" class="regular-text" placeholder="yourhandle" />
						<p class="description"><?php esc_html_e( 'Without the @ sign.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sxb_follow_label"><?php esc_html_e( 'Button Label', 'simple-x-buttons' ); ?></label></th>
					<td>
						<input type="text" id="sxb_follow_label" name="sxb_options[follow_label]"
							value="<?php echo esc_attr( $options['follow_label'] ); ?>" class="regular-text" />
					</td>
				</tr>
			</table>
		</div>

		<?php /* ── Section 5: Mention Button ───────────────────────────── */ ?>
		<div class="sxb-section">
			<h2 class="sxb-section__title">
				<span class="dashicons dashicons-admin-comments" aria-hidden="true"></span>
				<?php esc_html_e( 'Mention Button', 'simple-x-buttons' ); ?>
			</h2>
			<p class="description"><?php esc_html_e( 'Opens X compose with @handle pre-filled. Label defaults to "Tweet to @handle".', 'simple-x-buttons' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="sxb_mention_handle"><?php esc_html_e( 'X Handle', 'simple-x-buttons' ); ?></label></th>
					<td>
						<input type="text" id="sxb_mention_handle" name="sxb_options[mention_handle]"
							value="<?php echo esc_attr( $options['mention_handle'] ); ?>" class="regular-text" placeholder="yourhandle" />
						<p class="description"><?php esc_html_e( 'Without the @ sign.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<?php /* ── Section 6: Hashtag Button ───────────────────────────── */ ?>
		<div class="sxb-section">
			<h2 class="sxb-section__title">
				<span class="dashicons dashicons-tag" aria-hidden="true"></span>
				<?php esc_html_e( 'Hashtag Button', 'simple-x-buttons' ); ?>
			</h2>
			<p class="description"><?php esc_html_e( 'Opens X compose with #tag pre-filled. Label defaults to "Tweet #tag".', 'simple-x-buttons' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="sxb_hashtag_tag"><?php esc_html_e( 'Hashtag', 'simple-x-buttons' ); ?></label></th>
					<td>
						<input type="text" id="sxb_hashtag_tag" name="sxb_options[hashtag_tag]"
							value="<?php echo esc_attr( $options['hashtag_tag'] ); ?>" class="regular-text" placeholder="travel" />
						<p class="description"><?php esc_html_e( 'Without the # sign. Used as the default when no post tags are available.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Post Tags', 'simple-x-buttons' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="sxb_options[hashtag_include_tags]" value="1"
								<?php checked( 1, (int) $options['hashtag_include_tags'] ); ?> />
							<?php esc_html_e( 'Include post tags as hashtags (auto-placement only)', 'simple-x-buttons' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'When enabled, up to 5 post tags are merged with the hashtag above and pre-filled in the compose window. Tweets are 280 characters — too many hashtags leave no room to write.', 'simple-x-buttons' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<?php do_action( 'sxb_settings_sections', $options ); ?>

		<?php submit_button( __( 'Save Changes', 'simple-x-buttons' ) ); ?>
	</form>
</div>
