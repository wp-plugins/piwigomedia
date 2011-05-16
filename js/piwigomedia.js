// get image by id
function get_image(id) {
    img = null;
    $(images._content).each(function(index, obj) {
        if (obj.id == id) {
            img = obj;
            return false;
        }
    });
    return img;
};

// toggle image selection
function toggle_image_selection(id) {
    img = get_image(id);
    if (img == null)
        return false;

    $('ul.image-selector > li[title="'+id+'"]').toggleClass('selected');
};

// execute mceInsertContent for an image object 
function insert_image_obj(img) {
    align = $('div.style-section > fieldset > '+
        'input[name="alignment"]:checked').val();
    if (align == 'left')
        align = 'alignleft';
    else if (align == 'center')
        align = 'aligncenter';
    else if (align == 'right')
        align = 'alignright';
    else
        align = '';

    target = $('div.style-section > fieldset > '+
        'input[name="target"]:checked').val();
    if (target == 'same')
        target = '_self';
    else
        target = '_blank';

    url = $('div.style-section > fieldset > '+
        'input[name="url"]:checked').val();
    if (url == 'fullsize')
        url = img.element_url; // fullsize image
    else
        url = img.categories[0].page_url; // image page

    imurl = $('div.style-section > fieldset > '+
        'input[name="whatinsert"]:checked').val();
    if (imurl == 'fullsize')
        imurl = img.element_url
    else
        imurl = img.tn_url

    window.parent.tinyMCE.execCommand('mceInsertContent', 
        false, 
        '<a href="'+url+'" target="'+target+'" '+
        'class="piwigomedia-single-image">'+
            '<img src="'+imurl+'" class="'+align+'" />'+
        '</a>'
    );
};

// execute mceInsertContent for an image object (using id)
function insert_image(id) {
    img = get_image(id);
    if (img == null)
        return false;
    insert_image_obj(img);
};

// execute mceInsertContent for every selected image ('selected' variable)
function insert_selected() {
    $('ul.image-selector > li.selected').each(function(index, obj) {
        insert_image_obj(get_image(obj["title"]));
    });

};


// get category by id
function get_category(id) {
    cat = null;
    $(categories).each(function(index, obj) {
        if (obj.id == id) {
            cat = obj;
            return false;
        }
    });
    return cat;
};

// execute mceInsertContent for a category object (using id)
function insert_category(id) {
    cat = get_category(id);
    if (cat == null)
        return false;

    window.parent.tinyMCE.execCommand('mceInsertContent', 
        false, 
        '<a class="piwigomedia-gallery-link" href="'+cat.url+'">'+
            cat.name+'</a>'
    );
};

$(document).ready(function() {
    $('div.close-button > input').each(function(index, obj) {
        $(obj).click(function() {
            tinyMCEPopup.close();
        });
    });

    $(images._content).each(function(index, obj) {
        $('ul.image-selector').append(
            '<li title="'+obj.id+'">'+
                '<img src="'+obj.tn_url+'" '+
                'onclick="toggle_image_selection(\''+obj.id+'\');" />'+
            '</li>'
        );
    });


});

