// Author: TienNguyen
// Data injection: slider_object

// Media uploader
(function ($) {
    var mediaUploader;

    $('#thumb').on('click', function (e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Set a SLider Image',
            button: {
                text: 'Choose Picture'
            },
            multiple: false
        });

        mediaUploader.on('select', function () {
            attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#thumb img').attr('src', attachment.url);
            $('#thumb').attr('data-image', attachment.url);
        });

        mediaUploader.open();
    });
})(jQuery);

(function ($) {
    // Slide handle 
    const $body = $(document.body);
    const titleSlide = $('#title-slide');
    const descriptionSlide = $('#description-slide');
    const parentID = $('.thumbs-wrapper');
    const imgSlide = $('#thumb');
    const nonceField = $('#ajax-slider-nonce');

    class Slide {
        // ------------------------------------------------------------------------------------------------------
        // ------------------------------------------ Select a slide --------------------------------------------
        // ------------------------------------------------------------------------------------------------------
        selectSlide() {
            const self = this;
            $('.thumbs-wrapper').on('click', 'li', function (e) {
                e.preventDefault();

                $('.thumbs-wrapper li').removeClass('selected');
                $(this).addClass('selected');
                // Select slide
                if ($(this).hasClass('add-new-btn')) {
                    $('.slides-options').slideUp(400, function () {
                        $('#title-slide').val('');
                        $('#description-slide').val('');
                        $('#thumb img').attr('src', slider_object.no_img);

                        self.btnStatus(true);
                        $('.slides-options').slideDown();
                    });
                } else {
                    const id = $(this).data('id');
                    $('.slides-options').addClass('activated');

                    $('.slides-options').slideUp(400, function () {
                        $.ajax({
                            type: 'POST',
                            url: slider_object.ajax_url,
                            data: {
                                'action': 'select_side_cpt',
                                'id': id
                            },
                            success: function (response) {
                                if (!response) return;
                                const objSlide = JSON.parse(response);

                                $('#title-slide').val(objSlide.post_title);
                                $('#description-slide').val(objSlide.post_content);
                                $('#thumb img').attr('src', objSlide.post_excerpt);
                                $('#thumb').attr('data-image', objSlide.post_excerpt);

                                self.btnStatus(false);
                                $('.slides-options').slideDown(400, function () {
                                    $('.slides-options').removeClass('activated');
                                });
                            }
                        });
                    });
                }
            });
        }

        // ------------------------------------------------------------------------------------------------------
        // ------------------------------------------ Add a new slide -------------------------------------------
        // ------------------------------------------------------------------------------------------------------
        addSlide() {
            const self = this;
            $('#add-slide').on('click', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: slider_object.ajax_url,
                    data: {
                        'action': 'add_slide_cpt',
                        'idParent': parentID.attr('data-id-parent'),
                        'img': imgSlide.attr('data-image'),
                        'title': titleSlide.val(),
                        'description': descriptionSlide.val(),
                        'nonce': nonceField.val()
                    },
                    success: function (response) {
                        if (response) {
                            let data = JSON.parse(response);
                            let node = `<li data-id="${data.ID}" class="slide slide-${data.ID}">
                                <div class="delete-btn"></div>
                                <img src="${data.post_excerpt}" height="75" width="138">
                            </li>`;
                            $('.thumbs-wrapper').prepend(node);

                            // Act slide add new
                            $('.slides-options').slideUp(400, function () {
                                $('#title-slide').val('');
                                $('#description-slide').val('');
                                $('#thumb img').attr('src', slider_object.no_img);

                                self.btnStatus(true);
                                $('.slides-options').slideDown();
                            });
                        }
                    }
                });
            });
        }

        // ------------------------------------------------------------------------------------------------------
        // ------------------------------------------ Delete a Slide --------------------------------------------
        // ------------------------------------------------------------------------------------------------------
        delSlide() {
            $('.slide').on('click', '.delete-btn', function (e) {
                e.preventDefault();

                const id = $(this).parent('.slide').data("id");

                if (id) {
                    // Ajax
                    $.ajax({
                        type: 'POST',
                        url: slider_object.ajax_url,
                        dataType: 'JSON',
                        data: {
                            'action': 'del_slide_cpt',
                            'id': id
                        },
                        success: function (response) {
                            if (response) {
                                $('.slide-' + response).remove();

                                $('.add-new-btn').click();
                                $('.slides-options').removeClass('activated');
                            }
                        }
                    });
                }
            });
        }

        // Status: true|false add-slide/show|off update-slide/show|off
        btnStatus(status) {
            if (status) {
                $('#add-slide').show();
                $('#update-slide').hide();
            } else {
                $('#add-slide').hide();
                $('#update-slide').show();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const slide = new Slide();

        slide.selectSlide();
        slide.addSlide();
        slide.delSlide();
    });

})(jQuery);
