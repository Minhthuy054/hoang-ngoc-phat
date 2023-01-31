<?php $show_migration_button = apply_filters( 'directorist_show_migration_button', false ); ?>

<div class="wrap">
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">

    <div class="directorist_builder-wrap">
        <?php
            /**
             * Fires before all directory types table
             * @since 7.2.0
             */
            do_action( 'directorist_before_all_directory_types' );
        ?>
        <div class="directorist_builder-header">
            <div class="directorist_builder-header__left">
                <div class="directorist_logo">
                    <img src="https://directorist.com/wp-content/uploads/2020/08/directorist_logo.png" alt="">
                </div>
            </div>
            <div class="directorist_builder-header__right">
                <ul class="directorist_builder-links">
                    <li>
                        <a href="https://directorist.com/documentation/" target="_blank">
                            <i class="la la-file"></i>
                            <span class="link-text"><?php esc_html_e( 'Documentation', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/dashboard/#support" target="_blank">
                            <i class="la la-question-circle"></i>
                            <span class="link-text"><?php esc_html_e( 'Support', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/contact/" target="_blank">
                            <i class="la la-star"></i>
                            <span class="link-text"><?php esc_html_e( 'Feedback', 'directorist' ); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="directorist_builder-body">
            <h2 class="directorist_builder__title"><?php esc_html_e( 'All Directory Types', 'directorist' ); ?></h2>
            <div class="directorist_builder__content">
                <div class="directorist_builder__content--left">
                    <a href="<?php echo esc_attr( $data['add_new_link'] ); ?>" class="directorist_link-block directorist_link-block-primary">
                        <span class="directorist_link-icon">
                            <i class="fa fa-plus"></i>
                        </span>
                        <span class="directorist_link-text"><?php esc_html_e( 'Create New Directory Type', 'directorist' ); ?></span>
                    </a>

                    <a href="#" class="directorist_link-block directorist_link-block-success directorist_btn-import cptm-modal-toggle" data-target="cptm-import-directory-modal">
                        <span class="directorist_link-icon">
                            <i class="fa fa-download"></i>
                        </span>
                        <span class="directorist_link-text">
                            <?php esc_html_e( 'Import', 'directorist' ) ?>
                        </span>
                    </a>

                    <?php if ( $show_migration_button ) : ?>
                    <a href="#" class="directorist_link-block directorist_link-block-success directorist_btn-migrate cptm-modal-toggle" data-target="cptm-directory-mirgation-modal">
                        <span class="directorist_link-icon">
                            <i class="la la-download"></i>
                        </span>
                        <span class="directorist_link-text">
                            <?php esc_html_e( 'Migrate', 'directorist' ) ?>
                        </span>
                    </a>
                    <?php endif; ?>
                </div>
                <?php
                    $all_items =  wp_count_terms('atbdp_listing_types');
                    $listing_types = get_terms([
                       'taxonomy'   => 'atbdp_listing_types',
                       'hide_empty' => false,
                       'orderby'    => 'date',
                       'order'      => 'DSCE',
                     ]);
                ?>
                <div class="directorist_builder__content--right">
                    <div class="directorist_builder--tab">
                        <div class="atbd_tab_nav">
                            <ul>
                                <li class="directorist_builder--tab-item">
                                    <a href="#" target="all" class="atbd_tn_link tabItemActive"><?php esc_html_e( 'All','directorist' ); ?><span class="directorist_count">(<?php echo esc_attr( ! empty( $all_items ) ? $all_items : 0 ); ?>)</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="directorist_builder--tabContent">
                            <div class="atbd_tab_inner tabContentActive" id="all">
                                <div class="directorist_all-listing-table directorist_table-responsive">
                                    <table class="directorist_table">
                                        <thead>
                                            <tr>
                                                <th class="directorist_listing-title"><?php esc_html_e( 'Title', 'directorist' ); ?></th>
                                                <th class="directorist_listing-slug"><?php esc_html_e( 'Slug', 'directorist' ); ?></th>
                                                <th class="directorist_listing-count"><span class="directorist_listing-count-title"><?php esc_html_e( 'Listings', 'directorist' ); ?></span></th>
                                                <th class="directorist_listing-c-date"><?php esc_html_e( 'Created Date', 'directorist' ); ?></th>
                                                <th class="directorist_listing-c-action"><?php esc_html_e( 'Action', 'directorist' ); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if( $listing_types ) {
                                                foreach( $listing_types as $listing_type) {
                                                    $default = get_term_meta( $listing_type->term_id, '_default', true );
                                                    $edit_link = admin_url('edit.php' . '?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=' . absint( $listing_type->term_id ) . '&action=edit');
                                                    $delete_link = admin_url('admin-post.php' . '?listing_type_id=' . absint( $listing_type->term_id ) . '&action=delete_listing_type');
                                                    $delete_link = wp_nonce_url( $delete_link, 'delete_listing_type');
                                                    $created_time = get_term_meta( $listing_type->term_id, '_created_date', true );
                                            ?>
                                            <tr class="directory-type-row" data-term-id="<?php echo esc_attr( $listing_type->term_id ); ?>">
                                                <td>
                                                    <a href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>" class="directorist_title">
                                                        <?php echo esc_html( ! empty( $listing_type->name ) ? $listing_type->name : '-' ); ?>
                                                        <?php if( $default ) { ?>
                                                        <span class="directorist_badge"><?php esc_html_e( 'Default', 'directorist' ); ?></span>
                                                        <?php } ?>
                                                    </a>
                                                    <span class="directorist_listing-id">ID: #<?php echo esc_attr( ! empty( $listing_type->term_id ) ? $listing_type->term_id : '' ); ?></span>
                                                </td>
                                                <td class="directorist-type-slug">
                                                    <div class="directorist-type-slug-content">
                                                        <span class="directorist_listing-slug-text directorist-slug-text-<?php echo esc_attr( $listing_type->term_id ); ?>" data-value="<?php echo esc_attr( ! empty( $listing_type->slug ) ? $listing_type->slug : '-' ); ?>" contenteditable="false">
                                                            <?php echo esc_html( html_entity_decode( $listing_type->slug ) ); ?>
                                                        </span>
                                                        <div class="directorist-listing-slug-edit-wrap">
                                                            <a href="" class="directorist-listing-slug__edit" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="" class="directorist_listing-slug-formText-add" data-type-id="<?php echo absint( $listing_type->term_id ); ?>"></a>
                                                            <a href="#" class="directorist_listing-slug-formText-remove directorist_listing-slug-formText-remove--hidden"></a>
                                                        </div>
                                                    </div>
                                                    <p class='directorist-slug-notice directorist-slug-notice-<?php echo esc_attr( $listing_type->term_id ); ?>'></p>
                                                </td>
                                                <td><span class="directorist_listing-count"><?php echo esc_html( $listing_type->count ); ?></span></td>
                                                <td><?php
                                                if( $created_time ) {
                                                    echo esc_attr( date( 'F j, Y', $created_time ) );
                                                }
                                                ?></td>
                                                <td>
                                                    <div class="directorist_listing-actions">
                                                        <a href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>" class="directorist_btn directorist_btn-primary"><i class="fa fa-edit"></i><?php esc_html_e( 'Edit', 'directorist' ); ?></a>
                                                        <?php
                                                        if( ! $default ) {  ?>
                                                            <div class="directorist_more-dropdown">
                                                                <a href="#" class="directorist_more-dropdown-toggle">
                                                                    <i class="fa fa-ellipsis-h"></i>
                                                                </a>
                                                                <div class="directorist_more-dropdown-option">
                                                                    <ul>
                                                                        <li>
                                                                            <a href="#">
                                                                                <div data-type-id="<?php echo absint( $listing_type->term_id ); ?>" class="directorist_listing-type-checkbox directorist_custom-checkbox submitdefault">
                                                                                    <input class="submitDefaultCheckbox" type="checkbox" name="check-1" id="check-1">
                                                                                    <label for="check-1">
                                                                                        <span class="checkbox-text">
                                                                                        <?php esc_html_e( 'Make It Default', 'directorist' ); ?>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#" class="cptm-modal-toggle atbdp-directory-delete-link-action" data-delete-link="<?php echo esc_url( $delete_link ); ?>" data-target="cptm-delete-directory-modal">
                                                                                <i class="fa fa-trash"></i><?php esc_html_e( 'Delete', 'directorist' ); ?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="directorist_notifier"></div>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Import Directory -->
<div class="cptm-modal-container cptm-import-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Import', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-import-directory-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-import-directory-form">
                        <div class="cptm-form-group cptm-mb-10">
                            <input type="text" name="directory-name" class="cptm-form-control cptm-text-center cptm-form-field" placeholder="Directory Name or ID">
                            <div class="cptm-file-input-wrap">
                                <label for="directory-import-file" class="cptm-btn cptm-btn-secondery"><?php esc_html_e( 'Select File', 'directorist' ); ?></label>
                                <button type="submit" class="cptm-btn cptm-btn-primary">
                                    <span class="cptm-loading-icon cptm-d-none">
                                        <span class="fa fa-spin fa fa-spinner"></span>
                                    </span>
                                    <?php esc_html_e( 'Import', 'directorist' ); ?>
                                </button>
                                <input id="directory-import-file" name="directory-import-file" type="file" accept=".json" class="cptm-d-none cptm-form-field cptm-file-field">
                            </div>

                            <p class="cptm-info-text">
                                <b><?php esc_html_e( 'Note:', 'directorist' ); ?></b>
                                <?php esc_html_e( 'You can use an existed directory ID to update it the importing file', 'directorist' ); ?>
                            </p>
                        </div>

                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>
                    </form>
                </div>
            </div>

            <div class="cptm-section-alert-area cptm-import-directory-modal-alert cptm-d-none">
                <div class="cptm-section-alert-content">
                    <div class="cptm-section-alert-icon cptm-alert-success">
                        <span class="fa fa-check"></span>
                    </div>

                    <div class="cptm-section-alert-message">
                        <?php esc_html_e( 'The directory has been imported successfuly, redirecting...', 'directorist' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Delete Directory -->
<div class="cptm-modal-container cptm-delete-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Delete Derectory', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-delete-directory-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-import-directory-form">
                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <h2 class="cptm-title-2 cptm-text-center"><?php esc_html_e( 'Are you sure?', 'directorist' ) ?></h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondary cptm-modal-toggle atbdp-directory-delete-cancel-link" data-target="cptm-delete-directory-modal">
                                <?php esc_html_e( 'Cancel', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-danger atbdp-directory-delete-link">
                                <?php esc_html_e( 'Delete', 'directorist' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
;

if ( $show_migration_button ) : ?>
<!-- Model : Migration -->
<div class="cptm-modal-container cptm-directory-mirgation-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Migrate', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-directory-mirgation-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-directory-migration-form">
                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <h2 class="cptm-title-2 cptm-text-center cptm-comfirmation-text">
                            <?php esc_html_e( 'Are you sure?', 'directorist' ) ?>
                        </h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondery cptm-modal-toggle atbdp-directory-migration-cencel-link" data-target="cptm-directory-mirgation-modal">
                                <?php esc_html_e( 'No', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-primary atbdp-directory-migration-link">
                                <?php esc_html_e( 'Yes', 'directorist' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>