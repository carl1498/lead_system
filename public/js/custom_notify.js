function notif(title, message, type, glyphicon){
    $.notify({
        icon: 'glyphicon '+glyphicon,
        title: title,
        message: message,
    },{
        type:type,
        delay: 2000,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "right"
        },
        offset: 1,
        z_index: 3000,
    });
}