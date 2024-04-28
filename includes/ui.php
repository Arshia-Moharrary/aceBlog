<?php

// Return error alert
function error($val) {
    return "<div class='alert alert-danger'>{$val}</div>";
}

// Return success alert
function success($val) {
    return "<div class='alert alert-success'>{$val}</div>";
}