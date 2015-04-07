(function(window, document) {
    _up_Post.adminInit = function() {
        $('#deletePost').bind('click', this.remove);
    };

    _up_Post.remove = function() {
        alert('remove');
    };

    _up_Post.adminInit();
})(this, this.document);