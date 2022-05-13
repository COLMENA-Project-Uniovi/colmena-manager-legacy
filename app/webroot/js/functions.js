function loadFunctions(){
    loadFancybox();
    profileImageEffect();
    loadDatepicker();
    changeVisible();
    changeFeatured();
    loadTabs();
    loadMaps();
    loadAccordion();

    loadSelect2();
    autoSavedForm();

    loadDinamicTable();
}


function loadFancybox(){
    $(".fancybox").fancybox({
        openEffect : 'none'
    });
}

function profileImageEffect(){
    $(".input.file input[type=file]").change(function(){
        var files = $(this)[0].files;
        var filename = $(this).parent().parent().find(".filename");
        if(files.length > 0){
            filename.find("p.title").html("Archivos seleccionados");
            filename.find("p.file").remove();
        }
        for (var i = 0; i < files.length; i++) {
            filename.append('<p class="file">' + files[i].name + '</p>');
        };
    });
}


function loadDatepicker(){
    $(".datepicker").datepicker(
        {
            "dateFormat": "dd-mm-yy"
        }
    );
    $(".start_date").datepicker(
        {
            "dateFormat": "dd-mm-yy",
            "maxDate": $(".end_date.edit").val(),
            onClose: function( selectedDate ) {
                $(".end_date").datepicker( "option", "minDate", selectedDate );
            }
        }
    );
    $(".end_date").datepicker(
        {
            "dateFormat": "dd-mm-yy",
            "minDate": $(".start_date.edit").val(),
            onClose: function( selectedDate ) {
                $(".start_date").datepicker( "option", "maxDate", selectedDate );
            }
        }
    );
}

function changeVisible(){
    $(".visible .check").click(function(){
        var check = $(this);
        var visible_id = check.attr("id");
        var parts = visible_id.split("-");

        var url_post = admin_path + parts[0].toLowerCase() + "/change_visible/" + parts[1].toLowerCase();
        $.post(url_post,
            function(data){
                check.toggleClass("checked");
            }
        );
    });
}

function changeFeatured(){
    $(".featured .check").click(function(){
        var check = $(this);
        var featured_id = check.attr("id");
        var parts = featured_id.split("-");

        var url_post = admin_path + parts[0].toLowerCase() + "/change_featured/" + parts[1].toLowerCase();

        $.post(url_post,
            function(data){
                check.toggleClass("checked");
            }
        );
    });
}

function loadTabs(){
    var first_tab = $(".tabs .header-tabs .tab:first");

    first_tab.addClass("current");

    var first_content = $(".tabs .content-tabs ."+first_tab.attr("id"));
    first_content.show();

    $(".tabs .header-tabs .tab").click(function(){
        var current_tab = $(".tabs .header-tabs .tab.current");
        var current_content = $(".tabs .content-tabs ."+current_tab.attr("id"));
        var next_tab = $(this);
        if(next_tab.hasClass("current")){
            return;
        }
        var next_content = $(".tabs .content-tabs ."+next_tab.attr("id"));

        current_tab.removeClass("current");
        next_tab.addClass("current");

        current_content.slideUp(500);
        next_content.slideDown(500);
    });
}

function loadMaps(){
    $(".admin-add .map-canvas").mapsLoader({});

    $(".admin-form .map .address-search").click(function(){
        getCoordinates();
    });

    $(".admin-form .map-canvas").mapsLoader({
        "latitude" : $(".admin-form .map .latitude").val(),
        "longitude" : $(".admin-form .map .longitude").val(),
        "draggableMarker" : true,
        "zoom" : 15
    });

    $(".admin-view .map-canvas").mapsLoader({
        "latitude" : $(".admin-view .latitude").val(),
        "longitude" : $(".admin-view .longitude").val(),
        "zoom" : 15
    });
}

function getCoordinates(){
    var address1 = $(".admin-form .map .address1").val();
    var address2 = $(".admin-form .map .address2").val();
    var city = $(".admin-form .map .city").val();
    var province = $(".admin-form .map .province").val();

    if(address1 == "" ||
        address1 == "undefined"){

        $(".map-canvas").mapsLoader({});
    }else{
        $(".map-canvas").mapsLoader({
            "address" : address1 + "," + address2 + "," + city,
            "draggableMarker" : true,
            "zoom" : 15
        });
    }
}

function loadSelect2(){
    //Select 2 integration
    $("select").select2({
        width : "100%"
    });
    $(".keywords").select2({
        width : "100%",
        tags : [""],
        tokenSeparators : [","],
        dropdownCssClass: 'hideSearch'
    });
}

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}

function loadAccordion(){
    $('.accordion-title.closed').next('.accordion-content').hide();
    $('.accordion-title').on('click',function(){
        $(this).toggleClass('closed');

        if($(this).next(".accordion-content").is(':visible')){
          $(this).next(".accordion-content").slideToggle();

        }
        if($(this).next(".accordion-content").is(':hidden')){
           $(this).next(".accordion-content").slideToggle();
        }
       });
}

function autoSavedForm(){
    var form = $('form.autosave');
    if(form.size() > 0){
        setInterval(function() {
            CKupdate();
            form.ajaxSubmit({
                data: { autosave: true },
                success: function(data) {
                    $('#autosave-message').html('Guardado como borrador');
                    if(form.attr('data-when-autosave')){
                        arr = JSON.parse(data);
                        var new_action = admin_path + form.attr('data-when-autosave') + '/' + arr.last_id;
                        form.attr('action', new_action);
                        form.removeAttr('data-when-autosave');
                    }
                }
              });
        }, 180000); // every 3 minutes
    }
}


// add files to table when clicking
function loadDinamicTable(){
    addRowEvents = function(){
        $('.delete-row-table').on('click', function(){
            var url_post = admin_path + $(this).attr('data-action');
            var $parent = $(this).closest('.tr');
            var $ancestor = $(this).closest('.tbody');
            $.post(url_post,
                function(data){
                    $parent.remove();
                    if($ancestor.children('.tr').size() == 0)
                        $('.empty-message').fadeIn();
                }
            );
        })
        $('.edit-row-table').on('click', function(){
            var url_post = admin_path + $(this).attr('data-action');
            var $parent = $(this).closest('.tr');
            $.get(url_post,
                function(data){
                    $parent.html(data);
                    addRowEvents();
                }
            );
        })
        $('form.ajax-form').submit( function( event ){
            event.preventDefault();
            var $parent = $(this).closest('.tr');
            $(this).ajaxSubmit({
                success: function(data) {
                    console.log(data);
                    $parent.html(data);
                    addRowEvents();
                }
            });
        })
    }
    $('.add-row-table').on('click', function(){
        var data_table = $(this).attr('data-table');
        var $table = $('#' + data_table);
        var url_post = admin_path + $(this).attr('data-action');

        $.post(url_post,
            function(data){
                $table.find('.empty-message').remove();
                $table.append(data);
                addRowEvents();
            }
        );
    })
    addRowEvents();
}

function updateOrderColum(){
    var sort = 1;
    $('.wrapper-sortable .sortable .sort').each(function(){
        $(this).html(sort++);
    })
}
