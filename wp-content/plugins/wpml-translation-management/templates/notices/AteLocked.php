<?php

namespace WPML\TM\Templates\Notices;

class AteLocked {
	public function renderUser( $model ) {
		?>
        <div class="wpmltm-notice">
            <h2><?php echo esc_html( $model->title ); ?></h2>
            <p><?php echo esc_html( $model->intro ); ?></p>
        </div>

		<?php
	}

	public function renderAdmin( $model ) {
		?>
        <div class="wpmltm-notice">
            <h2><?php echo esc_html( $model->title ); ?></h2>
            <p><?php echo esc_html( $model->intro ); ?></p>
	        <?php wp_nonce_field( 'icl_doc_translation_method_cloned_nonce', 'icl_doc_translation_method_cloned_nonce' ) ?>
            <fieldset>
                <div>
                    <label>
                        <input type="radio" name="ate_locked_option" value="move" checked="checked">
                        <span><?php echo esc_html( $model->radio_option_1 ); ?></span>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="ate_locked_option" value="copy">
                        <span><?php echo esc_html( $model->radio_option_2 ); ?></span>
                    </label>
                </div>
            </fieldset>
            <div class="wpmltm-notice__actions">
                <a class="wpmltm-notice__actions-btn" id="wpml_save_cloned_sites_report_type"
                   href="#"><?php echo esc_html( $model->btn_text ); ?></a>
                <a class="wpmltm-notice__actions-link" href="https://wpml.org/documentation/translating-your-contents/advanced-translation-editor/using-advanced-translation-editor-when-you-move-or-use-a-copy-of-your-site/"><?php echo esc_html( $model->link_text ); ?></a>
            </div>
        </div>

		<?php
	}
}