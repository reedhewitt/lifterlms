<?php
/**
 * Import & Export LLMS Content
 *
 * @since 3.3.0
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

$courses = LLMS_Export_API::list();
?>

<div class="wrap lifterlms llms-import-export">

	<h1 class="wp-heading-inline"><?php _e( 'Import Courses', 'lifterlms' ); ?></h1>
	<button class="page-title-action" role="button"><?php _e( 'Upload', 'lifterlms' ); ?></button>

	<hr class="wp-header-end">

	<div class="llms-widget" style="display: none;" id="llms-import-uploader">

		<form action="" enctype="multipart/form-data" method="POST">

			<table class="form-table">

				<tr>
					<th><label for="llms-import-file"><?php _e( 'Import Course(s)', 'lifterlms' ); ?></label></th>
					<td>
						<p><?php _e( 'Upload export files generated by LifterLMS. Must be a ".json" file.', 'lifterlms' ); ?></p>
						<div class="llms-import-file-wrap">
							<input accept="application/json" name="llms_import" id="llms-import-file" type="file">
							<button class="button" type="submit"><?php _e( 'Import', 'lifterlms' ); ?></button>
						</div>
					</td>
				</tr>

				<?php
					/**
					 * Fires after core importer(s) on the "Import screen".
					 *
					 * Allows 3rd parties to add their own importers to the table.
					 *
					 * @since 3.3.0
					 */
					do_action( 'lifterlms_importer_tr' );
				?>

			</table>

			<?php wp_nonce_field( 'llms-importer', 'llms_importer_nonce' ); ?>

		</form>

	</div>

	<form action="" method="POST">

		<p>
			<?php
				// Translators: %s = anchor link HTML to LifterLMS.com.
				printf( __( 'Download and import courses, templates, and more from %s.', 'lifterlms' ), '<a href="https://lifterlms.com" target="_blank">LifterLMS.com</a>' );
			?>
			<button class="llms-cloud-import-help button-link" type="button" title="<?php esc_attr_e( 'Help', 'lifterlms' ); ?>">
				<span class="screen-reader-text"><?php _e( 'Help', 'lifterlms' ); ?></span>
				<span class="dashicons dashicons-editor-help"></span>
			</button>
		</p>

		<?php include LLMS_PLUGIN_DIR . 'includes/admin/views/importable-courses.php'; ?>
		<?php wp_nonce_field( 'llms-cloud-importer', 'llms_cloud_importer_nonce' ); ?>

	</form>

</div>

<script>
( function() {
	document.querySelector( '.page-title-action' ).addEventListener( 'click', function() {
		const el = document.getElementById( 'llms-import-uploader' );
		el.style.display = 'none' === el.style.display ? 'block' : 'none';
	} );
	document.querySelector( '.llms-cloud-import-help' ).addEventListener( 'click', function( e ) {
		document.getElementById( 'contextual-help-link' ).click();
	} );
} )();
</script>
