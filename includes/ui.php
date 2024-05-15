<?php

// Return error alert
function error($val) {
    return "<div class='alert alert-danger'>{$val}</div>";
}

// Return success alert
function success($val) {
    return "<div class='alert alert-success'>{$val}</div>";
}

// Return button
function button($status, $link, $val) {
    return "<a class='btn btn-{$status}' href='$link'>{$val}</a>";
}

// Return redirect
function redirect($link) {
    return "<script>window.location.href = '{$link}'</script>";
}