function warning(id) {
    $.post('/testpaper/public/index.php/api/log/warningauditor', {
        testpaper: id,
        from: $.cookie('userid'),
        note: '您有一份试卷被催单'
    })
}