<?php
defined('INPUSH') || die();

/* Create the content for each tab */
$html = [];

/* Extra Javascript needed */
$javascript = '';
?>

<?php /* Basic Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_name"><?= language()->notification->settings->name ?></label>
        <input type="text" id="settings_name" name="name" class="form-control" value="<?= $data->notification->name ?>" maxlength="256" required="required" />
    </div>

    <div class="form-group">
        <label for="settings_title"><?= language()->notification->settings->title ?></label>
        <input type="text" id="settings_title" name="title" class="form-control" value="<?= $data->notification->settings->title ?>" maxlength="256" required="required" />
    </div>

    <div class="d-flex justify-content-between">
        <h3 class="h5"><?= language()->notification->settings->engagement_links_categories ?></h3>

        <div>
            <button type="button" id="category_create" class="btn btn-success btn-sm"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->global->create ?></button>
        </div>
    </div>
    <div id="categories"></div>

    <template id="template_category">
        <div class="category">
            <div class="form-group">
                <label><?= language()->notification->settings->title ?></label>
                <input type="text" name="categories[category_index][title]" class="form-control" value="" maxlength="256" required="required" />
            </div>

            <div class="form-group">
                <label><?= language()->notification->settings->description ?></label>
                <input type="text" name="categories[category_index][description]" class="form-control" value="" maxlength="512" />
            </div>

            <div class="d-flex justify-content-between">
                <h3 class="h5"><?= language()->notification->settings->engagement_links_categories_links ?></h3>

                <div>
                    <button type="button" id="category_link_create_category_index" class="btn btn-outline-success btn-sm"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->global->create ?></button>
                </div>
            </div>

            <div class="category_links bg-gray-200 p-5 my-3"></div>

            <button type="button" class="category_delete btn btn-outline-danger btn-sm" aria-label="<?= language()->global->delete ?>"><i class="fa fa-fw fa-times"></i> <?= language()->global->delete ?></button>

            <hr class="my-4" />
        </div>
    </template>

    <template id="template_category_links">
        <div class="category_link">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label><?= language()->notification->settings->title ?></label>
                        <input type="text" name="categories[category_index][links][category_link_index][title]" class="form-control" value="" maxlength="256" required="required" />
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label><?= language()->notification->settings->description ?></label>
                        <input type="text" name="categories[category_index][links][category_link_index][description]" class="form-control" value="" maxlength="512" />
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label><?= language()->notification->settings->image ?></label>
                        <input type="text" name="categories[category_index][links][category_link_index][image]" class="form-control" value="" maxlength="2048" />
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label><?= language()->notification->settings->url ?></label>
                        <input type="text" name="categories[category_index][links][category_link_index][url]" class="form-control" value="" maxlength="2048" required="required" />
                    </div>
                </div>
            </div>

            <button type="button" class="category_link_delete btn btn-outline-danger btn-sm" aria-label="<?= language()->global->delete ?>"><i class="fa fa-fw fa-times"></i> <?= language()->global->delete ?></button>

            <hr class="my-4" />
        </div>
    </template>
<?php $html['basic'] = ob_get_clean() ?>


<?php /* Customize Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_title_color"><?= language()->notification->settings->title_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_title_color_pickr"></div>
            </div>
            <input type="text" id="settings_title_color" name="title_color" class="form-control border-left-0" value="<?= $data->notification->settings->title_color ?>" required="required" />
        </div>
    </div>

    <h3 class="h5"><?= language()->notification->settings->engagement_links_categories ?></h3>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="settings_categories_title_color"><?= language()->notification->settings->title_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_categories_title_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_categories_title_color" name="categories_title_color" class="form-control border-left-0" value="<?= $data->notification->settings->categories_title_color ?>" required="required" />
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="settings_categories_description_color"><?= language()->notification->settings->description_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_categories_description_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_categories_description_color" name="categories_description_color" class="form-control border-left-0" value="<?= $data->notification->settings->categories_description_color ?>" required="required" />
                </div>
            </div>
        </div>
    </div>

    <h3 class="h5"><?= language()->notification->settings->engagement_links_categories_links ?></h3>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="settings_categories_links_title_color"><?= language()->notification->settings->title_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_categories_links_title_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_categories_links_title_color" name="categories_links_title_color" class="form-control border-left-0" value="<?= $data->notification->settings->categories_links_title_color ?>" required="required" />
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="settings_categories_links_description_color"><?= language()->notification->settings->description_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_categories_links_description_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_categories_links_description_color" name="categories_links_description_color" class="form-control border-left-0" value="<?= $data->notification->settings->categories_links_description_color ?>" required="required" />
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="settings_categories_links_background_color"><?= language()->notification->settings->background_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_categories_links_background_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_categories_links_background_color" name="categories_links_background_color" class="form-control border-left-0" value="<?= $data->notification->settings->categories_links_background_color ?>" required="required" />
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="settings_categories_links_border_color"><?= language()->notification->settings->border_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_categories_links_border_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_categories_links_border_color" name="categories_links_border_color" class="form-control border-left-0" value="<?= $data->notification->settings->categories_links_border_color ?>" required="required" />
                </div>
            </div>
        </div>
    </div>

    <hr class="my-3">

    <div class="form-group">
        <label for="settings_background_color"><?= language()->notification->settings->background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_background_color" name="background_color" class="form-control border-left-0" value="<?= $data->notification->settings->background_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_background_pattern"><?= language()->notification->settings->background_pattern ?></label>
        <select class="form-control" id="settings_background_pattern" name="background_pattern">
            <option value="" <?= $data->notification->settings->background_pattern == '' ? 'selected="selected"' : null ?>><?= language()->notification->settings->background_pattern_none ?></option>

            <?php $background_patterns = (require_once APP_PATH . 'includes/notifications_background_patterns.php')(); ?>

            <?php foreach($background_patterns as $key => $value): ?>
                <option value="<?= $key ?>" <?= $data->notification->settings->background_pattern == $key ? 'selected="selected"' : null ?> data-value="<?= $value ?>"><?= language()->notification->settings->{'background_pattern_' . $key} ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_radius"><?= language()->notification->settings->border_radius ?></label>
                <select class="form-control" name="border_radius">
                    <option value="straight" <?= $data->notification->settings->border_radius == 'straight' ? 'selected="selected"' : null ?>><?= language()->notification->settings->border_radius_straight ?></option>
                    <option value="rounded" <?= $data->notification->settings->border_radius == 'rounded' ? 'selected="selected"' : null ?>><?= language()->notification->settings->border_radius_rounded ?></option>
                </select>
                <small class="form-text text-muted"><?= language()->notification->settings->border_radius_help ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_width"><?= language()->notification->settings->border_width ?></label>
                <input type="number" min="0" max="5" id="settings_border_width" name="border_width" class="form-control" value="<?= $data->notification->settings->border_width ?>" />
                <small class="form-text text-muted"><?= language()->notification->settings->border_width_help ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_color"><?= language()->notification->settings->border_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_border_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_border_color" name="border_color" class="form-control border-left-0" value="<?= $data->notification->settings->border_color ?>" required="required" />
                </div>
            </div>
        </div>
    </div>

    <div class="custom-control custom-switch mr-3 mb-3">
        <input
                type="checkbox"
                class="custom-control-input"
                id="settings_shadow"
                name="shadow"
                <?= $data->notification->settings->shadow ? 'checked="checked"' : null ?>
        >

        <label class="custom-control-label clickable" for="settings_shadow"><?= language()->notification->settings->shadow ?></label>

        <div>
            <small class="form-text text-muted"><?= language()->notification->settings->shadow_help ?></small>
        </div>
    </div>
<?php $html['customize'] = ob_get_clean() ?>


<?php ob_start() ?>
    <script>
        /* Categories and categories links handlers */
        let categories = <?= json_encode($data->notification->settings->categories) ?>;
        let template_category = document.querySelector('#template_category');
        let template_category_links = document.querySelector('#template_category_links');

        /* Create new link */
        let category_link_create = (category_index = null, category_link_index = null, title = null, description = null, image = null, url = null) => {
            /* Prepare template */
            let category_link_template_clone = template_category_links.content.cloneNode(true);

            $(category_link_template_clone).find('.category_link').attr('data-category-link-index', category_link_index);
            $(category_link_template_clone).find('input[name="categories[category_index][links][category_link_index][title]"]').attr('name', `categories[${category_index}][links][${category_link_index}][title]`).val(title);
            $(category_link_template_clone).find('input[name="categories[category_index][links][category_link_index][description]"]').attr('name', `categories[${category_index}][links][${category_link_index}][description]`).val(description);
            $(category_link_template_clone).find('input[name="categories[category_index][links][category_link_index][image]"]').attr('name', `categories[${category_index}][links][${category_link_index}][image]`).val(image);
            $(category_link_template_clone).find('input[name="categories[category_index][links][category_link_index][url]"]').attr('name', `categories[${category_index}][links][${category_link_index}][url]`).val(url);

            return category_link_template_clone;
        };

        /* Create new category */
        let category_create = (category_index = null, title = null, description = null) => {
            /* Prepare template */
            let category_template_clone = template_category.content.cloneNode(true);

            $(category_template_clone).find('.category').attr('data-category-index', category_index);
            $(category_template_clone).find('input[name="categories[category_index][title]"]').attr('name', `categories[${category_index}][title]`).val(title);
            $(category_template_clone).find('input[name="categories[category_index][description]"]').attr('name', `categories[${category_index}][description]`).val(description);
            $(category_template_clone).find('button[id="category_link_create_category_index"]').attr('id', `category_link_create_${category_index}`);

            return category_template_clone;
        };

        if(categories) {
            for (let category_index = 0; category_index < categories.length; category_index++) {

                let category_template_clone = category_create(category_index, categories[category_index].title, categories[category_index].description);

                /* Go over the category links */
                for (let category_link_index = 0; category_link_index < categories[category_index]['links'].length; category_link_index++) {

                    let category_link_template_clone = category_link_create(category_index, category_link_index, categories[category_index]['links'][category_link_index].title, categories[category_index]['links'][category_link_index].description, categories[category_index]['links'][category_link_index].image, categories[category_index]['links'][category_link_index].url);

                    /* Append the link to the category links section */
                    $(category_template_clone).find('.category_links').append(category_link_template_clone);

                }

                /* Append the category */
                $('#categories').append(category_template_clone);
            }
        }

        let initiate_handlers = () => {
            $('#category_create').off().on('click', () => {

                let category_index = $('#categories > .category').length;

                let new_category = category_create(category_index);
                let new_category_link = category_link_create(category_index, 0);

                /* Append category link to category */
                $(new_category).find('.category_links').append(new_category_link);

                /* Append the category */
                $('#categories').append(new_category);

                initiate_handlers();
            });

            $('button[id^="category_link_create_"]').off().on('click', event => {

                let category = $(event.currentTarget).closest('.category');
                let category_index = category.data('category-index');
                let category_link_index = category.find('.category_link').length;

                let new_category_link = category_link_create(category_index, category_link_index);

                /* Append category link to category */
                $(category).find('.category_links').append(new_category_link);

                initiate_handlers();
            });

            $('#categories > .category > .category_delete').off().on('click', event => {
                $(event.currentTarget).closest('.category').remove();

                initiate_handlers();
            });

            $('#categories > .category > .category_links > .category_link > .category_link_delete').off().on('click', event => {
                $(event.currentTarget).closest('.category_link').remove();

                initiate_handlers();
            });
        };

        initiate_handlers();

        $('#notification_preview .inpush-engagement-links-hidden').removeClass('inpush-engagement-links-hidden').addClass('inpush-engagement-links-shown');

        /* Notification Preview Handlers */
        $('#settings_title').on('change paste keyup', event => {
            $('#notification_preview .inpush-engagement-links-title').text($(event.currentTarget).val());
        });

        /* Title Color Handler */
        let settings_title_color_pickr = Pickr.create({
            el: '#settings_title_color_pickr',
            default: $('#settings_title_color').val(),
            ...pickr_options
        });

        settings_title_color_pickr.on('change', hsva => {
            $('#settings_title_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-engagement-links-title').css('color', hsva.toHEXA().toString());
        });


        /* Categories Title Color Handler */
        let settings_categories_title_color_pickr = Pickr.create({
            el: '#settings_categories_title_color_pickr',
            default: $('#settings_categories_title_color').val(),
            ...pickr_options
        });

        settings_categories_title_color_pickr.on('change', hsva => {
            $('#settings_categories_title_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-engagement-links-category-title').css('color', hsva.toHEXA().toString());
        });

        /* Categories Description Color Handler */
        let settings_categories_description_color_pickr = Pickr.create({
            el: '#settings_categories_description_color_pickr',
            default: $('#settings_categories_description_color').val(),
            ...pickr_options
        });

        settings_categories_description_color_pickr.on('change', hsva => {
            $('#settings_categories_description_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-engagement-links-category-description').css('color', hsva.toHEXA().toString());
        });

        /* Categories Links Title Color Handler */
        let settings_categories_links_title_color_pickr = Pickr.create({
            el: '#settings_categories_links_title_color_pickr',
            default: $('#settings_categories_links_title_color').val(),
            ...pickr_options
        });

        settings_categories_links_title_color_pickr.on('change', hsva => {
            $('#settings_categories_links_title_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-engagement-links-category-link-title').css('color', hsva.toHEXA().toString());
        });

        /* Categories Links Description Color Handler */
        let settings_categories_links_description_color_pickr = Pickr.create({
            el: '#settings_categories_links_description_color_pickr',
            default: $('#settings_categories_links_description_color').val(),
            ...pickr_options
        });

        settings_categories_links_description_color_pickr.on('change', hsva => {
            $('#settings_categories_links_description_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-engagement-links-category-link-description').css('color', hsva.toHEXA().toString());
        });

        /* Categories Links Background Color Handler */
        let settings_categories_links_background_color_pickr = Pickr.create({
            el: '#settings_categories_links_background_color_pickr',
            default: $('#settings_categories_links_background_color').val(),
            ...pickr_options
        });

        settings_categories_links_background_color_pickr.on('change', hsva => {
            $('#settings_categories_links_background_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-engagement-links-category-link').css('background', hsva.toHEXA().toString());
        });

        /* Categories Description Color Handler */
        let settings_categories_links_border_color_pickr = Pickr.create({
            el: '#settings_categories_links_border_color_pickr',
            default: $('#settings_categories_links_border_color').val(),
            ...pickr_options
        });

        settings_categories_links_border_color_pickr.on('change', hsva => {
            $('#settings_categories_links_border_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-engagement-links-category-link').css('border-color', hsva.toHEXA().toString());
        });

        /* Background Color Handler */
        let settings_background_color_pickr = Pickr.create({
            el: '#settings_background_color_pickr',
            default: $('#settings_background_color').val(),
            ...pickr_options
        });

        settings_background_color_pickr.on('change', hsva => {
            $('#settings_background_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-wrapper').css('background-color', hsva.toHEXA().toString());
        });

        /* Background Pattern Handler */
        $('#settings_background_pattern').on('change paste keyup', event => {
            let value = $(event.currentTarget).find(':selected').data('value');

            if(value) {
                $('#notification_preview .inpush-wrapper').css('background-image', `url(${value})`);
            } else {
                $('#notification_preview .inpush-wrapper').css('background-image', '');
            }
        });
    </script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
