
function checkConfirm() {
        var c = confirm('IMPORTANT: If you delete this course, all requests and completion records related to this course will be deleted. Do you want to proceed?');
        if (c) {
            return true;
        }
        else {
            return false;
        }
}