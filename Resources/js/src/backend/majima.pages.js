$.fn.addPage = function() {
    var me = this;
    me.click(function(e){
        e.preventDefault();
        var parentId = $(this).data('parentId'),
            targetUrl = $(this).data('target');

        $.alertable.prompt('Name of the new page:').then(function(data) {
            if (data.value) {
                $.ajax({
                    type: 'GET',
                    url: targetUrl,
                    data: {
                        "parentId": parentId,
                        "name": data.value
                    },
                    success: function(data) {
                        window.location.reload();
                    },
                    contentType: "application/json",
                    dataType: 'json'
                });
            }
        });
    });
};

$("*[data-add-page='true']").addPage();

$.contextMenu({
    selector: '*[data-edit-page="true"]',
    callback: function(key, options) {
        var pageId = options.$trigger.data('id');
        switch(key) {
            case 'add':
                $.alertable.prompt('Name of the new page:').then(function(data) {
                    if (data.value) {
                        $.ajax({
                            type: 'GET',
                            url: options.$trigger.data('addUrl'),
                            data: {
                                "parentId": pageId,
                                "name": data.value
                            },
                            success: function(data) {
                                window.location.reload();
                            },
                            contentType: "application/json",
                            dataType: 'json'
                        });
                    }
                });
                break;
            case 'edit':
                $.alertable.prompt('Update name of the page:').then(function(data) {
                    if (data.value) {
                        $.ajax({
                            type: 'GET',
                            url: options.$trigger.data('editUrl'),
                            data: {
                                "id": pageId,
                                "name": data.value
                            },
                            success: function(data) {
                                window.location.reload();
                            },
                            contentType: "application/json",
                            dataType: 'json'
                        });
                    }
                });
                break;
            case 'delete':
                $.alertable.confirm('Are you sure?').then(function() {
                    $.ajax({
                        type: 'GET',
                        url: options.$trigger.data('deleteUrl'),
                        data: {
                            "id": pageId
                        },
                        success: function(data) {
                            window.location.reload();
                        },
                        contentType: "application/json",
                        dataType: 'json'
                    });
                }, function() {
                });
                break;
        }
    },
    items: {
        "add": {name: "Add"},
        "edit": {name: "Edit"},
        "delete": {name: "Delete"}
    }
});

$('*[data-edit-page="true"]').on('click', function(e){
    e.preventDefault();
    $(this).contextmenu();
});